<?php

namespace App\Http\Livewire\Visitor;

use Livewire\Component;
use App\Models\Reservation;

class VisitorCard extends Component
{
    public $visitor;
    public $visitorInReservation;
    public $vKey;
    public $reservation;
    public $waitingForMove = false;
    public $editing = false;

    protected $rules = [
        'visitorInReservation.price' => 'integer|min:0',
    ];

    protected $listeners = ["deleteAction", "changeAction", "visitorUpdated"];

    public function changeAction($options)
    {
        if ($options[1] == 'visitorInReservation') $this->editing = ! $this->editing;
    }

    public function deleteAction($options)
    {
        if ($options[1] == 'visitorInReservation') {
            if ( $this->visitorInReservation->contact ) $this->emit('showAlert', [ __("Vous ne pouvez pas supprimer un visiteur contact, supprimez la réservation"), "bg-red-400"] );
            else {
                $this->reservation->visitors()->detach($this->visitor->id);
                $this->emit('showAlert', [ __("Le visiteur a bien été enlevé de la réservation"), "bg-green-400"] );
            }
        }
    }

    public function moveToNewReservation()
    {
        if ( $this->visitorInReservation->contact ) $this->emit('showAlert', [ __("Vous ne pouvez pas déplacer un visiteur contact"), "bg-red-400"] );
        else {
            $this->waitingForMove = false;
            $this->editing = false;
            $reservation = new Reservation();
            $reservation->arrivaldate = $this->reservation->arrivaldate;
            $reservation->departuredate = $this->reservation->departuredate;
            $reservation->nodeparturedate = $this->reservation->nodeparturedate;
            $reservation->confirmed = $this->reservation->confirmed;
            $reservation->remarks = $this->reservation->remarks;
            $reservation->save();
            $reservation->visitors()->attach($this->visitor->id, ['contact' => true, 'price' => $this->visitorInReservation->price ]);
            $this->reservation->visitors()->detach($this->visitor->id);
            $this->emit('showAlert', [ __("Le visiteur a bien été déplacé dans une nouvelle réservation"), "bg-green-400"] );
            $this->emitUp('displayReservations', [$this->reservation->id, $reservation->id]);
        }

    }

    public function updatePivot()
    {
        $this->visitorInReservation->save();
        $this->emit('showAlert', [ __("La réservation a bien été modifiée"), "bg-green-400"] );
    }

    public function visitorUpdated($vis_id)
    {
        if ( $this->visitor->id === $vis_id ) $this->visitor->refresh();
    }

    public function selectRoom()
    {
        $options = [ $this->reservation->id, $this->visitorInReservation->id ];
        $this->emit('initRoomSelection', $options);
    }
    public function mount() {
    }

    public function render()
    {
        return view('livewire.visitor.visitor-card');
    }
}
