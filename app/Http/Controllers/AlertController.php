<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlertResource;
use App\Models\Alert;
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
