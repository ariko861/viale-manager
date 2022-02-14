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

    public $showRoomSelection = false;
    public $visitorSelectedForRoom;
    public $reservationSelectedForRoom;
    public $amountDisplayedReservation = 20;
    public $advancedSearch = false;
    public $editing;
    public $newVisitorInReservation = false;
    public $visitorSearch;
    public $reservationNotInStats;
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

    protected $listeners = ["hideRoomSelection", "deleteAction", "changeAction", "displayReservation", "visitorAdded"];

    protected $rules = [
        'newArrivalDate' => 'required|date',
        'newDepartureDate' => '',
        'noDepartureDate' => '',
        'reservationConfirmed' => '',
        'reservations.*.removeFromStats' => 'boolean',
        'reservations.*.visitors.*.pivot.price' => 'integer|nullable',
    ];

    public function displayReservation($res_id)
    {
        $this->reservations = Reservation::where('id', $res_id)->get();
        $this->listTitle = __("Reservation")." ".$res_id;
        $this->emitSelf('scrollToReservationList');
    }

    public function changeAction($options)
    {
        if ($options[1] == 'reservation') $this->editReservation($options[0]);
        else if ($options[1] == 'visitorInReservation');
    }

    public function deleteAction($options)
    {
        if ($options[1] == 'reservation') $this->deleteReservation($options[0]);
        else if ($options[1] == 'visitorInReservation') $this->removeVisitorFromReservation($options[0]);
    }

    public function updateReservation($key)
    {
        $this->reservations[$key]->save();
        $this->emit('showAlert', [ __("La réservation a été mise à jour"), "bg-lime-600"] );
    }

    public function getReservationsComing()
    {
        $today = Carbon::now()->format('Y-m-d');
//         $allReservations = Reservation::whereDate('arrivaldate', '>', $today)->get()->sortBy('arrivaldate')->chunk($amount);
//         $this->reservations = $allReservations->first();
        $this->reservations = Reservation::where('quickLink', false)->whereDate('arrivaldate', '>=', $today)->orderBy('arrivaldate')->take($this->amountDisplayedReservation)->get();
        $this->listTitle = $this->amountDisplayedReservation." ".__("Prochaines arrivées");
        $this->emit('scrollToReservationList');
    }

    public function getReservationsWhereArrivalInBetween(){
        $this->reservations = Reservation::where('quickLink', false)->whereDate('arrivaldate', '>=', $this->beginDate)->whereDate('arrivaldate', '<=', $this->endDate)->get();
        $beginDate = new Carbon($this->beginDate);
        $endDate = new Carbon($this->endDate);

        $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("arrivées entre le")." ".$beginDate->format('d F Y')." ".__("et le")." ".$endDate->format('d F Y');
        $this->emit('scrollToReservationList');
    }

    public function getReservationsWhereDepartureInBetween(){
        $this->reservations = Reservation::where('quickLink', false)->whereDate('departuredate', '>=', $this->beginDateForDeparture)->whereDate('departuredate', '<=', $this->endDateForDeparture)->get();
        $beginDate = new Carbon($this->beginDateForDeparture);
        $endDate = new Carbon($this->endDateForDeparture);

        $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("départs entre le")." ".$beginDate->format('d F Y')." ".__("et le")." ".$endDate->format('d F Y');
        $this->emit('scrollToReservationList');
    }

    public function getReservationsPresenceBetweenDates($dateBegin, $dateEnd, $confirmed = true){

        $this->reservations = Reservation::where('quickLink', false)->where(function($query) use ($confirmed){
                    if ($confirmed) $query->where('confirmed', true);
                })->where(function($query) use ($dateBegin, $dateEnd) {
                $query->where(function($query) use ($dateBegin, $dateEnd) {
                    $query->whereDate('arrivaldate', '<=', $dateEnd)
                            ->whereDate('departuredate', '>=', $dateBegin);
                    })
                    ->orWhere(function($query) use ($dateBegin, $dateEnd) {
                        $query->whereDate('arrivaldate', '<=', $dateEnd)
                        ->where('nodeparturedate', true );
                    });
                })->get();
    }

    public function getReservationsHere() {

        $this->getReservationsPresenceBetweenDates($this->today, $this->today, true);
        $this->listTitle = $this->reservations->getTotalAmountOfVisitors()." ".__("personnes présentes en ce moment");
        $this->emit('scrollToReservationList');
    }

    public function getWaitingConfirmation() {
        $this->reservations = Reservation::where('confirmed', false)->orWhere('quickLink', true)->get()->sortBy('arrivaldate');
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


    public function selectRoom( $visitor, $reservation )
    {
        $this->showRoomSelection = true;
        $this->reservationSelectedForRoom = $reservation;
        $this->visitorSelectedForRoom = $visitor;
    }

    public function hideRoomSelection()
    {
        $this->showRoomSelection = false;
    }

    public function deleteReservation($reservation_id)
    {
        $this->reservations->find($reservation_id)->delete();
        $this->reservations = $this->reservations->reject(function($item) use ($reservation_id) {
            return $item->id == $reservation_id;
        });

        $this->emit('showAlert', [ __("La réservation a bien été supprimé"), "bg-red-600"] );
    }

    public function editReservation($reservation_id)
    {
        $this->editing = $reservation_id;
        $reservation = Reservation::find($reservation_id);
        $this->newArrivalDate = $reservation->arrivaldate;
        $this->newDepartureDate = $reservation->departuredate;
        $this->noDepartureDate = $reservation->nodeparturedate;
        $this->reservationConfirmed = $reservation->confirmed;

    }

    public function removeVisitorFromReservation($res_and_visitor_id)
    {
        $ids = explode('-', $res_and_visitor_id);
        $reservation = Reservation::find($ids[0]);
        $visitor = $reservation->visitors()->find($ids[1]);
        if ( $visitor->pivot->contact ) $this->emit('showAlert', [ __("Vous ne pouvez pas supprimer un visiteur contact, supprimez la réservation"), "bg-red-400"] );
        else {
            $reservation->visitors()->detach($ids[1]);
            $this->reservations->find($ids[0])->refresh();
            $this->emit('showAlert', [ __("Le visiteur a bien été enlevé de la réservation"), "bg-green-400"] );

        }
    }

    public function visitorAdded($options)
    {
       $visitor_id = $options[0]["id"];
       $reservation = $this->reservations->find($options[1]);
       if ( $reservation->visitors()->find( $visitor_id ) ) {
           $this->emit('showAlert', [ __("Cette personne est déjà dans cette réservation"), "bg-red-400"] );
           $this->emit('cancelVisitorSelection');
       } else {
            $reservation->visitors()->attach($visitor_id, ['contact' => false ]);
            $reservation->refresh();
            $this->newVisitorInReservation = false;
       }

    }

    public function deleteLink($link, $key)
    {
        ReservationLink::destroy($link["id"]);
        $this->reservations[$key]->refresh();
    }

    public function saveEdit($reservation_id)
    {
        $this->validate();
        $this->editing = "";
        $reservation = $this->reservations->find($reservation_id);
        $reservation->arrivaldate = $this->newArrivalDate;
        $reservation->departuredate = $this->newDepartureDate;
        $reservation->nodeparturedate = $this->noDepartureDate;
        $reservation->confirmed = $this->reservationConfirmed;
        foreach ($reservation->visitors as $visitor)
        {
            $visitor->pivot->save();
        }
        $reservation->save();

    }

    public function sendConfirmationMail($reservation_id)
    {
        $options = collect([
            'reservation_id' => $reservation_id,
        ]);
        $this->emit('engageLinkCreation', $options);

    }


    public function mount()
    {
//         $this->reservations = Reservation::all()->sortBy('arrivaldate');
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
