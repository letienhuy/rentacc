<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = "history";
    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function shop(){
        return $this->hasOne('App\User', 'id', 'shop_id');
    }
    public function account(){
        return $this->hasOne('App\ListAcc', 'id', 'acc_id');
    }
    public function category(){
        return $this->hasOne('App\Category', 'id', 'game_id');
    }
}
