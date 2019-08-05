<?php

namespace App\Http\Resources;

use App\Models\PropertyReport;
use Illuminate\Http\Resources\Json\Resource;

class PropertyReportResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $report = PropertyReport::find($this->id);


        return [
            'id'=> $this->id,
            'user'=> [
                'id'=>$this->user_id,
                'name'=> $report->user->name,
            ],
            'property'=> [
                'id'=> $report->property->id,
                'title'=> $report->property->title
            ],
            'report'=> $this->report,
            "created_at"=> $this->created_at->diffForHumans()

        ];
    }
}
