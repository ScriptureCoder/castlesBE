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
        $user = User::where("email", $request->email)->first();
        if ($user){
            $token = new PasswordReset();
            $token->user_id = $user->id;
            $token->token = Str::random(100);
            $token->expired_at = new Carbon("+24 hours");
            $token->save();
            $link = env("APP_URL")."/reset_password/".$token->token;

            $data = [
                'email' => $request->email,
                'subject' => $request->subject,
                'html'=> "
                <p><strong>Hello!</strong></p>
            <p>
                You are receiving this email because we received a password reset request for your account.
            </p>

            <div>
                    <a onmouseover='this.style.border=\"white\"' style=\"background-color: blue; border: none; color: white; padding: 10px 30px; text-align: center; text-decoration: none; margin: 5px 0 5px 0;\" href=\"{{$link}}\" target=\"_blank\">Reset Password</a>
            </div>


            <p>If you did not request a password reset, no further action is required.</p>

            <p>Regards,</p>

            <h6>If you're having trouble clicking the \"Reset Password\" button, copy and paste the URL below into your web browser:<br><a href=\"{{$link}}\">{{$link}}</a></h6>
                "
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

    public function resetPassword(ResetRequest $request, $param)
    {
        $token = PasswordReset::where("token", $param)->where('expired_at','>=', Carbon::now())->first();

        if ($token){
            $data = User::find($token->user_id);
            $data->password = bcrypt($request->password);
            $data->save();
            $token->delete();

            $response['status'] = 1;
            $response['message']= "Password changed!, kindly login to continue!";
            return response()->json($response, 200);
        }

        $response['status'] = 0;
        $response['error']= "Invalid token!";
        return response()->json($response, 422);

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
