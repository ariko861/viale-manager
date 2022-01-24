<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Room;
use App\Models\House;
use App\Models\VisitorReservation;


class RoomOrganizer extends Component
{


    public $newComers;

    protected $listeners = ["roomChanged", "movingVisitor"];

    public function backToToday()
    {
        $this->beginDay = Carbon::now()->format('Y-m-d');
        $this->endDay = Carbon::now()->format('Y-m-d');
    }

    public function getNewComers()
    {
        $this->resas = VisitorReservation::whereNull('room_id')->get();
    }

    public function mount()
    {
        $this->houses = House::all();
        $this->backToToday();
        $this->getNewComers();

    }

    public function getRoomAvailability($room)
    {
        $room = Room::find($room["id"]);
        return $room->visitorsInReservationsForRoom($this->beginDay, $this->endDay);
    }

    public function movingVisitor($resa_id)
    {
        $resa_id = substr($resa_id, 4);
        $visitorReservation = VisitorReservation::find($resa_id);
        $this->beginDay = $visitorReservation->reservation->arrivaldate;
        $this->endDay = $visitorReservation->reservation->departuredate;
//         dd($this->visibleDay);
        $refresh;

    }

    public function roomChanged($roomId)
    {
        $room_id = substr($roomId["room"], 4);
        $resa_id = substr($roomId["resa"], 4);
//         dd($room_id, $resa_id);
        $visitorReservation = VisitorReservation::find($resa_id);
        $visitorReservation->room()->associate($room_id);
        $visitorReservation->save();
        $this->backToToday();
        $this->getNewComers();
        $this->emit('showAlert', [ __("Le visiteur a bien été déplacé !"), "bg-green-500" ] );
    }

    public function render()
    {
        return view('livewire.room-organizer');
    }
}
