<?php

namespace App\Models;

use App\User;
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

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
