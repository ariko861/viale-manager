<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserForm extends Component
{
    public $modify = false;
    protected $listeners = ["startNewUser", "editUser"];

    protected $rules = [
        'user.name' => 'required|string',
        'rolesInUser.*' => ''
    ];

    public function startNewUser()
    {
        $this->user = new User();
        $this->rolesInUser = collect([]);
    }

    public function editUser($id)
    {
        $this->modify = true;
        $this->user = User::find($id);
        $this->rolesInUser = collect([]);
        if ( $this->user->roles->isNotEmpty() )
        {
            $this->rolesInUser = $this->user->roles->mapWithKeys(function($item, $key){
                return [ $item['id'] => true ];
            });
        }

    }

    public function cancelForm()
    {
        $this->emit('cancelForm');
    }
    public function mount()
    {
        $this->user = new User();
        $this->roles = Role::all();
    }

    public function save()
    {
        $this->validate();
        $this->user->save();
        $roles = $this->rolesInUser->map(function($item, $key){
            if ( $item )
            {
                return Role::find($key);
            }
        });
        $this->user->syncRoles($roles);

        $this->emit('cancelForm');

    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
