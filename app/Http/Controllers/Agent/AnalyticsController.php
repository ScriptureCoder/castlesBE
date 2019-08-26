<?php

namespace App\Http\Controllers\Agent;

use App\Http\Resources\ProjectViewsResource;
use App\Models\Property;
use App\Models\PropertyView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $data = collect();
        $data->push([
            'properties'=> Property::where('user_id', Auth::id())->count(),
        ]);

        return response()->json([
            "status"=> 1,
            "data"=> $data
        ],200);
    }

    public function project(Request $request)
    {
        $date = $request->date;
        $from = date($request->from);
        $to = date($request->to);

        $query= PropertyView::where('user_id', Auth::id())->when($date, function ($query) use ($date) {
            return $query->whereDate('created_at', date($date));})
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', array($from, $to));});

        $data = $query->paginate($request->paginate?$request->paginate:10);
        ProjectViewsResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }

    public function projectViews(Request $request,$id)
    {
        $date = $request->date;
        $from = date($request->from);
        $to = date($request->to);

        $query= PropertyView::where('user_id', Auth::id())->where('property_id', $id)
            ->when($date, function ($query) use ($date) {
                return $query->whereDate('created_at', date($date));})
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', array($from, $to));});

        $data = $query->paginate($request->paginate?$request->paginate:10);
        ProjectViewsResource::collection($data);

        return response()->json([
            "status"=> 1,
            "data"=> $data,
        ],200);
    }
}
