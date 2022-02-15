<?php

namespace App\Http\Livewire\Config;

use Livewire\Component;
use App\Models\Profile;

class ProfileList extends Component
{

    public $confirming;
    public $creatingProfile = false;

    protected $rules = [
        'newProfile.name' => 'required|string',
        'newProfile.price' => 'required|float',
        'newProfile.remarks' => '',

    ];

    public function mount()
    {
        $this->profiles = Profile::all()->sortBy('price');
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function deleteProfile($profile_id)
    {
        Profile::destroy($profile_id);

        $this->profiles = $this->profiles->filter(function($item) use ($profile_id) {
            return $item->id != $profile_id;
        });

        $this->emit('showAlert', [ __("Le profile a bien été supprimé"), "bg-red-600"] );
    }
    public function engageCreateProfile()
    {
        $this->newProfile = new Profile();
        $this->creatingProfile = true;
    }

    public function saveProfile()
    {
        $this->newProfile->save();
        $this->creatingProfile = false;
        $this->profiles->push($this->newProfile);
        $this->profiles = $this->profiles->sortBy('price');

    }
    public function render()
    {
        return view('livewire.config.profile-list');
    }
}
