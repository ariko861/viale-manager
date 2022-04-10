<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Reservation;

class ArrivalsAndDeparturesToday extends Component
{

    public $displayDate;
    public $formattedDisplayDate;
    public $arrivals;
    public $departures;
    public $presences;
    public $today = false;

    public function mount() {

        $this->backToToday();
    }

    public function changeDay($date) {
        $this->arrivals = Reservation::where('confirmed', true)->whereDate('arrivaldate', $date)->get();
        $this->departures = Reservation::where('confirmed', true)->where('nodeparturedate', false)->whereDate('departuredate', $date)->get();
        $this->presences = Reservation::getPresencesExcludeDates($date, $date, true);
        $this->formattedDisplayDate = $date->format('Y-m-d');
        $this->today = true;
    }

    public function nextDay() {
        $this->displayDate->addDay();
        $this->changeDay($this->displayDate);
    }

    public function previousDay() {
        $this->displayDate->subDay();
        $this->changeDay($this->displayDate);
    }

    public function updateDate() {
        $this->displayDate = new Carbon($this->formattedDisplayDate);
        $this->changeDay($this->displayDate);
    }

    public function backToToday() {
        $today = Carbon::now();
        $this->displayDate = $today;
        $this->changeDay($today);
        $this->today = false;
    }

    public function displayDayEvents( $type ) {
        $options = [
            'type' => $type,
            'date' => $this->displayDate->format('Y-m-d'),
        ];
        $this->emit('displayDayVisitors', $options);
    }


    public function render()
    {
        return view('livewire.arrivals-and-departures-today');
    }
}
