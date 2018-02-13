<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "category";
    public function listAcc(){
        return $this->hasMany('App\ListAcc', 'game_id', 'id');
    }
    public function history(){
        return $this->hasMany('App\History', 'game_id', 'id');
    }
}
