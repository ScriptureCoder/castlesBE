<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PropertyReport extends Model
{
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
