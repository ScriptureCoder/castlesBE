<?php

namespace App\Http\Controllers\Statics;

use App\Models\City;
use App\Models\Country;
use App\Models\Feature;
use App\Models\Label;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticContoller extends Controller
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

    public function cities($state_id)
    {
        $data = City::where("state_id", $state_id);
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ]);
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
