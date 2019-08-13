<?php

namespace App\Http\Controllers\Statics;

use App\Models\Country;
use App\Models\Feature;
use App\Models\Label;
use App\Models\Locality;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticController extends Controller
{
    public function countries()
    {
        $data = Country::all();
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ]);
    }

    public function states()
    {
        $data = State::all();
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ]);
    }

    public function localities($state_id)
    {
        $data = Locality::where("state_id", $state_id)->get();
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ]);
    }

    public function saveLocality(Request $request)
    {
        $request->validate([
            "state_id"=> "required|integer",
            "name"=> "required|string"
        ]);

        $data = new Locality();
        $data->state_id = $request->state_id;
        $data->name = $request->name;
        $data->save();

        return response()->json([
            "status"=> 1,
            "data"=> [
                "id"=> $data->id
            ],
        ],200);
    }

    public function labels()
    {
        $data = Label::all();
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ]);
    }

    public function features()
    {
        $data = Feature::all();
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ]);
    }

    public function statuses()
    {
        $data = PropertyStatus::all();
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ]);
    }

    public function types()
    {
        $data = PropertyType::all();
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ]);
    }
}
