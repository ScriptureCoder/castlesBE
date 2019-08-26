<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ImageResource extends Resource
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
            "src"=> env("STORAGE") !== "local"? env("STORAGE_PATH")."".$this->path:url("/storage/".$this->path)
        ];
    }
}
