<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\PropertyRequest;
use App\Http\Resources\Agent\PropertiesResource;
use App\Http\Resources\Agent\PropertyResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\PropertyReportResource;
use App\Http\Resources\PropertyRequestResource;
use App\Models\Image;
use App\Models\Property;
use App\Models\PropertyGallery;
use App\Models\PropertyReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertiesController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $type = $request->type;
        $approved = $request->approved;
        $featured = $request->featured;
        $bedrooms = $request->bedrooms;
        $bathrooms = $request->bathrooms;
        $toilets = $request->toilets;
        $furnished = $request->furnished;
        $label = $request->label_id;
        $state = $request->state_id;
        $locality = $request->locality_id;

        $query= Property::when($status, function ($query) use ($status) {
                return $query->where('property_status_id', $status);})
            ->when($type, function ($query) use ($type) {
                return $query->where('property_type_id', $type);})
            ->when($approved, function ($query) use ($approved) {
                return $query->where('approved', $approved);})
            ->when($featured, function ($query) use ($featured) {
                return $query->where('featured', $featured);})
            ->when($bedrooms, function ($query) use ($bedrooms) {
                return $query->where('bedrooms', $bedrooms);})
            ->when($bathrooms, function ($query) use ($bathrooms) {
                return $query->where('bathrooms', $bathrooms);})
            ->when($toilets, function ($query) use ($toilets) {
                return $query->where('toilets', $toilets);})
            ->when($furnished, function ($query) use ($furnished) {
                return $query->where('furnished', $furnished);})
            ->when($label, function ($query) use ($label) {
                return $query->where('label_id', $label);})
            ->when($state, function ($query) use ($state) {
                return $query->where('state_id', $state);})
            ->when($locality, function ($query) use ($locality) {
                return $query->where('locality_id', $locality);})
            ->orderBy('id', 'DESC');

        $data = $query->paginate($request->paginate?$request->paginate:10);
        PropertiesResource::collection($data);
        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }


    public function save(PropertyRequest $request)
    {
        $slug = preg_replace("/[^a-zA-Z]/","-",strtolower($request->title));
        $check = Property::where('slug', $slug)->first();

        $data = $request->id? Property::findOrFail($request->id) : new Property();
        $data->user_id = $data->agent_id?$data->agent_id:Auth::id();
        $data->featured = $request->featured === !null;
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

    public function multiple(Request $request)
    {
        foreach ($request->request as $request) {
            $slug = preg_replace("/[^a-zA-Z]/","-",strtolower($request['title']));
            $check = Property::where('slug', $slug)->first();

            $data = new Property();
            $data->user_id = $data->agent_id?$data->agent_id:Auth::id();
            $data->featured = $request['featured'] === !null;
            $data->title = $request['title'];
            if ($check){
                $data->slug = $slug."-".rand(1000, 50000);
            }else{
                $data->slug = $slug;
            }
            $data->price = $request['price'];
            $data->description = $request['description'];
            $data->property_status_id = $request['status_id'];
            $data->property_type_id = $request['type_id'];
            $data->bedrooms = $request['bedrooms'];
            $data->bathrooms = $request['bathrooms'];
            $data->toilets = $request['toilets'];
            $data->furnished = $request['furnished'] === !null;
            $data->serviced = $request['serviced'] === !null;
            $data->parking = $request['parking'];
            $data->total_area = $request['total_area'];
            $data->covered_area = $request['covered_area'];
            $data->address = $request['address'];
            $data->state_id = $request['state_id'];
            $data->locality_id = $request['locality_id'];
            $data->save();

        }
        return response()->json([
            "status"=> 1,
            "message"=> "Properties added successfully!",
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


    /**
     * $request param is the file to be uploaded
     * $user param is the user_id of the uploader
    */
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

    public function featureImage(Request $request, $id)
    {
        $image = Image::findOrFail($request->image_id);
        $data = Property::findOrFail($id);
        $data->image_id = $image->id;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Image featured successfully!"
        ]);
    }

    public function gallery($property_id)
    {
        $data = Property::findOrFail($property_id)->gallery;
        return response()->json([
            "status"=> 1,
            "data"=> GalleryResource::collection($data)
        ]);
    }

    public function removeFromGallery($id, $image)
    {
        $property= Property::findOrFail($id);
        $data = PropertyGallery::where("image_id", $image)->where("property_id",$id);
        Storage::delete($data->first()->image->path);
        $data->first()->image()->delete();
        $data->delete();
        if ($property->image_id == $image){
            $property->image_id = null;
            $property->save();
        }
        return response()->json([
            "status"=> 1,
            "message"=> "Removed successfully!"
        ]);
    }

    public function addToGallery(ImageRequest $request,$property_id)
    {
        $property = Property::findOrFail($property_id);

        if (is_array($request->images)){
            foreach ($request->images as $image) {
                $data = new PropertyGallery();
                $data->image_id= $this->image($image,Auth::id());
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


    public function delete(Request $request)
    {
        if (is_array($request->id)) {
            foreach ($request->id as $id) {
                $data = Property::find($id);
                $data->delete();
            }
        }else{
            $data = Property::findOrFail($request->id);
            $data->delete();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }

    public function destroy(Request $request)
    {
        if (is_array($request->id)){
            foreach ($request->id as $id) {
                $data = Property::withTrashed()->findOrFail($id);
                $data->forceDelete();
            }
        }else{
            $data = Property::withTrashed()->findOrFail($request->id);
            $data->forceDelete();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }

    public function approve(Request $request)
    {
        if (is_array($request->id)){
            foreach ($request->id as $id){
                $data = Property::findOrFail($id);
                $data->published = true;
                $data->save();
            }
        }else{
            $data = Property::findOrFail($request->id);
            $data->published = true;
            $data->save();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Successful!",
        ],200);
    }


    public function disapprove(Request $request)
    {
        if (is_array($request->id)){
            foreach ($request->id as $id){
                $data = Property::findOrFail($id);
                $data->published = false;
                $data->save();
            }
        }else{
            $data = Property::findOrFail($request->id);
            $data->published = false;
            $data->save();
        }

        return response()->json([
            "status"=> 1,
            "message"=> "Successful!",
        ],200);
    }

    public function requests()
    {
        $data = \App\Models\PropertyRequest::orderBy('id', 'DESC')->paginate(request("paginate")?request("paginate"):10);
        PropertyRequestResource::collection($data);

        return response()->json([
            "status"=> 1,
            'data'=> $data
        ]);
    }

    public function reports(Request $request)
    {
        $data = PropertyReport::orderBy('id', 'DESC')->paginate(request("paginate")?request("paginate"):10);
        PropertyReportResource::collection($data);

        return response()->json([
            "status"=> 1,
            'data'=> $data
        ]);
    }

    public function propertyReports($id)
    {
        $data = PropertyReport::where('property_id', $id)->orderBy('id', 'DESC')->paginate(request("paginate")?request("paginate"):10);
        PropertyReportResource::collection($data);

        return response()->json([
            "status"=> 1,
            'data'=> $data
        ]);
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
