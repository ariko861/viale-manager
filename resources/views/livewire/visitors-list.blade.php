<div>
    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
    @php
        $thead_class="border-2 border-gray-400 bg-gray-100";
        $tbody_class="border-2 border-gray-400 p-1"
    @endphp
    <thead>
        <tr>
            <td class="{{ $thead_class }}">{{ __("Nom de famille") }}</td>
            <td class="{{ $thead_class }}">{{ __("Prénom") }}</td>
            <td class="{{ $thead_class }}">{{ __("Âge") }}</td>
            <td class="{{ $thead_class }}">{{ __("Téléphone") }}</td>
            <td class="{{ $thead_class }}">{{ __("Email") }}</td>
            <td class="{{ $thead_class }}">{{ __("Remarques") }}</td>
            <td class="{{ $thead_class }}">{{ __("Modifications") }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach ( $visitors as $visitor )
            <tr>
                <td class="{{ $tbody_class }}">{{ $visitor["name"] }}</td>
                <td class="{{ $tbody_class }}">{{ $visitor["surname"] }}</td>
                <td class="{{ $tbody_class }}">{{ $visitor["age"] }}</td>
                <td class="{{ $tbody_class }}">{{ $visitor["phone"] }}</td>
                <td class="{{ $tbody_class }}"><a class="text-blue-600" href="mailto:{{ $visitor["email"] }}">{{ $visitor["email"] }}</a></td>
                <td class="{{ $tbody_class }}">{{ $visitor["remarks"] }}</td>
                <td class="{{ $tbody_class }}">
                @if($confirming===$visitor->id)
                <button wire:click="deleteVisitor({{ $visitor->id }})" class="bg-red-800 text-white w-32 px-4 py-1 hover:bg-red-600 rounded border">{{ __("Confirmer la suppression ?") }}</button>
                @else
                    <svg wire:click="engageVisitorChange({{ $visitor->id }})" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-left cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <svg wire:click="confirmDelete({{ $visitor->id }})" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-right stroke-red-600 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                @endif
                </td>
            </tr>
        @endforeach
   </tbody>
   </table>
</div>
