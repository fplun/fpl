<?php

namespace App\Admin\Controllers;

use App\Models\Miner;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class MinerController extends Controller
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
        return Admin::grid(Miner::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->level('矿机类型')->display(function ($level){
                switch ($level) {
                    case 1:
                        return '微型云矿机';
                    case 2:
                        return '小型云矿机';
                    case 3:
                        return '中型云矿机';
                    case 4:
                        return '大型云矿机';
                    case 5:
                        return '超级云矿机';
                }
            });
            $grid->yield('每天产量/算力')->display(function ($yield){
                return $yield.'  GH/s';
            });
            $grid->cycle('运行周期 (天)');
            $grid->price('价格');
            $grid->img('图片')->image();
            $grid->actions(function ($actions){
               $actions->disableDelete();
            });
            $grid->disableCreateButton();
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
        return Admin::form(Miner::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->image('img');
        });
    }
}
