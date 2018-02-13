<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = "withdraw";
    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
