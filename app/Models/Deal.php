<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $table="deals";
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }


    //对方获取自己昵称
    public function nickname(){
        return $this->hasOne(User::class,'id','user_id');
    }
    //自己获取对方昵称
    public function deal_nickname(){
        return $this->hasOne(User::class,'id','deal_id');
    }

    //卖出或买入  查询已有订单数量
    //$type  1买入  2卖出
    public static function deal_num($user_id,$type,$today){
        $count=Deal::where('user_id',$user_id)->where('type',$type)->where('create_time','>',$today)->whereIn('state',[0,1])->count();
        return $count;
    }
}
