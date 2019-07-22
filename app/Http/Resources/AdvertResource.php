<?php

namespace App\Http\Resources;

use App\Models\Advert;
use Illuminate\Http\Resources\Json\Resource;

class AdvertResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $advert = Advert::find($this->id);
        return [
            'id'=> $this->id,
            'title'=> $this->title,
            'description'=> $this->description,
            'days'=> $this->days,
            'image'=> env("STORAGE") !== "local"? env("STORAGE_PATH")."".$this->image:url("/storage/".$this->image),
            'expired_at'=> $this->expired_at->diffForHumans(),
            'total_properties'=> $advert->properties->count()
        ];
    }
}
