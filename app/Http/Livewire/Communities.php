<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use App\Models\House;
use App\Models\VisitorReservation;
use App\Models\Reservation;

class Communities extends Component
{

    public $resas;
    public $beginDate;
    public $endDate;

    protected $listeners = ["communityChanged"];

    public function getCommunities(){
        $this->communities = House::where('community', true)->get()->sortBy('name');
    }

    public function getResas()
    {
        $today = Carbon::now();
        $this->resas = VisitorReservation::whereNull('house_id')->whereRelation('reservation', function (Builder $query) use ($today){
            $query->where(function($query) use ($today) {
                $query->whereDate('departuredate', '>=', $today)
                    ->orWhere('nodeparturedate', true);
            })
            ->where(function($query) {
                $query->whereDate('arrivaldate', '<=', $this->endDate)
                        ->whereDate('departuredate', '>=', $this->beginDate);
            })
            ->orWhere(function($query) {
                $query->whereDate('arrivaldate', '<=', $this->endDate)
                        ->where('nodeparturedate', true );
            });
        })->get()->sortBy(function($item, $key) {
            return $item->reservation->arrivaldate;
        });
        $this->emit('dateChanged');
    }

    public function prevWeek()
    {
        $this->beginDate = Carbon::parse($this->beginDate)->subWeek()->format('Y-m-d');
        $this->endDate = Carbon::parse($this->endDate)->subWeek()->format('Y-m-d');
        $this->getResas();
    }
    public function nextWeek()
    {
        $this->beginDate = Carbon::parse($this->beginDate)->addWeek()->format('Y-m-d');
        $this->endDate = Carbon::parse($this->endDate)->addWeek()->format('Y-m-d');
        $this->getResas();
    }

    public function communityChanged($item)
    {
        $house_id = substr($item["house"], 4);
        $resa_id = substr($item["resa"], 4);
//         dd($room_id, $resa_id);
        $visitorReservation = VisitorReservation::find($resa_id);
        $visitorReservation->community()->associate($house_id);
        $visitorReservation->save();
        $this->getCommunities();
        $this->getResas();
        $this->emit('showAlert', [ __("Le visiteur a bien été déplacé !"), "bg-green-500" ] );
    }

    public function emptyCommunities()
    {
        $resas = VisitorReservation::whereNotNull('house_id')->get();
        foreach ($resas as $resa)
        {
            $resa->community()->dissociate();
            $resa->save();
        }
        $this->getCommunities();
        $this->getResas();
    }

    public function getOutOfCommunity($resa_id)
    {
        $resa = VisitorReservation::find($resa_id);
        $community = $resa->community;
        $resa->community()->dissociate();
        $resa->save();
        $this->getCommunities();
        $this->getResas();
    }

    public function mount()
    {
        $this->getCommunities();
        $today = Carbon::now();
        $this->beginDate = $today->startOfWeek()->format('Y-m-d');
        $this->beginDay = $today->startOfWeek()->format('l');
        $this->endDate = $today->endOfWeek()->format('Y-m-d');
        $this->endDay = $today->endOfWeek()->format('l');

        $this->getResas();
    }

    public function render()
    {
        return view('livewire.communities');
    }
}
