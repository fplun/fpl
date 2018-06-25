<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Home\ShopController;
use App\Models\Give;
use App\Models\Miner;
use App\Models\My_miner;
use App\Models\User;
use App\Models\Wallet;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Tab;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class GiveController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content){
            $content->header('系统赠送');
            $tab = new Tab();
            $tab->add('赠送矿机',$this->miner());
            $tab->add('赠送Coin',$this->coin());
            $content->row($tab);
        });
    }

    public function miner()
    {
        $form = new Form();
        $form->action('/admin/give/giveMiner');
        $form->method('post');
        $form->mobile('phone', '用户手机号码');
        $options = [
            1 => '微型云矿机',
            2 => '小型云矿机',
            3 => '中型云矿机',
            4 => '大型云矿机',
            5 => '超级云矿机'
        ];
        $form->select('level', '矿机类型')->options($options)->default(1);
        $form->number('number', '赠送数量')->default(1);
        $form->hidden('_token')->default(csrf_token());
        return $form;
    }
    public function coin()
    {
        $form = new Form();
        $form->method('post');
        $form->action('/admin/give/giveCoin');
        $form->mobile('phone', '用户手机号码');
        $form->number('number', '赠送数量')->default(1);
        $form->hidden('_token')->default(csrf_token());
        return $form;
    }

    public function giveMiner()
    {
        $input = Input::all();
        $user = User::where('phone',$input['phone'])->first();
        if (empty($user)){
            admin_toastr('用户不存在','error');
            return redirect('/admin/give')->withInput();
        }
        if ($input['number']<=0 || !is_numeric($input['number'])){
            admin_toastr('请输入正确的赠送数量','error');
            return redirect('/admin/give')->withInput();
        }
        $sc = new ShopController();
        $miners = Miner::where('level',$input['level'])->first();
        $data['user_id'] = $user['id'];
        $data['yield'] = $miners->yield;
        $data['class'] = $miners->class;
        $data['price'] = $miners->price;
        $data['cycle'] = $miners->cycle;
        $data['level'] = $input['level'];
        //赠送记录
        $info['phone'] = $input['phone'];
        $info['class'] = 1;
        $info['num'] = $input['number'];
        $info['level'] = $input['level'];
        $info['time'] = time();
        DB::beginTransaction();
        try{
            for ($i = 1; $i<=$input['number'];$i++){
                $data['order'] = $sc->get_order();
                My_miner::create($data);
            }
            Give::create($info);
            DB::commit();

        }catch (\Exception $e){
            dd($e);
            DB::rollBack();
            admin_toastr('赠送失败','error');
            return redirect('/admin/give')->withInput();
        }
        admin_toastr('赠送成功','success');
        return redirect('/admin/give');
    }

    public function giveCoin()
    {
        $input = Input::all();
        $user = User::where('phone',$input['phone'])->first();
        if (empty($user)){
            admin_toastr('用户不存在','error');
            return redirect('/admin/give')->withInput();
        }
        if ($input['number']<=0 || !is_numeric($input['number'])){
            admin_toastr('请输入正确的赠送数量','error');
            return redirect('/admin/give')->withInput();
        }
        $wallet = Wallet::where('user_id',$user['id'])->first();
        if ($wallet){
            $wallet->coin = $wallet->coin + $input['number'];
        }else{
            $wallet = new Wallet();
            $wallet->user_id = $user['id'];
            $wallet->coin = $wallet->coin + $input['number'];
        }
        //赠送记录
        $info['phone'] = $input['phone'];
        $info['class'] = 2;
        $info['num'] = $input['number'];
        $info['time'] = time();
        DB::beginTransaction();
        try{
            $wallet->save();
            Give::create($info);
            DB::commit();

        }catch (QueryException $e){
            DB::rollBack();
            admin_toastr('赠送失败','error');
            return redirect('/admin/give')->withInput();
        }
        admin_toastr('赠送成功','success');
        return redirect('/admin/give');
    }
}
