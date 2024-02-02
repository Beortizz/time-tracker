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
             
        $nightHours = 0;
        $dayHours = 0;
    
        for ($hour = clone $startTime; $hour->lt($endTime); $hour->addHour()) {
            if ($hour->hour >= 5 && $hour->hour < 22) {
                $dayHours++;
            } else {
                $nightHours++;
            }
        }

    
        return ['night_hours' => $nightHours, 'day_hours' => $dayHours];
    }
    
}
