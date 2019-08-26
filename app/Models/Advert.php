<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    protected $dates = ['expired_at'];

    public function properties()
    {
        return $this->belongsToMany(Property::class, "advert_properties");
    }

}
