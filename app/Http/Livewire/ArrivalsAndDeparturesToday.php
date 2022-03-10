<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Reservation;

class ArrivalsAndDeparturesToday extends Component
{

    public $arrivals;
    public $departures;
    public $presences;

    public function mount() {

        $today = Carbon::now();
        $this->arrivals = Reservation::where('confirmed', true)->whereDate('arrivaldate', $today)->get();
        $this->departures = Reservation::where('confirmed', true)->where('nodeparturedate', false)->whereDate('departuredate', $today)->get();
        $this->presences = Reservation::getPresencesBetweenDates($today, $today, true);
    }
    public function render()
    {
        return view('livewire.arrivals-and-departures-today');
    }
}
