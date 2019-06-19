<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $dates=['expired_at'];

    public function setUpdatedAt($value)
    {
    }
}
