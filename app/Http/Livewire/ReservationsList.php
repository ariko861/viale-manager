<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\VisitorReservation;

class ReservationsList extends Component
{

    public $showRoomSelection = false;
    public $visitorSelectedForRoom;
    public $reservationSelectedForRoom;
    public $confirmingDeletion;

    protected $listeners = ["hideRoomSelection"];

    public function mount()
    {
        $this->reservations = Reservation::all()->sortBy('arrivaldate');
    }

    public function selectRoom( $visitor, $reservation )
    {
        $this->showRoomSelection = true;
        $this->reservationSelectedForRoom = $reservation;
        $this->visitorSelectedForRoom = $visitor;
    }

    public function hideRoomSelection()
    {
        $this->showRoomSelection = false;
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = $id;
    }

    public function deleteReservation($profile_id)
    {
        Reservation::destroy($profile_id);

        $this->reservations = $this->reservations->filter(function($item) use ($profile_id) {
            return $item->id != $profile_id;
        });

        $this->emit('showAlert', [ __("La réservation a bien été supprimé"), "bg-red-600"] );
    }

    public function render()
    {
        return view('livewire.reservations-list');
    }
}
