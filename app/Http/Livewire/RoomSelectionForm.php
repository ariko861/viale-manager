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


    public function mount()
    {
        $today = Carbon::now()->format('Y-m-d');
        $this->houses = House::all();

        $this->fill([
            'beginDay' => $this->reservation["arrivaldate"],
            'endDay' => $this->reservation["departuredate"],
            'firstDay' => ($this->beginDay == $today ? __("aujourd'hui") : __("le premier jour")),
            'lastDay' => ($this->endDay == $today ? __("aujourd'hui") : __("le dernier jour")),
        ]);

    }

    public function getRoomAvailability($room)
    {
        $room = Room::find($room["id"]);
//         dd($room->reservationVisitors);
        return $room->visitorsInReservationsForRoom($this->reservation["arrivaldate"], $this->reservation["departuredate"]);
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
