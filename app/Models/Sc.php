<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sc extends Model
{
    protected $table = 'sc';
    protected $fillable = ['user_id','num','interest','start_time','end_time','add'];
    public $timestamps = false;
}
