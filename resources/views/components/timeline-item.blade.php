@props(['date' => null])

<li class="group relative flex gap-4 pb-8 last:pb-0">

    {{-- Left column: indicator + vertical connector --}}
    <div class="flex shrink-0 flex-col items-center">

        {{-- Indicator: custom slot or default gray dot --}}
        @isset($indicator)
            {{ $indicator }}
        @else
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                <span class="h-2 w-2 rounded-full bg-zinc-400 dark:bg-zinc-500"></span>
            </span>
        @endisset

        {{-- Connector line — hidden on the last item --}}
        <div class="mt-2 w-px flex-1 bg-gray-200 dark:bg-zinc-700 group-last:hidden"></div>
    </div>

    {{-- Right column: optional date + content --}}
    <div class="min-w-0 flex-1 pt-1.5 pb-6 group-last:pb-0">
        @if ($date)
            <time class="mb-1 block text-xs text-zinc-400 dark:text-zinc-500">{{ $date }}</time>
        @endif
        {{ $slot }}
    </div>
</li>
