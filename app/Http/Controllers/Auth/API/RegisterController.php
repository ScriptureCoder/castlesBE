<?php

namespace App\Http\Controllers\Auth\API;

use App\Http\Controllers\Statics\Mailer;
use App\Http\Requests\RegisterRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Route;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user= new User();
        $user->username= $request->username;
        $user->email= $request->email;
        $user->password= bcrypt($request->password);
        $user->role_id= $request->role > 2?1:$request->role;
        $user->remember_token= base64_encode(Str::uuid());
        $user->save();
        if (!Subscriber::where('email',$request->email)->first()) {
            $sub= new Subscriber();
            $sub->name= $request->username;
            $sub->email= $request->email;
            $sub->save();
        }

        /*Send welcome email with activation link*/
        $link = env("APP_URL")."/activate/".$user->remember_token;
        $email = [
            "subject"=> 'Welcome to '.env("APP_NAME"),
            'email' => $request->email,
            "html"=> "<p>Hello $request->username, <br> kindly click on the link bellow to activate your account <br> <a href='$link'>$link</a></p>"
        ];

        Mailer::send($email);
        /*Authenticate user and return token*/
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
            $sub->name= $request->name;
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
        $user=User::find(auth()->id());
        $link = env("APP_URL")."/activate/".$user->remember_token;
        $email = [
            "subject"=> 'Welcome to'.env("APP_NAME").'confirmation code',
            "html"=> "<p>Hello $user->username, <br> kindly follow the link bellow to activate your account <br> <a href='$link'>$link</a></p>"
        ];
        Mailer::send($email);

        $response['status'] = 1;
        $response['message']= "Sent Successfully!, Please check your email";
        return response()->json($response, 200);
    }


    public function activate(Request $request,$token)
    {
        $user= User::where("id", Auth::id())->where("remember_token", $token)->first();

        if ($user && $user->email_verified_at === null)
        {
            $user->email_verified_at = Carbon::now();
            $user->save();

            $response['status'] = 1;
            $response['message']= "Account activated successfully!";
            $response['data']= new UserResource($user);
            return response()->json($response, 200);
        }
        $response['status'] = 0;
        $response['error']= "Invalid activation link!";
        return response()->json($response, 422);
    }

}
