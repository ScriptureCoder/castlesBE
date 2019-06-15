<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index()
    {
        $data = User::find(Auth::id());

        return;
    }

    public function edit()
    {
        $data = User::find(Auth::id());
        return;
    }

    public function update(UserRequest $request)
    {
        $data = User::find(Auth::id());
        $data->name = $request->name;
        $data->username = $request->username;
        $data->address = $request->address;
        $data->phone = $request->phone;
        $data->country_id = $request->country_id;
        $data->state_id = $request->state_id;
        $data->image_id = $request->image?$this->image(Auth::id(), $request->image):null;
        return response()->json([
            'status'=> 1,
            'message'=> "Updated Successfully!"
        ]);
    }

    public static function image($user_id, $image){
        return ;
    }


}
