<?php

namespace App\Http\Resources;

use App\Models\PropertyRequest;
use Illuminate\Http\Resources\Json\Resource;

class PropertyRequestResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $property = PropertyRequest::find($this->id);

        return [
            "id"=> $this->id,
            "user"=> $this->user_id?[
                "name"=> $property->user->name,
                "email"=> $property->user->email
            ]:[
                "name"=> $this->name,
                "email"=> $this->email
            ],
            "comment"=> $this->comment,
            "budget"=> $this->budget,
            "type"=> $this->type_id?$property->type->name:"",
            "bedrooms"=> $this->bedrooms,
            "state"=> $this->state_id? $property->state->name:"",
            "locality"=> $this->locality_id? $property->locality->name:"",
            "created_at"=> $this->created_at->diffForHumans()
        ];
    }
}
