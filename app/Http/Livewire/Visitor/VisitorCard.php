<?php

namespace App\Http\Livewire\Visitor;

use Livewire\Component;

class VisitorCard extends Component
{
    public $visitor;
    public $vKey;
    public $reservation;
    public $editing;

    protected $listeners = ["deleteAction", "changeAction", "editingReservation"];

    public function changeAction($options)
    {
        if ($options[1] == 'reservation') $this->editReservation($options[0]);
        else if ($options[1] == 'visitorInReservation');
    }

    public function editingReservation()
    {
        $this->editing = true;
    }

    public function deleteAction($options)
    {
        if ($options[1] == 'reservation') $this->deleteReservation($options[0]);
        else if ($options[1] == 'visitorInReservation') $this->removeVisitorFromReservation($options[0]);
    }

    public function selectRoom( $visitor, $reservation )
    {
        dd($this->visitor);
        $options = [ $visitor, $reservation ];
        $this->emit('initRoomSelection', $options);
    }

    public function render()
    {
        return view('livewire.visitor.visitor-card');
    }
}
