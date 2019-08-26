<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertiesResource;
use App\Http\Resources\SearchResource;
use App\Models\Property;
use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'status_id' => 'integer',
            'type_id' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'toilets' => 'integer',
            'parking' => 'integer',
            'max_price' => 'integer',
            'min_price' => 'integer',
            'state_id' => 'integer',
            'locality_id' => 'integer',
        ]);

        $status = $request->status_id;
        $type = $request->type_id;
        $bedrooms = $request->bedrooms;
        $bathrooms = $request->bathrooms;
        $toilets = $request->toilets;
        $label = $request->label_id;
        $state = $request->state_id;
        $locality = $request->locality_id;
        $min = $request->min_price;
        $max = $request->max_price;

        $query= Property::where("published", 1)
            ->when($status, function ($query) use ($status) {
                return $query->where('property_status_id', $status);})
            ->when($type, function ($query) use ($type) {
                return $query->where('property_type_id', $type);})
            ->when($bedrooms, function ($query) use ($bedrooms) {
                return $query->where('bedrooms', ">=", $bedrooms);})
            ->when($bathrooms, function ($query) use ($bathrooms) {
                return $query->where('bathrooms',">=", $bathrooms);})
            ->when($toilets, function ($query) use ($toilets) {
                return $query->where('toilets',">=", $toilets);})
            ->when($label, function ($query) use ($label) {
                return $query->where('label_id', $label);})
            ->when($state, function ($query) use ($state) {
                return $query->where('state_id', $state);})
            ->when($locality, function ($query) use ($locality) {
                return $query->where('locality_id', $locality);})
            ->when($min, function ($query) use ($min) {
                return $query->where('price', ">=", $min);})
            ->when($max, function ($query) use ($max) {
                return $query->where('price',"<=", $max);})
            ->orderBy('id', 'DESC');

        $data = $query->paginate($request->paginate?$request->paginate:10);
        PropertiesResource::collection($data);

        $search = $this->save($request);

        return response()->json([
            "search_id"=> $search,
            "status"=> 1,
            "data"=> $data
        ],200);
    }

    public function save($request)
    {
        $data = new Search();
        $data->bedrooms = $request->bedrooms;
        $data->bathrooms = $request->bathrooms;
        $data->type_id = $request->type_id;
        if ($request->auth){
            $data->user_id = $request->auth['id'];
        }
        $data->status_id = $request->status_id;
        $data->min_price = $request->min_price;
        $data->max_price = $request->max_price;
        $data->locality_id = $request->locality_id;
        $data->state_id = $request->state_id;
        $data->save();

        return $data->id;
    }

    public function history()
    {
        $data = Search::where('user_id', Auth::id())->paginate(\request('paginate')?\request('paginate'):10);
        SearchResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }


}
