<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $fillable = [
        'name',
        'best_id',
        'best_value',
        'description'
    ];

    public function user () {
   		return $this->belongsTo(user::class);
    }
    
    public function problem () {
   		return $this->hasMany(problem::class);
    }
   
    public function admin () {
        return $this->hasMany(admin::class);
    }
}