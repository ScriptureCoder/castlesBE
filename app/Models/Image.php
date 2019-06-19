<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function properties()
    {
        return $this->hasOne(Property::class);
    }
}
