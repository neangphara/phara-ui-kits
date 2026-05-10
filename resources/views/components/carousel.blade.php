@props([
    'autoplay'    => false,
    'interval'    => 3000,   // ms between auto-advances
    'loop'        => true,
    'showArrows'  => true,
    'showDots'    => true,
    'height'      => null,   // explicit CSS height, e.g. '400px'
    'aspectRatio' => null,   // CSS aspect-ratio, e.g. '16/9'
])

@php
$containerStyle = '';
if ($aspectRatio) $containerStyle = "aspect-ratio:{$aspectRatio}";
elseif ($height)  $containerStyle = "height:{$height}";
@endphp

<div
    x-data="{
        current:  0,
        total:    0,
        loop:     {{ $loop     ? 'true' : 'false' }},
        autoplay: {{ $autoplay ? 'true' : 'false' }},
        interval: {{ $interval }},
        timer:    null,
        startX:   0,

        /* ── Lifecycle ──────────────────────────────────────────── */
        init() {
            this.total = this.$el.querySelectorAll('[data-carousel-slide]').length;
            if (this.autoplay) this.startTimer();

            this.$el.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft')  { e.preventDefault(); this.prev(); }
                if (e.key === 'ArrowRight') { e.preventDefault(); this.next(); }
            });
        },

        /* ── Navigation ─────────────────────────────────────────── */
        prev() {
            if (this.current === 0) {
                if (this.loop) this.current = this.total - 1;
            } else {
                this.current--;
            }
            this.resetTimer();
        },

        next() {
            if (this.current === this.total - 1) {
                if (this.loop) this.current = 0;
            } else {
                this.current++;
            }
            this.resetTimer();
        },

        goTo(i) {
            this.current = i;
            this.resetTimer();
        },

        get isFirst() { return this.current === 0; },
        get isLast()  { return this.current === this.total - 1; },

        /* ── Autoplay ────────────────────────────────────────────── */
        startTimer() {
            this.timer = setInterval(() => this.next(), this.interval);
        },
        stopTimer() {
            if (this.timer) { clearInterval(this.timer); this.timer = null; }
        },
        resetTimer() {
            if (!this.autoplay) return;
            this.stopTimer();
            this.startTimer();
        },

        /* ── Touch / swipe ──────────────────────────────────────── */
        handleTouchStart(e) { this.startX = e.touches[0].clientX; },
        handleTouchEnd(e) {
            const diff = this.startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 40) {
                diff > 0 ? this.next() : this.prev();
            }
        }
    }"
    @mouseenter="stopTimer()"
    @mouseleave="autoplay && startTimer()"
    @touchstart.passive="handleTouchStart($event)"
    @touchend.passive="handleTouchEnd($event)"
    {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-xl']) }}
    @if($containerStyle) style="{{ $containerStyle }}" @endif
    tabindex="0"
    role="region"
    aria-label="Carousel"
>
    {{-- ── Slide track ─────────────────────────────────────────── --}}
    <div
        class="flex h-full transition-transform duration-500 ease-in-out will-change-transform"
        :style="`transform: translateX(-${current * 100}%)`"
    >
        {{ $slot }}
    </div>

    {{-- ── Prev / Next arrows ──────────────────────────────────── --}}
    @if($showArrows)
        <button
            type="button"
            @click="prev()"
            :disabled="!loop && isFirst"
            class="absolute left-3 top-1/2 -translate-y-1/2 z-10 flex h-9 w-9 items-center justify-center rounded-full border border-white/20 bg-black/30 text-white backdrop-blur-sm transition-all hover:bg-black/50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-30"
            aria-label="Previous slide"
        >
            <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                <path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
            </svg>
        </button>

        <button
            type="button"
            @click="next()"
            :disabled="!loop && isLast"
            class="absolute right-3 top-1/2 -translate-y-1/2 z-10 flex h-9 w-9 items-center justify-center rounded-full border border-white/20 bg-black/30 text-white backdrop-blur-sm transition-all hover:bg-black/50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-30"
            aria-label="Next slide"
        >
            <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06L7.28 11.78a.75.75 0 0 1-1.06-1.06L9.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
            </svg>
        </button>
    @endif

    {{-- ── Pagination dots ─────────────────────────────────────── --}}
    @if($showDots)
        <div
            class="absolute bottom-4 left-1/2 z-10 flex -translate-x-1/2 items-center gap-1.5"
            role="tablist"
            aria-label="Slides"
        >
            <template x-for="i in total" :key="i">
                <button
                    type="button"
                    role="tab"
                    @click="goTo(i - 1)"
                    :aria-selected="current === i - 1"
                    :aria-label="`Slide ${i}`"
                    class="h-2 rounded-full bg-white/50 transition-all duration-300 focus:outline-none hover:bg-white/80"
                    :class="current === i - 1 ? 'w-5 bg-white' : 'w-2'"
                ></button>
            </template>
        </div>
    @endif

    {{-- ── Slide counter (sr-only, for accessibility) ─────────── --}}
    <div class="sr-only" aria-live="polite">
        Slide <span x-text="current + 1"></span> of <span x-text="total"></span>
    </div>
</div>
