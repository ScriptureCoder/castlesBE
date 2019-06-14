<?php

namespace App\Http\Controllers\Auth\API;

use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function getToken(Request $request)
    {
        $token = new PasswordReset();
        $token->user_id =
        $data = [
            'email' => $request->email,
            'link' => url("http://getdev.co/password/reset/" . $token),
        ];
        // notify referee
        Mail::send('emails.reset', $data, function ($message) use ($data) {
            $message->to($data['email'])->subject('Reset Password');
            $message->from('hire@getdev.co', 'GetDev.co');
        });
        $response['status'] = 1;
        $response['message'] = "We have e-mailed your password reset link!";
        return response()->json($response, 200);
        $response['status'] = 0;
        $response['error'] = "Email does not exist!";
        return response()->json($response, 404);
    }

    public function reset(ResetRequest $request)
    {
        $token = $request->token;
        if(!User::where('remember_token', $token)->exists()){
            $response['status'] = 0;
            $response['error']= "Invalid Token!";
            return response()->json($response, 422);
        }
        $password= bcrypt($request->password);
        DB::update('update users set password = ? where remember_token = ?',[$password,$token]);
        $response['status'] = 1;
        $response['message']= "Password changed!, kindly login to continue!";
        return response()->json($response, 200);
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
