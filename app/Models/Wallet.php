<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table="wallets";
    protected $fillable = ['user_id'];
    //计算当前金额
    public static function now($user_id){
        $wallet=Wallet::where('user_id',$user_id)->first();
        if(empty($wallet)){
            $wallet_pta=0;
        }else{
            $wallet_pta=$wallet->coin;
        }

        $interim=Interim::where('user_id',$user_id)->where('state',0)->where('time','>',time())->sum('coin');
        $money=$wallet_pta-$interim;
        return $money;
    }
}
