<?php

namespace App\Http\Livewire\Reservation;

use Livewire\Component;
use Carbon\Carbon;
use App\Http\Controllers\RecapController;

class ReservationRecap extends Component
{
    public $showResaRecap = false;
    public $beginDate;
    public $endDate;

    protected $listeners = ['showReservationRecap'];

    public function showReservationRecap() {
        $this->showResaRecap = true;
    }
    public function hideReservationRecap() {
        $this->showResaRecap = false;
    }

    public function thisWeek()
    {
        $today = Carbon::now();
        $this->beginDate = $today->startOfWeek()->translatedFormat('Y-m-d');
        $this->endDate = $today->endOfWeek()->translatedFormat('Y-m-d');
    }
    public function prevWeek()
    {
        $this->beginDate = Carbon::parse($this->beginDate)->subWeek()->translatedFormat('Y-m-d');
        $this->endDate = Carbon::parse($this->endDate)->subWeek()->translatedFormat('Y-m-d');
    }

    public function nextWeek()
    {
        $this->beginDate = Carbon::parse($this->beginDate)->addWeek()->format('Y-m-d');
        $this->endDate = Carbon::parse($this->endDate)->addWeek()->format('Y-m-d');
    }

    public function mount() {
        $this->thisWeek();
    }
    public function render()
    {
        return view('livewire.reservation.reservation-recap');
    }
}
