<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationLink;
use App\Models\Reservation;
use App\Models\ReservationLink;

class CreateLink extends Component
{

    public $reservation;
    public $maxDays = 0;
    public $maxVisitors = 0;
    public $linkCreated;
    public $emailSent = false;

    protected $listeners = ['engageLinkCreation'];

    protected $rules = [
        'maxDays' => 'required|int',
        'maxVisitors' => 'required|int',
    ];

    public function engageLinkCreation($reservation_id)
    {
        $this->reservation = Reservation::find($reservation_id);
//         dd($this->reservation->contact_person->full_name);
    }

    public function send($sendmail = false)
    {
        $this->validate();
        $reservationLink = new ReservationLink();
        $reservationLink->max_days_change = $this->maxDays;
        $reservationLink->max_added_visitors = $this->maxVisitors;

        $reservationLink->generateLinkToken();
        $this->reservation->links()->save($reservationLink);

        if ($sendmail)
        {
            // Ici code pour envoyer mail de confirmation
            Mail::to($this->reservation->contact_person->email)->queue(new ConfirmationLink($reservationLink));
            $this->emailSent = true;
        }
        $this->linkCreated = $reservationLink->getLink();


    }

    public function mount()
    {
        $this->reservation = new Reservation();
    }

    public function render()
    {
        return view('livewire.reservation.create-link');
    }
}
