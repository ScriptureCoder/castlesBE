<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ProjectViewsResource;
use App\Models\Property;
use App\Models\PropertyReport;
use App\Models\PropertyRequest;
use App\Models\PropertyView;
use App\Models\Subscriber;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
        $data = collect();
        $data->push([
            'users'=>User::count(),
            'agents'=>User::where('role_id', 2)->count(),
            'individuals'=>User::where('role_id', 1)->count(),
            'subscribers'=>Subscriber::count(),
            'properties'=> Property::count(),
            'reports'=> PropertyReport::count(),
            'requests'=> PropertyRequest::count(),
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

        $query= PropertyView::when($date, function ($query) use ($date) {
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

        $query= PropertyView::where('property_id', $id)
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

    public function view($id)
    {

        return;
    }
}
