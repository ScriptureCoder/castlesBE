<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Resources\Json\Resource;

class GalleryResource extends Resource
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
            "image"=> env("STORAGE") !== "local"? env("STORAGE_PATH")."".Image::find($this->id)->path:url("/storage/".Image::find($this->id)->path),
        ];
    }
}
