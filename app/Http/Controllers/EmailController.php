<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Statics\Mailer;
use App\Models\Email;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function send(Request $request)
    {

        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // send by user_id or send by email
        if ($request->user_id)
        {
            $request->validate([
                'user_id' => 'required|integer'
            ]);
            $user = User::find($request->user_id);
        }
        else {
            $request->validate([
                'email' => 'required|email'
            ]);
            $user = User::where('email', $request->email)->first();
        }

        // send email
        $email = [
            "subject"=> $request->subject,
            'email' => $user->email,
            "html"=> $request->message
        ];

        Mailer::send($email);

        // save email to database
        $email = new Email();
        $email->from = Auth::id();
        $email->to = $user->id;
        $email->subject = $request->subject;
        $email->message = $request->message;
        $email->save();

        return response()->json([
            'status'=> 1,
            'message'=> "Sent Successfully!"
        ]);
    }

    public function contact(Request $request)
    {

        $request->validate([
            'email' => 'required|string',
            'name' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);



        $subject = $request->subject;
        $name = $request->name;
        $email = $request->email;
        $message = $request->message;
        // send email
        $email = [
            "subject"=> $subject,
            'email' => env("EMAIL"),
            "html"=> "
            <table>
                <tr>
                    <th>Subject</th>
                    <td>$subject</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>$email</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>$name</td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td>$message</td>
                </tr>
            </table>
            "
        ];

        Mailer::send($email);


        return response()->json([
            'status'=> 1,
            'message'=> "Sent Successfully!"
        ]);
    }

}
