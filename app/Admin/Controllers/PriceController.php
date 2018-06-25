<?php

namespace App\Admin\Controllers;

use App\Models\Price;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
//pta价格设置
class PriceController extends Controller
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
        return Admin::grid(Price::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->price('价格');
            $grid->add_name('添加人');
            $grid->time('添加时间')->display(function ($time){
                return date('Y-m-d H:i:s',$time);
            });
            $grid->disableRowSelector();
            $grid->disableExport();
            $grid->model()->orderBy('time','DESC');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Price::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->currency('price','价格')->rules('required|numeric|between:0.001,99999.99999',['required'=>'价格不能为空','between'=>'请输入正确的价格']);
            $form->hidden('time');
            $form->hidden('add_name')->default(Admin::user()->username);
            $form->saving(function (Form $form){
                $form->time = time();
            });
        });
    }
}
