{{-- Shared inner content for list-item — included by the parent blade, all variables are in scope --}}

{{-- Leading icon --}}
@if($icon)
    <x-ui::icon :name="$icon" class="w-4.5 h-4.5 shrink-0 {{ $iconColor }}" />
@endif

{{-- Title + optional description --}}
<span class="flex-1 min-w-0">
    <span class="block truncate font-medium">{{ $slot }}</span>
    @if($description)
        <span class="block truncate text-xs text-zinc-400 dark:text-zinc-500 mt-0.5 font-normal">{{ $description }}</span>
    @endif
</span>

{{-- Trailing: badge shorthand --}}
@if($badge)
    <x-ui::badge :color="$badgeColor" size="sm" class="shrink-0">{{ $badge }}</x-ui::badge>
@endif

{{-- Trailing: append slot --}}
@if(isset($append) && $append->isNotEmpty())
    <span class="shrink-0 ml-auto">{{ $append }}</span>
@endif

{{-- Trailing: chevron --}}
@if($chevron)
    <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 shrink-0 text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400 dark:group-hover:text-zinc-500 transition-colors ml-auto">
        <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06L7.28 11.78a.75.75 0 0 1-1.06-1.06L9.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
    </svg>
@endif
