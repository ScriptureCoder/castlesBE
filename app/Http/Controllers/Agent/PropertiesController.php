<?php

namespace App\Http\Controllers\Agent;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\PropertyRequest;
use App\Http\Resources\Agent\PropertiesResource;
use App\Http\Resources\Agent\PropertyResource;
use App\Http\Resources\GalleryResource;
use App\Models\Image;
use App\Models\Property;
use App\Models\PropertyGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertiesController extends Controller
{

    public function index()
    {
        $data = Auth::user()->properties()->paginate(request("paginate")?request("paginate"):10);

        return response()->json([
            "status"=> 1,
            "data"=> PropertiesResource::collection($data),
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

    public function gallery($property_id)
    {
        $data = Property::where("user_id", Auth::id())->findOrFail($property_id)->gallery;
        return response()->json([
            "status"=> 1,
            "data"=> GalleryResource::collection($data)
        ]);
    }


    public function featureImage(Request $request, $id)
    {
        $image = Image::where("user_id", Auth::id())->findOrFail($request->image_id);
        $data = Property::where("user_id", Auth::id())->findOrFail($id);
        $data->image_id = $image->id;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Image featured successfully!"
        ]);
    }

    public function removeFromGallery($id, $image)
    {
        $property= Property::where("user_id", Auth::id())->findOrFail($id);
        $data = PropertyGallery::where("user_id", Auth::id())->where("image_id", $image)->where("property_id",$id);
        Storage::delete($data->first()->image->path);
        $data->first()->image()->delete();
        $data->delete();
        if ($property->image_id == $image){
            $property->image_id = null;
            $property->save();
        }
        return response()->json([
            "status"=> 1,
            "message"=> "Remove successfully!"
        ]);
    }

    public function addToGallery(ImageRequest $request,$property_id)
    {
        $property = Property::findOrFail($property_id);

        if (is_array($request->images)){
            foreach ($request->images as $image) {
                $data = new PropertyGallery();
                $data->image_id= \App\Http\Controllers\Admin\PropertiesController::image($image,Auth::id());
                $data->property_id= $property_id;
                $data->save();
                if (!$property->image)
                    $property->image_id= $data->image_id;
                $property->save();
            }
            return response()->json([
                "status"=> 1,
                "message"=> count($request->images)." Uploaded Successfully!",
            ],200);
        }else{
            $data = new PropertyGallery();
            $data->image_id= $this->image($request->image,Auth::id());
            $data->property_id= $property_id;
            $data->save();
            return response()->json([
                "status"=> 1,
                "message"=> "1 Uploaded Successfully!",
            ],200);
        }

    }

    public function view($id)
    {
        $data = Property::findOrFail($id);

        return response()->json([
            "status"=> 1,
            "data"=> new PropertyResource($data),
        ],200);
    }

    public function delete($id)
    {
        $data = Property::where("user_id", Auth::id())->findOrFail($id);
        $data->delete();

        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }



}
