<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddNewUser extends Component
{
    public $showUserForm = false;

    protected $listeners = ['hideVisitorForm' => 'hideUserForm'];

    public function hideUserForm()
    {
        $this->showUserForm = false;

    }

    public function render()
    {
        return view('livewire.add-new-user');
    }
}
