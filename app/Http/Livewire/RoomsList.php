<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\House;
use App\Models\Room;

class RoomsList extends Component
{

    public $creatingHouse = false;
    public $creatingRoom = false;
    public $modifiedHouseId;

    protected $rules = [
        'newHouse.name' => 'required|string',
        'newRoom.name' => 'required|string',
        'newRoom.beds' => 'required|int|min:0',
    ];

    public function mount()
    {
        $this->houses = House::all()->sortBy('name');
    }

    public function engageCreateHouse()
    {
        $this->newHouse = new House();
        $this->creatingHouse = true;
    }

    public function engageCreateRoom($house)
    {
        $this->modifiedHouseId = $house;
        $this->newRoom = new Room();
        $this->creatingRoom = true;
    }

    public function cancelRoomForm()
    {
        $this->creatingRoom = false;
    }
    public function saveHouse()
    {
        $this->validate([
            'newHouse.name' => 'required|string',
        ]);
        $this->newHouse->save();
        $this->creatingHouse = false;
        $this->houses->push($this->newHouse);
        $this->houses = $this->houses->sortBy('name');

    }

    public function saveRoom()
    {
        $this->validate([
            'newRoom.name' => 'required|string',
            'newRoom.beds' => 'required|int|min:0',

        ]);
        $house = House::find($this->modifiedHouseId);
        $house->rooms()->save($this->newRoom);
        $this->houses = $this->houses->fresh('rooms');
        $this->creatingRoom = false;

    }

    public function render()
    {
        return view('livewire.rooms-list');
    }
}
