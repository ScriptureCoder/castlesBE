<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function category()
    {
        return $this->belongsTo(PropertyAdvice::class, "category_id");
    }

    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }
}
