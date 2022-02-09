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

    protected $listeners = ['changeAction', 'deleteAction'];

    protected $rules = [
        'newHouse.name' => 'required|string',
        'newRoom.name' => 'required|string',
        'newRoom.beds' => 'required|int|min:0',
        'houses.*.community' => 'boolean',
    ];

    public function changeAction($options)
    {
        if ($options[1] == 'room')
        {
            $room = Room::find($options[0]);
            $this->modifiedHouseId = $room->house_id;
            $this->newRoom = $room;
            $this->creatingRoom = true;
        }
    }

    public function deleteAction($options)
    {
        if ($options[1] == 'room')
        {
            $this->deleteRoom($options[0]);
        }
    }

    public function deleteRoom($room_id)
    {
        Room::destroy($room_id);
        $this->houses = House::all()->sortBy('name');

    }

    public function updateCommunityRule()
    {
        foreach ($this->houses as $house) {
            $house->save();
        }
    }

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
