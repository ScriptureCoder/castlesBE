<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    public function status()
    {
        return $this->belongsTo(PropertyStatus::class);
    }

    public function type()
    {
        return $this->belongsTo(PropertyType::class);
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

    public function labels()
    {
        return $this->belongsToMany(Label::class, "property_labels");
    }

    public function gallery()
    {
        return $this->belongsToMany(Image::class, "property_galleries");
    }

    public function reviews()
    {
        return $this->hasMany(PropertyReview::class);
    }

    public function city()
    {
        return $this->belongsToMany(City::class);
    }

}
