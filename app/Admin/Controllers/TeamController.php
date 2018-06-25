<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

class TeamController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content){
            $phone = request('phone');
            $id = '';
            if ($phone) {
                $user = User::where('phone',$phone)->first();
                if ($user){
                    $id = $user->id;
                }else{
                    admin_toastr('请输入正确的电话号码','error');
                }
            }
            $content->header('团队关系');
            $content->row($this->search());
            $content->row($this->team($id));
        });
    }

    public function search()
    {
        $form = new Form();
        $form->action('/admin/team');
        $form->method();
        $form->text('phone','请输入手机号码');
        $form->hidden('_token')->default(csrf_token());
        $box = new Box('手机号码',$form);
        return $box;
    }

    public function team($id='')
    {
        if (!$id){
            return null;
        }
        $user = User::find($id);
        $header = ['昵称','电话','状态'];
        $rows = [$user->nickname,$user->phone,$user->status];
        dd($rows);
        $table = new Table($header,$rows);
        return $table;
    }
}
