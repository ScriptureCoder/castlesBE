<?php

namespace App\Http\Resources;

use App\Models\Property;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class MigrateResource extends Resource
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
            "agent_id"=> $this->user_id,
            "price"=> $this->price,
            "description"=> $this->description,
            "status_id"=> $this->property_status_id,
            "type_id"=> $this->property_type_id,
            "featured"=> !!$this->featured,
            "label_id"=> $this->label_id,
            "image"=> $request->image_id?base64_encode(Storage::get($property->image->path)):"",
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
            "views"=> $property->views()->sum("views"),
            "pictures"=> PicturesMigrate::collection($property->gallery),
//            "features"=> FeaturesResource::collection($property->features),
        ];
    }
}
