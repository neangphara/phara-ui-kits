@props([
    'paginate'       => null,
    'paginationSize' => 'md',
    'showInfo'       => true,
    'onEachSide'     => 1,
])

<div {{ $attributes->only('class')->merge(['class' => 'w-full']) }}>
    <div class="w-full overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full text-sm">
            {{ $slot }}
        </table>
    </div>

    @if($paginate)
        <div class="mt-4">
            <x-ui::pagination
                :paginator="$paginate"
                :size="$paginationSize"
                :show-info="$showInfo"
                :on-each-side="$onEachSide"
            />
        </div>
    @endif
</div>
