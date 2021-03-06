<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
