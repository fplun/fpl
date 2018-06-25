<?php

namespace App\Http\Controllers\Api;

use App\Models\SmsCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Rzy\Smscode\SmsTrait;

/**
 * @title 认证
 * @description 接口说明
 */
class AuthenticateController extends ApiController
{
    use SmsTrait;
    /**
     * @title 注册接口
     * @description 接口说明
     * @author ruziyi
     * @url /api/register
     * @method POST

     * @param name:phone type:string require:1 default: other: desc:手机号
     * @param name:name type:string require:1 default: other: desc:姓名
     * @param name:password type:string require:1 default: other: desc:密码
     * @param name:password_confirmation type:string require:1 default: other: desc:确认密码
     * @param name:id_card type:string require:1 default: other: desc:身份证号
     * @param name:p_user type:string require:0 default: other: desc:推荐人
     * @param name:sms_code type:string require:0 default: other: desc:验证码
     *
     */

    public function register(Request $request)
    {
        $this->validate(
            $request,
            [
                'phone' => 'required|unique:users|regex:/^1[34578][0-9]{9}$/',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ],
            [
                'phone.required' => '缺少手机号',
                'phone.unique' => '该手机号已注册',
                'password.required' => '请输入密码',
                'password.confirmed' => '密码和确认密码不一致',
                'password_confirmation.required' => '请输入确认密码',
            ]
        );
        // $check_sms = $this->checkSmsCode(request('phone'), request('sms_code'));
        // if ($check_sms !== true) {
        //     return $this->failed([$check_sms['message']]);
        // }
        $recommend_user_phone = $request->p_user; //推荐人

        if (!empty($recommend_user_phone)) {
            $recommend_user = User::where('phone', $recommend_user_phone)
                ->orWhere('name', $recommend_user_phone)
                ->first();
            if (!$recommend_user) {
                return $this->failed('推荐人不存在');
            }
            if ($recommend_user->phone == $request->phone) {
                return $this->failed('不能推荐自己');
            }
        }

        $recommend_uid = isset($recommend_user) ? $recommend_user->id : 0;
        $password = bcrypt($request->password);

        $data = [
            'name' => $request->phone,
            'phone' => $request->phone,
            'password' => $password,
            'token' => str_random(),
            'pid' => $recommend_uid,
            'id_card' => $request->input('id_card', ''),
        ];
        if (!empty($recommend_user_phone)) {
            if ($recommend_user->pid > 0) {
                $data['ppid'] = $recommend_user->pid;
            }
            // if ($recommend_user->recommend_user && $recommend_user->recommend_user->pid > 0) {
            //     $data['pppid'] = $recommend_user->recommend_user->pid;
            // }
        } else {
            $data['ppid'] = 0;
            // $data['pppid'] = 0;
        }

        $user = User::create($data);
        if (!$user) {
            return $this->failed('注册失败');
        }
        return $this->message('注册成功');
    }
    /**
     * @title 登录接口
     * @description 接口说明
     * @author ruziyi
     * @url /api/login
     * @method POST
     *
     * @param name:phone type:string require:1 default: other: desc:手机号
     * @param name:password type:string require:1 default: other: desc:密码
     *
     * @return token:token
     *
     */

    public function login(Request $request)
    {
        $this->validate(
            $request,
            [
                'phone' => 'required',
                'password' => 'required',
            ],
            [
                'phone.required' => '请输入手机号或用户名',
                'password.required' => '请输入密码',
            ]
        );
        $user = User::where('phone', $request->phone)
            ->orWhere('name', $request->phone)
            ->first();
        if (!$user) {
            return $this->failed('用户不存在');
        }
        //被禁用做判断
        if ($user->status == User::STATUS_DISABLED) {
            return $this->failed('您已被禁用！');
        }
        if (!Hash::check($request->password, $user->password)) {
            return $this->failed('密码错误');
        }
        $user->token = str_random();
        $user->save();

        return $this->success([
            'token' => $user->id . '.' . $user->token,
        ]);
    }

    /**
     * @title 注册短信验证码接口
     * @description 接口说明
     * @author ruziyi
     * @url /api/smsCode
     * @method POST

     * @param name:phone type:string require:1 default: other: desc:手机号
     *
     */
    public function smsCode()
    {
        $phone = request('phone', '');
        $result = $this->sendCodeAndSave($phone);
        if ($result['success']) {
            return $this->message('发送成功');
        }
        return $this->failed($result['message']);
    }

    /**
     * @title 重置密码
     * @description 接口说明
     * @author ruziyi
     * @url /api/resetPassword
     * @method POST
     * @header name:Token require:1 default: desc:Token

     * @param name:phone type:string require:1 default: other: desc:手机号
     * @param name:password type:string require:1 default: other: desc:密码
     * @param name:password_confirmation type:string require:1 default: other: desc:确认密码
     * @param name:sms_code type:string require:0 default: other: desc:验证码
     * @return token:token
     *
     */
    public function resetPassword()
    {
        $this->validate(
            request(),
            [
                'phone' => 'required|exists:users|regex:/^1[34578][0-9]{9}$/',
                'password' => 'required|confirmed',
            ],
            [
                'phone.required' => '缺少手机号',
                'phone.exists' => '该账户不存在',
                'password.required' => '请输入密码',
            ]
        );
        $check_sms = $this->checkSmsCode(request('phone'), request('sms_code'));
        if ($check_sms !== true) {
            return $this->failed([$check_sms['message']]);
        }
        $user = User::where('phone', request('phone'))->first();
        $user->password = bcrypt(request('password'));
        $user->save();

        return $this->message('重设密码成功');
    }
    /**
     * @title 退出登录
     * @description 接口说明
     * @author ruziyi
     * @url /api/logout
     * @method GET
     * @header name:Token require:1 default: desc:Token
     */

    public function logout(Request $request)
    {
        $user = $request->user;
        if (!$user) {
            return $this->failed('没有此用户');
        }
        $user->token = str_random();
        $user->save();

        return $this->message('退出成功');
    }

}
