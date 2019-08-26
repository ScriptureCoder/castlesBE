<?php

namespace App;

use App\Models\Alert;
use App\Models\Country;
use App\Models\Image;
use App\Models\Property;
use App\Models\Role;
use App\Models\Search;
use App\Models\State;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes;

    use HasApiTokens, Notifiable;

    public function findForPassport($identifier) {
        return $this->orWhere('email', $identifier)->orWhere('username', $identifier)->first();
    }

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'deleted_at' => 'datetime',
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

    public function savedProperties()
    {
        return $this->belongsToMany(Property::class, 'saved_properties');
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /*public function search()
    {
        return $this->hasMany(Search::class);
    }*/


}
