<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    protected $table = "recharge";
    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
