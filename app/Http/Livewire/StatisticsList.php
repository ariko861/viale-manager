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

        $this->totalIncome = number_format($total, 2,'€',' ');
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
        $this->reservations = Reservation::where('removeFromStats', '!=', true)->whereDate('departuredate', '>=', $this->beginDate)->whereDate('departuredate', '<=', $this->endDate)->orderBy('departuredate')->get();
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
                $query->where('name', 'ilike', '%'.$this->visitorSearch.'%')
                    ->orWhere('surname', 'ilike', '%'.$this->visitorSearch.'%');
            })->orderBy('departuredate')->get();
        }
        else if ( Str::length($value) == 0 )
        {
            $this->getReservationsInBetween();
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

        $this->reservations = Reservation::where('removeFromStats', '!=', true)
                        ->where('confirmed', true)
                        ->whereDate('departuredate', '>=', $this->beginDate)
                        ->whereDate('departuredate', '<=', $this->endDate)
                        ->orderBy('departuredate')
                        ->get();
        $this->getTotalIncome();
        $this->getTotalNights();

    }
    public function render()
    {
        return view('livewire.statistics-list');
    }
}
