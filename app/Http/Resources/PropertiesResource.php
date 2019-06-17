<?php

namespace App\Http\Resources;

use App\Models\Property;
use App\User;
use Illuminate\Http\Resources\Json\Resource;

class PropertiesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $agent = User::find($this->user_id);
        $property = Property::find($this->id);

        return [
            "id"=> $this->id,
            "agent"=> [
                "id" => $agent->id,
                "name"=> $agent->name,
                "phone"=> $agent->phone,
                "username"=> $agent->username
            ],
            "title" => $this->title,
            "slug"=> $this->slug,
            "price"=> $this->price,
            "description"=> str_limit($this->description,"150","..."),
            "status"=> $this->property_status_id?$property->status->name:"",
            "type"=> $this->property_type_id?$property->type->name:"",
            "featured"=> $this->featured,
            "image"=> $this->image_id? $property->image->url:"",
            "bedrooms"=> $this->bedrooms,
            "bathrooms"=> $this->bathrooms,
            "toilets"=> $this->toilets,
            "state"=> $this->state_id? $property->state->name:"",
            "country"=> $this->state_id? $property->country->name:"",
            "city"=> $this->city_id? $property->city->name:"",
            "address"=> $this->address,
            "approved"=> $this->approved,
            "views"=> $this->views,
            "created_at"=> $this->created_at
        ];
    }
}