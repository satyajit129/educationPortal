<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $guarded = [];
    protected $table = "user_answers";

    public function attempt()
    {
        return $this->belongsTo(UserAttempt::class, 'user_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
