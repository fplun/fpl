<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\MessageState;
use App\Models\Message;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class MessageController extends Controller
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
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(Message::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->user_id('用户名')->display(function ($user_id){
                $name = Message::where('id',$user_id)->value('nickname');
                return $name;
            });
            $grid->content('留言内容');
            $grid->reply('回复内容')->editable('textarea');
            $grid->created_at('留言时间');
            $grid->updated_at('回复时间');
            $states = [
                'off'  => ['value' => 0, 'text' => '未处理', 'color' => 'primary'],
                'on' => ['value' => 1, 'text' => '已处理', 'color' => 'default'],
            ];
            $grid->state('回复状态')->switch($states);
            $grid->disableRowSelector();
            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableExport();
            $grid->model()->orderBy('created_at', 'desc');
            $grid->model()->where('state', 0);
            $grid->filter(function ($filter){
                $filter->disableIDFilter();
                $filter->equal('nickname', '用户昵称');
                $filter->equal('created_at','留言日期')->date();
            });
            $grid->tools(function ($tools){
                $tools->append(new MessageState());
            });
            if (in_array(\Illuminate\Support\Facades\Request::get('state'), ['0', '1'])) {
                $grid->model()->where('state', \Illuminate\Support\Facades\Request::get('status'));
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
        return Admin::form(Message::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->switch('state');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
