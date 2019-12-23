<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocalityFilter;
use App\Http\Resources\PropertiesResource;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\StateFilter;
use App\Models\Locality;
use App\Models\Property;
use App\Models\PropertyReport;
use App\Models\PropertyRequest;
use App\Models\PropertyView;
use App\Models\SavedProperty;
use App\Models\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Route;

class PropertiesController extends Controller
{
    public function exploreCount()
    {
        return response()->json([
            "status"=> 1,
            "data"=> [
                "schools"=> Property::where('property_type_id',19)->count(),
                "lands"=> Property::where('property_type_id',15)->count(),
                "houses"=> Property::where('property_type_id',14)->count(),
                "apartments"=> Property::where('property_type_id',2)->count(),
            ],
        ],200);
    }

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
        ],200);
    }

    public function agent(Request $request, $id)
    {
        $agent = User::findOrFail($id);
        $data = Property::where("published", 1)->where('user_id', $id)->paginate($request->paginate?$request->paginate:10);
        PropertiesResource::collection($data);

        return response()->json([
            "status"=> 1,
            "agent"=> [
                "id"=> $agent->id,
                "name"=> $agent->name,
                "email"=> $agent->email,
                "bio"=> $agent->bio,
                "address"=> $agent->address,
                "state"=> $agent->state?$agent->state->name:"",
                "phone"=> $agent->phone,
                "image"=> $agent->image_id? env("STORAGE") !== "local"? env("STORAGE_PATH")."".$agent->image->path:url("/storage/".$agent->image->path):"",
            ],
            "data"=> $data,
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

        foreach ($locality as $lo){
            $data->push(new LocalityFilter($lo));
        }

        return response()->json([
            'status'=> 1,
            'data'=> $data
        ]);
    }

    public function view(Request $request, $slug)
    {
        $data = Property::where("slug",$slug)->first();

        /*persist views for analytics par day*/
        $view = PropertyView::whereDate('created_at', '=', date('Y-m-d'))->first();
        if (!$view)
            $view = new PropertyView();
        $view->property_id = $data->id;
        $view->views++;
        $view->save();

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

    public function save(Request $request)
    {
        $data =  SavedProperty::where("user_id", auth()->id())->where("property_id", $request->property_id)->first();
        if ($data){
            $data->delete();

            return response()->json([
                "status"=> 1,
                "message"=> "Removed Successfully!",
            ],200);
        }else{
            $data = new SavedProperty();
            $data->user_id = auth()->id();
            $data->property_id = $request->property_id;
            $data->save();

            return response()->json([
                "status"=> 1,
                "message"=> "Saved Successfully!",
            ],200);
        }

    }

    public function viewSaved(Request $request)
    {
        $data = Auth::user()->savedProperties()->paginate($request->paginate?$request->paginate:10);
        PropertiesResource::collection($data);
        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }

    public function report(Request $request)
    {
        Property::findOrFail($request->property_id);
        $request->validate([
            'report' => 'required|string',
        ]);

        $data = new PropertyReport();
        $data->property_id = $request->property_id;
        $data->user_id = Auth::id();
        $data->report = $request->report;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Report Sent Successfully!",
        ],200);
    }

    public function request(Request $request)
    {
//        return $request;
        $request->validate([
            'type_id' => 'required|integer',
            'category_id' => 'required|integer',
            'budget' => 'integer',
            'bedrooms' => 'integer',
            'state_id' => 'required|integer',
            'locality_id' => 'required|integer',
            'role_id' => 'required|integer',
        ]);

        $data = new PropertyRequest();
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
        $data->type_id = $request->type_id;
        $data->status_id = $request->category_id;
        $data->budget = $request->budget;
        $data->bedrooms = $request->bedrooms;
        $data->state_id = $request->state_id;
        $data->locality_id = $request->locality_id;
        $data->role_id = $request->role_id;
        $data->comment = $request->comment;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Request Submitted Successfully!",
        ],200);
    }

}
