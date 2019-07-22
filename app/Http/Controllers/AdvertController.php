<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdvertResource;
use App\Http\Resources\PropertiesResource;
use App\Models\Advert;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    public function list(Request $request)
    {
        $data = Advert::where("expired_at", ">", Carbon::now())
            ->orderBy('id', "DESC")
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


}
