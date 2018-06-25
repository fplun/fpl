<?php

namespace App\Http\Controllers\Home;

use App\Models\Miner;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\My_miner;
use App\Api\Helpers\ApiResponse;

class MinerController extends Controller
{
    use ApiResponse;
    //我的矿机主页
    public function index(Request $request)
    {
        $no_run=My_miner::where('user_id', $request->user->id)->where('state', 0)->get();
        $run=My_miner::where('user_id', $request->user->id)->where('die_time', '>', time())->where('state', 1)->get();
        if (session('lang') == 'english') {
            $run->transform(function ($item, $key) {
                switch ($key) {
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
            $no_run->transform(function ($item, $key) {
                switch ($key) {
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
        }
        $miner = Miner::pluck('img');
        if (session('lang') == 'english'){
            return view('home_us.miner.index')->with('no_run', $no_run)->with('run', $run)->with('miner', $miner);
        }
        return view('home.miner.index')->with('no_run', $no_run)->with('run', $run)->with('miner', $miner);
    }
    //矿机收益
    public function profit(Request $request)
    {
        $run=My_miner::where('user_id', $request->user->id)->where('die_time', '>', time())->where('state', 1)->get();

        $run=$run->map(function ($item, $key) {
            $item->run_hour=floor((time()-$item->run_time)/3600);
            $item->sum_yield=number_format($item->yield/3600, 8)*(time()-$item->run_time);
            return $item;
        });
        if (session('lang') == 'english'){
            return view('home_us.miner.profit')->with('run', $run);
        }
        return view('home.miner.profit')->with('run', $run);
    }


    //查看矿机
    public function run(Request $request)
    {
        $sum_price=My_miner::where('state', 1)->where('die_time', '>', time())->sum('yield');
        $my_miner=My_miner::where('user_id', $request->user->id)->where('id', $request->id)
        ->where('state', 1)->where('die_time', '>', time())->first();

        if (!$my_miner) {
            if (session('lang') == 'english'){
                return redirect("/miner/index")->with(['code'=>2,'message'=>'No miner']);
            }
            return redirect("/miner/index")->with(['code'=>2,'message'=>'没有该矿机']);
        }

        $my_miner->com_power=($my_miner->yield);
        //每秒产量
        $my_miner->sec_yield=number_format($my_miner->yield/24/3600, 8);
        //总产量
        $my_miner->sum_yield=$my_miner->sec_yield*(time()-$my_miner->run_time);
        //当前产出
        $my_miner->now_yield=$my_miner->sum_yield-$my_miner->collect;
        if (session('lang') == 'english'){
            return view('home_us.miner.run')->with('my_miner', $my_miner)->with('sum_price', $sum_price);
        }
        return view('home.miner.run')->with('my_miner', $my_miner)->with('sum_price', $sum_price);
    }

    public function run_make(Request $request)
    {
        $my_miner=My_miner::where('user_id', $request->user->id)->where('id', $request->id)->where('state', 0)->first();
        if (!$my_miner) {
            if (session('lang') == 'english'){
                return redirect("/miner/index")->with(['code'=>2,'message'=>'No miner']);
            }
            return redirect("/miner/index")->with(['code'=>2,'message'=>'没有该矿机']);
        }
        $my_miner->state=1;
        $my_miner->run_time=time();
        $my_miner->die_time=time()+$my_miner->cycle*3600*24;
        $res=$my_miner->save();
        if (session('lang') == 'english'){
            if ($res) {
                return redirect("/miner/index")->with(['code'=>2,'message'=>'Run successfully']);
            } else {
                return redirect("/miner/index")->with(['code'=>2,'message'=>'Run failure']);
            }
        }else{
            if ($res) {
                return redirect("/miner/index")->with(['code'=>2,'message'=>'运行成功']);
            } else {
                return redirect("/miner/index")->with(['code'=>2,'message'=>'运行失败']);
            }
        }

    }

    public function upcomputing(Request $request)
    {
        $miner = Miner::all();
        $my_miner = My_miner::where('user_id',$request->user->id)->paginate(5);
        if (session('lang') == 'english'){
            return view('home_us.miner.upcomputing')->with(['miner'=>$miner,'my_miner'=>$my_miner]);
        }
        return view('home.miner.upcomputing')->with(['miner'=>$miner,'my_miner'=>$my_miner]);
    }

    public function buy_make(Request $request)
    {
        $miner=Miner::where('price', $request->id)->first();
        if (!$miner) {
            if (session('lang') == 'english'){
                return $this->success(['code'=>2,'message'=>'There is no such commodity for the time being']);
            }
            return $this->success(['code'=>2,'message'=>'暂无此商品']);
        }
        $now_pta=Wallet::now($request->user->id);
        $wallet=Wallet::where('user_id', $request->user->id)->first();

        if ($now_pta<$miner->price) {
            if (session('lang') == 'english'){
                return $this->success(['code'=>2,'message'=>'Insufficient number of available OCC']);
            }
            return $this->success(['code'=>2,'message'=>'可用OCC数量不足']);
        }
        $order=$this->get_order();
        $user = User::find($request->user->id);
        \DB::beginTransaction();
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
            $user->status = 0;
            $user->save();
            $wallet->coin=($wallet->coin)-($miner->price);
            $wallet->save();
            \DB::commit();
        } catch (QueryException $ex) {
            \DB::rollback();
            if (session('lang') == 'english'){
                return $this->success(['code'=>2,'message'=>'Purchase failure']);
            }
            return $this->success(['code'=>2,'message'=>'购买失败']);
        }
        if (session('lang') == 'english'){
            return $this->success(['code'=>2,'message'=>'Purchase success']);
        }
        return $this->success(['code'=>1,'message'=>'购买成功']);
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
