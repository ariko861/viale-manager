<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class UsersList extends Component
{


    public $showForm = false;
    public $confirmingDelete;

    protected $listeners = ["cancelForm"];

    public function cancelForm()
    {
        $this->showForm = false;
        $this->users = User::all();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function delete($id)
    {
        User::destroy($id);

        $this->users = $this->users->filter(function($item) use ($id) {
            return $item->id != $id;
        });

        $this->emit('showAlert', [ __("L'utilisateur a bien été supprimé"), "bg-red-600"] );
    }

    public function openForm($id = null)
    {
        if ( $id ){
            $this->emit("editUser", $id);
        } else {
            $this->emit("startNewUser");
        }
        $this->showForm = true;
    }

    public function mount()
    {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.users-list');
    }
}
