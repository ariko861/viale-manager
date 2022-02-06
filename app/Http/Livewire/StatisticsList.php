<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;

class StatisticsList extends Component
{

    public $reservations;
    public $beginDate;
    public $endDate;
    public $totalIncome = 0;
    public $totalNights = 0;
    public $listTitle;
    public $visitorSearch;

    public function getTotalIncome()
    {
        $total = 0;
        foreach ( $this->reservations as $reservation )
        {
            $total += $reservation->total_price;
        }

        $this->totalIncome = number_format($total, 2,'€','€');
    }

    public function getTotalNights()
    {
        $this->totalNights = 0;
        foreach ( $this->reservations as $reservation )
        {
            $this->totalNights += $reservation->nights;
        }
    }

    public function getReservationsInBetween(){
        $this->reservations = Reservation::where('removeFromStats', '!=', true)->whereDate('departuredate', '>=', $this->beginDate)->whereDate('departuredate', '<=', $this->endDate)->get();
        $beginDate = new Carbon($this->beginDate);
        $endDate = new Carbon($this->endDate);

        $this->getTotalIncome();
        $this->getTotalNights();

    }

    public function getReservationsByVisitorName(){
        $value = $this->visitorSearch;
        if ( Str::length($value) >= 3 )
        {
            $this->reservations = Reservation::where('removeFromStats', '!=', true)->whereHas('visitors', function (Builder $query) {
                $query->where('name', 'like', '%'.$this->visitorSearch.'%')
                    ->orWhere('surname', 'like', '%'.$this->visitorSearch.'%')
                    ->orWhere('full_name', 'like', '%'.$this->visitorSearch.'%')
                    ->orderBy('updated_at', 'desc');
            })->get();
        }
        else
        {
            $this->reservations = [];
        }
        $this->getTotalIncome();
        $this->getTotalNights();
    }


    public function mount()
    {
        $today = Carbon::now();
        $this->beginDate = $today->copy()->startOfYear()->format('Y-m-d');
        $this->endDate = $today->copy()->endOfYear()->format('Y-m-d');

        $this->reservations = Reservation::where('removeFromStats', '!=', true)->whereDate('departuredate', '>=', $this->beginDate)->whereDate('departuredate', '<=', $this->endDate)->get();
        $this->getTotalIncome();
        $this->getTotalNights();

    }
    public function render()
    {
        return view('livewire.statistics-list');
    }
}
