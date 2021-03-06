<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    public function status()
    {
        return $this->belongsTo(PropertyStatus::class,"property_status_id");
    }

    public function type()
    {
        return $this->belongsTo(PropertyType::class, "property_type_id");
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, "property_features");
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    public function gallery()
    {
        return $this->belongsToMany(Image::class, "property_galleries");
    }

    public function reviews()
    {
        return $this->hasMany(PropertyReview::class);
    }

    public function views()
    {
        return $this->hasMany(PropertyView::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

}
