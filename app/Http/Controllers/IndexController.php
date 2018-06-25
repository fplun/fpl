<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class IndexController extends Controller
{
    public function index()
    {
        $this->validate(request(), [
            'name' => 'required',
            'password' => 'required',
            ],
            [
                'name.required' => '请输入用户名',
                'password.required' => '请输入密码',
            ]);

        return redirect('/get-form')->with('message', '成功');

    }

    public function getForm()
    {
        return view('getForm');
    }

    public function test(Request $request)
    {
        //已登录用户
        dd($request->user);
    }
}
