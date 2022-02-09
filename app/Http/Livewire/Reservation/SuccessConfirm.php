<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Reservation;
use App\Models\Option;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;

class SuccessConfirm extends Component
{
    public $reservation;
    public $showPrice = false;

    protected $listeners = ['showRecapReservation'];

    public function showRecapReservation($res_id)
    {
        $this->reservation = Reservation::find($res_id);
        $to = Option::firstOrNew(['name' => 'email'])->value;
        Mail::to($to)->queue(new ReservationConfirmed($this->reservation));

    }

    public function mount()
    {
        $confirmation_message = Option::firstOrNew(['name' => 'confirmation_message'])->value;
        $this->confirmation_message = Str::markdown($confirmation_message);
    }

    public function render()
    {
        return view('livewire.reservation.success-confirm');
    }
}
