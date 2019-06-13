<?php

namespace App;

use App\Models\Country;
use App\Models\Image;
use App\Models\Property;
use App\Models\Role;
use App\Models\State;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);

    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }


}
