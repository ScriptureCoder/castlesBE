<?php

namespace App\Http\Resources;

use App\Models\Search;
use Illuminate\Http\Resources\Json\Resource;

class SearchResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = Search::find($this->id);

        return [
            'id'=> $this->id,
            'bedrooms'=> $this->bedrooms,
            'type'=> $data->type_id?$data->type->name:'',
            'status'=> $data->status_id?$data->status->name:"",
            'min_price'=> $data->min_price,
            'max_price'=> $data->max_price,
            'locality'=> $data->locality_id?$data->locality->name:"",
            'state'=> $data->state_id?$data->state->name:"",
            'created_at'=> $data->created_at->diffForHumans()
        ];
    }
}
