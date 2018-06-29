<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_problem extends Model
{
    protected $fillable = [
        'user_id',
        'problem_id',
        'answered',
        'reported'
    ];

    public function user () {
   		return $this->belongsToMany(user::class);
   }

      public function problem () {
   		return $this->hasOne(problem::class);
   }
   
}