<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Miner;
use App\Models\Wallet;
use App\Models\My_miner;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    
    //矿机商城主页
    public function index()
    {
        $miner=Miner::all();
        if (session('lang') == 'english'){
            $m = $miner->map(function ($item,$key){
                switch ($key){
                    case 0:
                        $item->class = 'Micro miner';
                        break;
                    case 1:
                        $item->class = 'Minitype miner';
                        break;
                    case 2:
                        $item->class = 'Medium miner';
                        break;
                    case 3:
                        $item->class = 'Large miner';
                        break;
                    case 4:
                        $item->class = 'Super miner';
                        break;
                }
                return $item;
            });
            return view('home_us.shop.index')->with('miner', $m);
        }
        return view('home.shop.index')->with('miner', $miner);
    }

//    //购买矿机页面
//    public function buy(Request $request)
//    {
//        $miner=Miner::where('id', $request->id)->first();
//        if (!$miner) {
//            return redirect('/shop/index')->with(['code'=>2,'message'=>'没有该商品']);
//        }
//        $miner->com_power=($miner->price)/1000;
//        $miner->sum_yield=ceil(($miner->yield)*($miner->cycle));
//        return view('home.shop.buy')->with('miner', $miner);
//    }

    public function buy_make(Request $request)
    {
        $miner=Miner::where('id', $request->id)->first();
        if (!$miner) {
            if (session('lang') == 'english'){
                return redirect("/shop/index")->with(['code'=>2,'message'=>'No goods']);
            }
            return redirect("/shop/index")->with(['code'=>2,'message'=>'没有该商品']);
        }
        $now_coin=Wallet::now($request->user->id);

        $wallet=Wallet::where('user_id', $request->user->id)->first();


        if ($now_coin<$miner->price) {
            if (session('lang') == 'english'){
                return redirect("/shop/index")->with(['code'=>2,'message'=>'Insufficient number of available OCC']);
            }
            return redirect("/shop/index")->with(['code'=>2,'message'=>'可用OCC数量不足']);
        }
        $order=$this->get_order();
        $user = User::find($request->user->id);
        DB::beginTransaction();
        try {
            $my_miner_add=[
                'user_id'=>$request->user->id,
                'yield'=>$miner->yield,
                'class'=>$miner->class,
                'price'=>$miner->price,
                'cycle'=>$miner->cycle,
                'level'=>$miner->level,
                'order'=>$order,
            ];
            My_miner::create($my_miner_add);

            $wallet->coin=($wallet->coin)-($miner->price);
            $wallet->save();
            $user->sataus = 0;
            $user->save();
            DB::commit();
        } catch (QueryException $ex) {
            DB::rollback();
            if (session('lang') == 'english'){
                return redirect("/shop/index")->with(['code'=>2,'message'=>'Purchase failure']);
            }
            return redirect("/shop/index")->with(['code'=>2,'message'=>'购买失败']);
        }
        if (session('lang') == 'english'){
            return redirect("/shop/index")->with(['code'=>1,'message'=>'Purchase success']);
        }
        return redirect("/shop/index")->with(['code'=>1,'message'=>'购买成功']);
    }

    public function get_order()
    {
        $order='H'.date('di', time()).mt_rand(100000, 999999);
        $my_miner=My_miner::where('order', $order)->first();

        if ($my_miner) {
            return $this->get_order();
        } else {
            return $order;
        }
    }
}
