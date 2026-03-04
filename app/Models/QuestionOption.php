<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $table = 'question_options';
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class);

    
    }
    public $timestamps = false; // ✅ disables auto timestamps
}
