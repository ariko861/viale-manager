<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function getIsAvailableAttribute(/*$beginDate, $endDate*/)
    {
//         foreach ($this->reserationVisitors as $reservationVisitor) {
//             if ( $reserationVisitors->pivot->contact )
//             {
//                 return $visitor;
//             }
        $visitorReservation = $this->reservationVisitors();

//         $visitorReservations = VisitorReservation::query()
//             ->whereDate('arrivaldate', '<=', $endDate)
//             ->whereDate('departuredate', '>=', $beginDate)
//             ->get();

    }
}
