<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class PicturesMigrate extends Resource
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
            "src"=> base64_encode(Storage::get(Image::find($this->id)->path))
        ];
    }
}
