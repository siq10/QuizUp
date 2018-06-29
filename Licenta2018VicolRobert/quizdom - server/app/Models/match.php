<?php
/**
 * Created by PhpStorm.
 * User: siq13
 * Date: 6/1/18
 * Time: 3:15 PM
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class match extends Model
{
    protected $fillable = [
        'user_id1',
        'user_id2',
        'winner',
        'category_id',
        'odds1',
        'odds2',
        'xp1',
        'xp2'

    ];

    public function user() {
        return $this->belongsToMany(user::class);
    }

    public function match_problem () {
        return $this->belongsTo(user_problem::class);
    }

    public function category () {
        return $this->hasMany(category::class);
    }

}