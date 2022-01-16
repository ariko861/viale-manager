<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\House;
use App\Models\VisitorReservation;
use App\Models\Room;

class RoomSelectionForm extends Component
{

    public $visitor;

    public function mount()
    {
        $this->houses = House::all();
    }

    public function test()
    {
        dd($this->visitor);
    }
    public function cancelRoomSelection()
    {
        $this->emitUp("hideRoomSelection");
    }
    public function selectRoom($room)
    {
        $room = Room::find($room["id"]);
//         dd($this->visitor);
        $resa = $this->visitor["pivot"];
        $visitorReservation = VisitorReservation::find($resa["id"]);
//         dd($room["id"]);
        $visitorReservation->room()->associate($room);
//         dd($visitorReservation);
        $visitorReservation->save();
    }

    public function render()
    {
        return view('livewire.room-selection-form');
    }
}
