<?php

namespace App\Http\Controllers\Home;

use App\Models\My_miner;
use App\Models\Sys;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Api\Helpers\ApiResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Deal;
use App\Models\Interim;
use App\Models\Info;
use App\Models\Complaint;
use App\Models\Day_line;
use App\Models\Sec_line;
use App\Models\Price;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DealController extends Controller
{
    use ApiResponse;
    //交易中心主页
    public function index(Request $request)
    {
        if (empty($request->session()->get('deal_password'))) {
            $deal_password=1;
        } else {
            $deal_password=0;
        }
        $today=strtotime(date("Y-m-d", time()));

        $last_day=strtotime('-1 day');
        $last_month=strtotime('last month');


        //顶部数据
        $day_top=Day_line::orderBy('id', 'desc')->limit(2)->get();
        $price=Price::orderBy('id', 'desc')->first();
        $top_data['price']=$price->price;
        $top_data['max']=$day_top[0]['max'];
        $top_data['num']=$day_top[0]['num'];
        $top_data['max_increase']=abs((($day_top[1]['max']-$day_top[0]['max'])/$day_top[1]['max'])*100);

        //日线数据
        $day_line=Day_line::where('time', '>', $last_month)->orderBy('time', 'desc')->limit(7)->get();
        foreach ($day_line as $k=>$v) {
            $day_data[]=[date('m-d', $v->time),$v->first,$v->last,$v->min,$v->max];
            $day_time[]=date('m-d', $v->time);
            $day_max[]=$v->max;
        }
        $day_data=json_encode($day_data);
        $day_all['time']=json_encode(array_reverse($day_time));
        $day_all['max']=json_encode(array_reverse($day_max));

        //买入单
        $buy=Deal::where('type', 1)->where('create_time', '>', $today)->whereIn('state', [0,1])->with('user')->get();
        //卖出单
        $sell=Deal::where('type', 2)->where('create_time', '>', $today)->whereIn('state', [0,1])->with('user')->get();
        if (session('lang') == 'english'){
            return view('home_us.deal.index')->with('top_data', $top_data)->with('day_data', $day_data)->with('day_all', $day_all)->with('deal_password', $deal_password)->with('buy', $buy)->with('sell', $sell);
        }
        return view('home.deal.index')->with('top_data', $top_data)->with('day_data', $day_data)->with('day_all', $day_all)->with('deal_password', $deal_password)->with('buy', $buy)->with('sell', $sell);
    }


    //创建买入订单
    public function buy_make(Request $request)
    {
        $tips = $this->tips();
        $this->Validate(
            $request,
            [
                'price' => 'required|numeric',
                'num' => 'required|integer',
            ],
            $tips['vali']
        );
        if (User::find($request->user->id)->status == 1){
            return $this->success(['state'=>1,'message'=>$tips['tip1']]);
        }
        if ($request->num%10 != 0){
            return $this->success(['state'=>1,'message'=>$tips['tip2']]);
        }
        $sys = Sys::find(1);
        $now = date('H:i:s',time());
        $price = new Price();
        $price = $price->orderBy('price')->first();
        if ($now < $sys->start_time || $now > $sys->end_time){
            if (session('lang') == 'english'){
                return $this->success(['state'=>1,'message'=>"The transaction time is$sys->start_time to $sys->end_time"]);
            }
            return $this->success(['state'=>1,'message'=>"交易时间为$sys->start_time 到 $sys->end_time"]);
        }

        $info=Info::where('user_id', $request->user->id)->first();
        if ($info->is_perfect!=2) {
            return $this->success(['state'=>1,'message'=>$tips['tip3']]);
        }

        if (empty($request->session()->get('deal_password'))) {
            return $this->success(['state'=>1,'message'=>$tips['tip4']]);
        }
        if ($request->price<$price->price) {
            return $this->success(['state'=>1,'message'=>$tips['tip5'].$price->price]);
        }
        if ($request->num<$sys->min_num) {
            return $this->success(['state'=>1,'message'=>$tips['tip6'].$sys->min_num]);
        }
        $today=strtotime(date("Y-m-d", time()));

        $my_deal_count=Deal::deal_num($request->user->id, 1, $today);

        if ($my_deal_count>0) {
            return $this->success(['state'=>1,'message'=>$tips['tip7']]);
        }
        $deal_order=Deal::where('create_time', '>', $today)->count();

        $order=$this->order($deal_order);
        $deal_add=[
            'user_id'=>$request->user->id,
            'price'=>$request->price,
            'num'=>$request->num,
            'type'=>1,
            'order'=>$order,
            'create_time'=>time(),
        ];
        $res=Deal::insert($deal_add);
        if ($res) {
            return $this->success(['state'=>0,'message'=>$tips['tip8']]);
        } else {
            return $this->success(['state'=>1,'message'=>$tips['tip9']]);
        }
    }
    public function sell_make(Request $request)
    {
        $tips = $this->tips();
        if (User::find($request->user->id)->status == 1){
            return $this->success(['state'=>1,'message'=>$tips['tip1']]);
        }
        $sys = Sys::find(1);
        $now = date('H:i:s',time());
        if ($now < $sys->start_time || $now > $sys->end_time){
            if (session('lang') == 'english'){
                return $this->success(['state'=>1,'message'=>"The transaction time is$sys->start_time to $sys->end_time"]);
            }
            return $this->success(['state'=>1,'message'=>"交易时间为$sys->start_time 到 $sys->end_time"]);
        }
        $this->Validate(
            $request,
            [
                'price' => 'required|numeric',
                'num' => 'required|integer',
            ],
            $this->vali
        );

        if ($request->num%10 != 0){
            return $this->success(['state'=>1,'message'=>$tips['tip2']]);
        }
        $price = new Price();
        $price = $price->orderByDesc('price')->first();
        if (empty($request->session()->get('deal_password'))) {
            return $this->success(['state'=>1,'message'=>$tips['tip4']]);
        }
        $info=Info::where('user_id', $request->user->id)->first();
        if ($info->is_perfect!=2) {
            return $this->success(['state'=>1,'message'=>$tips['tip3']]);
        }

        if ($request->price<$price->price) {
            return $this->success(['state'=>1,'message'=>$tips['tips_sell_price'].$price->price]);
        }
        if ($request->num<$sys->min_num) {
            return $this->success(['state'=>1,'message'=>$tips['tips_sell_num'].$sys->min_num.'个']);
        }
        $today=strtotime(date("Y-m-d", time()));
        $my_deal_count=Deal::deal_num($request->user->id, 2, $today);
        if ($my_deal_count>0) {
            return $this->success(['state'=>1,'message'=>$tips['tip7']]);
        }

        //查询余额

        $now_pta=Wallet::now($request->user->id);

        $money=$request->num*($sys->brokerage/100)+$request->num;


        if ($money>$now_pta) {
            return $this->success(['state'=>1,'message'=>$tips['tip_account']]);
        }


        $deal_order=Deal::where('create_time', '>', $today)->count();
        $order=$this->order($deal_order);


        DB::beginTransaction();
        try {
            $deal_add=[
                'user_id'=>$request->user->id,
                'price'=>$request->price,
                'num'=>$request->num,
                'type'=>2,
                'order'=>$order,
                'create_time'=>time(),
            ];
            $deal_id=Deal::insertGetId($deal_add);

            $data=[
                'user_id'=>$request->user->id,
                'coin'=>$money,
                'type'=>1,
                'other_id'=>$deal_id,
                'time'=>$today+86400,
            ];
            Interim::insert($data);

            DB::commit();
        } catch (QueryException $ex) {
            DB::rollback();
            return $this->success(['state'=>1,'message'=>$tips['tip9']]);
        }

        return $this->success(['state'=>0,'message'=>$tips['tip8']]);
    }

    public function order($count)
    {
        $order=date('Ymd').substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $order=substr_replace($order, $count, 8, strlen($count));
        return $order;
    }
    

    //接受买入订单  卖出
    public function buy_accept(Request $request)
    {
        $tips = $this->tips();
        if (User::find($request->user->id)->status == 1){
            return $this->success(['state'=>1,'message'=>$tips['tip1']]);
        }
        $this->Validate(
            $request,
            [
                'order' => 'required|integer',
            ],
            [
                'order.required' => $tips['order_num'],
                'order.integer' => $tips['order_error'],
            ]
        );
        $today=strtotime(date("Y-m-d", time()));
        $order=Deal::where('order', $request->order)->where('state', 0)->where('create_time', '>', $today)->first();
        if (empty($order)) {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }

        $info=Info::where('user_id', $request->user->id)->first();
        if ($info->is_perfect!=2) {
            return $this->success(['state'=>1,'message'=>$tips['tip3']]);
        }

        if ($order->user_id==$request->user->id) {
            return $this->success(['state'=>1,'message'=>$tips['no_self_order']]);
        }

        $now_pta=Wallet::now($request->user->id);
        if ($order->type == 1){
            if ($now_pta<$order->num) {
                return $this->success(['state'=>1,'message'=>$tips['no_occ']]);
            }
            DB::beginTransaction();
            try {
                Deal::where('order', $order->order)->update(['update_time'=>time(),'state'=>1,'deal_id'=>$request->user->id]);
                $data=[
                    'user_id'=>$request->user->id,
                    'coin'=>$order->num,
                    'time'=>time()+7200,
                    'type'=>1,
                    'other_id'=>$order->id,
                ];
                Interim::insert($data);
                DB::commit();
            } catch (QueryException $ex) {
                DB::rollback();
                return $this->success(['state'=>1,'message'=>$tips['order_shibai']]);
            }

            return $this->success(['state'=>0,'message'=>$tips['sell_success']]);
        }else{
            DB::beginTransaction();
            try {
                Deal::where('order', $order->order)->update(['update_time'=>time(),'state'=>1,'deal_id'=>$request->user->id]);
                DB::commit();
            } catch (QueryException $ex) {
                DB::rollback();
                return $this->success(['state'=>1,'message'=>$tips['order_shibai']]);
            }
            return $this->success(['state'=>0,'message'=>$tips['buy_success']]);
        }
    }


    //输入密码
    public function deal_password(Request $request)
    {
        $tips = $this->tips();
        $this->Validate(
            $request,
            [
                'deal_password' => 'required',
            ],
            [
                'deal_password.required' => $tips['security'],
            ]
        );
        $user=User::where('id', $request->user->id)->first();
        if (empty($user)) {
            return $this->success(['state'=>1,'message'=>$tips['nothing']]);
        }
        if (!Hash::check($request->deal_password, $user->security)) {
            return $this->success(['state'=>1,'message'=>$tips['password_error']]);
        }
        $request->session()->put('deal_password', 1);
        return $this->success(['state'=>0,'message'=>$tips['password_success']]);
    }




    //我的交易
    public function my_deal(Request $request)
    {
        $tips = $this->tips();
//        if (User::find($request->user->id)->status == 1){
//            return $this->success(['state'=>1,'message'=>$tips['tip1']]);
//        }
        $today=strtotime(date("Y-m-d", time()));
        //求购列表
        $buy=Deal::where('user_id', $request->user->id)->where('state', 0)->where('type', 1)->where('create_time', '>', $today)->with('nickname')->get();


        //出售列表
        $sell=Deal::where('user_id', $request->user->id)->where('state', 0)->where('type', 2)->where('create_time', '>', $today)->with('nickname')->get();

        $all_deal=Deal::where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->with('deal_nickname')->with('nickname')->get();

        //交易中

        //自己的订单
        $deal_buy=Deal::where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->whereIn('state', [1,2])->with('deal_nickname')->with('nickname')->get();




        //已完成
        $deal_finish=Deal::where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->where('state', 3)->with('deal_nickname')->with('nickname')->get();

        if (session('lang') == 'english'){
            return view('home_us.deal.my_deal')->with('buy', $buy)->with('sell', $sell)->with('deal_buy', $deal_buy)->with('user_id', $request->user->id)->with('deal_finish', $deal_finish)->with('all_deal',$all_deal);
        }
        return view('home.deal.my_deal')->with('buy', $buy)->with('sell', $sell)->with('deal_buy', $deal_buy)->with('user_id', $request->user->id)->with('deal_finish', $deal_finish)->with('all_deal',$all_deal);
    }

    //买入订单取消   未匹配
    public function buy_cancel(Request $request)
    {
        $tips = $this->tips();
        $this->Validate(
            $request,
            [
                'order' => 'required|integer',
            ],
            [
                'order.required' => $tips['order_num'],
                'order.integer' => $tips['order_error'],
            ]
        );

        $buy=Deal::where('order', $request->order)->where('user_id', $request->user->id)->where('state', 0)->where('type', 1)->first();
        if (empty($buy)) {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }

        $buy->state=4;
        $buy->end_time=time();
        $res=$buy->save();

        if ($res) {
            return $this->success(['state'=>0,'message'=>$tips['order_cancel_1']]);
        } else {
            return $this->success(['state'=>1,'message'=>$tips['order_cancel_0']]);
        }
    }
    //卖出订单取消  未匹配
    public function sell_cancel(Request $request)
    {
        $tips = $this->tips();
        $this->Validate(
            $request,
            [
                'order' => 'required|integer',
            ],
            [
                'order.required' => $tips['order_num'],
                'order.integer' => $tips['order_error'],
            ]
        );

        $sell=Deal::where('order', $request->order)->where('user_id', $request->user->id)->where('state', 0)->where('type', 2)->first();

        if (empty($sell)) {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }

        $interim=Interim::where('user_id', $request->user->id)->where('other_id', $sell->id)->where('type', 1)->where('state', 0)->first();
        

        DB::beginTransaction();
        try {
            $sell->state=4;
            $sell->end_time=time();
            $sell->save();

            $interim->state=1;
            $interim->save();
            DB::commit();
        } catch (QueryException $ex) {
            DB::rollback();
            return $this->success(['state'=>1,'message'=>$tips['order_cancel_1']]);
        }

        return $this->success(['state'=>0,'message'=>$tips['order_cancel_0']]);
    }


    //上传图片该状态

    public function upload_img(Request $request)
    {
        $tips = $this->tips();
        $this->Validate(
            $request,
            [
                'order' => 'required|integer',
            ],
            [
                'order.required' => $tips['order_num'],
                'order.integer' => $tips['order_error'],
            ]
        );

        $sell=Deal::where('order', $request->order)->where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->where('state', 1)->first();

        if (empty($sell)) {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }

        if (!$request->hasFile('file')) {
            return $this->success(['state'=>1,'message'=>$tips['imgex_error']]);
        }

        $photo = $request->file('file');
        $realPath = $photo->getRealPath(); //临时文件的绝对路径
        $newFileName = md5(microtime()) . '.' . $photo->getClientOriginalExtension();
        $fiepach='uploads/'.date('Ymd').'/'.$newFileName;
        $bool=Storage::disk('uploads')->put($newFileName, file_get_contents($realPath));
        if (!$bool) {
            return $this->success(['state'=>1,'message'=>$tips['img_error']]);
        }
        $interim=Interim::where('type', 1)->where('other_id', $sell->id)->where('state', 0)->first();
        DB::beginTransaction();
        try {
            $sell->img=$fiepach;
            $sell->waiting_time=time();
            $sell->state=2;
            $sell->save();

            $interim->time=time();
            $interim->save();
            DB::commit();
        } catch (QueryException $ex) {
            DB::rollback();
            return $this->success(['state'=>1,'message'=>$tips['img_error']]);
        }

        return $this->success(['state'=>0,'message'=>$tips['img_success']]);
    }



    //查看图片
    public function look_img(Request $request)
    {
        $tips = $this->tips();
        $sell=Deal::where('order', $request->order)->where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->whereIn('state', [1,2])->first();

        if (empty($sell)) {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }

        if (empty($sell->img)) {
            return $this->success(['state'=>1,'message'=>$tips['no_img']]);
        }
        return $this->success(['state'=>0,'message'=>$sell->img]);
    }

    //查看信息
    public function look_info(Request $request)
    {
        $tips = $this->tips();
        $sell=Deal::where('order', $request->order)->where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->first();

        if (empty($sell)) {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }

        if ($sell->user_id==$request->user->id) {
            $id='deal_id';
        } elseif ($sell->deal_id==$request->user->id) {
            $id='user_id';
        } else {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }

        $info=Info::where('user_id', $sell->$id)->first();
        $user=User::where('id', $sell->$id)->first();

        $data=[
            'nickname'=>$user->nickname,
            'truename'=>$info->truename,
            'zfb_num'=>$info->zfb_num,
            'weixin_num'=>$info->weixin_num,
        ];
        return $this->success(['state'=>0,'message'=>$data]);
    }

    //投诉
    public function complaint(Request $request)
    {
        $tips = $this->tips();
        $this->Validate(
            $request,
            [
                'text' => 'max:250',
            ],
            [
                'text.max' => $tips['long'],
            ]
        );
        if (!$request->text){
            return $this->success(['state'=>1,'message'=>$tips['content']]);
        }
        $sell=Deal::where('order', $request->order)->where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->first();

        if (empty($sell)) {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }


        $complaint=Complaint::where('user_id', $request->user->id)->where('deal_id', $sell->id)->count();
        if ($complaint>0) {
            return $this->success(['state'=>1,'message'=>$tips['tousu']]);
        }

        $data=[
            'user_id'=>$request->user->id,
            'deal_id'=>$sell->id,
            'text'=>$request->text,
            'time'=>time(),
        ];
        $res=Complaint::insert($data);
        if ($res) {
            return $this->success(['state'=>0,'message'=>$tips['ts_ok']]);
        } else {
            return $this->success(['state'=>1,'message'=>$tips['ts_no']]);
        }
    }

    //我的交易完成
    public function deal_finish(Request $request)
    {
        $tips = $this->tips();
        $sell=Deal::where('order', $request->order)->where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->where('state', 2)->first();
        if (empty($sell)) {
            return $this->success(['state'=>1,'message'=>$tips['no_qr']]);
        }
        if ($sell->type==1) {
            $id='user_id';
            if ($sell->deal_id!=$request->user->id) {
                return $this->success(['state'=>1,'message'=>$tips['no_order']]);
            }
        } else {
            $id='deal_id';
            if ($sell->user_id!=$request->user->id) {
                return $this->success(['state'=>1,'message'=>$tips['no_order']]);
            }
        }
        $interim=Interim::where('other_id', $sell->id)->where('state', 0)->first();
        
        DB::beginTransaction();
        try {
            $sell->state=3;
            $sell->end_time=time();
            $sell->save();

            Wallet::where('user_id', $request->user->id)->decrement('coin', $interim->coin);

            Wallet::where('user_id', $sell->$id)->increment('coin', $sell->num);

            $interim->state=1;
            $interim->save();
            DB::commit();
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollback();
            return $this->success(['state'=>1,'message'=>$tips['deal_ok']]);
        }

        return $this->success(['state'=>0,'message'=>$tips['deal_no']]);
    }

    //我的交易取消
    public function deal_cancel(Request $request)
    {
        $tips = $this->tips();
        $sell=Deal::where('order', $request->order)->where(function ($query) use ($request) {
            $query->where('user_id', $request->user->id)->orWhere('deal_id', $request->user->id);
        })->first();
        if ($sell->state != 1) {
            return $this->success(['state'=>1,'message'=>$tips['tsdf']]);
        }
        if (empty($sell)) {
            return $this->success(['state'=>1,'message'=>$tips['no_order']]);
        }

        $interim=Interim::where('other_id', $sell->id)->where('state', 0)->first();

        DB::beginTransaction();
        try {
            $sell->state=4;
            $sell->end_time=time();
            $sell->save();

            $interim->state=1;
            $interim->save();
            DB::commit();
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollback();
            return $this->success(['state'=>1,'message'=>$tips['order_cancel_0']]);
        }
        return $this->success(['state'=>0,'message'=>$tips['order_cancel_1']]);
    }

    public function tips()
    {
        if (session('lang') == 'english') {
            return [
                'vali' => [],
                'tip1' => 'You are the experience user, please activate your account',
                'tip2' => 'Buy a multiple of 10',
                'tip3' => 'Please perfect the information first',
                'tip4' => 'Please enter the transaction password first',
                'tip5' => 'Minimum purchase price',
                'tip6' => 'Minimum purchase quantity',
                'tip7' => 'There are already orders being executed',
                'tip8' => 'Success of order creation',
                'tip9' => 'Failure of order creation',
                'tip_sell_price' => 'Minimum selling price',
                'tip_sell_num' => 'Minimum selling number',
                'tip_account' => 'Insufficient account balance',
                'order_num' => 'Please enter the order number',
                'order_error' => 'Error in order number format',
                'no_order' =>'There is no order for it',
                'no_self_order' => 'You can\'t buy your own order',
                'no_occ' =>'Insufficient number of OCC accounts',
                'order_shibai'=> 'Match order failure',
                'sell_success' => 'The order was successfully created, please wait for the buyer to pay',
                'buy_success' => 'The order has been successfully created, please pay as soon as possible',
                'security' => 'Please enter the security password',
                'nothing' => 'No user',
                'password_error' => 'Password error',
                'password_success' => 'password success',
                'order_cancel_1' => 'Order cancellation success',
                'order_cancel_0' => 'Failure of order cancellation',
                'img_error' => 'Image upload failure',
                'img_success' => 'Image upload success',
                'imgex_error' => 'Picture format error',
                'no_img' => 'No uploads',
                'long' => 'Too long',
                'content' => 'Please enter the content',
                'tousu' => 'You have already complained, the backstage staff are handling',
                'ts_ok' => 'Success',
                'ts_no' => 'Error',
                'no_qr' => 'The other is not confirmed yet',
                'deal_ok' => 'Successful trade',
                'deal_no' => 'Transaction failure',
                'tsdf' => 'The other side has already paid. If there is a problem, please complain'
            ];
        } else {
            return [
                'vali' => [
                    'price.required' => '请输入单价',
                    'price.numeric' => '单价格式错误',
                    'num.required' => '请输入数量',
                    'num.integer' => '数量格式错误',
                ],
                'tip1' => '您是体验用户,请激活您的帐号',
                'tip2' => '购买数量为10的倍数',
                'tip3' => '请先完善信息',
                'tip4' => '请先输入交易密码',
                'tip5' => '最低购买价格为',
                'tip6' => '最低购买数量为',
                'tip7' => '已有进行中的订单',
                'tip8' => '订单创建成功',
                'tip9' => '订单创建失败',
                'tip_sell_price' => '最低卖出价格',
                'tip_sell_num' => '最低卖出数量',
                'tip_account' => '帐户余额不足',
                'order_num' => '请输入订单号',
                'order_error' => '订单号格式错误',
                'no_order' =>'没有该订单',
                'no_self_order' => '你不能购买自己的订单',
                'no_occ' => '账户OCC数量不足',
                'order_shibai' => '匹配订单失败',
                'sell_success' =>'匹配订单成功,请等待买家付款',
                'buy_success' => '匹配订单成功,请尽快付款',
                'security' => '请输入安全密码',
                'nothing' => '没有该用户',
                'password_error' => '密码错误',
                'password_success' => '密码正确',
                'order_cancel_1' => '订单取消成功',
                'order_cancel_0' => '订单取消失败',
                'img_error' => '图片上传失败',
                'img_success' => '图片上传成功',
                'imgex_error' => '图片格式错误',
                'no_img' => '对方没有上传图片',
                'long' => '内容过长',
                'content' => '请输入内容',
                'tousu' => '您已经投诉过了，后台人员正在处理',
                'ts_ok' => '投诉成功',
                'ts_no' => '投诉失败',
                'no_qr' => '对方还没有确认',
                'deal_ok' => '交易成功',
                'deal_no' => '交易失败',
                'tsdf' => '对方已经付款,如有问题请投诉',
            ];
        };
    }

}
