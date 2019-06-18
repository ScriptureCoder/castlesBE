<?php

namespace App\Http\Controllers\Agent;

use App\Http\Requests\PropertyRequest;
use App\Http\Resources\Agent\PropertiesResource;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PropertiesController extends Controller
{

    public function index()
    {
        $data = Auth::user()->properties()->paginate(request("paginate")?request("paginate"):10);

        $data= collect($data);

        return response()->json([
            "status"=> 1,
            "data"=> new PropertiesResource($data),
            "pagination"=> $data
        ],200);
    }


    public function save(PropertyRequest $request)
    {
        $slug = preg_replace("/[^a-zA-Z]/","-",strtolower($request->title));
        $check = Property::where('slug', $slug)->first();

        $data = $request->id? Property::findOrFail($request->id) : new Property();
        $data->user_id = Auth::id();
        $data->title = $request->title;
        if ($check && $check->id !== $request->id){
            $data->slug = $slug."-".rand(1000, 50000);
        }else{
            $data->slug = $slug;
        }
        $data->price = $request->price;
        $data->description = $request->description;
        $data->property_status_id = $request->status_id;
        $data->property_type_id = $request->type_id;
        $data->bedrooms = $request->bedrooms;
        $data->bathrooms = $request->bathrooms;
        $data->toilets = $request->toilets;
        $data->furnished = $request->furnished === !null;
        $data->serviced = $request->serviced === !null;
        $data->parking = $request->parking;
        $data->total_area = $request->total_area;
        $data->covered_area = $request->covered_area;
        $data->address = $request->address;
//        $data->country_id = $request->country_id;
        $data->state_id = $request->state_id;
        $data->locality_id = $request->locality_id;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Saved Successfully!",
            "data"=> [
                "id"=> $data->id,
            ]
        ],200);
    }


}
