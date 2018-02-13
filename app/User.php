<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'facebook_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function history(){
        return $this->hasMany('App\History', 'user_id','id');
    }
    public function shopHistory(){
        return $this->hasMany('App\History', 'shop_id','id');
    }
    public function listAcc(){
        return $this->hasMany('App\ListAcc', 'shop_id','id');
    }
    public function recharge(){
        return $this->hasMany('App\Recharge', 'user_id','id');
    }
    public function withdraw(){
        return $this->hasMany('App\Withdraw', 'user_id','id');
    }
    public function bank(){
        return $this->hasOne('App\Bank', 'user_id','id');
    }
}
