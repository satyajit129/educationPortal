<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAttempt extends Model
{
    protected $table = "user_attempts";

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }
    public function exam()
{
    return $this->belongsTo(Exam::class);
}
}
