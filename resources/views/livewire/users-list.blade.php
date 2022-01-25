<div>

    <h2 class="text-xl text-center">{{ __("Utilisateurs") }}</h2>
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
                    @foreach($user->getRoleNames() as $v)
                    <label class="badge badge-success">{{ $v }}</label>
                    @endforeach
                @endif
                </td>
                <td class="{{ $tbody_class }}">{{ $user["email"] }}</td>


            </tr>
        @endforeach

   </tbody>
   </table>

</div>
