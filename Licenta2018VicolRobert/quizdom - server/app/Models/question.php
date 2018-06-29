<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    protected $fillable = [
        'text'
    ];


      public function problem () {
   		return $this->hasMany(problem::class);
   }
   
}