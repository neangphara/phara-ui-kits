@props([
    'paginator'  => null,  // Laravel LengthAwarePaginator
    'page'       => 1,     // current page (manual mode)
    'totalPages' => 1,     // total pages (manual mode)
    'size'       => 'md',  // sm | md | lg
    'showInfo'   => true,  // show "Showing X–Y of Z"
    'onEachSide' => 1,     // pages shown either side of current
])

@php
// ── Resolve page numbers ─────────────────────────────────────────────────────
$currentPage = $paginator ? $paginator->currentPage() : max(1, (int) $page);
$lastPage    = $paginator ? $paginator->lastPage()    : max(1, (int) $totalPages);
$isFirst     = $currentPage <= 1;
$isLast      = $currentPage >= $lastPage;

// ── Build page window ────────────────────────────────────────────────────────
// null in the array = ellipsis marker
if ($lastPage <= 7) {
    $pageWindow = range(1, $lastPage);
} else {
    $start  = max(2, $currentPage - $onEachSide);
    $end    = min($lastPage - 1, $currentPage + $onEachSide);
    $middle = range($start, $end);

    $pageWindow = [1];
    if ($start > 2)           $pageWindow[] = null;
    array_push($pageWindow, ...$middle);
    if ($end < $lastPage - 1) $pageWindow[] = null;
    $pageWindow[] = $lastPage;
}

// ── URL helpers (null → dispatch JS event) ───────────────────────────────────
$pageUrl = fn (int $p): ?string => $paginator ? $paginator->url($p) : null;
$prevUrl = $paginator?->previousPageUrl();
$nextUrl = $paginator?->nextPageUrl();

// ── Info string ───────────────────────────────────────────────────────────────
$from  = $paginator?->firstItem();
$to    = $paginator?->lastItem();
$total = $paginator?->total();

// ── Size map ──────────────────────────────────────────────────────────────────
$sizeMap = [
    'sm' => ['btn' => 'h-7  min-w-7  px-1.5 text-xs', 'icon' => 'w-3.5 h-3.5', 'gap' => 'gap-0.5', 'text' => 'text-xs'],
    'md' => ['btn' => 'h-9  min-w-9  px-2   text-sm', 'icon' => 'w-4   h-4',   'gap' => 'gap-1',   'text' => 'text-sm'],
    'lg' => ['btn' => 'h-11 min-w-11 px-2.5 text-sm', 'icon' => 'w-4.5 h-4.5', 'gap' => 'gap-1',   'text' => 'text-sm'],
];
$sc = $sizeMap[$size] ?? $sizeMap['md'];

// ── Shared button base ────────────────────────────────────────────────────────
$base     = 'inline-flex items-center justify-center rounded-lg font-medium transition-colors select-none ' . $sc['btn'];
$active   = $base . ' bg-primary-600 text-white shadow-sm';
$default  = $base . ' text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800';
$disabled = $base . ' text-zinc-300 dark:text-zinc-600 cursor-not-allowed pointer-events-none';
$dot      = $base . ' text-zinc-400 dark:text-zinc-500 cursor-default pointer-events-none';

// ── JS dispatch helper (manual mode navigation) ───────────────────────────────
$dispatch = fn (int $p): string => "window.dispatchEvent(new CustomEvent('page-change',{detail:{page:{$p}},bubbles:true}))";

// ── Wire navigate attr (passed through to <a> tags when present) ──────────────
$wireNavigate = $attributes->has('wire:navigate') ? 'wire:navigate' : '';
@endphp

@if($lastPage > 1)
<nav
    role="navigation"
    aria-label="Pagination"
    {{ $attributes->except(['wire:navigate'])->merge(['class' => 'flex flex-wrap items-center justify-between gap-3']) }}
>
    {{-- Info text ──────────────────────────────────────────────────────── --}}
    @if($showInfo && $paginator)
        <p class="{{ $sc['text'] }} text-zinc-500 dark:text-zinc-400 tabular-nums">
            Showing
            <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ number_format($from) }}</span>
            –
            <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ number_format($to) }}</span>
            of
            <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ number_format($total) }}</span>
            results
        </p>
    @else
        <span></span>{{-- keeps flex justify-between alignment --}}
    @endif

    {{-- Page buttons ───────────────────────────────────────────────────── --}}
    <div class="flex items-center {{ $sc['gap'] }}">

        {{-- Previous ──────────────────────────────────────────────────── --}}
        @if($isFirst)
            <span class="{{ $disabled }}" aria-disabled="true" aria-label="Previous page">
                <svg class="{{ $sc['icon'] }}" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/></svg>
            </span>
        @elseif($prevUrl)
            <a href="{{ $prevUrl }}" {{ $wireNavigate }} class="{{ $default }}" aria-label="Previous page">
                <svg class="{{ $sc['icon'] }}" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/></svg>
            </a>
        @else
            <button type="button" class="{{ $default }}" aria-label="Previous page" onclick="{{ $dispatch($currentPage - 1) }}">
                <svg class="{{ $sc['icon'] }}" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/></svg>
            </button>
        @endif

        {{-- Page numbers ──────────────────────────────────────────────── --}}
        @foreach($pageWindow as $p)
            @if($p === null)
                <span class="{{ $dot }}">…</span>
            @elseif($p === $currentPage)
                <span class="{{ $active }}" aria-current="page" aria-label="Page {{ $p }}">{{ $p }}</span>
            @elseif($pageUrl($p))
                <a href="{{ $pageUrl($p) }}" {{ $wireNavigate }} class="{{ $default }}" aria-label="Page {{ $p }}">{{ $p }}</a>
            @else
                <button type="button" class="{{ $default }}" aria-label="Page {{ $p }}" onclick="{{ $dispatch($p) }}">{{ $p }}</button>
            @endif
        @endforeach

        {{-- Next ──────────────────────────────────────────────────────── --}}
        @if($isLast)
            <span class="{{ $disabled }}" aria-disabled="true" aria-label="Next page">
                <svg class="{{ $sc['icon'] }}" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06L7.28 11.78a.75.75 0 0 1-1.06-1.06L9.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
            </span>
        @elseif($nextUrl)
            <a href="{{ $nextUrl }}" {{ $wireNavigate }} class="{{ $default }}" aria-label="Next page">
                <svg class="{{ $sc['icon'] }}" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06L7.28 11.78a.75.75 0 0 1-1.06-1.06L9.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
            </a>
        @else
            <button type="button" class="{{ $default }}" aria-label="Next page" onclick="{{ $dispatch($currentPage + 1) }}">
                <svg class="{{ $sc['icon'] }}" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06L7.28 11.78a.75.75 0 0 1-1.06-1.06L9.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
            </button>
        @endif

    </div>
</nav>
@endif
