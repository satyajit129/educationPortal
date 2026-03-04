<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionCategory extends Model
{
    protected $guarded = [];

        /* Parent category */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_category_id');
    }

    /* Child categories */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_category_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'category_id');
    }
    public function childrenRecursive()
    {
        return $this->hasMany(self::class, 'parent_category_id')
                    ->with('childrenRecursive');
    }
}
