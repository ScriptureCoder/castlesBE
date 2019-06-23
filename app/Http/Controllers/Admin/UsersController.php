<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Statics\Mailer;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\Admin\UsersResource;
use App\Models\Subscriber;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->role;
        $name = $request->name;
        $email = $request->email;
        $state = $request->state;
//        $verified = $request->verified?$request->verified == "true"?1:0:false;
//        $ver = $verified == 1?!null:null;

        $query = User::where("deleted_at", null)
            ->when($role, function ($query) use ($role) {
                return $query->where('role_id', $role);})
            ->when($name, function ($query) use ($name) {
                return $query->where('name', 'LIKE', '%'.$name.'%');})
            ->when($email, function ($query) use ($email) {
                return $query->where('email', $email);})
            ->when($state, function ($query) use ($state) {
                return $query->where('state_id', $state);})
//            ->when($verified, function ($query) use ($ver) {
//                return $query->where('email_verified_at', $ver);})
            ->orderBy('id', 'DESC');

        $data = $query->paginate($request->paginate?$request->paginate:10);

        UsersResource::collection($data);
        return response()->json([
            "status"=> 1,
            "data"=> $data
        ],200);
    }

    public function view($id)
    {
        $user= User::findOrFail($id);

        return response()->json([
            "status"=> 1,
            "data"=> new UsersResource($user)
        ],200);
    }

    public function create(RegisterRequest $request)
    {
        $user= new User();
        $user->username= $request->username;
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= bcrypt($request->password);
        $user->role_id= $request->role > 4?1:$request->role;
        $user->remember_token= Str::random(100);
        $user->save();

        if (!Subscriber::where('email',$request->email)->first()) {
            $sub= new Subscriber();
            $sub->user_id= $user->id;
            $sub->email= $request->email;
            $sub->save();
        }

        return response()->json([
            'status'=> 1,
            'message'=> 'User created Successfully!',
        ]);
    }

    public function edit(UserRequest $request, $id)
    {
        $user= User::findOrFail($id);
        $user->username= $request->username;
        $user->name= $request->name;
        $user->email= $request->email;
        $user->phone= $request->phone;
        $user->address= $request->address;
        $user->password= bcrypt($request->password);
        $user->role_id= $request->role?$request->role > 4?1:$request->role:1;
        $user->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Edited Successfully!",
        ],200);
    }

    public function activate($id)
    {
        $user= User::findOrFail($id);
        $user->email_verified_at = new Carbon();
        $user->save();

        return response()->json([
            "status"=> 1,
            "message"=> "User Activated Successfully!",
        ],200);
    }


    public function suspend($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
        return response()->json([
            "status"=> 1,
            "message"=> "User Suspended!",
        ],200);
    }


    public function restore($id)
    {
        $data = User::onlyTrashed()->findOrFail($id);
        $data->restore();

        return response()->json([
            "status"=> 1,
            "message"=> "User restored!",
        ],200);
    }


    public function suspended(Request $request)
    {
        $data = User::onlyTrashed()->paginate($request->paginate?$request->paginate:10);;

        UsersResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data ,
        ],200);
    }


    public function delete($id)
    {
        $data = User::withTrashed()->findOrFail($id);
        $data->forceDelete();

        return response()->json([
            "status"=> 1,
            "message"=> "User deleted successfully!" ,
        ],200);
    }




}
