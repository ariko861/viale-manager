<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\House;
use App\Models\VisitorReservation;
use App\Models\Room;

class RoomSelectionForm extends Component
{

    public $visitor;
    public $reservation;
    public $endDay;
    public $beginDay;
    public $lastDay;
    public $firstDay;
    public $showRoomSelection = false;

    protected $listeners = ['initRoomSelection'];

    public function mount()
    {
        $today = Carbon::now()->format('Y-m-d');
        $this->houses = House::all();
        $this->fill([
//             'beginDay' => $this->reservation["arrivaldate"],
//             'endDay' => $this->reservation["departuredate"],
            'firstDay' => ($this->beginDay == $today ? __("aujourd'hui") : __("le premier jour")),
            'lastDay' => ($this->endDay == $today ? __("aujourd'hui") : __("le dernier jour")),
        ]);

    }

    public function initRoomSelection($options){
        $this->showRoomSelection = true;
        $this->visitor = $options[0];
        $this->reservation = $options[1];
    }

    public function getRoomAvailability($room)
    {
        $room = Room::find($room["id"]);
        return $room->visitorsInReservationsForRoom($this->reservation["arrivaldate"], $this->reservation["departuredate"]);
    }
    public function cancelRoomSelection()
    {
        $this->showRoomSelection = false;
        $this->emit('reservationUpdated', $this->reservation["id"]);
    }

    public function cancelRoom()
    {
        $resa = $this->visitor["pivot"];
        $visitorReservation = VisitorReservation::find($resa["id"]);
        $visitorReservation->room()->dissociate();
        $visitorReservation->save();
        $this->cancelRoomSelection();
    }
    public function selectRoom($room)
    {
        $room = Room::find($room["id"]);
        $resa = $this->visitor["pivot"];
        $visitorReservation = VisitorReservation::find($resa["id"]);
        $visitorReservation->room()->associate($room);
        $visitorReservation->save();
        $this->cancelRoomSelection();
    }

    public function render()
    {
        return view('livewire.room-selection-form');
    }
}
