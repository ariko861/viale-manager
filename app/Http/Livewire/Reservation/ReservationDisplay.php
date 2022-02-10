<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;
use App\Models\Reservation;

class ReservationDisplay extends Component
{

    public $reservation;

    protected $listeners = ['displayReservation'];

    public function displayReservation($resaId)
    {
        $this->reservation = Reservation::find($resaId);
    }
    public function hideReservation()
    {
        $this->reservation = null;
    }

    public function render()
    {
        return view('livewire.reservation.reservation-display');
    }
}
