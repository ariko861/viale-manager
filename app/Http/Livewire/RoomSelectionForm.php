<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\House;
use App\Models\VisitorReservation;
use App\Models\Room;

class RoomSelectionForm extends Component
{

    public $visitor;
    public $reservation;

    public function mount()
    {
        $this->houses = House::all();
    }

    public function getRoomAvailability($room)
    {
        $room = Room::find($room["id"]);
//         dd($room->reservationVisitors);
        return $room->usersInRoom($this->reservation["arrivaldate"], $this->reservation["departuredate"]);
    }
    public function cancelRoomSelection()
    {
        $this->emitUp("hideRoomSelection");
    }
    public function selectRoom($room)
    {
        $room = Room::find($room["id"]);
        $resa = $this->visitor["pivot"];
        $visitorReservation = VisitorReservation::find($resa["id"]);
        $visitorReservation->room()->associate($room);
        $visitorReservation->save();
        $this->emitUp("hideRoomSelection");
    }

    public function render()
    {
        return view('livewire.room-selection-form');
    }
}
