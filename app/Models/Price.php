<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //

    public $timestamps = false;
    protected $fillable = ['id','price','add_name','time'];


    protected $table="prices";

}
