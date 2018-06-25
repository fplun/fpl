<?php

namespace App\Http\Controllers\Home;

use App\Models\Filiation;
use App\Models\Income;
use App\Models\My_miner;
use App\Models\Sc;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Info;
use App\Models\Message;
use App\Models\Wallet;
use App\Api\Helpers\ApiResponse;
use Illuminate\Support\Facades\Hash;
use function PHPSTORM_META\map;

class CenterController extends Controller
{
    use ApiResponse;
    //个人中心主页
    public function index(Request $request)
    {
        $user_id=$request->user->id;
        $wallet=Wallet::firstOrCreate(['user_id'=>$user_id]);
        $now_pta=Wallet::now($user_id);
        $status = User::where('id', $request->user->id)->value('status');
        if (session('lang') == 'english') {
            if ($status == 1){
                $status = 'Experience users';
            }else{
                $status = 'Normal user';
            }
        } else {
            if ($status == 1){
                $status = '未激活';
            }else{
                $status = '正常用户';
            }
        }
        if (session('lang') == 'english'){
            return view('home_us.center.index')->with('wallet', $wallet)->with('user', $request->user)->with('now_pta', $now_pta)->with('status',$status);
        }
        return view('home.center.index')->with('wallet', $wallet)->with('user', $request->user)->with('now_pta', $now_pta)->with('status',$status);
    }

    //个人资料
    public function info(Request $request)
    {
        $user_id=$request->user->id;
        $info = Info::firstOrCreate(['user_id' => $user_id])->where('user_id', $user_id)->first();
        if (session('lang') == 'english'){
            return view('home_us.center.info')->with('info', $info)->with('user', $request->user);
        }
        return view('home.center.info')->with('info', $info)->with('user', $request->user);
    }

    public function info_make(Request $request)
    {
        $info = Info::where('user_id', $request->user->id)->first();
        if (session('lang') == 'english'){
            $vali = [];
        }else{
            $vali = [
                'truename.required' => '姓名不能为空',
                'identity.required' => '身份证不能为空',
                'bankname.required' => '银行名称不能为空',
                'banknum.required' => '银行卡号不能为空',
                'zfb_num.required' => '支付宝号不能为空',
                'weixin_num.required' => '微信号不能为空',
            ];
        }
        $this->validate(
            $request,
            [
                'identity' => 'required',
                'truename' => 'required',
                'bankname' => 'required',
                'banknum' => 'required',
                'zfb_num' => 'required',
                'weixin_num' => 'required',
            ],
            $vali
        );

        $info->truename = $request->truename;
        $info->identity = $request->identity;
        $info->bankname = $request->bankname;
        $info->banknum = $request->banknum;
        $info->zfb_num = $request->zfb_num;
        $info->weixin_num = $request->weixin_num;
        $info->is_perfect = 2;
        $res = $info->save();
        if (session('lang') == 'english'){
            if ($res) {
                return back()->withErrors('success');
            } else {
                return back()->withInput()->withErrors('error');
            }
        }
        if ($res) {
            return back()->withErrors('修改成功');
        } else {
            return back()->withInput()->withErrors('修改失败');
        }
    }
    //密码管理
    public function password(Request $request)
    {
        if (session('lang') == 'english'){
            return view('home_us.center.password');
        }
        return view('home.center.password');
    }

    public function password_make(Request $request)
    {
        if (session('lang') == 'english'){
            $this->validate(
                $request,
                [
                    'old_security' => 'required',
                    'security' => 'required|confirmed',
                ]
            );
        }else{
            $this->validate(
                $request,
                [
                    'old_security' => 'required',
                    'security' => 'required|confirmed',
                ],
                [
                    'old_security.required' => '请输入原交易密码',
                    'security.required' => '请输入交易密码',
                    'security.confirmed' => '两次输入不一致',
                ]
            );
        }

        if (!Hash::check($request->old_security, $request->user->security)) {
            if (session('lang') == 'english'){
                return redirect('/center/password')->with(['code' => 2, 'message' => 'Original password error']);
            }
            return redirect('/center/password')->with(['code' => 2, 'message' => '原交易密码错误']);
        }
        $request->user->security = bcrypt($request->security);
        $request->user->save();
        if (session('lang') == 'english'){
            return redirect('/center/index')->with(['code' => 2, 'message' => 'success']);
        }
        return redirect('/center/index')->with(['code' => 2, 'message' => '修改成功']);
    }

