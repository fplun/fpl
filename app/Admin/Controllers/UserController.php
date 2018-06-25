<?php

namespace App\Admin\Controllers;

use App\Models\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Table;
use function foo\func;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('用户管理');
            $content->description('用户管理');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('用户修改');
            $content->description('用户修改');

            $content->body($this->edituser()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('新用户');
            $content->description('新用户');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->nickname('用户昵称');
            $grid->model()->with('info');
            $grid->column('详细信息')->info(function (){
                if (!empty($this->info)){
                    $info = array_only($this->info,['identity', 'truename', 'bankname', 'banknum', 'zfb_num', 'weixin_num']);
                    $userinfo['身份证号'] = $info['identity'];
                    $userinfo['真实姓名'] = $info['truename'];
                    $userinfo['银行名字'] = $info['bankname'];
                    $userinfo['银行卡号'] = $info['banknum'];
                    $userinfo['支付宝帐号'] = $info['zfb_num'];
                    $userinfo['微信帐号'] = $info['weixin_num'];
                    return new Table([], $userinfo );
                }else{
                    return '无';
                }
            });
            $grid->phone('手机号码');
            $grid->pid('推荐人')->display(function ($id){
                if ($id == 0){
                    return '无';
                }
                return User::find($id)->nickname;
            });
            $grid->status('用户状态')->display(function ($status){
                if ($status == 1){
                    return '未激活'.'&nbsp;&nbsp;/&nbsp;&nbsp;'."<a href='users/status/$status/$this->id'>激活</a>";
                }elseif ($status == 0) {
                    return '正常用户'.'&nbsp;&nbsp;/&nbsp;&nbsp;'."<a href='users/status/$status/$this->id'>冻结</a>";
                }else{
                    return '冻结'.'&nbsp;&nbsp;/&nbsp;&nbsp;'."<a href='users/status/$status/$this->id'>解冻</a>";
                }
            });
            $grid->code('邀请码');
            $grid->created_at('注册时间');
//            $grid->actions(function ($actions){
//                $actions->disableDelete();
//            });
            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->equal('phone', '手机号码');
                $filter->equal('nickname', '用户昵称');
            });
            $grid->disableExport();
            $grid->disableRowSelector();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {

            $form->tab('基本信息',function ($form){
                $form->text('pid','推荐人帐号')
                    ->rules('regex:/^1[3456789][0-9]{9}$/|exists:users,phone', [
                        'regex' => '请输入正确的推荐人帐号',
                        'exists'   => '推荐人不存在',
                    ])
                    ->help('请输入正确的推荐人手机号码');
                $form->text('phone','手机号码')->rules('regex:/^1[3456789][0-9]{9}$/|unique:users,phone',[
                    'regex' => '请输入正确的手机号码',
                    'unique'   => '手机号码已经注册',
                    ]);
                $form->text('nickname','昵称')->rules('required',['required'=>'昵称不能为空']);
                $form->hidden('status')->default(1);
                $form->password('password','密码')->placeholder('不设置请留空,默认123456');
                $form->password('security','安全码')->placeholder('不设置请留空,默认123456');
                $form->hidden('created_at', '注册时间')->default(time());
                $form->hidden('updated_at', '更新时间');
                $form->hidden('code','邀请码');
            })->tab('详细信息',function ($form){
                $form->text('info.identity','身份证号');
                $form->text('info.truename','真实姓名');
                $form->text('info.bankname','银行名');
                $form->text('info.banknum','银行卡号');
                $form->text('info.zfb_num','支付宝帐号');
                $form->text('info.weixin_num','微信帐号');
            });
            $form->saving(function (Form $form) {
                $form->pid = User::where('phone',$form->pid)->value('id');
                $form->code = $this->randstr();
                if (empty($form->password)) {
                    $form->password = bcrypt(123456);
                } else {
                    $form->password = bcrypt($form->password);
                }
                if (empty($form->security)) {
                    $form->security = bcrypt(123456);
                } else {
                    $form->security = bcrypt($form->security);
                }
            });
        });
    }

    protected function edituser()
    {
        return Admin::form(User::class,function (Form $form){

                $form->text('nickname','昵称');
                $form->display('phone','手机号码');
                $form->text('info.identity','身份证号');
                $form->text('info.truename','真实姓名');
                $form->text('info.bankname','银行名');
                $form->text('info.banknum','银行卡号');
                $form->text('info.zfb_num','支付宝帐号');
                $form->text('info.weixin_num','微信帐号');


        });
    }
    public function status()
    {
        $s = \request('s');
        $id = \request('id');
        $user = User::find($id);
        if ($s == 0){
            $user->status = 2;
            $user->save();
        }elseif ($s == 1){
            $user->status = 0;
            $user->save();
        }else{
            $user->status = 0;
            $user->save();
        }
        admin_toastr('修改成功','success');
        return redirect('admin/users');
    }
    protected function randstr()
    {
        $str = str_random(6);
        $count = User::where('code',$str)->count();
        if ($count > 0) {
            $this->randstr();
        }else{
            return strtolower($str);
        }
    }
}
