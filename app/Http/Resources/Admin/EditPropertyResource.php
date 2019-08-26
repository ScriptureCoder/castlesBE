<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\FeaturesResource;
use App\Http\Resources\GalleryResource;
use App\Models\Property;
use Illuminate\Http\Resources\Json\Resource;

class EditPropertyResource extends Resource
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
            "agent_id" => $this->user_id,
            "slug"=> $this->slug,
            "price"=> $this->price,
            "description"=> $this->description,
            "status_id"=> $this->property_status_id,
            "type_id"=> $this->property_type_id,
            "featured"=> !!$this->featured,
            "label_id"=> $this->label_id,
            "image"=> $this->image_id? env("STORAGE") !== "local"? env("STORAGE_PATH")."".$property->image->path:url("/storage/".$property->image->path):"",
            "bedrooms"=> $this->bedrooms,
            "bathrooms"=> $this->bathrooms,
            "toilets"=> $this->toilets,
            "furnished"=> !!$this->furnished,
            "serviced"=> !!$this->serviced,
            "parking"=> $this->parking,
            "total_area"=> $this->total_area,
            "covered_area"=> $this->covered_area,
            "state_id"=> $this->state_id,
            "locality_id"=> $this->locality_id,
            "address"=> $this->address,
            "published"=> !!$this->published,
            "pictures"=> GalleryResource::collection($property->gallery),
            "features"=> FeaturesResource::collection($property->features),
        ];
    }
}
