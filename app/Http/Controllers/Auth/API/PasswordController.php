<?php

namespace App\Http\Controllers\Auth\API;

use App\Http\Controllers\Statics\Mailer;
use App\Http\Requests\ResetRequest;
use App\Models\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $user = User::where("email", $request->email);
        if ($user){
            $token = new PasswordReset();
            $token->user_id = $user->id;
            $token->token = base64_encode(Str::uuid());
            $token->expired_at = new Carbon("+24hrs");
            $token->save();
            $data = [
                'email' => $request->email,
                'subject' => $request->subject,
                'html'=> "<p> Hello <a href='$token->token'></a></p>"
            ];

            Mailer::send($data);

            $response['status'] = 1;
            $response['message'] = "We have emailed your password reset link!";
            return response()->json($response, 200);
        }
        $response['status'] = 0;
        $response['message'] = "Email does not exist!";
        return response()->json($response, 200);
    }

    public function resetPassword(ResetRequest $request)
    {
        $token = PasswordReset::where("token", $request->token)->where('expired_at','>=', Carbon::now());
        if ($token){
            $data = User::find($token->user_id);
            $data->password = bcrypt($request->password);
            $data->save();

            $response['status'] = 1;
            $response['message']= "Password changed!, kindly login to continue!";
            return response()->json($response, 200);
        }

    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user= Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password= bcrypt($request->password);
            $user->save();

            $response['status'] = 1;
            $response['message']= "Password changed successfully!";
            return response()->json($response, 200);
        }
        $response['status'] = 0;
        $response['error']= "Incorrect old password!";
        return response()->json($response, 422);
    }
}
