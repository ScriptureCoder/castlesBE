<?php

namespace App\Http\Resources\Agent;

use App\Http\Resources\FeaturesResource;
use App\Http\Resources\GalleryResource;
use App\Models\Image;
use App\Models\Property;
use Illuminate\Http\Resources\Json\Resource;

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
        $property = Property::find($this->id);

        return [
            "id"=> $this->id,
            "title" => $this->title,
            "slug"=> $this->slug,
            "price"=> $this->price > 0? $this->price:"Price on call",
            "description"=> $this->description,
            "status"=> $this->property_status_id?$property->status->name:"",
            "type"=> $this->property_type_id?$property->type->name:"",
            "featured"=> !!$this->featured,
            "label"=> $this->label_id? $property->label->name:"",
            "image"=> $this->image_id? env("STORAGE") !== "local"? env("STORAGE_PATH")."".$property->image->path:url("/storage".$property->image->path):"",
            "bedrooms"=> $this->bedrooms,
            "bathrooms"=> $this->bathrooms,
            "toilets"=> $this->toilets,
            "furnished"=> !!$this->furnished,
            "serviced"=> !!$this->serviced,
            "parking"=> $this->parking,
            "total_area"=> $this->total_area,
            "covered_area"=> $this->covered_area,
            "state"=> $this->state_id? $property->state->name:"",
            "locality"=> $this->locality_id? $property->locality->name:"",
            "address"=> $this->address,
            "published"=> !!$this->published,
            "views"=> $property->views()->sum("views"),
            "pictures"=> GalleryResource::collection($property->gallery),
            "features"=> FeaturesResource::collection($property->features),
            "created_at"=> $this->created_at->diffForHumans()
        ];
    }
}