    //联系我们
    public function contact(Request $request)
    {
        $message=Message::where('user_id', $request->user->id)->orderBy('id', 'desc')->get();
        if (session('lang') == 'english'){
            return view('home_us.center.contact')->with('message', $message);
        }
        return view('home.center.contact')->with('message', $message);
    }

    public function contact_make(Request $request)
    {
        if (session('lang') == 'english'){
            $this->validate(
                $request,
                [
                    'text' => 'required|max:200'
                ]
            );
        }else{
            $this->validate(
                $request,
                [
                    'text' => 'required|max:200'
                ],
                [
                    'text.required'=>'请输入留言内容',
                    'text.max'=>'内容长度超出限制',
                ]
            );
        }
        $data=[
            'user_id'=>$request->user->id,
            'content'=>$request->text,
            'nickname'=>$request->user->nickname,
        ];
        //dd($data);
        $res=Message::create($data);
        if (session('lang') == 'english'){
            if ($res) {
                return redirect('/center/contact')->with(['code'=>1,'message'=>'success']);
            } else {
                return redirect('/center/contact')->with(['code'=>2,'message'=>'error']);
            }
        }
        if ($res) {
            return redirect('/center/contact')->with(['code'=>1,'message'=>'修改成功']);
        } else {
            return redirect('/center/contact')->with(['code'=>2,'message'=>'修改失败']);
        }
    }
//白皮书
    public function read()
    {
        if (session('lang') == 'english'){
            return view('home_us.read.index');
        }
        return view('home.read.index');
    }

    //网络算力申请
    public function sq()
    {
        if (session('lang') == 'english'){
            return view('home_us.center.sq');
        }
        return view('home.center.sq');
    }
//我的锁仓
    public function sc(Request $request)
    {
        $user = $request->user;
        $sc = Sc::where('user_id',$user->id)->paginate(3);
        $sc_occ = Sc::where('user_id',$user->id)->sum('num');
        $my_occ = Wallet::where('user_id',$user->id)->sum('coin');
        if (session('lang') == 'english'){
            return view('home_us.center.sc')->with(['sc'=>$sc,'sc_occ'=>$sc_occ,'my_occ'=>$my_occ]);
        }
        return view('home.center.sc')->with(['sc'=>$sc,'sc_occ'=>$sc_occ,'my_occ'=>$my_occ]);
    }

