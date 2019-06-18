<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyGallery extends Model
{
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
