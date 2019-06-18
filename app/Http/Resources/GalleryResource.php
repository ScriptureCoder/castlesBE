<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
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
            "image"=> env("STORAGE") !== "local"? env("STORAGE_PATH").Image::find($this->image_id)->path:url("/storage/".Image::find($this->image_id)->path),
        ];
    }
}