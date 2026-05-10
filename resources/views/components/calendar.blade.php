@props([
    'name'        => null,
    'value'       => null,
    'mode'        => 'single',   // single | multiple | range
    'min'         => null,       // Y-m-d | 'today'
    'max'         => null,       // Y-m-d | 'today'
    'unavailable' => null,       // comma-separated Y-m-d
    'withToday'   => false,
    'startDay'    => 0,          // 0=Sun … 6=Sat
    'size'        => 'md',       // sm | md | lg
])

@php
use Carbon\Carbon;

$now = Carbon::today();

$minDateStr = match($min) { 'today' => $now->format('Y-m-d'), null => null, default => $min };
$maxDateStr = match($max) { 'today' => $now->format('Y-m-d'), null => null, default => $max };

$unavailableArr = $unavailable
    ? array_values(array_filter(array_map('trim', explode(',', $unavailable))))
    : [];

// Parse initial value → initialise view to the right month
$initialSelected   = null;
$initialMultiple   = [];
$initialRangeStart = null;
$initialRangeEnd   = null;
$initialYear       = (int) $now->format('Y');
$initialMonth      = (int) $now->format('n') - 1; // 0-indexed for JS

if ($value) {
    if ($mode === 'range' && str_contains($value, '/')) {
        [$initialRangeStart, $initialRangeEnd] = explode('/', $value, 2);
        $d = Carbon::parse($initialRangeStart);
        $initialYear  = $d->year;
        $initialMonth = $d->month - 1;
    } elseif ($mode === 'multiple') {
        $initialMultiple = array_values(array_filter(array_map('trim', explode(',', $value))));
        if ($initialMultiple) {
            $d = Carbon::parse($initialMultiple[0]);
            $initialYear  = $d->year;
            $initialMonth = $d->month - 1;
        }
    } else {
        $initialSelected = $value;
        $d = Carbon::parse($value);
        $initialYear  = $d->year;
        $initialMonth = $d->month - 1;
    }
}

$sizeMap = [
    'sm' => ['cell' => 'w-8 h-8',   'text' => 'text-xs',   'header' => 'text-sm'],
    'md' => ['cell' => 'w-9 h-9',   'text' => 'text-sm',   'header' => 'text-sm'],
    'lg' => ['cell' => 'w-10 h-10', 'text' => 'text-base', 'header' => 'text-sm'],
];
$sc = $sizeMap[$size] ?? $sizeMap['md'];

$isDisabled = $attributes->has('disabled');
@endphp

