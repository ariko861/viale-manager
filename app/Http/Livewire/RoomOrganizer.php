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
    public $today;
    public $lastDay;
    public $firstDay;

    protected $listeners = ["roomChanged", "movingVisitor", "restoreDays", "dateChanged"];

    public function backToToday()
    {
        $this->beginDay = Carbon::now()->format('Y-m-d');
        $this->endDay = Carbon::now()->format('Y-m-d');
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
    }

    public function dateChanged(){
        if ($this->beginDay == $this->today ) $this->firstDay = __("aujourd'hui");
        else $this->firstDay = __("le premier jour");
        if ($this->endDay == $this->today ) $this->lastDay = __("aujourd'hui");
        else $this->lastDay = __("le dernier jour");
    }

    public function getNewComers()
    {
        $this->today = Carbon::now();
        $this->resas = VisitorReservation::whereNull('room_id')->whereRelation('reservation', function (Builder $query) {
                $query->whereDate('departuredate', '>=', $this->today)
                    ->orWhere('nodeparturedate', true );
        })->get()->sortBy(function($item, $key) {
            return $item->reservation->arrivaldate;
        });
    }

    public function mount()
    {
        $this->houses = House::all();
        $this->backToToday();
        $this->getNewComers();
        $this->today = Carbon::now()->format('Y-m-d');
        $this->fill([
            'firstDay' => __("aujourd'hui"),
            'lastDay' => __("aujourd'hui"),
        ]);

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
        $this->saveDays();
        $this->beginDay = $visitorReservation->reservation->arrivaldate;
        if ($visitorReservation->reservation->nodeparturedate)
        {
            $this->endDay = Carbon::now()->addYear()->format('Y-m-d');

        } else {
            $this->endDay = $visitorReservation->reservation->departuredate;
        }
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
        $this->restoreDays();
        $this->getNewComers();
        $this->emit('showAlert', [ __("Le visiteur a bien été déplacé !"), "bg-green-500" ] );
    }

    public function render()
    {
        return view('livewire.room-organizer');
    }
}
