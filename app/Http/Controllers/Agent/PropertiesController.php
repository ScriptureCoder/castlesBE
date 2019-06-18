<?php

namespace App\Http\Controllers\Agent;

use App\Http\Requests\PropertyRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PropertiesController extends Controller
{
    public function save(PropertyRequest $request)
    {
        $data = $request->id? Property::find($request->id) : new Property();
        $data->user_id = Auth::id();
        $data->title = $request->title;
        $data->slug = str_replace("[^a-zA-Z]","_",$request->title);
        $data->price = $request->price;
        $data->description = $request->description;
        $data->property_status_id = $request->status;
        $data->property_type_id = $request->type;
        $data->featured = $request->featured;
        $data->image_id = $data->image? $this->image($data->image,$data->user_id):"" ;
        $data->bedrooms = $request->bedrooms;
        $data->bathrooms = $request->bathrooms;
        $data->toilets = $request->toilets;
        $data->address = $request->address;
//        $data->country_id = $request->country_id;
        $data->state_id = $request->state_id;
        $data->city_id = $request->city_id;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Saved Successfully!",
        ],200);
    }
}