<div
    x-data="{
        mode:             '{{ $mode }}',
        viewYear:         {{ $initialYear }},
        viewMonth:        {{ $initialMonth }},
        selected:         @js($initialSelected),
        selectedMultiple: @js($initialMultiple),
        rangeStart:       @js($initialRangeStart),
        rangeEnd:         @js($initialRangeEnd),
        hoverDate:        null,
        minDate:          @js($minDateStr),
        maxDate:          @js($maxDateStr),
        unavailable:      @js($unavailableArr),
        startDay:         {{ $startDay }},

        /* ── Helpers ────────────────────────────────────────────────── */
        todayStr() {
            const t = new Date();
            return `${t.getFullYear()}-${String(t.getMonth()+1).padStart(2,'0')}-${String(t.getDate()).padStart(2,'0')}`;
        },

        get monthLabel() {
            return new Date(this.viewYear, this.viewMonth, 1)
                .toLocaleDateString('en-US', { month: 'long' });
        },

        get dayAbbr() {
            const all = ['Su','Mo','Tu','We','Th','Fr','Sa'];
            return [...all.slice(this.startDay), ...all.slice(0, this.startDay)];
        },

        /* Effective range end (committed or hover preview) */
        get effectiveEnd() {
            return this.rangeEnd ?? (this.rangeStart ? this.hoverDate : null);
        },

        /* Sorted visual start/end for range display */
        get visualStart() {
            if (!this.rangeStart || !this.effectiveEnd) return this.rangeStart;
            return this.rangeStart <= this.effectiveEnd ? this.rangeStart : this.effectiveEnd;
        },
        get visualEnd() {
            if (!this.rangeStart || !this.effectiveEnd) return null;
            return this.rangeStart <= this.effectiveEnd ? this.effectiveEnd : this.rangeStart;
        },

        /* ── Calendar grid ──────────────────────────────────────────── */
        calendarDays() {
            const firstDow = new Date(this.viewYear, this.viewMonth, 1).getDay();
            const offset   = (firstDow - this.startDay + 7) % 7;
            return Array.from({ length: 42 }, (_, i) => {
                const d    = new Date(this.viewYear, this.viewMonth, i - offset + 1);
                const yyyy = d.getFullYear();
                const mm   = String(d.getMonth() + 1).padStart(2, '0');
                const dd   = String(d.getDate()).padStart(2, '0');
                return { date: `${yyyy}-${mm}-${dd}`, day: d.getDate(), currentMonth: d.getMonth() === this.viewMonth };
            });
        },

        /* ── State checks ───────────────────────────────────────────── */
        isToday(d)    { return d === this.todayStr(); },
        isDisabled(d) {
            if (this.minDate && d < this.minDate) return true;
            if (this.maxDate && d > this.maxDate) return true;
            return this.unavailable.includes(d);
        },

        isSelected(d) {
            if (this.mode === 'single')   return d === this.selected;
            if (this.mode === 'multiple') return this.selectedMultiple.includes(d);
            if (this.mode === 'range')    return d === this.visualStart || d === this.visualEnd;
            return false;
        },

        /* Show right half-band behind range-start circle */
        showStartBand(d) {
            return this.mode === 'range' && d === this.visualStart && this.visualEnd && this.visualStart !== this.visualEnd;
        },
        /* Show left half-band behind range-end circle */
        showEndBand(d) {
            return this.mode === 'range' && d === this.visualEnd && this.visualStart && this.visualStart !== this.visualEnd;
        },
        isInRange(d) {
            if (this.mode !== 'range' || !this.visualStart || !this.visualEnd) return false;
            return d > this.visualStart && d < this.visualEnd;
        },

        /* ── Dynamic button classes ─────────────────────────────────── */
        dayClass(day) {
            if (!day.currentMonth) return 'text-zinc-300 dark:text-zinc-700 cursor-default pointer-events-none';
            if (this.isDisabled(day.date)) return 'text-zinc-300 dark:text-zinc-600 cursor-not-allowed line-through';

            if (this.isSelected(day.date)) {
                return 'bg-primary-600 dark:bg-primary-500 text-white hover:bg-primary-700 dark:hover:bg-primary-600 font-semibold';
            }
            if (this.isToday(day.date)) {
                return 'ring-2 ring-primary-500 dark:ring-primary-400 text-primary-600 dark:text-primary-400 font-semibold hover:bg-primary-50 dark:hover:bg-primary-900/20';
            }
            return 'text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800';
        },

        /* ── Date selection ─────────────────────────────────────────── */
        selectDate(d) {
            if (this.isDisabled(d)) return;
            if (this.mode === 'single') {
                this.selected = this.selected === d ? null : d;
            } else if (this.mode === 'multiple') {
                const i = this.selectedMultiple.indexOf(d);
                if (i >= 0) this.selectedMultiple.splice(i, 1);
                else        this.selectedMultiple.push(d);
            } else if (this.mode === 'range') {
                if (!this.rangeStart || this.rangeEnd) {
                    this.rangeStart = d; this.rangeEnd = null; this.hoverDate = null;
                } else {
                    if (d === this.rangeStart) { this.rangeStart = null; }
                    else if (d < this.rangeStart) { this.rangeEnd = this.rangeStart; this.rangeStart = d; }
                    else                          { this.rangeEnd = d; }
                    this.hoverDate = null;
                }
            }
            this.sync();
        },

        /* ── Navigation ─────────────────────────────────────────────── */
        prevMonth() {
            if (this.viewMonth === 0)  { this.viewYear--; this.viewMonth = 11; }
            else                         this.viewMonth--;
        },
        nextMonth() {
            if (this.viewMonth === 11) { this.viewYear++; this.viewMonth = 0; }
            else                         this.viewMonth++;
        },
        goToday() {
            const t = new Date();
            this.viewYear  = t.getFullYear();
            this.viewMonth = t.getMonth();
            this.selectDate(this.todayStr());
        },

        /* ── Sync hidden input ──────────────────────────────────────── */
        sync() {
            let v = '';
            if      (this.mode === 'single')                        v = this.selected ?? '';
            else if (this.mode === 'multiple')                      v = this.selectedMultiple.join(',');
            else if (this.mode === 'range' && this.rangeStart && this.rangeEnd) v = `${this.rangeStart}/${this.rangeEnd}`;
            const el = this.$refs.hiddenInput;
            if (el) { el.value = v; el.dispatchEvent(new Event('input',{bubbles:true})); el.dispatchEvent(new Event('change',{bubbles:true})); }
        }
    }"
    {{ $attributes->except(['wire:model', 'x-model', 'disabled'])->merge(['class' => 'inline-block']) }}
