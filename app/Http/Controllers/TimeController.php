<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Time;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    public function index()
    {
        $time = Time::orderBy("created_at","desc")->get();
        return response()->json($time);
    }
    
    public function store(Request $request)
    {
        $time = Time::create($request->all());
        return response()->json($time);
    }
}
