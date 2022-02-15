<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;

class ReservationCard extends Component
{
    public $reservation;
    public $rKey;
    public $editing;

    public function render()
    {
        return view('livewire.reservation.reservation-card');
    }
}
