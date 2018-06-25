<?php

namespace App\Admin\Controllers;

use App\Models\Credit;
use App\Models\Deal;

use App\Models\Interim;
use App\Models\User;
use App\Models\Wallet;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class DealController extends Controller
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
        return Admin::grid(Deal::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->user_id('用户名')->display(function ($user_id){
                $user =  User::where('id',$user_id)->first();
                return "<a href='/admin/users?&phone=$user->phone'>$user->nickname</a>";
            });
            $grid->price('价格');
            $grid->num('数量');
            $grid->state('订单状态')->display(function ($state){
                if ($state == 0){
                    if ($this->create_time < strtotime(date('Y-m-d',1526352183)) + 24 * 3600){
                        return '<span style="color: #CCCCCC;text-decoration:line-through;">订单已过期</span>';
                    }
                    return '等待中';
                };
                if ($state == 1){
                    if (time() - $this->update_time >= 7200 && !is_null($this->update_time)){
                        return "<a href='deal/order/$this->id'>取消订单</a>";
                    };
                    return '交易中';
                };
                if ($state == 2){
                    if (!is_null($this->waiting_time) && time() >= $this->waiting_time){
                        return "<a href='deal/order/$this->id'>确认订单</a>";
                    };
                    return '买家付款';
                };
                if ($state == 3){
                    return '交易成功';
                };
                if ($state == 4){
                    return '订单取消';
                };
            })->sortable();
            $grid->type('订单类型')->display(function ($type){
                if ($type == 1){
                    return '买入';
                }else{
                    return '卖出';
                }
            });
            $grid->create_time('创建时间')->display(function($create_time){
                return date('m-d h:i:s',$create_time);
            });
            $grid->update_time('匹配时间')->display(function($update_time){
                if (is_null($update_time)) return '无';
                return date('m-d h:i:s',$update_time);
            });;
            $grid->deal_id('交易方')->display(function ($deal_id){
                $deal = User::where('id',$deal_id)->first();
                if (empty($deal)){
                    return '';
                }
                return "<a href='/admin/users?&phone=$deal->phone'>$deal->nickname</a>";
            });
            $grid->filter(function ($filter){
                $filter->where(function ($query){
//                    $user_id = User::where('phone',$this->input)->value('user_id');
                    $query->whereHas('user',function ($query){

                        $query->where('phone',$this->input);
                    });
                },'手机号码');
            });
            $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableRowSelector();
            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Deal::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
    public function order($id)
    {
        $deal = new Deal();
        $info = $deal->find($id);
        //$b_id买方id,     $s_id 卖方id
        if ($info->type == 1){
            $b_id = $info->user_id;
            $s_id = $info->deal_id;
        }else{
            $b_id = $info->deal_id;
            $s_id = $info->user_id;
        }
        $wallet = new Wallet();
        //买方钱包
        $b_wallet = $wallet->find($b_id);
        //卖方钱包
        $s_wallet = $wallet->find($s_id);
        if ($info->state == 1){
            $info->state = 4;
            $info->end_time = time();
            $data = [
                'id'    =>  $info->id,
                'user_id'    =>  $info->user_id,
                'price'    =>  $info->price,
                'num'    =>  $info->num,
                'state'    =>  4,
                'type'    =>  $info->type,
                'create_time'    =>  $info->create_time,
                'order'    =>  $info->order,
                'update_time'    =>  $info->update_time,
                'deal_id'    =>  $info->deal_id,
                'waiting_time'    =>  $info->waiting_time,
                'end_time'    =>  $info->end_time,
            ];
            \DB::beginTransaction();
            try{
                $info->save();
                User::where('id',$b_id)->update(['status'=>2]);
                Credit::insert($data);
                \DB::commit();
            }catch (\Exception $e){
                \DB::rollback();
                admin_toastr('取消订单失败','error');
                return redirect('/admin/deal');
            }
            admin_toastr('取消订单成功','success');
            return redirect('/admin/deal');
        }
        if ($info->state ==2){
            $info->state = 3;
            $info->end_time = time();
            \DB::beginTransaction();
            try{
                $info->save();
                $b_wallet->Coin = $b_wallet->Coin + $info->price;
                $s_wallet->Coin = $s_wallet->Coin - $info->price;
                $b_wallet->save();
                $s_wallet->save();
                Interim::where('order_id',$id)->update(['state'=>1]);
                \DB::commit();
            }catch (\Exception $e){
                \DB::rollback();
                admin_toastr('确认订单失败','error');
                return redirect('/admin/deal');
            }
            admin_toastr('确认订单成功','success');
            return redirect('/admin/deal');
        }
    }
}
