@props([
    'align' => 'left',
    'width' => 'md',
    'trigger' => null,
])

@php
$widthClasses = [
    'sm' => 'w-48',
    'md' => 'w-56',
    'lg' => 'w-64',
    'xl' => 'w-72',
];

$widthClass = $widthClasses[$width] ?? $widthClasses['md'];
@endphp

<div
    x-data="{
        open: false,
        align: '{{ $align }}',
        updatePosition() {
            if (!this.open) return;

            const trigger = this.$refs.trigger;
            const menu = this.$refs.menu;

            if (!trigger || !menu) return;

            const rect = trigger.getBoundingClientRect();
            const menuWidth = menu.offsetWidth;
            const menuHeight = menu.offsetHeight;
            const spaceBelow = window.innerHeight - rect.bottom;

            // Flip upward when not enough space below
            if (spaceBelow < menuHeight + 8 && rect.top > menuHeight + 8) {
                menu.style.top = (rect.top - menuHeight - 8) + 'px';
            } else {
                menu.style.top = (rect.bottom + 8) + 'px';
            }

            // Align based on prop
            if (this.align === 'right') {
                menu.style.left = (rect.right - menuWidth) + 'px';
                menu.style.right = 'auto';
            } else if (this.align === 'center') {
                menu.style.left = (rect.left + (rect.width / 2) - (menuWidth / 2)) + 'px';
                menu.style.right = 'auto';
            } else {
                menu.style.left = rect.left + 'px';
                menu.style.right = 'auto';
            }
        },
        toggleScrollLock(shouldLock) {
            if (shouldLock) {
                const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
                document.body.style.paddingRight = scrollbarWidth + 'px';
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        }
    }"
    x-init="$watch('open', value => toggleScrollLock(value))"
    @click.away="open = false"
    @resize.window="updatePosition()"
    @scroll.window="updatePosition()"
    class="relative inline-block text-left"
>
    {{-- Trigger --}}
    <div
        x-ref="trigger"
        @click="open = !open; $nextTick(() => updatePosition())"
        class="cursor-pointer"
    >
        {{ $trigger }}
    </div>

    {{-- Dropdown Menu - Fixed positioning to escape parent containers --}}
    <div
        x-ref="menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed z-[9999] {{ $widthClass }} rounded-xl bg-white dark:bg-zinc-800 shadow-lg ring-1 ring-black/5 dark:ring-white/10"
        style="display: none;"
        x-cloak
    >
        <div class="py-1" @click="open = false">
            {{ $slot }}
        </div>
    </div>
</div>
