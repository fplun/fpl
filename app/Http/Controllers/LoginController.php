<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('home.login.login');
        }
        $this->validate($request, [
            'phone' => 'required|exists:users,phone|regex:/^1[345789][0-9]{9}$/',
            'password' => 'required',
//            'captcha' => 'required|captcha'
        ],
            [
                'phone.required' => '请输入手机号',
                'phone.exists' => '没有该用户',
                'phone.regex' => '手机号格式错误',

                'password.required' => '请输入密码',
                'captcha.required' => '请输入验证码',
                'captcha.captcha' => '验证码错误',
            ]);
        $user = User::where('phone', $request->phone)->first();
        if (!Hash::check($request->password, $user->password)) {
            return redirect('/login')->with(['code' => 2, 'message' => '密码错误']);
        }
        if ($user->status == 2) {
            return redirect('/login')->with(['code' => 2, 'message' => '您的账号已被封停']);
        }
        $request->session()->put('uid', $user->id);
        return redirect('/center/index')->with(['code' => 0, 'message' => '登陆成功']);
    }

    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('home.login.register');
        }
        $this->validate($request, [
            'phone' => 'required|unique:users,phone|regex:/^1[345789][0-9]{9}$/',
            'pwd' => 'required|confirmed',
            'pwd_confirmation' => 'required',
//            'captcha' => 'required|captcha'
        ],
            [
                'phone.required' => '请输入手机号',
                'phone.unique' => '该手机号已注册',
                'phone.regex' => '请填写正确手机号码',

                'pwd.required' => '请输入密码',
                'pwd_confirmation.required' => '请再次输入密码',
                'pwd.confirmed' => '两次输入密码不一致',
//                'captcha.required' => '请输入验证码',
//                'captcha.captcha' => '验证码错误',
            ]);
        $p = User::where('code',$request->code)->first();
        if (!$p){
            $pid = 0;
        }else{
            $pid = $p->id;
        }
        $user = new User();
        $user->password = bcrypt($request->pwd);
        $user->phone = $request->phone;
        $user->code = $this->get_code();
        $user->name = $this->get_name();
        $user->pid = $pid;
        $user->status = 1;
        $user->save();
    }

    public function forget()
    {
        return view('home.login.forget');
    }

    protected function get_code()
    {
        $str = str_random(6);
        $count = User::where('code',$str)->count();
        if ($count > 0) {
            $this->randstr();
        }else{
            return strtolower($str);
        }
    }

    public function get_name()
    {
        $name = 'C-'.mt_rand(000000,999999);
        $user = User::where('name',$name)->first();
        if ($user) {
            return $this->get_name();
        }else{
            return $name;
        }
    }
}
