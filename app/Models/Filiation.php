<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Filiation extends Model
{
    //
    use ModelTree,AdminBuilder;
    protected $table="filiations";

    public function get_power(){
        return $this->belongsToMany('App\Models\My_miner','user_id','user_id');
    }


    public function profit(){
        return $this->hasMany(Profit::class,'user_id','user_id');
    }
}
