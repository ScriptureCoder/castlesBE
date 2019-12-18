<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\AgentsResource;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function index()
    {
        $data = User::find(Auth::id());

        return response()->json([
            "status"=> 1,
            "data"=> new UserResource($data),
        ],200);
    }

    public function agents(Request $request)
    {
//        $data = User::where("email_verified_at", !null)->where('role_id', 2)->paginate($request->paginate?$request->paginate:10);
        $data = User::where('role_id', 2)->paginate($request->paginate?$request->paginate:10);
        AgentsResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }

    public function update(UserRequest $request)
    {
        $username = User::where('username', $request->username)->first();
        $data = User::find(Auth::id());
        if (!$username || $data->id === $username->id){
            $data->name = $request->name;
            $data->username = $request->username;
            $data->address = $request->address;
            $data->bio = $request->bio;
            $data->phone = $request->phone;
            $data->whatsapp = $request->whatsapp;
            $data->country_id = $request->country_id;
            $data->state_id = $request->state_id;
            $data->save();

            return response()->json([
                'status'=> 1,
                'message'=> "Updated Successfully!"
            ]);
        }

        return response()->json([
            'status'=> 0,
            'errors'=> ["username"=>["Username already exists"]]
        ]);

    }

    public function check()
    {
        return Auth::id();
    }

    public function picture()
    {
        $data = User::find(Auth::id());
        $data->image_id = $this->image(request("image"), Auth::id());
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Uploaded Successfully!",
        ],200);
    }

    public static function image($request, $user)
    {
        $image = Storage::disk(env("STORAGE"))->put('/profile', $request);
        $data = new Image();
        $data->category = "profile";
        $data->path= $image;
        $data->user_id= $user;
        $data->save();

        return $data->id;
    }


}
