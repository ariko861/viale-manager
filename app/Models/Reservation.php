<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'arrivaldate', 'departuredate',
    ];

    protected $attributes = [
        'nodeparturedate' => false,
        'confirmed' => false,
        'otherVisitorsAuthorized' => false,
    ];

    protected $appends = ['arrival', 'departure', 'contact_person'];

    public function getArrivalAttribute()
    {
        $date = new Carbon($this->arrivaldate);
        return $date->format('d F Y');;
    }

    public function getDepartureAttribute()
    {
        if ($this->nodeparturedate)
        {
            return __("Pas de date de départ définie");
        }
        else {
            $date = new Carbon($this->departuredate);
            return $date->format('d F Y');;
        }
    }

    public function getContactPersonAttribute()
    {
        foreach ($this->visitors as $visitor) {
            if ( $visitor->pivot->contact )
            {
                return $visitor;
            }
        }

    }

    public function isBetweenDates($beginDate, $endDate)
    {
        $departureDate = new Carbon($this->departuredate);
        $arrivalDate = new Carbon($this->arrivaldate);
        return ( $arrivalDate <= $endDate ) && ( $departureDate >= $beginDate || $this->nodeparturedate );
    }




    public function visitors()
    {
        return $this->belongsToMany(Visitor::class, 'visitor_reservation')->using(VisitorReservation::class)->withPivot('contact', 'room_id', 'id');
    }

}
