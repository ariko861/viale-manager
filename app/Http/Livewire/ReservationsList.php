<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\VisitorReservation;

class ReservationsList extends Component
{

    public $showRoomSelection = false;
    public $visitorSelectedForRoom;

    protected $listeners = ["hideRoomSelection"];

    public function mount()
    {
        $this->reservations = Reservation::all()->sortBy('arrivaldate');
    }

    public function selectRoom($visitor)
    {
        $this->showRoomSelection = true;
        $this->visitorSelectedForRoom = $visitor;
    }

    public function hideRoomSelection()
    {
        $this->showRoomSelection = false;
    }

    public function render()
    {
        return view('livewire.reservations-list');
    }
}
