<div>

    <div class="option-field">
        <h2 class="mt-2 mb-4 text-center">{{__("Recherche de visiteurs")}} :</h2>
        <p class="my-8"><strong>{{__("Rechercher par nom d'un visiteur")}} :</strong> <input type="text" wire:keyup.debounce.500ms="getVisitorsByName" wire:model="visitorSearch"></p>
        @if ($tags)
        <p>
            <h4 class="mt-6 mb-4">{{__("Filtrer les visiteurs par étiquette")}} :</h4>
            @foreach ($tags as $tag)
                <span class="badge mx-4 cursor-pointer" wire:click="filterByTag('{{$tag->id}}')" style="background-color: {{ $this->isTagSelected($tag->id) ? $tag->color : '#CBD5E1' }}">{{ $tag->name }}</span>
            @endforeach
        </p>
        @endif
        <p class="text-center text-sm mt-4 mb-6"><span wire:click="$toggle('advancedSearch')" class="cursor-pointer">{{__("Recherche avancée")}}<x-buttons.arrow-chevron :up="$advancedSearch" size=4/></span></p>
        @if ($advancedSearch)
        <div>
            <p class="my-8"><strong>{{__("Afficher uniquement les visiteurs confirmés")}} :</strong> <input type="checkbox" wire:change.debounce.100ms="getAllVisitors" wire:model="onlyConfirmed"></p>

        </div>
        @endif
    </div>
    <h3 class="mt-4 mb-2 text-center">{{$visitors->count()}} {{__("visiteurs dans la liste")}}</h3>
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
            <td class="{{ $thead_class }}">{{ __("Étiquettes") }}</td>
            <td class="{{ $thead_class }}">{{ __("Remarques") }}</td>
            <td class="{{ $thead_class }}">{{ __("Réservations faites") }}</td>
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
                <td class="{{ $tbody_class }}">

                    <livewire:visitor.tag-field :wire:key="'tags-'.$visitor->id" :visitor_id="$visitor->id" :visitor_tags="$visitor->tags">
                </td>
                <td class="{{ $tbody_class }}">{{ $visitor["remarks"] }}</td>
                <td class="{{ $tbody_class }}">{{ $visitor->reservations->count() ?? 0 }}</td>
                @canany(['visitor-edit', 'visitor-delete'])
                <td class="{{ $tbody_class }}">
                <livewire:buttons.edit-buttons :wire:key="$visitor->id" model="visitor" :modelId="$visitor->id" editRights="visitor-edit" deleteRights="visitor-delete">
                </td>
                @endcanany
            </tr>
        @endforeach
   </tbody>
   </table>
</div>
