<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class problem extends Model
{
    protected $fillable = [
        'question_id',
        'owner_id',
        'right_id',
        'bad1_id',
        'bad2_id',
        'bad3_id',
        'category_id',
        'answers',
        'mistakes',
        'checked'
    ];

    public function user () {
   		return $this->belongsTo(user::class);
   }

    public function user_problem () {
   		return $this->belongsToMany(user_problem::class);
   }
    
    public function category () {
        return $this->belongsTo(category::class);
    }
    
    public function question () {
        return $this->belongsTo(question::class);
    }
    
    public function answer () {
        return $this->belongsTo(answer::class);
    }
}