@props([
    'text'     => '',
    'position' => 'top',   // top | bottom | left | right
    'variant'  => 'dark',  // dark | light
])

@php
$tipClasses = $variant === 'light'
    ? 'bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 shadow-lg ring-1 ring-black/10 dark:ring-white/10'
    : 'bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 shadow-md';
@endphp

<div
    x-data="{
        show: false,

        showTip() {
            this.show = true;
            this.$nextTick(() => this.place());
        },

        hideTip() {
            this.show = false;
        },

        place() {
            const trigger = this.$refs.trigger;
            const tip     = this.$refs.tip;
            if (!trigger || !tip) return;

            const r   = trigger.getBoundingClientRect();
            const tw  = tip.offsetWidth;
            const th  = tip.offsetHeight;
            const gap = 8;
            const vw  = window.innerWidth;
            const vh  = window.innerHeight;
            let top, left;

            const pos = '{{ $position }}';

            if (pos === 'bottom') {
                top  = r.bottom + gap;
                left = r.left + r.width / 2 - tw / 2;
            } else if (pos === 'left') {
                top  = r.top + r.height / 2 - th / 2;
                left = r.left - tw - gap;
            } else if (pos === 'right') {
                top  = r.top + r.height / 2 - th / 2;
                left = r.right + gap;
            } else {
                top  = r.top - th - gap;
                left = r.left + r.width / 2 - tw / 2;
            }

            tip.style.top  = Math.max(8, Math.min(top,  vh - th - 8)) + 'px';
            tip.style.left = Math.max(8, Math.min(left, vw - tw - 8)) + 'px';
        }
    }"
    @mouseenter="showTip()"
    @mouseleave="hideTip()"
    @focus.capture="showTip()"
    @blur.capture="hideTip()"
    {{ $attributes->merge(['class' => 'inline-block']) }}
>
    {{-- Trigger --}}
    <div x-ref="trigger">
        {{ $slot }}
    </div>

    {{-- Tooltip bubble --}}
    <div
        x-ref="tip"
        x-show="show"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed z-[9999] max-w-xs rounded-lg px-2.5 py-1.5 text-xs font-medium pointer-events-none {{ $tipClasses }}"
        style="display: none;"
        role="tooltip"
        x-cloak
    >{{ $text }}</div>
</div>
