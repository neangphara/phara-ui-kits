@props([
    'align' => 'start',
    'sortable' => false,
    'sorted' => false,
    'direction' => 'asc',
    'href' => null,
    'sticky' => false,
])

@php
$alignMap = [
    'start'  => 'text-left',
    'center' => 'text-center',
    'end'    => 'text-right',
];

$base        = 'px-4 py-3 font-semibold text-xs uppercase tracking-wide text-zinc-500 dark:text-zinc-400 whitespace-nowrap';
$alignClass  = $alignMap[$align] ?? 'text-left';
$stickyClass = $sticky ? 'sticky left-0 bg-zinc-50 dark:bg-zinc-800/50 z-10' : '';

$classes = trim("$base $alignClass $stickyClass");
@endphp

<th scope="col" {{ $attributes->merge(['class' => $classes]) }}>
    @if($sortable)
        <a href="{{ $href ?? '#' }}" class="inline-flex items-center gap-1 group hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors cursor-pointer">
            {{ $slot }}

            @if($sorted && $direction === 'asc')
                {{-- Arrow up (sorted ascending) --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5 text-primary-600 dark:text-primary-400">
                    <path fill-rule="evenodd" d="M8 14a.75.75 0 0 0 .75-.75V4.56l1.47 1.47a.75.75 0 1 0 1.06-1.06L8.53 2.22a.75.75 0 0 0-1.06 0L4.72 4.97a.75.75 0 0 0 1.06 1.06L7.25 4.56v8.69c0 .414.336.75.75.75Z" clip-rule="evenodd" />
                </svg>
            @elseif($sorted && $direction === 'desc')
                {{-- Arrow down (sorted descending) --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5 text-primary-600 dark:text-primary-400">
                    <path fill-rule="evenodd" d="M8 2a.75.75 0 0 1 .75.75v8.69l1.47-1.47a.75.75 0 1 1 1.06 1.06l-2.75 2.75a.75.75 0 0 1-1.06 0L4.72 11.03a.75.75 0 0 1 1.06-1.06L7.25 11.44V2.75A.75.75 0 0 1 8 2Z" clip-rule="evenodd" />
                </svg>
            @else
                {{-- Chevrons up-down (unsorted) --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5 text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400 dark:group-hover:text-zinc-500 transition-colors">
                    <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                </svg>
            @endif
        </a>
    @else
        {{ $slot }}
    @endif
</th>
