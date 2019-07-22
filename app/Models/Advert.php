<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    public function properties()
    {
        return $this->belongsToMany(Property::class);
    }

}
