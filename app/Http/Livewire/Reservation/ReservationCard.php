<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\Visitor;
use App\Models\VisitorReservation;

class ReservationCard extends Component
{
    public $reservation;
    public $rKey;
    public $editing;
    public $newVisitorInReservation = false;
    public $setDepartureDate = false;
    public $daysGapForPlaces = 5;

    protected $rules = [
        'reservation.departuredate' => 'date|after_or_equal:reservation.arrivaldate|required_unless:reservation.nodeparturedate,true|nullable',
        'reservation.arrivaldate' => 'date|required',
        'reservation.nodeparturedate' => 'boolean',
        'reservation.confirmed' => 'boolean',
        'reservation.remarks' => 'string|nullable',
        'reservation.removeFromStats' => 'boolean',
        'reservation.lookForCarPlaces' => 'boolean',
        'reservation.hasCarPlaces' => 'boolean',
        'reservation.sharePhone' => 'boolean',
        'reservation.shareEmail' => 'boolean',
        'reservation.coming_from' => 'string|max:100|nullable',
        'reservation.numberCarPlaces' => 'integer|min:0'


    ];

    protected $listeners = ["deleteAction", "changeAction", "visitorAdded", "displayReservation", "displayReservations"];

    public function changeAction($options)
    {
        if ($options[1] == 'reservation') $this->editing = ! $this->editing;
    }

    public function lookForCars()
    {
        $this->emitUp('lookForCars', [ $this->reservation->arrivaldate, $this->daysGapForPlaces]);
    }

    public function deleteAction($options)
    {
        if ($options[1] == 'reservation') $this->deleteReservation($options[0]);
    }

    public function visitorAdded($options)
    {
       $visitor_id = $options[0]["id"];
       if ( $this->reservation->visitors()->find( $visitor_id ) ) {
           $this->emit('showAlert', [ __("Cette personne est déjà dans cette réservation"), "bg-red-400"] );
           $this->emit('cancelVisitorSelection');
       } else {
            $this->reservation->visitors()->attach($visitor_id, ['contact' => false ]);
            $this->reservation->refresh();
            $this->newVisitorInReservation = false;
       }
    }

    public function displayReservations()
    {
        $this->editing = false;
        $this->reservation->refresh();
    }
    public function displayReservation($res_id)
    {
        if ( $this->reservation->id === $res_id ) $this->reservation->refresh();
    }

    public function deleteLink($link)
    {
        $this->reservation->links->find($link)->delete();
        $this->reservation->refresh();
    }

    public function updateReservation($option = null)
    {
        $this->validate();
        if ($option && $option === 'departure') $this->reservation->nodeparturedate = false;
        $this->reservation->save();
        $this->emitUp("reservationUpdated", $this->reservation->id);
        $this->setDepartureDate = false;
        $this->emit('showAlert', [ __("La réservation a été mise à jour"), "bg-lime-600"] );
    }

    public function deleteReservation()
    {
        $this->reservation->delete();
        $this->emitUp('reservationDeleted', $this->reservation->id);
        $this->emit('showAlert', [ __("La réservation a bien été supprimé"), "bg-red-600"] );
    }


    public function sendConfirmationMail()
    {
        $options = collect([
            'reservation_id' => $this->reservation->id,
        ]);
        $this->emit('engageLinkCreation', $options);

    }


    public function render()
    {
        return view('livewire.reservation.reservation-card');
    }
}
