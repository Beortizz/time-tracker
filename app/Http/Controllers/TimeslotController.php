<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timeslot;
use Carbon\Carbon;

class TimeslotController extends Controller
{
    public function index()
    {
       $timeslots = Timeslot::orderBy("created_at", "asc")->select('start_time', 'end_time')->get();


        // $timeslotsWithNightAndDayHours = $timeslots->map(function ($timeslot) {
        //     $hours = $timeslot->calculateNightAndDayHours();
        //     $timeslot->night_hours = $hours['night_hours'];
        //     $timeslot->day_hours = $hours['day_hours'];
        //     return $timeslot;
        // });
    
        return response()->json($timeslots);
    }

    public function store(Request $request)
    {
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);

        // if ($endTime->lessThan($startTime)) {
        //     $endTime->addDay();
        // }

        if ($startTime->diffInHours($endTime) > 24) {
            return response()->json(['error' => 'A diferença entre a hora de início e a hora de término não pode exceder 24 horas.'], 422);
        }

        $timeslot = Timeslot::create($request->all());

        return response()->json(['message' => 'Timeslot created', 'timeslot' => $timeslot]);
    }

    public function update(Request $request, $id)
    {
        // $this->validate($request, [
        //     'start_time' => 'required|date_format:H:i',
        //     'end_time' => 'required|date_format:H:i|after:start_time',
        // ]);
        $timeslot = Timeslot::find($id);
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);
    
        if ($startTime->diffInHours($endTime) > 24) {
            return response()->json(['error' => 'A diferença entre a hora de início e a hora de término não pode exceder 24 horas.'], 422);
        }
        $timeslot->update($request->all());
        return response()->json(['message' => 'Timeslot updated', 'timeslot' => $timeslot]);
    }

    public function destroy($id)
    {
        $timeslot = Timeslot::find($id);
        $timeslot->delete();
        return response()->json(['message' => 'Timeslot deleted']);
    }

}
