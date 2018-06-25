<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class My_miner extends Model
{
    protected $table="my_miners";
    protected $fillable=['user_id','yield','die_time','class','price','cycle','level','order','created_at'];
}
