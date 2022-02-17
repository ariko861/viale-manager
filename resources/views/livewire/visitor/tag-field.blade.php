<div>
    <div wire:click="$toggle('showTagForm')" class="text-center cursor-pointer float-right">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
    </div>
    @if ($showTagForm)
        <input class="w-full" placeholder="{{__('Ajouter un tag')}}" type="text" wire:model="tagSearchQuery" wire:keyup="searchTag" >
        <ul class="col-span-2 bg-white absolute">
        @if ($tags && $tags->count())
            <li></li>
            @foreach ($tags as $tag)
                <li class="p-2 border-2 cursor-pointer" wire:click="setTag('{{$tag->id}}')"><span class="font-bold">{{__("Ajouter l'étiquette")}}</span> <span  class="badge" style="{{ $tag->color ? 'background-color : '.$tag->color : '' }}">{{ $tag->name }}</span> <span class="font-bold">{{__("au visiteur")}}</span></li>
            @endforeach
        @endif
        @if ($tagSearchQuery)
            <li class="border-4"></li>
            @foreach ($colors as $color)
                <li class="p-2 border-2 cursor-pointer" wire:click="createTag('{{ $color }}')"><span class="italic text-sm">{{__("Créer l'étiquette")}}</span> <span class="badge" style="background-color : {{ $color }}">{{ $tagSearchQuery }}</span> <span class="italic text-sm">{{__("et ajouter au visiteur")}}</span></li>
            @endforeach
        @endif
        </ul>
    @endif
</div>
