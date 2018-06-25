<?php

namespace App\Admin\Controllers;

use App\Models\Sys;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;

class SysController extends Controller
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
        return Admin::grid(Sys::class, function (Grid $grid) {

            $grid->min_num('最低交易数量');
            $grid->start_time('交易开始时间');
            $grid->end_time('交易结束时间');
            $grid->brokerage('交易手续费');
            $grid->disableRowSelector();
            $grid->disableExport();
            $grid->disableCreateButton();
            $grid->disablePagination();
            $grid->actions(function ($actions){
                $actions->disableDelete();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Sys::class, function (Form $form) {

            $form->number('min_num','最低交易数量');
            $form->time('start_time','交易开始时间');
            $form->time('end_time','交易结束时间');
            $form->number('brokerage','交易手续费')->help('按百分比填写,10%请填写10');
//            $form->saving(function(Form $form){
//                $form->start_time
//            });
        });
    }
}
