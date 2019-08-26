<?php

namespace App\Http\Resources;

use App\Models\Alert;
use Illuminate\Http\Resources\Json\Resource;

class AlertResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $alert = Alert::find($this->id);
        $search = $alert->search;

        return [
            "id"=> $this->id,
            "search"=> [
                'id'=> $search->id,
                'bedrooms'=> $search->bedrooms,
                'bathrooms'=> $search->bathrooms,
                'locality'=> $search->locality?$search->locality->name:"",
                'state'=> $search->state?$search->state->name:"",
            ]

        ];
    }
}
