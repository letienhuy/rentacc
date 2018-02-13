<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListAcc extends Model
{
    protected $table = "list_acc";
    public function category(){
        return $this->hasOne('App\Category', 'id', 'game_id');
    }
    public function shop(){
        return $this->hasOne('App\User', 'id', 'shop_id');
    }
    public function history(){
        return $this->hasMany('App\History', 'acc_id', 'id');
    }
}
