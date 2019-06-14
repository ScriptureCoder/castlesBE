<?php

namespace App\Http\Controllers\Auth\API;

use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Client;
use Route;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validate([
            'username' => 'required|alpha_dash|string|max:25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user= new User();
        $user->username= $request->username;
        $user->email= $request->email;
        $user->password= bcrypt($request->password);
        $user->role_id= $request->role_id;
        $user->status= 0;
        $user->save();
        $id= $user->id;
        if (!Subscriber::where('email',$request->email)->first()) {
            $sub= new Subscriber();
            $sub->email= $request->email;
            $sub->save();
        }
//        Mail::send(new WelcomeMail($id));
        $client = Client::where('password_client', 1)->first();
        /*Authenticate user and return access token*/
        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => null,
        ]);
        // Fire off the internal request.
        $token = Request::create(
            'oauth/token',
            'POST'
        );
        return Route::dispatch($token);
    }
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:100|unique:subscribers',
        ]);
        if (!Subscriber::where('email',$request->email)->first()) {
            $sub= new Subscriber();
            $sub->email= $request->email;
            $sub->save();
            $response['status'] = 1;
            $response['message']= "Subscription Successful!";
            return response()->json($response, 200);
        }
        $response['status'] = 0;
        $response['error']= "Email already exist!";
        return response()->json($response, 200);

    }
    public function resend()
    {
        $id= Auth::id();
        $user=User::find($id);
        if ($user->code < 1)
        {
            $user->code = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            $user->save();
        }
        Mail::send(new WelcomeMail($id));
        $response['status'] = 1;
        $response['message']= "Sent Successfully!, Please check your email";
        return response()->json($response, 200);
    }
    public function activate(Request $request)
    {
        $user= User::find(Auth::id());
        if ($user->status == 1)
        {
            $response['status'] = 1;
            $response['message']= "Account activated!";
            $response['data']= new UserResource($user);
            return response()->json($response, 200);
        }
        elseif ($request->code > 0 && $request->code == $user->code)
        {
            $user->status = 1;
            $user->save();
            $response['status'] = 1;
            $response['message']= "Account activated successfully!";
            $response['data']= new UserResource($user);
            return response()->json($response, 200);
        }
        $response['status'] = 0;
        $response['error']= "Invalid Activation Code!";
        return response()->json($response, 422);
    }
}
