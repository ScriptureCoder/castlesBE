<?php

namespace App\Http\Resources;

use App\Models\Image;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\User;
use Illuminate\Http\Resources\Json\Resource;
use phpDocumentor\Reflection\DocBlock\Tags\Property;

class PropertyResource extends Resource
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
            "slug"=> $this->slug,
            "price"=> $this->price,
            "description"=> $this->description,
            "status"=> $this->property_status_id?$property->status->name:"",
            "type"=> $this->property_type_id?$property->type->name:"",
            "featured"=> !!$this->featured,
            "label"=> $this->label_id? $property->label->name:"",
            "image"=> $this->image_id? $property->image->url:"",
            "bedrooms"=> $this->bedrooms,
            "bathrooms"=> $this->bathrooms,
            "toilets"=> $this->toilets,
            "furnished"=> !!$this->furnished,
            "serviced"=> !!$this->serviced,
            "parking"=> $this->parking,
            "total_area"=> $this->total_area,
            "covered_area"=> $this->covered_area,
            "state"=> $this->state_id? $property->state->name:"",
            "country"=> $this->state_id? $property->country->name:"",
            "locality"=> $this->locality_id? $property->locality->name:"",
            "address"=> $this->address,
            "published"=> $this->published,
            "views"=> $this->views,
            "pictures"=> GalleryResource::collection($property->gallery),
            "features"=> FeaturesResource::collection($property->features),
            "created_at"=> $this->created_at->diffForHumans()
        ];
    }
}
