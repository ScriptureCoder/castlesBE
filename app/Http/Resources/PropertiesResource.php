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
                "whatsapp"=> $agent->whatsapp,
                "username"=> $agent->username
            ],
            "title" => $this->title,
            "slug"=> $this->slug,
            "price"=> $this->price,
            "description"=> str_limit($this->description,"150","..."),
            "status"=> $this->property_status_id?$property->status->name:"",
            "type"=> $this->property_type_id?$property->type->name:"",
            "featured"=> !!$this->featured,
            "label"=> $this->label_id? $property->label->name:"",
            "image"=> $this->image_id? env("STORAGE") !== "local"? env("STORAGE_PATH")."".$property->image->path:url("/storage/".$property->image->path):"",
            "bedrooms"=> $this->bedrooms,
            "bathrooms"=> $this->bathrooms,
            "toilets"=> $this->toilets,
            "furnished"=> !!$this->furnished,
            "serviced"=> !!$this->serviced,
            "parking"=> $this->parking,
            "total_area"=> $this->total_area,
            "state"=> $this->state_id? $property->state->name:"",
            "locality"=> $this->locality_id? $property->locality->name:"",
            "address"=> $this->address,
            "created_at"=> $this->created_at->diffForHumans(),
        ];
    }
}
