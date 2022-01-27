<?php

namespace App\Http\Livewire;

use Illuminate\Validation\Validator;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Action\Fortify\CreateNewUser;

class UserForm extends Component
{
    public $modify = false;
    protected $listeners = ["editUser"];

    protected $rules = [
        'rolesInUser.*' => ''
    ];


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
