<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','tokens','superuser_id','imgpath'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function user_problem () {
        return $this->hasMany(user_problem::class);
    }
    
    public function problem () {
        return $this->hasMany(problem::class);
    }
    
    public function category () {
        return $this->hasMany(category::class);
    }
}
