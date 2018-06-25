<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subordinate extends Model
{
    //
    protected $table="subordinates";

    public function nickname(){
        return $this->hasOne(User::class,'id','subordinate_id');
    }
}
