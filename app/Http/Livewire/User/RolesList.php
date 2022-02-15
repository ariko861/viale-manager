<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesList extends Component
{


    public $showRoleForm = false;
    public $confirmingDelete;

    protected $listeners = ["cancelRoleForm"];

    public function cancelRoleForm()
    {
        $this->showRoleForm = false;
        $this->roles = Role::whereNotIn('name', ['Super Admin'])->get();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDelete = $id;
    }

    public function deleteRole($role_id)
    {
        Role::destroy($role_id);

        $this->roles = $this->roles->filter(function($item) use ($role_id) {
            return $item->id != $role_id;
        });

        $this->emit('showAlert', [ __("Le role a bien été supprimé"), "bg-red-600"] );
    }
    public function mount()
    {
        $this->roles = Role::whereNotIn('name', ['Super Admin'])->get();
    }

    public function openRoleForm($role_id = null)
    {
        if ( $role_id ){
            $this->emit("editRole", $role_id);
        } else {
            $this->emit("startNewRole");
        }
        $this->showRoleForm = true;
    }

    public function render()
    {
        return view('livewire.user.roles-list');
    }
}
