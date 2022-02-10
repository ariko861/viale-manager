<span class="inline">
    @if ($up)
    <svg title="chevron-up" xmlns="http://www.w3.org/2000/svg" class="h-{{$size}} w-{{$size}} inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
    @else
    <svg title="chevron-down" xmlns="http://www.w3.org/2000/svg" class="h-{{$size}} w-{{$size}} inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
    @endif
</span>
