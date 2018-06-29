<?php
/**
 * Created by PhpStorm.
 * User: siq13
 * Date: 6/1/18
 * Time: 4:25 PM
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class match extends Model
{
    protected $fillable = [
        'match_id',
        'problem_id',
        'order'
    ];

    public function match() {
        return $this->hasMany(match::class);
    }

    public function problem () {
        return $this->belongsToMany(problem::class);
    }


}