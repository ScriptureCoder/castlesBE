<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function myImages()
    {
        $data = Image::where('user_id', Auth::id())
            ->paginate(request('paginate')?request('paginate'):10);
        ImageResource::collection($data);

        return response()->json([
            'status'=> 1,
            'data'=> $data
        ]);
    }


    public function upload(Request $request)
    {
        $urls = collect();

        foreach ($request->images as $image) {
            $image = Storage::disk(env("STORAGE"))->put('/uploads', $image);
            $data = new Image();
            $data->category = "uploads";
            $data->path= $image;
            $data->user_id= Auth::id();
            $data->save();

            $url = env("STORAGE") !== "local"? env("STORAGE_PATH")."".$data->path:url("/storage/".$data->path);

            $urls->push($url);
        }

        return response()->json([
            'status'=> 1,
            'data'=> $urls
        ]);
    }

}
