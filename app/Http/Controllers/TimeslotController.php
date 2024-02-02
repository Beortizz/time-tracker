<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timeslot;
use Carbon\Carbon;
use DateTime;

class TimeslotController extends Controller
{
    public function index()
    {
        $timeslots = Timeslot::orderBy("created_at", "asc")->select('id','start_time', 'end_time')->get();
        $totalNightHours = 0;
        $totalDayHours = 0;

        $timeslotsWithNightAndDayHours = $timeslots->map(function ($timeslot) use (&$totalNightHours, &$totalDayHours) {
            $hours = $timeslot->calculateNightAndDayHours();
            $timeslot->night_hours = $hours['night_hours'];
            $timeslot->day_hours = $hours['day_hours'];
            $totalNightHours += $hours['night_hours'];
            $totalDayHours += $hours['day_hours'];
            return $timeslot;
        });

        return response()->json([
            'timeslots' => $timeslotsWithNightAndDayHours,
            'totalNightHours' => $totalNightHours,
            'totalDayHours' => $totalDayHours,
        ], 200);
    }

    public function store(Request $request)
    {
        $startTime = Carbon::instance(new DateTime($request->start_time));
        $endTime = Carbon::instance(new DateTime($request->end_time));

        if ($endTime->lessThan($startTime)) {
            return response()->json(['error' => 'A hora de término não pode ser anterior à hora de início.'], 422);
        }

        if ($startTime->diffInHours($endTime) > 24) {
            return response()->json(['error' => 'A diferença entre a hora de início e a hora de término não pode exceder 24 horas.'], 422);
        }

        $timeslot = Timeslot::create([
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return response()->json(['message' => 'Timeslot created', 'timeslot' => $timeslot], 201);
    }

    public function update(Request $request, $id)
    {
        $startTime = Carbon::instance(new DateTime($request->start_time));
        $endTime = Carbon::instance(new DateTime($request->end_time));

        if ($endTime->lessThan($startTime)) {
            return response()->json(['error' => 'A hora de término não pode ser anterior à hora de início.'], 422);
        }

        if ($startTime->diffInHours($endTime) > 24) {
            return response()->json(['error' => 'A diferença entre a hora de início e a hora de término não pode exceder 24 horas.'], 422);
        }
        $timeslot = Timeslot::find($id);
        $timeslot->update([
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
        return response()->json(['message' => 'Timeslot updated', 'timeslot' => $timeslot]);
    }

    public function destroy(Timeslot $timeslot)
    {
        Timeslot::destroy($timeslot->id);
        return response()->json(['message' => 'Timeslot deleted', 'timeslot' => $timeslot]);
    }

}
