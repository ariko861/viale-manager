<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleForm extends Component
{

    public $role;
    public $permissionsInRole;
    public $modify = false;
    protected $listeners = ["startNewRole", "editRole"];

    protected $rules = [
        'role.name' => 'required|string',
        'permissionsInRole.*' => ''
    ];

    public function startNewRole()
    {
        $this->role = new Role();
        $this->permissionsInRole = collect([]);
    }

    public function editRole($role_id)
    {
        $this->modify = true;
        $this->role = Role::find($role_id);
        $this->permissionsInRole = collect([]);
        if ( $this->role->permissions->isNotEmpty() )
        {
            $this->permissionsInRole = $this->role->permissions->mapWithKeys(function($item, $key){
                return [ $item['id'] => true ];
            });
        }

    }

    public function cancelRoleForm()
    {
        $this->emit('cancelRoleForm');
    }
    public function mount()
    {
        $this->role = new Role();
        $this->permissions = Permission::all();
    }

    public function save()
    {
        $this->validate();
        $this->role->save();
        $permissions = $this->permissionsInRole->map(function($item, $key){
            if ( $item )
            {
                return Permission::find($key);
            }
        });
        $this->role->syncPermissions($permissions);

        $this->emit('cancelRoleForm');

    }

    public function render()
    {
        return view('livewire.role-form');
    }
}
