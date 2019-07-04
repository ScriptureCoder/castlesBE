<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PropertyRequest extends Model
{
    public function type()
    {
        return $this->belongsTo(PropertyType::class, "type_id");
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }
}
