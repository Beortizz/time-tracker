<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTime;

class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
    ];

    public function calculateNightAndDayHours()
    {
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->start_time);
        $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->end_time);
    
        $nightMinutes = 0;
        $dayMinutes = 0;
    
        for ($time = clone $startTime; $time->lt($endTime); $time->addMinute()) {
            if ($time->hour >= 5 && $time->hour < 22) {
                $dayMinutes++;
            } else {
                $nightMinutes++;
            }
        }
    
        $nightHours = round($nightMinutes / 60, 2);
        $dayHours = round($dayMinutes / 60, 2);
    
        return ['night_hours' => $nightHours, 'day_hours' => $dayHours];
    }

  
    
}
