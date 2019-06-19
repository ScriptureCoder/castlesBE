<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyGallery extends Model
{
    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function setCreatedAtAttribute($value)
    {
        // to Disable updated_at
    }

    public function setUpdatedAtAttribute($value)
    {
        // to Disable updated_at
    }
}
