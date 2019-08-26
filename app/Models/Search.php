<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    public function alert()
    {
        return $this->hasOne(Alert::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function status()
    {
        return $this->belongsTo(PropertyStatus::class, "status_id");
    }

    public function type()
    {
        return $this->belongsTo(PropertyType::class, "type_id");
    }
}
