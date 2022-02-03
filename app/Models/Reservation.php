<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VisitorReservation;

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

    protected $casts = [
        'nodeparturedate' => 'boolean',
        'confirmed' => 'boolean',
        'otherVisitorsAuthorized' => 'boolean',

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

    public function getNonContactVisitors()
    {
        $visitors = collect([]);
//         return $this->belongsToMany(Visitor::class)->wherePivot('contact', false);
        foreach ( $this->visitors as $visitor )
        {
            if (! $visitor->pivot->contact)
            {
                $visitors->push($visitor);
            }
        }
        return $visitors;
    }


    public function visitors()
    {
        return $this->belongsToMany(Visitor::class, 'visitor_reservation')->using(VisitorReservation::class)->withPivot('contact', 'room_id', 'id', 'price');
    }

    public function links()
    {
        return $this->hasMany(ReservationLink::class);
    }

}
