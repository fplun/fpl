<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\ComplaintSataus;
use App\Models\Complaint;

use App\Models\Deal;
use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ComplaintController extends Controller
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

            $content->header('header');
            $content->description('description');

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

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Complaint::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->disableRowSelector();
            $grid->disableExport();
            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->user_id('用户名')->display(function ($user_id){
                $user = User::where('id',$user_id)->first();
                if (empty($user)){
                    return '系统错误';
                }
                return "<a href='users?&phone=$user->phone'>$user->nickname</a>";
            });
            $grid->deal_id('投诉订单')->display(function ($deal_id){
                $order = Deal::where('id',$deal_id)->first();
                if (empty($order)){
                    return '订单已失效';
                }
                return "<a href='deal?&id=$order->id'>$order->order</a>";
            });
            $grid->text('投诉原因');
            $status = [
                'off'  => ['value' => 0, 'text' => '未处理', 'color' => 'primary'],
                'on' => ['value' => 1, 'text' => '已处理', 'color' => 'default'],
            ];
            $grid->status('处理状态')->switch($status);
            $grid->tools(function ($tools){
                $tools->append(new ComplaintSataus());
            });
            if (in_array(\Illuminate\Support\Facades\Request::get('status'), ['0', '1'])) {
                $grid->model()->where('status', \Illuminate\Support\Facades\Request::get('status'));
            }
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Complaint::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->switch('status');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
