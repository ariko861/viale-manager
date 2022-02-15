<?php

namespace App\Http\Livewire\User;

use Illuminate\Validation\Validator;
use Livewire\Component;
use App\Models\User;
use App\Models\UserInvite;

class UsersList extends Component
{


    public $showForm = false;
    public $confirmingDelete;
    public $confirmingInviteDelete;
    public $inviteEmail;
    protected $listeners = ["cancelForm"];

    protected $rules = [
        'inviteEmail' => 'required|email',
    ];

    public function getUsers()
    {
        $this->users = User::doesntHave('roles')->orWhereRelation("roles", function($q) {
            $q->where("name", "!=" , "Super Admin");
        })->get();
    }
    public function cancelForm()
    {
        $this->showForm = false;
        $this->getUsers();
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
    public function confirmInviteDelete($id)
    {
        $this->confirmingInviteDelete = $id;
    }

    public function deleteInvite($id)
    {
        UserInvite::destroy($id);

        $this->userInvites = $this->userInvites->filter(function($item) use ($id) {
            return $item->id != $id;
        });

        $this->emit('showAlert', [ __("Le lien d'invitation a bien été supprimé"), "bg-red-600"] );
    }

    public function sendInviteEmail()
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {
                if ( UserInvite::where('email', $this->inviteEmail)->count() ) {
                    $validator->errors()->add('inviteEmail', __("Une invitation a déjà été envoyée à cet utilisateur.") );
                }
                if (User::where('email', $this->inviteEmail)->count()) {
                    $validator->errors()->add('inviteEmail', __("Un utilisateur avec cette adresse email existe déjà.") );
                }

            });

        })->validate();
        $invitation = new UserInvite();
        $invitation->email = $this->inviteEmail;
        $invitation->generateInvitationToken();
        $invitation->save();
        $this->userInvites = UserInvite::all();
    }
    public function openForm($id)
    {
        $this->emit("editUser", $id);
        $this->showForm = true;
    }

    public function mount()
    {
//         $this->users = User::all();
        $this->getUsers();
        $this->userInvites = UserInvite::all();
    }

    public function render()
    {
        return view('livewire.user.users-list');
    }
}
