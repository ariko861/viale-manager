<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reservation;


class ReservationsList extends Component
{

    public function mount()
    {
        $this->reservations = Reservation::all()->sortBy('arrivaldate');
    }


    public function render()
    {
        return view('livewire.reservations-list');
    }
}
