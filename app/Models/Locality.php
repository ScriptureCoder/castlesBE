<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
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
