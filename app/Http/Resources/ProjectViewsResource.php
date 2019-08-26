<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProjectViewsResource extends Resource
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
            'year'=> $this->created_at->format('Y'),
            'month'=> $this->created_at->format('F'),
            'day'=> $this->created_at->format('l'),
            'date'=> $this->created_at->format('Y-m-d'),
            'views'=> $this->views
        ];
    }
}