    public function sc_make(Request $request)
    {
        if (User::find($request->user->id)->status == 1){
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>2,'message'=>'You are the experience user, please activate your account']);
        }
            return redirect('/center/sc')->with(['code'=>2,'message'=>'您是体验用户,请激活您的帐号']);
        }
        $user = $request->user;
        $num = $request->sc_num;
        if ($num%100 != 0){
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>2,'message'=>'The number of applications is 100'])->withInput();
            }
            return redirect('/center/sc')->with(['code'=>2,'message'=>'申请数量为100的倍数'])->withInput();
        }
        $wallet = Wallet::where('user_id',$user->id)->value('coin');
        if ($num > $wallet){
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>2,'message'=>'Lack of purse'])->withInput();
            }
            return redirect('/center/sc')->with(['code'=>2,'message'=>'钱包数量不足'])->withInput();
        }
        $team = User::where('pid',$user->id)->get();
        $count = $team->count();
        $power = 0;
        //直推人数最低为10人
        if ($count <= 10){
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>2,'message'=>'The community is not enough to apply for a lock'])->withInput();
            }
            return redirect('/center/sc')->with(['code'=>2,'message'=>'直推人数不够,不能申请锁仓'])->withInput();
        }
        //最低要有一台大型 矿机
        $level = My_miner::where('user_id',$user->id)->where('state',1)->max('level');
        if ($level < 4){
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>2,'message'=>'The application of a lock requires a large mining machine in operation.'])->withInput();
            }
            return redirect('/center/sc')->with(['code'=>2,'message'=>'申请锁仓需要拥有一台运行中的大型矿机'])->withInput();
        }
        //计算团队总算力
        foreach ($team as $k){
            $power += My_miner::where('user_id',$k->id)->value('yield');
        }
        //申请锁仓最低要有1000的社群算力
        if ($power < 1000) {
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>2,'message'=>'The community is not enough to apply for a lock'])->withInput();
            }
            return redirect('/center/sc')->with(['code'=>2,'message'=>'社群总算力不够,不能申请锁仓'])->withInput();
        }
        if ($level <5 && $num > 4000){
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>2,'message'=>'You need to have a running super miner'])->withInput();
            }
            return redirect('/center/sc')->with(['code'=>2,'message'=>'需要拥有一台运行中的超级矿机'])->withInput();
        }
        if ($num > 4000){
            if ($count < 30 || $power < 5000){
                if (session('lang') == 'english'){
                    return redirect('/center/sc')->with(['code'=>2,'message'=>'According to the current level, the maximum application for the lock is 2000'])->withInput();
                }
                return redirect('/center/sc')->with(['code'=>2,'message'=>'根据目前等级,申请锁仓最高4000'])->withInput();
            }
            if ($count >= 40 && $power >= 10000){
                \DB::beginTransaction();
                try{
                    $wallet = Wallet::where('user_id',$user->id)->first();
                    $wallet->coin = $wallet->coin-$num;
                    $wallet->save();
                    $data = [
                        'user_id' => $user->id,
                        'num' => $num,
                        'interest' => 3,
                        'add' =>3,
                        'start_time' => time(),
                        'end_time' => time()+30*24*3600,
                        'power' => $power
                    ];
                    Sc::create($data);
                    \DB::commit();
                }catch (\Exception $e){
                    \DB::rollBack();
                    if (session('lang') == 'english'){
                        return redirect('/center/sc')->with(['code'=>2,'message'=>'error'])->withInput();
                    }
                    return redirect('/center/sc')->with(['code'=>2,'message'=>'申请锁仓失败'])->withInput();
                }
                if (session('lang') == 'english'){
                    return redirect('/center/sc')->with(['code'=>1,'message'=>'success']);
                }
                return redirect('/center/sc')->with(['code'=>1,'message'=>'申请成功']);
            }
            \DB::beginTransaction();
            try{
                $wallet = Wallet::where('user_id',$user->id)->first();
                $wallet->coin = $wallet->coin-$num;
                $wallet->save();
                $data = [
                    'user_id' => $user->id,
                    'num' => $num,
                    'interest' => 3,
                    'add' => 2,
                    'start_time' => time(),
                    'end_time' => time()+30*24*3600,
                    'power' => $power
                ];
                Sc::create($data);
                \DB::commit();
            }catch (\Exception $e){
                \DB::rollBack();
                if (session('lang') == 'english'){
                    return redirect('/center/sc')->with(['code'=>2,'message'=>'error'])->withInput();
                }
                return redirect('/center/sc')->with(['code'=>2,'message'=>'申请锁仓失败'])->withInput();
            }
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>1,'message'=>'success']);
            }
            return redirect('/center/sc')->with(['code'=>1,'message'=>'申请成功']);
        }else{
            if ($count >= 20 && $power >= 2000){
                \DB::beginTransaction();
                try{
                    $wallet = Wallet::where('user_id',$user->id)->first();
                    $wallet->coin = $wallet->coin-$num;
                    $wallet->save();
                    $data = [
                        'user_id' => $user->id,
                        'num' => $num,
                        'interest' => 3,
                        'start_time' => time(),
                        'end_time' => time()+30*24*3600,
                        'power' => $power
                    ];
                    Sc::create($data);
                    \DB::commit();
                }catch (\Exception $e){
                    \DB::rollBack();
                    if (session('lang') == 'english'){
                        return redirect('/center/sc')->with(['code'=>2,'message'=>'error'])->withInput();
                    }
                    return redirect('/center/sc')->with(['code'=>2,'message'=>'申请锁仓失败'])->withInput();
                }
                if (session('lang') == 'english'){
                    return redirect('/center/sc')->with(['code'=>1,'message'=>'success']);
                }
                return redirect('/center/sc')->with(['code'=>1,'message'=>'申请成功']);
            }
            if ($num > 2000){
                if (session('lang') == 'english'){
                    return redirect('/center/sc')->with(['code'=>2,'message'=>'According to the current level, the maximum application for the lock is 2000'])->withInput();
                }
                return redirect('/center/sc')->with(['code'=>2,'message'=>'根据目前等级,申请锁仓最高2000'])->withInput();
            }
            \DB::beginTransaction();
            try{
                $wallet = Wallet::where('user_id',$user->id)->first();
                $wallet->coin = $wallet->coin-$num;
                $wallet->save();
                $data = [
                    'user_id' => $user->id,
                    'num' => $num,
                    'interest' => 2,
                    'start_time' => time(),
                    'end_time' => time()+30*24*3600,
                    'power' => $power
                ];
                Sc::create($data);
                \DB::commit();
            }catch (\Exception $e){
                \DB::rollBack();
                if (session('lang') == 'english'){
                    return redirect('/center/sc')->with(['code'=>2,'message'=>'error'])->withInput();
                }
                return redirect('/center/sc')->with(['code'=>2,'message'=>'申请锁仓失败'])->withInput();
            }
            if (session('lang') == 'english'){
                return redirect('/center/sc')->with(['code'=>1,'message'=>'success']);
            }
            return redirect('/center/sc')->with(['code'=>1,'message'=>'申请成功']);
        }
    }
