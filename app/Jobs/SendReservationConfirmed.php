<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Mail\ReservationConfirmed;
use Illuminate\Support\Facades\Mail;

class SendReservationConfirmed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $details;

    public $tries = 7;


    public function __construct($details)
    {
        //
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $email = new ReservationConfirmed($this->details['reservation']);
        Mail::to($this->details["email"])->send($email);
    }

    public function backoff()
    {
        return [10, 60, 600, 1800, 3600, 18000, 36000]; // essaie de renvoyer le mail après 10 secondes, puis 60, etc...
    }
}
