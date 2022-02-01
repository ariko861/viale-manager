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
            @canany(['visitor-edit', 'visitor-delete'])
                <td class="{{ $thead_class }}">{{ __("Modifications") }}</td>
            @endcanany
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
                @canany(['visitor-edit', 'visitor-delete'])
                <td class="{{ $tbody_class }}">
                <livewire:buttons.edit-buttons :wire:key="$visitor->id" :modelId="$visitor->id" editRights="visitor-edit" deleteRights="visitor-delete">
                </td>
                @endcanany
            </tr>
        @endforeach
   </tbody>
   </table>
</div>