//我的钱包
    public function wallet(Request $request)
    {
//        if (User::find($request->user->id)->status == 1){
//            if (session('lang') == 'english'){
//                return $this->success(['state'=>1,'message'=>'You are the experience user, please activate your account']);
//            }
//            return $this->success(['state'=>1,'message'=>'您是体验用户,请激活您的帐号']);
//        }
        $user_id=$request->user->id;
        $wallet=Wallet::firstOrCreate(['user_id'=>$user_id]);
        $now_coin=Wallet::now($user_id);
        $now_coin = number_format($now_coin,2);
        $sc = Sc::where('user_id',$user_id)->sum('num');
        $sc = number_format($sc,2);
        if (session('lang') == 'english'){
            return view('home_us.center.wallet')->with(['now_coin'=>$now_coin,'wallet'=>$wallet,'sc'=>$sc]);
        }
        return view('home.center.wallet')->with(['now_coin'=>$now_coin,'wallet'=>$wallet,'sc'=>$sc]);
    }

    //退出
    public function out(Request $request)
    {
        $request->session()->put('uid', '');
        return redirect('/login');
    }
    //首页
    public function home(Request $request)
    {
        $user = $request->user;
        $now = time();
        //计算排行
        $rank = '无';
        $res = \DB::select("select user_id from my_miners where state=1 and die_time > $now group by user_id order by sum(yield) desc");
        foreach ($res as $k=>$v){
            if ($user->id == $v->user_id){
                $rank = $k+1;
            }
        }
        //能力算力
        $power = My_miner::where('user_id',$user->id)->where('state',1)->where('die_time','>',$now)->sum('yield');
        //节点算力
        $level = My_miner::where('user_id',$user->id)->where('state',1)->where('die_time','>',$now)->max('level');

        $jd_power = 0;
        $rank3_power = 0;
        $rank2_power = 0;
        $rank1_power = 0;
        if ($level >= 3){
            $rank3 = Filiation::where('top_id',$user->id)->where('level',3)->get(['user_id']);
            $rank2 = Filiation::where('top_id',$user->id)->where('level',2)->get(['user_id']);
            $rank1 = Filiation::where('top_id',$user->id)->where('level',1)->get(['user_id']);
            foreach ($rank3 as $k){
                $rank3_power += My_miner::where('user_id',$k->id)->where('state',1)->where('die_time','>',$now)->sum('yield');
            }
            foreach ($rank2 as $k){
                $rank2_power += My_miner::where('user_id',$k->id)->where('state',1)->where('die_time','>',$now)->sum('yield');
            }
            foreach ($rank1 as $k){
                $rank1_power += My_miner::where('user_id',$k->id)->where('state',1)->where('die_time','>',$now)->sum('yield');
            }
            $jd_power = $rank3_power*0.02 + $rank2_power*0.03 + $rank1_power*0.05;
        }elseif ($level == 2){
            $rank2 = Filiation::where('top_id',$user->id)->where('level',2)->get(['user_id']);
            $rank1 = Filiation::where('top_id',$user->id)->where('level',1)->get(['user_id']);
            foreach ($rank2 as $k){
                $rank2_power += My_miner::where('user_id',$k->id)->where('state',1)->where('die_time','>',$now)->sum('yield');
            }
            foreach ($rank1 as $k){
                $rank1_power += My_miner::where('user_id',$k->id)->where('state',1)->where('die_time','>',$now)->sum('yield');
            }
            $jd_power = $rank2_power*0.03 + $rank1_power*0.05;
        }elseif ($level == 1){
            $rank1 = Filiation::where('top_id',$user->id)->where('level',1)->get(['user_id']);
            foreach ($rank1 as $k){
                $rank1_power += My_miner::where('user_id',$k->id)->where('state',1)->where('die_time','>',$now)->sum('yield');
            }
            $jd_power = $rank1_power*0.05;
        }
        //算力产出
        $income = My_miner::where('user_id',$user->id)->where('state',1)->where('die_time','>',$now)->get();
        $in = 0;
        foreach ($income as $k){
            $in += ($now-$k->run_time) * ($k->yield/24/3600);
        }
        $in = number_format($in,8);
        //全网算力
        $all = My_miner::where('state',1)->where('die_time','>',$now)->sum('yield');
        //OCC
        $coin = Wallet::where('user_id',$user->id)->value('coin');
        //最新挖矿记录
        $log = My_miner::where('user_id',$user->id)->where('die_time','>',$now)->orderByDesc('run_time')->first();
        if (session('lang') == 'english'){
            switch ($log->class){
                case '微型云矿机':
                    $log->class = 'Micro miner';
                    break;
                case '小型云矿机':
                    $log->class = 'Minitype miner';
                    break;
                case '中型云矿机':
                    $log->class = 'Medium miner';
                    break;
                case '大型云矿机':
                    $log->class = 'Large miner';
                    break;
                case '超级云矿机':
                    $log->class = 'Super miner';
                    break;
            }
        }

        //已经生产数量(昨天)
        $produce = Wallet::sum('coin');
        if (session('lang') == 'english'){
            return view('home_us.home.index')->with(['rank'=>$rank,'power'=>$power,'jd_power'=>$jd_power,'income'=>$in,'all'=>$all,'coin'=>$coin,'log'=>$log,'produce'=>$produce]);
        }
        return view('home.home.index')->with(['rank'=>$rank,'power'=>$power,'jd_power'=>$jd_power,'income'=>$in,'all'=>$all,'coin'=>$coin,'log'=>$log,'produce'=>$produce]);
    }
}
