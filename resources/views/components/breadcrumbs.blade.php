@props([])

<nav {{ $attributes->merge(['class' => 'flex items-center text-sm']) }} aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        {{ $slot }}
    </ol>
</nav>