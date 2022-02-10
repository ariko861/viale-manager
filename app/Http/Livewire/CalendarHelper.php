<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CalendarHelper extends Component
{

    protected $listeners = ['eventClicked'];

    public function eventClicked($eventId)
    {
        $resaId = substr($eventId, 1);
        $this->emit('displayReservation', $resaId);

    }

    public function render()
    {
        return view('livewire.calendar-helper');
    }
}