>
    <div class="p-4 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 select-none {{ $isDisabled ? 'opacity-60 pointer-events-none' : '' }}">

        {{-- ── Month navigation header ─────────────────────────────── --}}
        <div class="flex items-center justify-between mb-4 px-0.5">
            <button
                type="button"
                @click="prevMonth()"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none"
                aria-label="Previous month"
            >
                <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
                </svg>
            </button>

            <span class="{{ $sc['header'] }} font-semibold text-zinc-800 dark:text-zinc-100">
                <span x-text="monthLabel"></span>&nbsp;<span x-text="viewYear"></span>
            </span>

            <button
                type="button"
                @click="nextMonth()"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none"
                aria-label="Next month"
            >
                <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M6.22 4.22a.75.75 0 0 1 1.06 0l3.25 3.25a.75.75 0 0 1 0 1.06L7.28 11.78a.75.75 0 0 1-1.06-1.06L9.94 8 6.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        {{-- ── Day-of-week header ───────────────────────────────────── --}}
        <div class="grid grid-cols-7 mb-1">
            <template x-for="abbr in dayAbbr" :key="abbr">
                <div class="flex items-center justify-center {{ $sc['cell'] }}">
                    <span class="text-[11px] font-semibold text-zinc-400 dark:text-zinc-500 uppercase" x-text="abbr"></span>
                </div>
            </template>
        </div>

        {{-- ── Calendar grid ────────────────────────────────────────── --}}
        <div class="grid grid-cols-7">
            <template x-for="day in calendarDays()" :key="day.date">
                <div class="relative">

                    {{-- Full-width range band (in-range days) --}}
                    <div
                        class="absolute inset-y-1 inset-x-0 bg-primary-100 dark:bg-primary-900/30 pointer-events-none"
                        x-show="isInRange(day.date) && day.currentMonth"
                        style="display:none"
                    ></div>

                    {{-- Right-half band for range start --}}
                    <div
                        class="absolute inset-y-1 left-1/2 right-0 bg-primary-100 dark:bg-primary-900/30 pointer-events-none"
                        x-show="showStartBand(day.date) && day.currentMonth"
                        style="display:none"
                    ></div>

                    {{-- Left-half band for range end --}}
                    <div
                        class="absolute inset-y-1 left-0 right-1/2 bg-primary-100 dark:bg-primary-900/30 pointer-events-none"
                        x-show="showEndBand(day.date) && day.currentMonth"
                        style="display:none"
                    ></div>

                    {{-- Day button --}}
                    <button
                        type="button"
                        class="relative z-10 mx-auto flex items-center justify-center rounded-full transition-colors focus:outline-none {{ $sc['cell'] }} {{ $sc['text'] }}"
                        :class="dayClass(day)"
                        @click="day.currentMonth && selectDate(day.date)"
                        @mouseover="mode === 'range' && rangeStart && !rangeEnd && day.currentMonth && (hoverDate = day.date)"
                        @mouseleave="mode === 'range' && !rangeEnd && (hoverDate = null)"
                        :disabled="isDisabled(day.date) || !day.currentMonth"
                        :aria-label="day.date"
                        :aria-pressed="isSelected(day.date)"
                        :aria-current="isToday(day.date) ? 'date' : false"
                    >
                        <span x-text="day.day"></span>
                    </button>

                </div>
            </template>
        </div>

        {{-- ── Today button (optional) ─────────────────────────────── --}}
        @if($withToday)
            <div class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex justify-center">
                <button
                    type="button"
                    @click="goToday()"
                    class="{{ $sc['text'] }} font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors focus:outline-none"
                >
                    Today
                </button>
            </div>
        @endif

    </div>

    {{-- Hidden input for wire:model / form submission --}}
    <input
        type="hidden"
        x-ref="hiddenInput"
        @if($name) name="{{ $name }}" @endif
        {{ $attributes->whereStartsWith(['wire:', 'x-model']) }}
    />
</div>
