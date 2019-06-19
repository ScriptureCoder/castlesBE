<?php

namespace App\Http\Resources;

use App\Models\State;
use Illuminate\Http\Resources\Json\Resource;

class LocalityFilter extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "locality_id"=> $this->id,
            "state_id"=> $this->state_id,
            "label"=> $this->name.", ".State::find($this->state_id)->name
        ];
    }
}
