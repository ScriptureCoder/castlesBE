<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\MigrateResource;
use App\Models\Image;
use App\Models\Property;
use App\Models\PropertyGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OfflineController extends Controller
{
    public function getAll()
    {
        $data = Property::all();
        return response()->json([
            "status"=> 1,
            "data"=> MigrateResource::collection($data),
        ],200);
    }


    public function migrate(Request $request)
    {
        foreach ($request->data as $request) {
            $slug = preg_replace("/[^a-zA-Z]/","-",strtolower($request['title']));
            $check = Property::where('slug', $slug)->first();

            $data = new Property();
            $data->user_id = $request['agent_id']?$request['agent_id']:Auth::id();
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

            foreach ($request['pictures'] as $image) {
                $photo = new PropertyGallery();
                $photo->image_id= $this->image($image['src'],$data->user_id);
                $photo->property_id= $data->id;
                $photo->save();
                if (!$data->image)
                    $data->image_id= $photo->image_id;
                $data->save();
            }

        }

        return response()->json([
            "status"=> 1,
            "message"=> "Properties added successfully!",
        ],200);
    }

    public static function image($request, $user)
    {
        $fileName= str_random(50).'.png';

        $image = Storage::disk(env("STORAGE"))->put('/properties/'.$fileName, base64_decode($request));
        $data = new Image();
        $data->category = "properties";
        $data->path= "/properties/$fileName";
        $data->user_id= $user;
        $data->save();

        return $data->id;
    }
}
