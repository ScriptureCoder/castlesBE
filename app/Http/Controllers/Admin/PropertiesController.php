<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyRequest;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\PropertiesResource;
use App\Http\Resources\PropertyResource;
use App\Models\Feature;
use App\Models\Image;
use App\Models\Label;
use App\Models\Property;
use App\Models\PropertyGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertiesController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $type = $request->type;
        $approved = $request->approved;

        $query= Property::where("deleted_at", null)
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
        $data->slug = preg_replace("/[^a-zA-Z]/","-",strtolower($request->title));
        $data->price = $request->price;
        $data->description = $request->description;
        $data->property_status_id = $request->status_id;
        $data->property_type_id = $request->type_id;
        $data->featured = $request->featured === !null;
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

    public static function image($request, $user)
    {
        $image = Storage::disk(env("STORAGE"))->put('/properties', $request);
        $data = new Image();
        $data->category = "properties";
        $data->path= $image;
        $data->user_id= $user;
        $data->save();

        return $data->id;
    }

    public function featureImage(Request $request)
    {
        $image = Image::findOrFail($request->image_id)->path;
        $data = Property::find($request->property_id);
        $data->image_id = $image;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Image featured successfully!"
        ]);
    }

    public function gallery($property_id)
    {
        $data = Property::findorFind($property_id)->gallery;
        return response()->json([
            "status"=> 1,
            "data"=> GalleryResource::collection($data)
        ]);
    }

    public function removeFromGallery($id)
    {
        $data = PropertyGallery::find($id)->image;
        $data()->delete();
        Storage::delete($data->path);

        return response()->json([
            "status"=> 1,
            "message"=> "Remove successfully!"
        ]);
    }

    public function addToGallery(Request $request,$property_id)
    {
        if (is_array($request->image)){
            foreach ($request->image as $image) {
                $data = new PropertyGallery();
                $data->image_id= $this->image($image,Auth::id());
                $data->property_id= $property_id;
                $data->save();
            }
        }else{
            $data = new PropertyGallery();
            $data->image_id= $this->image($request->image,Auth::id());
            $data->property_id= $property_id;
            $data->save();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Uploaded Successfully!",
        ],200);
    }



    public function delete($ids)
    {
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $data = Property::findOrFail($id);
                $data->delete();
            }
        }else{
            $data = Property::findOrFail($ids);
            $data->delete();
        }


        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }

    public function destroy($ids)
    {
        if (is_array($ids)){
            foreach ($ids as $id) {
                $data = Property::onlyTrashed()->findOrFail($id);
                $data->forceDelete();
            }
        }else{
            $data = Property::onlyTrashed()->findOrFail($ids);
            $data->forceDelete();
        }

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
