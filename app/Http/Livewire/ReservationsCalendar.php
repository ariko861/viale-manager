<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Asantibanez\LivewireCalendar\LivewireCalendar;
use App\Models\Reservation;
use Illuminate\Support\Collection;

class ReservationsCalendar extends LivewireCalendar
{

    public $gridStartsAt;
    public $gridEndsAt;

    public function onDayClick($year, $month, $day)
    {
        // This event is triggered when a day is clicked
        // You will be given the $year, $month and $day for that day
    }

    public function onEventClick($eventId)
    {
        // This event is triggered when an event card is clicked
        // You will be given the event id that was clicked

    }

    public function onEventDropped($eventId, $year, $month, $day)
    {
        // This event will fire when an event is dragged and dropped into another calendar day
        // You will get the event id, year, month and day where it was dragged to
        $date = new Carbon("{$year}-{$month}-${day}");
        $newdate = new Carbon("{$year}-{$month}-${day}");
        $reservationId = substr($eventId, 1);
        $eventType = $eventId[0];

        $reservation = Reservation::find($reservationId);
        switch($eventType){
            case "a":
                $reservation->arrivaldate = $date;
                if ($date >= $reservation->departuredate)
                {
                    $reservation->departuredate = $newdate->addDays(1);
//                     dd($date);
                }
                $reservation->save();
                break;
            case "d":
                if ($date <= $reservation->arrivaldate)
                {
                    $this->emit('showAlert', [ __("La date de départ ne peut pas précéder la date d'arrivée !"), "bg-red-500" ] );

                } else {
                    $reservation->departuredate = $date;
                    $reservation->save();
                }
                break;
            default:
                return;
        }
    }

    public function events() : Collection
    {
//         $event = collect([]);
//         Reservation::all()->foreach(function($item, $key){
//
//         });

        $reservations = Reservation::query()
            ->where(function($query) {
                $query->whereDate('arrivaldate', '>=', $this->gridStartsAt)
                        ->whereDate('arrivaldate', '<=', $this->gridEndsAt);
            })
            ->orWhere(function($query) {
                $query->whereDate('departuredate', '>=', $this->gridStartsAt)
                        ->whereDate('departuredate', '<=', $this->gridEndsAt);
            })
            ->get();

        $arrivalEvents = $reservations->map(function (Reservation $model) {
            $contact_person_name = ( $model->contact_person ? $model->contact_person->full_name : "" );
            return [
                'id' => "a{$model->id}",
                'title' => __("Arrivée").' '.$contact_person_name,
                //'description' => $model->remarks,
                'date' => $model->arrivaldate,
            ];
        });

        $departureEvents = $reservations->map(function (Reservation $model) {
            $contact_person_name = ( $model->contact_person ? $model->contact_person->full_name : "" );
            return [
                'id' => "d{$model->id}",
                'title' => __("Départ").' '.$contact_person_name,
                'description' => $model->remarks,
                'date' => $model->departuredate,
            ];
        });

        return $arrivalEvents->concat($departureEvents);
    }
}
