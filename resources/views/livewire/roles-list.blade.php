<div>

    <h2 class="text-xl text-center">{{ __("Roles") }}</h2>

    <button class="btn" wire:click="openRoleForm">{{ __("Créer un nouveau rôle") }}</button>
    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
    @php
        $thead_class="border-2 border-gray-400 bg-gray-100";
        $tbody_class="border-2 border-gray-400 p-1"
    @endphp
    <thead>
        <tr>
            <td class="{{ $thead_class }}">{{ __("Nom") }}</td>
            <td class="{{ $thead_class }}">{{ __("Actions") }}</td>


        </tr>
    </thead>
    <tbody>
        @foreach ( $roles as $role )
            <tr>
                <td class="{{ $tbody_class }}">{{ $role->name }}</td>
                <td class="{{ $tbody_class }}">
                @if($confirmingDelete===$role->id)
                    <button wire:click="deleteRole({{ $role->id }})" class="btn-warning">{{ __("Confirmer la suppression ?") }}</button>
                @else
                    <button wire:click="openRoleForm({{ $role->id }})">{{ __("Modifier") }}</button>
                    <button class="btn-warning" wire:click="confirmDelete({{ $role->id }})">{{ __("Supprimer") }}</button>
                @endif
                </td>


            </tr>
        @endforeach

   </tbody>
   </table>
   @if ( $showRoleForm )
     <livewire:role-form>
   @endif

</div>
