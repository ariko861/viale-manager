<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;
use App\Models\Reservation;
use Carbon\Carbon;

class SplitReservation extends Component
{
    public $reservation;
    public $splitStart;
    public $splitEnd;

    protected $listeners = ['engageSplitReservation'];

    protected $rules = [
        'splitStart' => 'required|date',
        'splitEnd' => 'required|date|after_or_equal:splitStart',
    ];

    public function engageSplitReservation($resaId)
    {
        $today = Carbon::now()->format('Y-m-d');
        $this->splitStart = $today;
        $this->splitEnd = $today;
        $this->reservation = Reservation::find($resaId);
    }

    public function startChanged() {
        $start = new Carbon($this->splitStart);
        $end = new Carbon($this->splitEnd);
        if ($start->gt($end)) $this->splitEnd = $this->splitStart;
    }

    public function submit() {
        $this->validate();
        $newReservation = $this->reservation->replicate();
        $this->reservation->nodeparturedate = false;
        $this->reservation->departuredate = $this->splitStart;
        $newReservation->arrivaldate = $this->splitEnd;
        $this->reservation->save();
        $newReservation->save();
        foreach ($this->reservation->visitors as $visitor){
            $newReservation->visitors()->attach($visitor, ['contact' => $visitor->pivot->contact, 'room_id' => $visitor->pivot->room_id, 'price' => $visitor->pivot->price, 'house_id' => $visitor->pivot->house_id]);
        }

        $this->emit('displayReservations', [$this->reservation->id, $newReservation->id]);
        $this->hideReservation();

    }

    public function hideReservation()
    {
        $this->reservation = null;
    }

    public function render()
    {
        return view('livewire.reservation.split-reservation');
    }
}
