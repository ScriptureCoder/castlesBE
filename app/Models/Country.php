<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
