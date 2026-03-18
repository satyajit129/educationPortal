<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUniqueCode
{
    /**
     * Generate a unique code for the model
     *
     * @param string $column
     * @param int $length
     * @return string
     */
    public function generateUniqueCode(string $column = 'code', int $length = 6): string
    {
        do {
            $code = strtoupper(Str::random($length));
        } while ($this->where($column, $code)->exists());

        return $code;
    }
}