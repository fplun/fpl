<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Filiation;
use App\Models\My_miner;
use App\Models\Subordinate;
use App\Api\Helpers\ApiResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DB;

class UnionController extends Controller
{
    use ApiResponse;
    //工会主页
    public function index(Request $request)
    {
        $url = $url=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']."/register/{$request->user->code}";
        if (empty($request->user->code_img) || !file_exists($request->user->code_img)) {
            $dir='qrcodes/'.date('y-m-d');
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $path=$dir.'/'.md5(microtime(true)).'.png';
            QrCode::format('png')->size(500)->generate($url, public_path($path));
            $request->user->code_img=$path;
            $request->user->save();
        }
        $user = $request->user;
        $rank1 = Filiation::where('top_id',$user->id)->where('level',1)->count('id');
        $rank2 = Filiation::where('top_id',$user->id)->where('level',2)->count('id');
        $rank3 = Filiation::where('top_id',$user->id)->where('level',3)->count('id');
        if (session('lang') == 'english'){
            return view('home_us.union.index')->with(['rank1'=>$rank1,'rank2'=>$rank2,'rank3'=>$rank3,'url'=>$url,'img_url'=>$request->user->code_img]);
        }
        return view('home.union.index')->with(['rank1'=>$rank1,'rank2'=>$rank2,'rank3'=>$rank3,'url'=>$url,'img_url'=>$request->user->code_img]);
    }

    public function get_subordinate(Request $request)
    {
        $user=User::where('nickname', $request->nickname)->first();
        if (!$user) {
            return $this->failed(['state'=>1,'message'=>'没有该用户']);
        }
        $subordinate=User::where('pid', $user->id)->get();

        foreach ($subordinate as $v) {
            $nickname_data[]=$v->nickname;
        }
        if (empty($nickname_data)) {
            return $this->failed(['state'=>2,'message'=>'该用户没有属下']);
        }

        return $this->success($nickname_data);
    }


    //工会收益
    public function profit(Request $request)
    {
        $subordinate=Subordinate::where('user_id', $request->user->id)->with('nickname')->get();
        if (session('lang') == 'english'){
            return view('home_us.union.profit')->with('subordinate', $subordinate);
        }
        return view('home.union.profit')->with('subordinate', $subordinate);
    }

    //工会招募
    public function recruit(Request $request)
    {
        $url=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']."/register/{$request->user->code}";
        if (empty($request->user->code_img)) {
            $dir='qrcodes/'.date('y-m-d');
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $path=$dir.'/'.md5(microtime(true)).'.png';
            QrCode::format('png')->size(500)->generate($url, public_path($path));
            $request->user->code_img=$path;
            $request->user->save();
        }
        if (session('lang') == 'english'){
            return view('home_us.union.recruit')->with('url', $url)->with('img_url', $request->user->code_img);
        }
        return view('home.union.recruit')->with('url', $url)->with('img_url', $request->user->code_img);
    }


    public function rank1(Request $request)
    {
        $user = $request->user;
        $friend = User::where('pid',$user->id)->paginate(10);
        $friend->transform(function ($item,$key){
            $item->power = My_miner::where('user_id',$item->id)->where('state', 1)->where('die_time', '>', time())->sum('yield');
            $item->time = $item->created_at;
            return $item;
        });
        if (session('lang') == 'english'){
            return view('home_us.union.rank1')->with(['friend'=>$friend]);
        }
        return view('home.union.rank1')->with(['friend'=>$friend]);
    }
    public function rank2(Request $request)
    {
        $user = $request->user;
        $friend = Filiation::where('top_id',$user->id)->where('level',2)->paginate(10);
        $friend->transform(function ($item,$key){
            $user = User::find($item->user_id);
            $item->power = My_miner::where('user_id',$item->user_id)->where('state', 1)->where('die_time', '>', time())->sum('yield');
            $item->nickname = $user->nickname;
            $item->phone = $user->phone;
            $item->time = $user->created_at;
            return $item;
        });
        if (session('lang') == 'english'){
            return view('home_us.union.rank2')->with(['friend'=>$friend]);
        }
        return view('home.union.rank2')->with(['friend'=>$friend]);
    }

    public function rank3(Request $request)
    {
        $user = $request->user;
        $friend = Filiation::where('top_id',$user->id)->where('level',3)->paginate(10);
        $friend->transform(function ($item,$key){
            $user = User::find($item->user_id);
            $item->power = My_miner::where('user_id',$item->user_id)->where('state', 1)->where('die_time', '>', time())->sum('yield');
            $item->nickname = $user->nickname;
            $item->phone = $user->phone;
            $item->time = $user->created_at;
            return $item;
        });
        if (session('lang') == 'english'){
            return view('home_us.union.rank3')->with(['friend'=>$friend]);
        }
        return view('home.union.rank3')->with(['friend'=>$friend]);
    }
}
