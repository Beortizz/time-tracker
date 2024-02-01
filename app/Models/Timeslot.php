<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
    ];

    public function calculateNightAndDayHours()
    {
        $start = Carbon::createFromFormat('H:i', $this->start_time);
        $end = Carbon::createFromFormat('H:i', $this->end_time);

        $nightHours = 0;
        $dayHours = 0;

        $diffInHours = $start->diffInHours($end);

        for ($i = 0; $i < $diffInHours; $i++) {
            if ($start->hour >= 22 || $start->hour < 5) {
                $nightHours++;
            } else {
                $dayHours++;
            }

            $start->addHour();
        }

        return ['night_hours' => $nightHours, 'day_hours' => $dayHours];
    }
}
