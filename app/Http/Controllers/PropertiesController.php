<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocalityFilter;
use App\Http\Resources\PropertiesResource;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\StateFilter;
use App\Models\Locality;
use App\Models\Property;
use App\Models\State;
use Illuminate\Http\Request;

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

        $query= Property::where("published", 1)
            ->when($status, function ($query) use ($status) {
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
            "count"=> $query->count(),
        ],200);
    }

    public function filter(Request $request)
    {
        $data = collect();
        $state = State::where('name', 'LIKE', '%'.$request->search.'%')->get();
        $locality = Locality::where('name', 'LIKE', '%'.$request->search.'%')->get();
        foreach ($state as $st){
            $data->push(new StateFilter($st));
        }
        /*foreach ($state as $st){
            $locality = Locality::where("state_id", $st->id)->get();
            foreach ($locality as $lo){
                $data->push(new LocalityFilter($lo));
            }
        }*/

        foreach ($locality as $lo){
            $data->push(new LocalityFilter($lo));
        }

        return response()->json([
            'status'=> 1,
            'data'=> $data
        ]);
    }

    public function view($slug)
    {
        $data = Property::where("slug",$slug)->first();
        $data->views++;
        $data->save();
        if ($data){
            return response()->json([
                "status"=> 1,
                "data"=> new PropertyResource($data),
            ],200);
        }
        return response()->json([
            "status"=> 0,
            "message"=> "Not Found",
        ],404);
    }
}
