<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MagazineResource extends Resource
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
            "id"=> $this->id,
            "title"=> $this->title,
            "image"=> env("STORAGE") !== "local"? env("STORAGE_PATH")."".$this->image:url("/storage/".$this->image),
            "description"=> $this->description,
            "created_at"=> $this->created_at->diffForHumans()
        ];
    }
}
