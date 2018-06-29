<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class onesignal extends Model
{
    protected $table = 'onesignal';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'user_id'
    ];

    public function user () {
        return $this->hasMany(User::class);
    }
}