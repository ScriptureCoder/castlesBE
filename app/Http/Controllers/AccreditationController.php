<?php

namespace App\Http\Controllers;

use App\Models\Accreditation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccreditationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Accreditation::where('user_id', Auth::id())
            ->paginate(request('paginate')?request('paginate'):10);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'issuer'=> 'required',
            'issuing_date'=> 'required',
        ]);

        $data = new Accreditation();
        $data->name = $request->name;
        $data->issuer= $request->issuer;
        $data->issuing_date = $request->issuing_date;
        $data->expiry_data =$request->expiry_data;
        $data->ID = $request->ID;
        $data->url = $request->url;
        $data->save();

        return response()->json([
            "status"=> 1,
            "message"=> "Saved Successfully!",
        ],200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accreditation  $accreditation
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $data = Accreditation::findOrFail($id);
        $data->delete();

        return response()->json([
            "status"=> 1,
            "message"=> "Deleted Successfully!",
        ],200);
    }
}
