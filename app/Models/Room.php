<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\VisitorReservation;

class Room extends Model
{
    use HasFactory;

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function reservationVisitors()
    {
        return $this->hasMany(VisitorReservation::class);
    }

    public function usersInRoom($beginDate, $endDate)
    {

        $beginDate = new Carbon($beginDate);
        $endDate = new Carbon($endDate);
        $result = collect([]);

        foreach ($this->reservationVisitors as $resaVisitor)
        {
            $reservation = Reservation::find($resaVisitor->reservation_id);
            if ( $reservation->isBetweenDates($beginDate, $endDate) )
            {
//                 dd($reservation);
                $visitor = Visitor::find($resaVisitor->visitor_id);
                $result->push($visitor);
            }
        }
        return $result;


    }
}
