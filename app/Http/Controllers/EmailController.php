<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Statics\Mailer;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $email = [
            "subject"=> $request->subject,
            'email' => $request->user_id,
            "html"=> $request->message
        ];

        Mailer::send($email);
        return response()->json([
            'status'=> 1,
            'message'=> "Sent Successfully!"
        ]);
    }

    public function save(Request $request)
    {
        $email = new Email();
        $email->user_id = Auth::id();
        $email->subject = $request->subject;
        $email->message = $request->message;

        return response()->json([
            'status'=> 1,
            'message'=> "Sent Successfully!"
        ]);
    }
}
