<?php

namespace App\Http\Livewire\Reservation;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Reservation;
use App\Models\ReservationLink;
use App\Models\Visitor;

class SuperQuickReservation extends Component
{

    public function createSuperQuickReservation() {

        $visitor = Visitor::createQuickVisitor("");
        $reservation = Reservation::createQuickReservation($visitor->id);
        $options = collect([
            'reservation_id' => $reservation->id,
            'max_days' => 250,
            'max_visitors' => 5,
        ]);
        $this->emit('engageLinkCreation', $options);

    }
    public function render()
    {
        return view('livewire.reservation.super-quick-reservation');
    }
}
