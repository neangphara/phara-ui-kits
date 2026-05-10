@props([
    'width' => 'md',
])

@php
$widthMap = [
    'sm' => 'w-48',
    'md' => 'w-56',
    'lg' => 'w-64',
    'xl' => 'w-72',
];
$widthClass = $widthMap[$width] ?? $widthMap['md'];
@endphp

<div
    x-data="{
        open: false,
        x: 0,
        y: 0,
        openAt(event) {
            event.preventDefault();
            {{-- Close any other open context menus on the page --}}
            window.dispatchEvent(new CustomEvent('ui:close-context-menus'));
            this.$nextTick(() => {
                this.x = event.clientX;
                this.y = event.clientY;
                this.open = true;
                this.$nextTick(() => this.adjustPosition());
            });
        },
        adjustPosition() {
            const menu = this.$refs.menu;
            if (!menu) return;
            const { width, height } = menu.getBoundingClientRect();
            const pad = 8;
            if (this.x + width > window.innerWidth - pad) {
                this.x = this.x - width;
            }
            if (this.y + height > window.innerHeight - pad) {
                this.y = this.y - height;
            }
        }
    }"
    @contextmenu.prevent="openAt($event)"
    @click.away="open = false"
    @keydown.escape.window="open = false"
    @scroll.window.passive="open = false"
    @ui:close-context-menus.window="open = false"
    {{ $attributes }}
>
    {{-- Right-click area --}}
    {{ $slot }}

    {{-- Floating context menu --}}
    <div
        x-ref="menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        :style="`left: ${x}px; top: ${y}px`"
        class="fixed z-[9999] {{ $widthClass }} rounded-xl bg-white dark:bg-zinc-800 shadow-xl ring-1 ring-zinc-950/5 dark:ring-white/10 py-1 origin-top-left"
        style="display: none;"
        @click="open = false"
        x-cloak
    >
        {{ $menu }}
    </div>
</div>
