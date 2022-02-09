<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Models\Option;

class ReservationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $reservation;
    public $link;

    public function __construct(Reservation $reservation)
    {
        //
        $this->reservation = $reservation;
        $this->link = urldecode(route('reservations') . '/' . $reservation->id);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__("Réservation à")." ".env("APP_NAME")." ".__("confirmée"))
                    ->view('emails.reservation-confirmed');
    }
}
