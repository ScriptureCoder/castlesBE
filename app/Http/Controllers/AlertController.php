<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Statics\Mailer;
use App\Http\Resources\AlertResource;
use App\Models\Alert;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    public function index()
    {
        $data = Auth::user()->alerts()->paginate();
        AlertResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }


    public function save(Request $request)
    {
        $request->validate([
            'search_id' => 'required|integer',
        ]);

        $data = new Alert();
        $data->search_id = $request->id;
        if ($request->auth){
            $data->user_id = $request->auth["id"];
        }else{
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email',
            ]);
            $data->name = $request->name;
            $data->email = $request->email;
        }
        $data->search_id = $request->search_id;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Successful!",
        ],200);
    }

    public function check()
    {
        $alerts = Alert::all();
        $collect = collect();
        foreach ($alerts as $alert){
            $search = Alert::find($alert->id)->search;

            $status = $search->status_id;
            $type = $search->type_id;
            $bedrooms = $search->bedrooms;
            $bathrooms = $search->bathrooms;
            $toilets = $search->toilets;
            $label = $search->label_id;
            $state = $search->state_id;
            $locality = $search->locality_id;
            $min = $search->min_price;
            $max = $search->max_price;

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
                ->count();

            if ($query > 0){
                $collect->search($alert->id);
            }
        }
        return $collect->unique();
    }

    public function sendAlert($collect)
    {
        foreach ($collect as $id){
            $data = Alert::find($id);

            $email = [
                "subject"=> "Property Alert",
                'email' => $data->email,
//                "html"=> "<p>Hello $fname, <br> kindly click on the link below to verify your account <br> <a href='$link'>$link</a></p>"
            ];

            Mailer::send($email);
        }

        return ;
    }


    public function delete($id)
    {
        $data = Alert::where("user_id", Auth::id())->findOrFail($id);
        $data->delete();

        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }
}
