<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class UsersList extends Component
{


    public function mount()
    {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.users-list');
    }
}
