<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $table="income";
    protected $fillable=['user_id','type','num'];
}
