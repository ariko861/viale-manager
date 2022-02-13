<?php

namespace App\Http\Livewire\Reservation;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Reservation;
use App\Models\ReservationLink;
use App\Models\Visitor;


class QuickReservation extends Component
{

    public $maxVisitors = 5;
    public $showQuickReservationForm = false;

    protected $listeners = ['visitorSelectedFromEmail', 'emailNotFound'];

    public function visitorSelectedFromEmail($visitor_id)
    {
        $this->createQuickReservation($visitor_id);
    }

    public function emailNotFound($email)
    {
        $visitor = $this->createQuickVisitor($email);
        $this->createQuickReservation($visitor->id);
    }

    public function createQuickVisitor($email)
    {
        $visitor = new Visitor();
        $visitor->email = $email;
        $visitor->name = "x-inconnu";
        $visitor->surname = "x-inconnu";
        $visitor->confirmed = false;
        $visitor->quickLink = true;
        $visitor->save();
        return $visitor;
    }


    public function createQuickReservation($visitor_id) {
        $num_d = 300;
        $date = Carbon::now()->addDays(30);

        $reservation = new Reservation();
        $reservation->arrivaldate = $date;
        $reservation->departuredate = $date;
        $reservation->nodeparturedate = false;
        $reservation->confirmed = false;
        $reservation->quickLink = true;
        $reservation->save();
        $reservation->visitors()->attach($visitor_id, ['contact' => true ]);

        $reservationLink = new ReservationLink();
        $reservationLink->max_days_change = $num_d;
        $reservationLink->max_added_visitors = $this->maxVisitors;
        $reservationLink->generateLinkToken();

        $reservation->links()->save($reservationLink);
        $this->showQuickReservationForm = false;
        $this->emit('displayReservation', $reservation->id);
        $this->emit('scrollToReservationList');
    }

    public function render()
    {
        return view('livewire.reservation.quick-reservation');
    }
}
