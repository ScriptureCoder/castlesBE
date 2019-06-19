<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    public function property()
    {
        return $this->belongsToMany(Property::class, "property_features");
    }
}
