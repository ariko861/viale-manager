<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Room;
use App\Models\House;
use App\Models\VisitorReservation;


class RoomOrganizer extends Component
{


    public $newComers;
    public $previousBeginDay;
    public $previousEndDay;
    public $beginDay;
    public $endDay;
    public $today;
    public $lastDay;
    public $firstDay;
    public $onMoving;
    public $movingFromRoom;
    protected $listeners = ["roomChanged", "movingVisitor", "restoreDays"];

    public function backToToday()
    {
        $this->beginDay = Carbon::now()->format('Y-m-d');
        $this->endDay = Carbon::now()->format('Y-m-d');
        $this->fill([
            'firstDay' => __("aujourd'hui"),
            'lastDay' => __("aujourd'hui"),
        ]);
    }

    public function saveDays()
    {
        $this->previousBeginDay = $this->beginDay;
        $this->previousEndDay = $this->endDay;
    }

    public function restoreDays()
    {
        $this->beginDay = $this->previousBeginDay;
        $this->endDay = $this->previousEndDay;
        $this->dateChanged();
    }

    public function dateChanged(){
        if ($this->endDay == $this->today ) $this->lastDay = __("aujourd'hui");
        else $this->lastDay = __("le dernier jour");
        if ($this->beginDay == $this->today ) $this->firstDay = __("aujourd'hui");
        else $this->firstDay = __("le premier jour");
    }

    public function getNewComers()
    {
        $today = Carbon::now();
        $this->resas = VisitorReservation::whereNull('room_id')->whereRelation('reservation', function (Builder $query) use ($today) {
                $query->whereDate('departuredate', '>=', $today)
                    ->orWhere('nodeparturedate', true );
        })->get();

    }

    public function getHouses()
    {
        $this->houses = House::all()->sortBy('name');
    }

    public function mount()
    {
        $this->getHouses();
        $this->backToToday();
        $this->getNewComers();
        $this->today = Carbon::now()->format('Y-m-d');


    }

    public function getRoomAvailability($room)
    {
        $this->dateChanged();
        $room = Room::find($room["id"]);
        return $room->visitorsInReservationsForRoom($this->beginDay, $this->endDay);
    }

    public function movingVisitor($resa_id, $room_id = null)
    {
//         $resa_id = substr($resa_id, 4);
        if ($room_id) $this->movingFromRoom = $room_id;
        if ($this->onMoving === $resa_id) {
            $this->onMoving = null;
            $this->restoreDays();
            return;
        }
        if (! $this->onMoving) $this->saveDays(); // Ne conserver les jours sélectionnés que si un visiteur n'a pas été sélectionné avant

        $this->onMoving = $resa_id;

        $visitorReservation = VisitorReservation::find($resa_id);
        $this->beginDay = $visitorReservation->reservation->arrivaldate;
        if ($visitorReservation->reservation->nodeparturedate)
        {
            $this->endDay = Carbon::now()->addYear()->format('Y-m-d');

        } else {
            $this->endDay = $visitorReservation->reservation->departuredate;
        }
        $this->dateChanged();
        $refresh;

    }

    public function takeOutOfRoom()
    {
        if ($this->onMoving) {
            $resa_id = $this->onMoving;
            $this->onMoving = null;
            $visitorReservation = VisitorReservation::find($resa_id);
            $visitorReservation->room()->dissociate();
            $visitorReservation->save();
            $this->restoreDays();
//
        }

    }

    public function roomChanged($room_id)
    {
        if ($this->onMoving) {
            $resa_id = $this->onMoving;
            $visitorReservation = VisitorReservation::find($resa_id);
            $visitorReservation->room()->associate($room_id);
            $visitorReservation->save();
            $this->restoreDays();
            $this->onMoving = null;
            $this->getNewComers();
        }
    }

    public function render()
    {
        return view('livewire.room-organizer');
    }
}
