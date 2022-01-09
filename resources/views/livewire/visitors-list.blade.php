<div>
    <table class="mt-8 w-full table-auto border-collapse border border-gray-400">
    <thead>
        <tr>
            <td class="border-2 border-gray-400 bg-gray-100">{{ __("Nom de famille") }}</td>
            <td class="border-2 border-gray-400 bg-gray-100">{{ __("Prénom") }}</td>
            <td class="border-2 border-gray-400 bg-gray-100">{{ __("Âge") }}</td>
            <td class="border-2 border-gray-400 bg-gray-100">{{ __("Téléphone") }}</td>
            <td class="border-2 border-gray-400 bg-gray-100">{{ __("Email") }}</td>
        </tr>
    </thead>
    <tbody>
        @foreach ( $visitors as $visitor )
            <tr>
                <td class="border-2 border-gray-400">{{ $visitor["name"] }}</td>
                <td class="border-2 border-gray-400">{{ $visitor["surname"] }}</td>
                <td class="border-2 border-gray-400">{{ $visitor["age"] }}</td>
                <td class="border-2 border-gray-400">{{ $visitor["phone"] }}</td>
                <td class="border-2 border-gray-400"><a class="text-blue-600" href="mailto:{{ $visitor["email"] }}">{{ $visitor["email"] }}</a></td>
            </tr>
        @endforeach
   </tbody>
   </table>
</div>
