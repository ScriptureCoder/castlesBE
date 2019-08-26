<?php

namespace App\Http\Controllers\Auth\API;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OauthController extends Controller
{
    public function oauth(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'provider' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user){
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        }else{
            return response()->json([
                "message"=> 'Unauthenticated',
            ],401);
        }

    }

    public function oauthRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'provider' => 'required|string',
            'role' => 'integer|min:1',
        ]);

        $user= new User();
        $user->username= $request->username;
        $user->name= $request->name;
        $user->email= $request->email;
        $user->provider= $request->provider;
        $user->password= bcrypt("passw@@@rd");
        $user->role_id= $request->role > 2?1:$request->role;
        $user->remember_token= Str::random(100);
        $user->save();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
}
