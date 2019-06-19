<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    
}
