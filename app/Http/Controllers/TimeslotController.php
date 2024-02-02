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
        $timeslots = Timeslot::orderBy("created_at", "asc")->select('id', 'start_time', 'end_time')->get();
        $totalNightHours = 0;
        $totalDayHours = 0;

        $timeslotsWithNightAndDayHours = $timeslots->map(function ($timeslot) use (&$totalNightHours, &$totalDayHours, &$totalNightMinutes, &$totalDayMinutes) {
            $hours = $timeslot->calculateNightAndDayHours();

            $timeslot->night_hours = floor($hours['night_hours']);
            $timeslot->day_hours = floor($hours['day_hours']);

            $nightFractionMinutes = $this->convertFractionToMinutes($hours['night_hours']);
            $dayFractionMinutes = $this->convertFractionToMinutes($hours['day_hours']);

            $totalNightMinutes += $nightFractionMinutes;
            $totalDayMinutes += $dayFractionMinutes;

            $totalNightHours += $hours['night_hours'];
            $totalDayHours += $hours['day_hours'];

            $timeslot->night_minutes = $nightFractionMinutes;
            $timeslot->day_minutes = $dayFractionMinutes;

            return $timeslot;
        });
        
        $totalNightFractionMinutes = $this->convertFractionToMinutes($totalNightHours);
        $totalDayFractionMinutes = $this->convertFractionToMinutes($totalDayHours);

        return response()->json([
            'timeslots' => $timeslotsWithNightAndDayHours->map(function($timeslot){
                return [
                    'id' => $timeslot->id,
                    'start_time' => $timeslot->start_time,
                    'end_time' => $timeslot->end_time,
                    'night_hours' => [
                        'hours' => $timeslot->night_hours,
                        'minutes' => $timeslot->night_minutes,
                    ],
                    'day_hours' => [
                        'hours' => $timeslot->day_hours,
                        'minutes' => $timeslot->day_minutes,
                    ],
                ];
            }),
            'totalNightHours' => [
                'hours' => floor($totalNightHours),
                'minutes' => $totalNightFractionMinutes,
            ],
            'totalDayHours' => [
                'hours' => floor($totalDayHours),
                'minutes' => $totalDayFractionMinutes,
            ],
        ], 200);
    }

    public function convertFractionToMinutes($hours)
    {
        $fractionalHours = $hours - floor($hours);
        $minutes = round($fractionalHours * 60, 2);
        return $minutes;
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

    public function update(Request $request, Timeslot $timeslot)
    {
        $startTime = Carbon::instance(new DateTime($request->start_time));
        $endTime = Carbon::instance(new DateTime($request->end_time));

        if ($endTime->lessThan($startTime)) {
            return response()->json(['error' => 'A hora de término não pode ser anterior à hora de início.'], 422);
        }

        if ($startTime->diffInHours($endTime) > 24) {
            return response()->json(['error' => 'A diferença entre a hora de início e a hora de término não pode exceder 24 horas.'], 422);
        }
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
