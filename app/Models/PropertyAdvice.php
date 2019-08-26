<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyAdvice extends Model
{
    public function articles()
    {
        return $this->hasMany(Article::class, "category_id");
    }
}
