<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\MagazineResource;
use App\Models\Magazine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MagazineController extends Controller
{
    public function index(Request $request)
    {
        $data = Magazine::paginate($request->paginate?$request->paginate:10);
        MagazineResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }


    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'string|max:1000',
            'file' => 'required',
        ]);
        $data = new Magazine();
        $data->title = $request->title;
        $data->description = $request->description;
        $data->image = $request->image?Storage::disk(env("STORAGE"))->put('/magazines/images/', $request->image):"";
        $data->file = Storage::disk(env("STORAGE"))->put('/magazines/', $request->file);

        return response()->json([
            "status"=> 1,
            "message"=> "Save Successfully!",
        ],200);
    }


    public function download($id)
    {
        $data = Magazine::findOrFail($id);
        return Storage::disk(env("STORAGE"))->download($data->file);
    }


    public function delete($id)
    {
        $data = Magazine::findOrFail($id);
        $data->delete();

        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }
}
