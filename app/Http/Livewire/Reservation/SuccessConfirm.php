<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Reservation;
use App\Models\Option;
use App\Jobs\SendReservationConfirmed;

class SuccessConfirm extends Component
{
    public $reservation;
    public $showPrice = false;

    protected $listeners = ['showRecapReservation'];

    public function showRecapReservation($res_id)
    {
        $this->reservation = Reservation::find($res_id);
        $to = Option::firstOrNew(['name' => 'email'])->value;
        $details = [
            'email' => $to,
            'reservation' => $this->reservation,
        ];
        dispatch(new SendReservationConfirmed($details));
//         Mail::to($to)->queue(new ReservationConfirmed($this->reservation));

    }

    public function mount()
    {
        $this->confirmation_messages = collect([]);
        $confirmation_messages = Option::where('name', 'confirmation_message')->orderBy('id')->get();
        foreach ($confirmation_messages as $message )
        {
            $this->confirmation_messages->push( Str::markdown($message->value) );
        }
    }

    public function render()
    {
        return view('livewire.reservation.success-confirm');
    }
}
