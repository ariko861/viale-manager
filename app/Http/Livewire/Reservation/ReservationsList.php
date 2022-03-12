<?php

namespace App\Http\Livewire\Reservation;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;
use App\Models\Visitor;
use App\Models\VisitorReservation;
use App\Models\ReservationLink;

class ReservationsList extends Component
{

    public $amountDisplayedReservation = 20;
    public $advancedSearch = false;
    public $visitorSearch;
    public $reservations;
    public $reservation_id;
    public $beginDate;
    public $endDate;
    public $beginDateForDeparture;
    public $endDateForDeparture;
    public $presenceBeginDate;
    public $presenceEndDate;
    public $listTitle;
    public $numberOfReservationsDisplayed = 20;

    protected $listeners = ["displayReservation", "displayReservations", "reservationDeleted", "displayDayVisitors"];

    public function displayReservation($res_id)
    {
        $this->reservations = Reservation::where('id', $res_id)->get();
        $this->listTitle = __("Reservation")." ".$res_id;
        $this->emitSelf('scrollToReservationList');
    }

    public function displayDayVisitors($options) {
        if ( $options["type"] == "arrivals") {
            $this->beginDate = $options["date"];
            $this->endDate = $options["date"];
            $this->getReservationsWhereArrivalInBetween();
        }
        if ( $options["type"] == "departures") {
            $this->beginDateForDeparture = $options["date"];
            $this->endDateForDeparture = $options["date"];
            $this->getReservationsWhereDepartureInBetween();
        }
        if ( $options["type"] == "presences") {
            $this->getReservationsPresenceBetweenDates($options["date"], $options["date"], $confirmed = true);
            $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("personnes présentes ce jour");
            $this->emit('scrollToReservationList');
        }
    }

    public function displayReservations($res_ids)
    {
        $this->reservations = Reservation::whereIn('id', $res_ids)->get();
        $this->listTitle = __("Reservations")." ".implode(" ", $res_ids);
        $this->emitSelf('scrollToReservationList');
    }

    public function getReservationsComing()
    {
        $today = Carbon::now()->format('Y-m-d');
        $this->reservations = Reservation::where('quickLink', false)->whereDate('arrivaldate', '>=', $today)->orderBy('arrivaldate')->take($this->amountDisplayedReservation)->get();
        $this->listTitle = $this->amountDisplayedReservation." ".__("Prochaines arrivées");
        $this->emit('scrollToReservationList');
    }

    public function getLastConfirmations()
    {
        $this->reservations = Reservation::where('quickLink', false)->where('confirmed', true)->where('confirmed_at', '<>', null)->orderBy('confirmed_at', 'desc')->take($this->amountDisplayedReservation)->get();
        $this->listTitle = $this->amountDisplayedReservation." ".__("dernières confirmations");
        $this->emit('scrollToReservationList');
    }

    public function getReservationsWhereArrivalInBetween(){
        $this->reservations = Reservation::where('quickLink', false)->whereDate('arrivaldate', '>=', $this->beginDate)->whereDate('arrivaldate', '<=', $this->endDate)->get();
        $beginDate = new Carbon($this->beginDate);
        $endDate = new Carbon($this->endDate);

        if ($beginDate == $endDate) $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("arrivées le")." ".$beginDate->translatedFormat('d F Y');
        else $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("arrivées entre le")." ".$beginDate->translatedFormat('d F Y')." ".__("et le")." ".$endDate->translatedFormat('d F Y');

        $this->emit('scrollToReservationList');
    }

    public function getReservationsWhereDepartureInBetween(){
        $this->reservations = Reservation::where('quickLink', false)->whereDate('departuredate', '>=', $this->beginDateForDeparture)->whereDate('departuredate', '<=', $this->endDateForDeparture)->get();
        $beginDate = new Carbon($this->beginDateForDeparture);
        $endDate = new Carbon($this->endDateForDeparture);

        if ($beginDate == $endDate ) $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("départs le")." ".$beginDate->translatedFormat('d F Y');
        else $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("départs entre le")." ".$beginDate->translatedFormat('d F Y')." ".__("et le")." ".$endDate->translatedFormat('d F Y');

        $this->emit('scrollToReservationList');
    }

    public function getReservationsPresenceBetweenDates($dateBegin, $dateEnd, $confirmed = true){

        $this->reservations = Reservation::getPresencesBetweenDates($dateBegin, $dateEnd, $confirmed);
    }

    public function getReservationsHere() {

        $this->getReservationsPresenceBetweenDates($this->today, $this->today, true);
        $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("personnes présentes en ce moment");
        $this->emit('scrollToReservationList');
    }

    public function getWaitingConfirmation() {
        $this->reservations = Reservation::where('confirmed', false)->orWhere('quickLink', true)->get()->sortBy('arrivaldate');
        $this->listTitle = $this->reservations->count()." ".__("réservations en attente de confirmation");
        $this->emit('scrollToReservationList');
    }

    public function getPresenceBetweenDates() {

        $this->getReservationsPresenceBetweenDates($this->presenceBeginDate, $this->presenceEndDate, false);
        $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("personnes prévues entre ces dates");
        $this->emit('scrollToReservationList');
    }

    public function getReservationsByVisitorName(){
        $value = $this->visitorSearch;

        if ( Str::length($value) >= 3 )
        {
            $this->reservations = Reservation::whereHas('visitors', function (Builder $query) {
                $query->where('name', 'ilike', '%'.$this->visitorSearch.'%')
                    ->orWhere('surname', 'ilike', '%'.$this->visitorSearch.'%')
                    ->orWhere('email', 'ilike', '%'.$this->visitorSearch.'%');
            })->orderBy('arrivaldate')->get();
        }
        else
        {
            $this->reservations = collect([]);
        }
        $this->listTitle = __("Cette personne est présente dans")." ".$this->reservations->count()." ".__("réservations");
    }

    public function reservationDeleted($reservation_id){
        $this->reservations = $this->reservations->reject(function($item) use ($reservation_id) {
            return $item->id == $reservation_id;
        });
    }

    public function mount()
    {
        $today = Carbon::now()->format('Y-m-d');
        $this->today = Carbon::now();
        $this->fill([
            'beginDate' => $today,
            'endDate' => $today,
            'beginDateForDeparture' => $today,
            'endDateForDeparture' => $today,
            'presenceBeginDate' => $today,
            'presenceEndDate' => $today,
        ]);
        if ($this->reservation_id)
        {
            $this->displayReservation($this->reservation_id);
        } else {
            $this->getReservationsComing($this->numberOfReservationsDisplayed);
        }
    }

    public function render()
    {
        return view('livewire.reservation.reservations-list');
    }
}
