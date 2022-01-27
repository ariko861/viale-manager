<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="bg-white rounded-lg border py-2 px-3 shadow-md cursor-pointer {{ $event['classes'] ?? '' }} ">

    <p class="text-sm font-medium">
        {{ $event['title'] }}
    </p>
    <p class="mt-2 text-xs">
        {!! nl2br($event['description'] ?? 'No description') !!}
    </p>
</div>
