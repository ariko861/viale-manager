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
    public $modifConf;
    public $modif;

    public function __construct(Reservation $reservation, $modif = false)
    {
        //
        $this->reservation = $reservation;
        $this->link = urldecode(route('reservations') . '/' . $reservation->id);
        $this->modifConf = ( $modif ? __("modifiée par") : __("confirmée par"));
        $this->modif = $modif;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__("Réservation à")." ".env("APP_NAME")." ".$this->modifConf." ".$this->reservation->contact_person->full_name )
                    ->view('emails.reservation-confirmed', ['modif' => $this->modif]);
    }
}
