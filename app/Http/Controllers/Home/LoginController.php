<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Filiation;
use Rzy\Smscode\SmsTrait;
use App\Api\Helpers\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    use SmsTrait;
    use ApiResponse;

    //登录
    public function guide()
    {
        if (session('lang') == 'english'){
            return view('home_us.login.guide');
        }
        return view('home.login.guide');
    }

    public function login()
    {
        if (session('lang') == 'english'){
            return view('home_us.login.login');
        }
        return view('home.login.login');
    }

    public function login_make(Request $request)
    {
        $tips = $this->tips();
        $this->validate(
            $request,
            [
                'phone' => 'required|regex:/^1[34578][0-9]{9}$/|exists:users,phone',
                'password' => 'required',
//                'captcha' => 'required|captcha'
            ],
            [
                'phone.required' => $tips['phone'],
                'phone.regex' => $tips['phone_error'],
                'phone.exists' => $tips['no_user'],

                'password.required' => $tips['pwd'],
//                'captcha.required' => '请输入验证码',
//                'captcha.captcha' => '验证码错误',
            ]
        );
        $user = User::where('phone', $request->phone)->first();
        if (!Hash::check($request->password, $user->password)) {
            return redirect('/login')->with(['code' => 2, 'message' => $tips['pwd_error']]);
        }
        if ($user->status == 2) {
            return redirect('/login')->with(['code' => 2, 'message' => $tips['user_frozen']]);
        }
        $request->session()->put('uid', $user->id);
        return redirect('/verify')->with(['code' => 0, 'message' => $tips['login_ok']]);
    }

    public function verify()
    {
        if (session('lang') == 'english'){
            return view('home_us.login.verify');
        }
        return view('home.login.verify');
    }

    //注册
    public function register(Request $request)
    {
        if (!$request->code) {
            $request->code = '';
        }
        if (session('lang') == 'english'){
            return view('home_us.login.register')->with('code', $request->code);
        }
        return view('home.login.register')->with('code', $request->code);
    }

    public function register_make(Request $request)
    {
        $tips = $this->tips();
        $this->validate(
            $request,
            [
                'phone' => 'required|unique:users|regex:/^1[34578][0-9]{9}$/',
                'password' => 'required',
//                'password_confirmation' => 'required',
//                'security' => 'required|confirmed',
//                'security_confirmation' => 'required',
                'sms_code' => 'required',
                'code' => 'required'
            ],
            [
                'phone.required' => $tips['phone'],
                'phone.unique' => $tips['phone_ok'],
                'phone.regex' => $tips['phone_error'],

                'password.required' => $tips['pwd'],
//                'password.confirmed' => '密码和确认密码不一致',
//                'password_confirmation.required' => '请输入确认密码',

//                'security.required' => '请输入安全密码',
//                'security.confirmed' => '安全密码和确认安全密码不一致',
//                'security_confirmation.required' => '请输入确认安全密码',

                'sms_code.required' => $tips['sms_code'],
                'code.required' => $tips['code']
            ]
        );

        $check_sms = $this->checkSmsCode(request('phone'), request('sms_code'), 1);
        if ($check_sms !== true) {
            return redirect("/register/{$request->code}")->with(['code' => 2, 'message' => $check_sms['message']])->withInput();
        }

        $user_code = $request->code; //推荐人code
        $recommend_user = User::where('code', $user_code)->first();

        if (!$recommend_user) {
            $pid = 0;
        } else {
            $pid = $recommend_user->id;
            $recommend_filiation = Filiation::where('user_id', $recommend_user->id)->orderBy('top_id', 'desc')->get();
        }
        $password = bcrypt($request->password);
        $security = bcrypt($request->password);
        $nickname = $this->get_name();
        $code = $this->get_code();

        $data = [
            'phone' => $request->phone,
            'password' => $password,
            'security' => $security,
            'nickname' => $nickname,
            'created_at' => date('Y-m-d H:i:s', time()),
            'pid' => $pid,
            'status' => 1,
            'code' => $code
        ];

        DB::beginTransaction();
        try {
            $user_id = User::insertGetId($data);
            if ($recommend_user) {
                $filiation_data[0] = ['user_id' => $user_id, 'top_id' => $recommend_user->id, 'level' => 1];
                $i = 0;
                foreach ($recommend_filiation as $k => $v) {
                    $filiation_data[$k + 1] = ['user_id' => $user_id, 'top_id' => $v->top_id, 'level' => $k + 2];
                    $i++;
                }
                Filiation::insert($filiation_data);
            }

            DB::commit();
        } catch (QueryException $ex) {
            DB::rollback();
            return redirect("/register/{$request->code}")->with(['code' => 2, 'message' => $tips['reg_no']])->withInput();
        }

        return redirect('/register')->with(['code' => 1, 'message' => $tips['reg_ok']])->withInput();
    }

    //获取昵称
    public function get_name()
    {
        $nickname = 'G' . mt_rand(100000, 999999);
        $user = User::where('nickname', $nickname)->first();

        if ($user) {
            return $this->get_name();
        } else {
            return $nickname;
        }
    }

    //获取邀请码
    public function get_code()
    {
        $str = str_random(6);
        $count = User::where('code', $str)->count();
        if ($count > 0) {
            $this->randstr();
        } else {
            return strtolower($str);
        }
    }

    //发送验证码
    public function send_code(Request $request)
    {
        $tips = $this->tips();
        $this->validate(
            $request,
            [
                'phone' => 'required|regex:/^1[34578][0-9]{9}$/',
                'type' => 'required|max:2|min:1',
            ],
            [
                'phone.required' => $tips['phone'],
                'phone.regex' => $tips['phone_error'],

                'type.required' => '验证码类型错误',
                'type.max' => '验证码类型错误',
                'type.min' => '验证码类型错误',
            ]
        );
        if ($request->type == 1) {
            $user = User::where('phone', $request->phone)->first();
            if ($user) {
                return $this->message($tips['phone_ok']);
            }
        }
        $result = $this->sendCodeAndSave($request->phone, $request->type);
        if ($result['success']) {
            return $this->message($tips['ok']);
        }
        return $this->message($result['message']);
    }

    //忘记密码
    public function forget()
    {
        if (session('lang') == 'english'){
            return view('home_us.login.forget');
        }
        return view('home.login.forget');
    }

    public function forget_make(Request $request)
    {
        $tips = $this->tips();
        $this->validate(
            $request,
            [
                'phone' => ['required', 'exists:users,phone', 'regex:/^((13[0-9])|(15[^4])|(166)|(17[0-8])|(18[0-9])|(19[8-9])|(147)|(145))\\d{8}$/'],
                'password' => 'required|confirmed',
                'sms_code' => 'required',
            ],
            [
                'phone.required' => $tips['phone'],
                'phone.exists' => $tips['nouser'],
                'phone.regex' => $tips['phone_error'],

                'password.required' => $tips['pwd'],
                'password.confirmed' => $tips['pwd_no'],
                'sms_code' => $tips['sms_code'],
            ]
        );

        $check_sms = $this->checkSmsCode($request->phone, $request->sms_code, 2);
        if ($check_sms !== true) {
            return redirect('/forget')->with(['code' => 2, 'message' => $check_sms['message']]);
        }
        $password = bcrypt($request->password);
        $data = [
            'password' => $password,
        ];
        $user = User::where('phone', $request->phone)->update($data);

        if (!$user) {
            return redirect('/forget')->with(['code' => 2, 'message' => $tips['modify_ok']]);
        }
        return redirect('/login')->with(['code' => 2, 'message' => $tips['modify_no']]);
    }

    public function tips()
    {
        if (session('lang') == 'english'){
            return [
                'phone_ok' => 'The phone number has been registered',
                'phone' => 'Please enter the cell phone number',
                'phone_error' => 'Cell phone number error',
                'no_user' => 'No user',
                'pwd' => 'Please input a password',
                'pwd_error' => 'Password error',
                'user_forzen' => 'Your account has been frozen',
                'login_ok' => 'Login success',
                'sms_code' => 'Please enter the cell phone verification code',
                'code' => 'Please enter the invitation code',
                'reg_no' => 'Registration failure',
                'reg_ok' => 'Successful registration',
                'ok' => 'Send success',
                'nouser' => 'Without this user, please register first',
                'pwd_no' => 'The password and the confirmation password are not consistent',
                'modify_ok' => 'Amend the success',
                'modify_no' => 'Failure to modify',
            ];
        }else{
            return [
                'phone_ok' => '该号码已被注册',
                'phone' => '请输入手机号码',
                'phone_error' => '手机号格式错误',
                'no_user' => '没有该用户',
                'pwd' => '请输入密码',
                'pwd_error' => '密码错误',
                'user_forzen' => '您的帐号已被冻结',
                'login_ok' => '登录成功',
                'sms_code' => '请输入邀请码',
                'reg_no' => '注册失败',
                'reg_ok' => '注册成功',
                'ok' => '发送成功',
                'nouser' => '没有该用户，请先注册',
                'pwd_no' => '密码和确认密码不一致',
                'modify_ok' => '修改成功',
                'modify_no' => '修改失败',
            ];
        }
    }
}
