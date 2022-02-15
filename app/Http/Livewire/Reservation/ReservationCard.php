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

    protected $rules = [
        'reservation.departuredate' => 'date|after_or_equal:reservation.arrivaldate|required_unless:reservation.nodeparturedate,true',
        'reservation.arrivaldate' => 'date|required',
        'reservation.nodeparturedate' => 'boolean',
        'reservation.confirmed' => 'boolean',
        'reservation.removeFromStats' => 'boolean',

    ];

    protected $listeners = ["deleteAction", "changeAction", "visitorAdded", "reservationUpdated"];

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

    public function editReservation($reservation_id)
    {
        $this->editing = $reservation_id;
        $this->emit("editingReservation");
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
       if ( $this->reservation->visitors()->find( $visitor_id ) ) {
           $this->emit('showAlert', [ __("Cette personne est déjà dans cette réservation"), "bg-red-400"] );
           $this->emit('cancelVisitorSelection');
       } else {
            $this->reservation->visitors()->attach($visitor_id, ['contact' => false ]);
            $this->reservation->refresh();
            $this->newVisitorInReservation = false;
       }

    }

    public function deleteLink($link, $key)
    {
        ReservationLink::destroy($link["id"]);
        $this->reservations[$key]->refresh();
    }

    public function saveEdit()
    {
        $this->validate();
        $this->editing = "";
        foreach ($this->reservation->visitors as $visitor)
        {
            $visitor->pivot->save();
        }
        $this->reservation->save();
        $this->emitUp("reservationUpdated", $this->reservation->id);
        $this->emit('showAlert', [ __("La réservation a été mise à jour"), "bg-lime-600"] );
    }


    public function updateReservation()
    {
        $this->validate();
        $this->reservation->save();
        $this->emitUp("reservationUpdated", $this->reservation->id);
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
