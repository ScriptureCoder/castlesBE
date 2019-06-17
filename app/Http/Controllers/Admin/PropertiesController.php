<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyRequest;
use App\Http\Resources\PropertiesResource;
use App\Http\Resources\PropertyResource;
use App\Models\Label;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PropertiesController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $type = $request->type;
        $approved = $request->approved;

        if ($request->label) {
            $model = Label::find($request->label)->properties;
        }else{
            $model = Property::all();
        }

        $query= $model
            ->when($status, function ($query) use ($status) {
                return $query->where('property_status_id', $status);})
            ->when($type, function ($query) use ($type) {
                return $query->where('property_type_id', $type);})
            ->when($approved, function ($query) use ($approved) {
                return $query->where('approved', $approved);})
            ->orderBy('id', 'DESC');

        $data = $query->paginate($request->paginate?$request->paginate:10);

        return response()->json([
            "status"=> 1,
            "data"=> PropertiesResource::collection($data),
            "pagination"=> $data,
            "count"=> $query->count(),
        ],200);
    }

    public function view($id)
    {
        $data = Property::findOrFail($id);

        return response()->json([
            "status"=> 1,
            "data"=> new PropertyResource($data),
        ],200);
    }

    public function save(PropertyRequest $request)
    {

        $data = $request->id? Property::find($request->id) : new Property();
        $data->user_id = $data->agent_id?$data->agent_id:Auth::id();
        $data->title = $request->title;
        $data->slug = str_replace("[^a-zA-Z]","_",$request->title);
        $data->price = $request->price;
        $data->description = $request->description;
        $data->property_status_id = $request->status;
        $data->property_type_id = $request->type;
        $data->featured = $request->featured;
        $data->image_id = $request->image;
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

    public function delete($id)
    {
        $data = Property::findOrFail($id);
        $data->delete();
        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }

    public function destroy($id)
    {
        $data = Property::onlyTrashed()->findOrFail($id);
        $data->forceDelete();
        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }

    public function approve($ids)
    {
        if (is_array($ids)){
            foreach ($ids as $id){
                $data = Property::findOrFail($id);
                $data->approved = true;
                $data->save();
            }
        }else{
            $data = Property::findOrFail($ids);
            $data->approved = true;
            $data->save();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Approved Successfully!",
        ],200);
    }


    public function disapprove($ids)
    {
        if (is_array($ids)){
            foreach ($ids as $id){
                $data = Property::findOrFail($id);
                $data->approved = false;
                $data->save();
            }
        }else{
            $data = Property::findOrFail($ids);
            $data->approved = false;
            $data->save();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Approved Successfully!",
        ],200);
    }

    public function trash()
    {
        $data = Property::onlyTrashed()->paginate(request("paginate")?request("paginate"):10);

        return response()->json([
            "status"=> 1,
            "data"=> PropertiesResource::collection($data),
            "pagination"=> $data,
            "count"=> $data->count(),
        ],200);
    }

}
