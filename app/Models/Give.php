<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Give extends Model
{
    protected $table = 'gives';
    protected $fillable = ['phone','class','num','level','time'];
    public $timestamps = false;
}
