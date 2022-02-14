<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VisitorReservation;
use App\Support\ReservationCollection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'arrivaldate', 'departuredate',
    ];

    protected $attributes = [
        'nodeparturedate' => false,
        'confirmed' => false,
        'removeFromStats' => false,
    ];

    protected $casts = [
        'nodeparturedate' => 'boolean',
        'confirmed' => 'boolean',
        'removeFromStats' => 'boolean',

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

    public function getVisitorListAttribute()
    {
        $visitors = "";
        foreach ($this->visitors as $visitor) {
            if ($visitors == "")
            {
                $visitors = $visitors.$visitor->full_name;
            } else {
                $visitors = $visitors.", ".$visitor->full_name;
            }
        }
        return $visitors;
    }

    public function getNightsAttribute()
    {
        $begindate = new Carbon($this->arrivaldate);
        $enddate = new Carbon($this->departuredate);
        return $begindate->diffInDays($enddate);

    }

    public function getPerNightAttribute()
    {
        if ( $this->confirmed )
        {
            $total = 0;

            foreach ($this->visitors as $visitor)
            {
                $total += $visitor->pivot->price;
            }
            return $total;

        } else {
            return 0;
        }
    }

    public function getPerNightEuroAttribute()
    {
        return number_format($this->per_night, 2,'€',' ');
    }

    public function getTotalPriceAttribute()
    {
        return $this->per_night * $this->nights;
    }

    public function getTotalPriceEuroAttribute()
    {
        return number_format($this->per_night * $this->nights, 2,'€',' ');
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

    public function newCollection(array $models = [])
    {
        return new ReservationCollection($models);
    }

    public static function createQuickReservation($visitor_id) {
        $date = Carbon::now();
        $reservation = new static();
        $reservation->arrivaldate = $date;
        $reservation->departuredate = $date;
        $reservation->nodeparturedate = false;
        $reservation->confirmed = false;
        $reservation->quickLink = true;
        $reservation->save();
        $reservation->visitors()->attach($visitor_id, ['contact' => true ]);
        return $reservation;
    }

    protected static function booted()
    {
        static::updated(function ($reservation) {

            if ($reservation->confirmed && $reservation->quickLink) {
                $reservation->quickLink = false;
                $reservation->save();
            }
        });
    }

}
