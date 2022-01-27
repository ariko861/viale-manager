<div>

    <h2 class="text-xl text-center">{{ __("Utilisateurs") }}</h2>
    <br>
    <input type="email" wire:model="inviteEmail" placeholder='{{ __("Saississez un email")}}'>
    <button class="btn" wire:click="sendInviteEmail">{{ __("Créer un lien d'invitation pour un utilisateur") }}</button>
    @error('inviteEmail') <span class="col-span-1"></span><span class="red col-span-2 error">{{ $message }}</span> @enderror
    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
    @php
        $thead_class="border-2 border-gray-400 bg-gray-100";
        $tbody_class="border-2 border-gray-400 p-1"
    @endphp
    <thead>
        <tr>
            <td class="{{ $thead_class }}">{{ __("Nom") }}</td>
            <td class="{{ $thead_class }}">{{ __("Email") }}</td>
            <td class="{{ $thead_class }}">{{ __("Roles") }}</td>
            <td class="{{ $thead_class }}">{{ __("Actions") }}</td>


        </tr>
    </thead>
    <tbody>
        @foreach ( $users as $user )
            <tr>
                <td class="{{ $tbody_class }}">{{ $user->name }}</td>
                <td class="{{ $tbody_class }}">{{ $user->email }}</td>
                <td class="{{ $tbody_class }}">
                @if(!empty($user->getRoleNames()))
                    @foreach($user->getRoleNames() as $role)
                    <label class="badge badge-success">{{ $role }}</label>
                    @endforeach
                @endif
                </td>
                <td class="{{ $tbody_class }}">
                @if($confirmingDelete===$user->id)
                    <button wire:click="delete({{ $user->id }})" class="btn-warning">{{ __("Confirmer la suppression ?") }}</button>
                @else
                    <button wire:click="openForm({{ $user->id }})">{{ __("Modifier") }}</button>
                    <button class="btn-warning" wire:click="confirmDelete({{ $user->id }})">{{ __("Supprimer") }}</button>
                @endif
                </td>


            </tr>
        @endforeach

   </tbody>
   </table>
    @if ( $showForm )
     <livewire:user-form>
   @endif

   @if ( $userInvites->count() )
   <br>
   <h2 class="text-lg text-center">{{ __("Invitations envoyées") }}</h2>
   <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
    @php
        $thead_class="border-2 border-gray-400 bg-gray-100";
        $tbody_class="border-2 border-gray-400 p-1"
    @endphp
    <thead>
        <tr>
            <td class="{{ $thead_class }}">{{ __("Email") }}</td>
            <td class="{{ $thead_class }}">{{ __("Lien d'invitation") }}</td>
            <td class="{{ $thead_class }}">{{ __("Crée le") }}</td>
            <td class="{{ $thead_class }}">{{ __("Actions") }}</td>

        </tr>
    </thead>
    <tbody>
        @foreach ( $userInvites as $userInvite )
            <tr>
                <td class="{{ $tbody_class }}">{{ $userInvite->email }}</td>
                <td class="{{ $tbody_class }}">{{ $userInvite->getLink() }}</td>
                <td class="{{ $tbody_class }}">{{ $userInvite->created_at }}</td>

                <td class="{{ $tbody_class }}">
                @if($confirmingInviteDelete===$userInvite->id)
                    <button wire:click="deleteInvite({{ $userInvite->id }})" class="btn-warning">{{ __("Confirmer la suppression ?") }}</button>
                @else
                    <button class="btn-warning" wire:click="confirmInviteDelete({{ $userInvite->id }})">{{ __("Supprimer") }}</button>
                @endif
                </td>


            </tr>
        @endforeach

   </tbody>
   </table>
   @endif

</div>
