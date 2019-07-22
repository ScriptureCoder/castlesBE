<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\AdvertResource;
use App\Http\Resources\Agent\PropertiesResource;
use App\Models\Advert;
use App\Models\AdvertProperty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdvertController extends Controller
{
    public function list(Request $request)
    {
        $data = Advert::orderBy('id', "DESC")
            ->paginate($request->paginate?$request->paginate:10);
        AdvertResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }

    public function properties($id)
    {
        $data = Advert::findOrFail($id);
        PropertiesResource::collection($data->properties);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }

    public function addProperties(Request $request, $id)
    {
        foreach ($request->id as $prop){
            $data = new AdvertProperty();
            $data->advert_id = $id;
            $data->property_id = $prop;
            $data->save();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Added Successfully!",
        ],200);
    }

    public function save(Request $request)
    {
        $request->validate([
            "title"=> "required|string",
            "days"=> "required|integer"
        ]);

        $days = $request->days;
        $data = $request->id?Advert::findOrFail($request->id):new Advert();
        $data->title = $request->title;
        $data->description = $request->description;
        $data->days = $days;
        $data->image = $request->image?Storage::disk(env("STORAGE"))->put('/advert/images', $request->image):"";
// set expiry date
        $data->expired_at = new Carbon("+ $days days");
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Created Successfully!",
        ],200);
    }

    public function refresh(Request $request)
    {
        if (is_array($request->id)){
            foreach ($request->id as $id){
                $data = Advert::findOrFail($id);
                $days = $data->days;
                $data->expired_at = new Carbon("+ $days days");
                $data->save();
            }
        }else{
            $data = Advert::findOrFail($request->id);
            $days = $data->days;
            $data->expired_at = new Carbon("+ $days days");
            $data->save();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Successful",
        ],200);
    }

    public function delete(Request $request)
    {
        if (is_array($request->id)) {
            foreach ($request->id as $id){
                $data = Advert::findOrFail($id);
                $data->delete();
            }

        }else{
            $data = Advert::findOrFail($request->id);
            $data->delete();
        }


        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }
}
