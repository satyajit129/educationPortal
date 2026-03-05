<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $guarded = [];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'year_question');
    }
}
