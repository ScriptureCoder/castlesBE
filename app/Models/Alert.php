<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    public function search()
    {
        return $this->belongsTo(Search::class);
    }

}
