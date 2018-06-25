<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Day_line;
use App\Models\Deal;
use App\Models\Sec_line;
use App\Models\Price;
use Illuminate\Support\Facades\DB;

class Day_task extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:day_line';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '日线';

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
        $start_time=strtotime(date("Y-m-d", strtotime("-1 day")));
        $end_time=$start_time+86400;

        $day_count=Day_line::where('time', $start_time)->count();
        if ($day_count>0) {
            return ;
        }
        $price=Price::orderBy('id', 'desc')->first();

        $sec=Deal::whereBetween('end_time', [$start_time,$end_time])->where('state', 3)->select(DB::raw('MAX(price) as max,MIN(price) as min,COUNT(*) as all_count'))->first();

        $sec_first=Deal::limit(7)->orderBy('id', 'asc')->first();
        $sec_last=Deal::limit(7)->orderBy('id', 'desc')->first();
        if ($sec_first=='') {
            $sec_first->price=$price->price;
        }
        if ($sec_last=='') {
            $sec_last->price=$price->price;
        }
        if ($sec->min=='') {
            $sec->min=$price->price;
        }
        if ($sec->max=='') {
            $sec->max=$price->price;
        }

        $date=[
            'min'=>$sec->min,
            'max'=>$sec->max,
            'first'=>$sec_first->price,
            'last'=>$sec_last->price,
            'num'=>$sec->all_count,
            'time'=>$start_time,
        ];
        Day_line::insert($date);
    }
}
