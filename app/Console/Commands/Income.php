<?php

namespace App\Console\Commands;

use App\Models\Sc;
use Illuminate\Console\Command;
use App\Models\My_miner;
use App\Models\Profit;
use App\Models\User;
use App\Models\Filiation;
use App\Models\Subordinate;
use App\Models\Wallet;

class Income extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:income';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用户每天获取收益';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //产生前一天的产量

        //时间
        $start_time=strtotime(date("Y-m-d", strtotime("-1 day")));
        $end_time=$start_time+86400;
        $date_time=date('Y-m-d', $start_time);

        //是否产生
        $profit_count=Profit::where('time', $date_time)->count();
        if ($profit_count>0) {
            return ;
        }

        //矿机产生
        $my_miner=My_miner::where('state', 1)->where('die_time', '>', $start_time)->get();

        foreach ($my_miner as $key => $value) {
            //秒产量
            $sec_yield=number_format($value->yield/24/3600, 8);

            if ($start_time<$value->run_time && $end_time>$value->run_time) {//昨天开始运行
                $day_yield=$sec_yield*($end_time-$value->run_time);
            } elseif ($start_time<$value->die_time && $end_time>$value->die_time) {//死亡前一天时间不足一天
                $day_yield=$sec_yield*($value->die_time-$start_time);
            } else {
                $day_yield=$value->yield;
            }
            $day_yield=number_format($day_yield, 8);
            Wallet::where('user_id', $value->user_id)->increment('coin', $day_yield);
            My_miner::where('id', $value->id)->increment('collect', $day_yield);
            Profit::insert(['user_id'=>$value->user_id,'profit'=>$day_yield,'my_miner_id'=>$value->id,'time'=>$date_time]);
        }


        //计算  会长产生下属收益
        $subordinate=Filiation::where('level', '<', 4)->whereHas('profit', function ($query) use ($date_time) {
            $query->where('time', $date_time);
        })->with('profit')->get()->toArray();

        $i=0;
        if ($subordinate){
            foreach ($subordinate as $v) {
                $subordinate_profit=0;
                foreach ($v['profit'] as $p_v) {
                    $subordinate_profit+=$p_v['profit'];
                }
                $myminer = My_miner::where('user_id',$v['top_id'])->orderByDesc('level')->first();
                if ($myminer->levle >= 3){
                    if ($v['level']==1) {
                        $subordinate_profit=$subordinate_profit*0.05;
                    } elseif ($v['level']==2) {
                        $subordinate_profit=$subordinate_profit*0.03;
                    } else {
                        $subordinate_profit=$subordinate_profit*0.02;
                    }
                    $subordinate_profit=number_format($subordinate_profit, 8);

                    Wallet::where('user_id', $v['top_id'])->increment('coin', $subordinate_profit);
                    $data[$i]=['user_id'=>$v['top_id'],'subordinate_id'=>$v['user_id'],'money'=>$subordinate_profit,'time'=>$date_time];
                    $i++;
                }elseif ($myminer->level == 2){
                    if ($v['level']==1) {
                        $subordinate_profit=$subordinate_profit*0.05;
                    } elseif ($v['level']==2) {
                        $subordinate_profit=$subordinate_profit*0.03;
                    } else {
                        $subordinate_profit=0;
                    }
                    $subordinate_profit=number_format($subordinate_profit, 8);

                    Wallet::where('user_id', $v['top_id'])->increment('coin', $subordinate_profit);
                    $data[$i]=['user_id'=>$v['top_id'],'subordinate_id'=>$v['user_id'],'money'=>$subordinate_profit,'time'=>$date_time];
                    $i++;
                }elseif ($myminer->level == 1){
                    if ($v['level']==1) {
                        $subordinate_profit=$subordinate_profit*0.05;
                    } else {
                        $subordinate_profit=0;
                    }
                    $subordinate_profit=number_format($subordinate_profit, 8);

                    Wallet::where('user_id', $v['top_id'])->increment('coin', $subordinate_profit);
                    $data[$i]=['user_id'=>$v['top_id'],'subordinate_id'=>$v['user_id'],'money'=>$subordinate_profit,'time'=>$date_time];
                    $i++;
                }
            }
            Subordinate::insert($data);
        }

        //锁仓收益
        $sc = Sc::where('end_time','>',$start_time)->where('end_time','<',$end_time)->get();
        if ($sc){
            foreach ($sc as $val){
                $shouyi = $val['num']+$val['num']*$val['interest']/100 + $val['num']*$val['power']/100;
                Wallet::where('user_id',$val['user_id'])->increment('coin',$shouyi);
            }
        }
    }
}
