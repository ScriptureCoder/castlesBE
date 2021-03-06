<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyView extends Model
{
    public function project()
    {
        return $this->belongsTo(Property::class);
    }
}
