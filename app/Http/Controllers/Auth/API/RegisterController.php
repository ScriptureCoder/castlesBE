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
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user'=> new UserResource($user)
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user= new User();
        $user->username= $request->username;
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= bcrypt($request->password);
        $user->role_id= $request->role > 2?1:$request->role;
        $user->remember_token= Str::random(100);
        $user->save();

        if ($request->subscribe && !Subscriber::where('email',$request->email)->first()) {
            $sub= new Subscriber();
            $sub->user_id= $user->id;
            $sub->email= $request->email;
            $sub->save();
        }

        /*Send welcome email with activation link*/
        $link = env("APP_URL")."/activate/".$user->remember_token;
        $fname = explode(" ",$request->name)[0];
        $email = [
            "subject"=> 'Welcome to '.env("APP_NAME"),
            'email' => $request->email,
            "html"=> "<p>Hello $fname, <br> kindly click on the link below to verify your account <br> <a href='$link'>$link</a></p>"
        ];

        Mailer::send($email);
        /*Authenticate user and return token*/
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'status'=> 1,
            'message'=> 'Kindly check your email for activation link',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at,
            'user'=> new UserResource($user)
        ]);
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
        $user=User::find(auth()->id());
        $link = env("APP_URL")."/activate/".$user->remember_token;
        $email = [
            "email"=> $user->email,
            "subject"=> 'Welcome to'.env("APP_NAME").'confirmation code',
            "html"=> "<p>Hello $user->username, <br> kindly follow the link below to verify your account <br> <a href='$link'>$link</a></p>"
        ];
        Mailer::send($email);

        $response['status'] = 1;
        $response['message']= "Sent Successfully!, Please check your email";
        return response()->json($response, 200);
    }


    public function oauth(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'name' => 'required|string',
            'provider' => 'required|string',
            'role' => 'integer',
        ]);

        $user = User::where("email", $request->email)->first();

        if (!$user){
            $user= new User();
            $user->provider= $request->provider;
            $user->name= $request->name;
            $user->email= $request->email;
            $user->password= bcrypt("g3n3r@8p@33w0rd");
            $user->role_id= $request->role?$request->role > 2?1:$request->role:1;
            $user->remember_token= Str::random(100);
            $user->email_verified_at= Carbon::now();
            $user->save();

            if (!Subscriber::where('email',$request->email)->first()) {
                $sub= new Subscriber();
                $sub->user_id= $user->id;
                $sub->email= $request->email;
                $sub->save();
            }
            /*Authenticate user and return token*/
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();



            return response()->json([
                'status'=> 1,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->token->expires_at,
                'user' => new UserResource($user)
            ]);
        }

        /*Authenticate user and return token*/
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'status'=> 1,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at,
            'user' => new UserResource($user)
        ]);

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
