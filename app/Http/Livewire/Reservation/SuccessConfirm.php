<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;
use App\Models\Reservation;

class SuccessConfirm extends Component
{
    public $reservation;
    public $showPrice = false;

    protected $listeners = ['showRecapReservation'];

    public function showRecapReservation($res_id)
    {
        $this->reservation = Reservation::find($res_id);
    }

    public function render()
    {
        return view('livewire.reservation.success-confirm');
    }
}
