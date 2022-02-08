<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;
use App\Models\VisitorReservation;

class ReservationsList extends Component
{

    public $showRoomSelection = false;
    public $visitorSelectedForRoom;
    public $reservationSelectedForRoom;
    public $amountDisplayedReservation = 10;
    public $editing;
    public $visitorSearch;
    public $reservations;
    public $beginDate;
    public $endDate;
    public $beginDateForDeparture;
    public $endDateForDeparture;
    public $listTitle;
    public $numberOfReservationsDisplayed = 20;
    public $showSendLinkForm = false;

    protected $listeners = ["hideRoomSelection", "deleteAction", "changeAction", "cancelLinkForm", "displayReservation"];

    protected $rules = [
        'newArrivalDate' => 'required|date',
        'newDepartureDate' => '',
        'noDepartureDate' => '',
        'reservationConfirmed' => '',
    ];

    public function displayReservation($res_id)
    {
        $this->reservations = Reservation::where('id', $res_id)->get();
        $this->listTitle = __("Reservation")." ".$res_id;
    }

    public function changeAction($options)
    {
        if ($options[1] == 'reservation')
        {
            $this->editReservation($options[0]);
        }
    }

    public function deleteAction($options)
    {
        if ($options[1] == 'reservation')
        {
            $this->deleteReservation($options[0]);
        }
    }

    public function getReservationsComing()
    {
        $today = Carbon::now()->format('Y-m-d');
//         $allReservations = Reservation::whereDate('arrivaldate', '>', $today)->get()->sortBy('arrivaldate')->chunk($amount);
//         $this->reservations = $allReservations->first();
        $this->reservations = Reservation::whereDate('arrivaldate', '>', $today)->orderBy('arrivaldate')->take($this->amountDisplayedReservation)->get();
        $this->listTitle = $this->amountDisplayedReservation." ".__("Prochaines arrivées");
    }

    public function getReservationsWhereArrivalInBetween(){
        $this->reservations = Reservation::whereDate('arrivaldate', '>=', $this->beginDate)->whereDate('arrivaldate', '<=', $this->endDate)->get();
        $beginDate = new Carbon($this->beginDate);
        $endDate = new Carbon($this->endDate);

        $this->listTitle = __("Arrivées entre le")." ".$beginDate->format('d F Y')." ".__("et le")." ".$endDate->format('d F Y');
    }

    public function getReservationsWhereDepartureInBetween(){
        $this->reservations = Reservation::whereDate('departuredate', '>=', $this->beginDateForDeparture)->whereDate('departuredate', '<=', $this->endDateForDeparture)->get();
        $beginDate = new Carbon($this->beginDateForDeparture);
        $endDate = new Carbon($this->endDateForDeparture);

        $this->listTitle = __("Départs entre le")." ".$beginDate->format('d F Y')." ".__("et le")." ".$endDate->format('d F Y');
    }

    public function getReservationsByVisitorName(){
        $value = $this->visitorSearch;
        if ( Str::length($value) >= 3 )
        {
            $this->reservations = Reservation::whereHas('visitors', function (Builder $query) {
                $query->where('name', 'ilike', '%'.$this->visitorSearch.'%')
                    ->orWhere('surname', 'ilike', '%'.$this->visitorSearch.'%');
            })->orderBy('arrivaldate')->get();
        }
        else
        {
            $this->reservations = [];
        }
    }

    public function mount()
    {
//         $this->reservations = Reservation::all()->sortBy('arrivaldate');
        $today = Carbon::now()->format('Y-m-d');
        $this->beginDate = $today;
        $this->endDate = $today;
        $this->beginDateForDeparture = $today;
        $this->endDateForDeparture = $today;
        $this->getReservationsComing($this->numberOfReservationsDisplayed);
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

    public function deleteReservation($profile_id)
    {
        Reservation::destroy($profile_id);
        VisitorReservation::where('reservation_id', $profile_id)->delete();

        $this->reservations = $this->reservations->filter(function($item) use ($profile_id) {
            return $item->id != $profile_id;
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

    public function saveEdit($reservation_id)
    {
        $this->validate();
        $this->editing = "";
        $reservation = Reservation::find($reservation_id);
//         $reservation->update([
//             'arrivaldate' => $this->newArrivalDate,
//             'departuredate' => $this->newDepartureDate,
//             'nodeparturedate' => $this->noDepartureDate,
//             'confirmed' => $this->reservationConfirmed,
//
//         ]);
        $reservation->arrivaldate = $this->newArrivalDate;
        $reservation->departuredate = $this->newDepartureDate;
        $reservation->nodeparturedate = $this->noDepartureDate;
        $reservation->confirmed = $this->reservationConfirmed;
        $reservation->save();
        $this->getReservationsComing($this->numberOfReservationsDisplayed);

    }

    public function sendConfirmationMail($reservation_id)
    {
        $this->emit('engageLinkCreation', $reservation_id);
        $this->showSendLinkForm = true;

    }
    public function cancelLinkForm()
    {
        $this->showSendLinkForm = false;
    }

    public function render()
    {
        return view('livewire.reservations-list');
    }
}
