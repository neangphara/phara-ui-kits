@props([
    'sticky'     => false,
    'selectable' => false,
])

@php
$classes = 'border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50';

if ($sticky) {
    $classes .= ' sticky top-0 z-10';
}
@endphp

<thead>
    <tr {{ $attributes->merge(['class' => $classes]) }}>
        @if($selectable)
            <th scope="col" class="w-10 px-4 py-3">
                <input
                    type="checkbox"
                    class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-900 text-primary-600
                           focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 cursor-pointer"
                    :checked="allSelected()"
                    :indeterminate="someSelected()"
                    @click="toggleAll()"
                />
            </th>
        @endif
        {{ $slot }}
    </tr>
</thead>
