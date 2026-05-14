<?php

namespace Phara\UIKit;

class UiKit
{
    public static function styles(): string
    {
        return <<<'HTML'
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
/>
<style>
@keyframes toast-progress {
    from { transform: scaleX(1); }
    to   { transform: scaleX(0); }
}
.ui-code-scroll { scrollbar-width: thin; scrollbar-color: #3d444d transparent; }
.ui-code-scroll::-webkit-scrollbar        { height: 5px; width: 5px; }
.ui-code-scroll::-webkit-scrollbar-track  { background: transparent; }
.ui-code-scroll::-webkit-scrollbar-thumb  { background-color: transparent; border-radius: 9999px; transition: background-color .2s; }
.ui-code-scroll:hover::-webkit-scrollbar-thumb  { background-color: #3d444d; }
.ui-code-scroll::-webkit-scrollbar-thumb:hover  { background-color: #57606a; }
.ui-code-scroll::-webkit-scrollbar-thumb:active { background-color: #6e7681; }
.ui-code-scroll::-webkit-scrollbar-corner { background: transparent; }

.ui-editor [contenteditable]:empty::before {
    content: attr(data-placeholder);
    color: #a1a1aa;
    pointer-events: none;
    position: absolute;
}
.ui-editor [contenteditable] { position: relative; }
.ui-editor-body h1 { font-size: 1.5em; font-weight: 700; line-height: 1.3; margin: 0.6em 0 0.2em; }
.ui-editor-body h2 { font-size: 1.25em; font-weight: 700; line-height: 1.3; margin: 0.6em 0 0.2em; }
.ui-editor-body h3 { font-size: 1.1em; font-weight: 600; line-height: 1.3; margin: 0.6em 0 0.2em; }
.ui-editor-body p  { min-height: 1.4em; margin: 0; }
.ui-editor-body ul { list-style-type: disc;    padding-left: 1.5em; margin: 0.4em 0; }
.ui-editor-body ol { list-style-type: decimal; padding-left: 1.5em; margin: 0.4em 0; }
.ui-editor-body li { margin: 0.1em 0; }
.ui-editor-body blockquote {
    border-left: 3px solid #d1d5db; padding-left: 0.9em;
    margin: 0.5em 0; color: #6b7280; font-style: italic;
}
.dark .ui-editor-body blockquote { border-left-color: #52525b; color: #a1a1aa; }
.ui-editor-body a { color: #3b82f6; text-decoration: underline; cursor: pointer; }
.ui-editor-body code {
    font-family: ui-monospace, monospace; font-size: 0.875em;
    background: #f4f4f5; color: #18181b;
    padding: 0.1em 0.35em; border-radius: 0.25em;
}
.dark .ui-editor-body code { background: #27272a; color: #e4e4e7; }
.ui-editor-body pre {
    background: #18181b; color: #e4e4e7;
    padding: 0.9em 1.1em; border-radius: 0.5em;
    overflow-x: auto; margin: 0.5em 0; font-size: 0.875em;
}
.ui-editor-body pre code { background: transparent; color: inherit; padding: 0; }
.ui-editor-body sub { vertical-align: sub;   font-size: smaller; }
.ui-editor-body sup { vertical-align: super; font-size: smaller; }

@keyframes ui-mq-scroll-h {
    from { transform: translateX(0); }
    to   { transform: translateX(calc(-100% / var(--mq-n, 2))); }
}
@keyframes ui-mq-scroll-v {
    from { transform: translateY(0); }
    to   { transform: translateY(calc(-100% / var(--mq-n, 2))); }
}
.ui-mq-h { animation: ui-mq-scroll-h var(--mq-dur, 30s) linear infinite; width: max-content; }
.ui-mq-v { animation: ui-mq-scroll-v var(--mq-dur, 30s) linear infinite; height: max-content; }
.ui-mq-reverse { animation-direction: reverse; }
.ui-mq-pause:hover .ui-mq-h,
.ui-mq-pause:hover .ui-mq-v { animation-play-state: paused; }
.ui-mq-fade-x {
    mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
}
.ui-mq-fade-y {
    mask-image: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);
    -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);
}

@keyframes ui-progress-stripe {
    from { background-position: 1rem 0; }
    to   { background-position: 0 0; }
}
@keyframes ui-progress-indeterminate {
    0%   { left: -45%; width: 45%; }
    60%  { left: 100%; width: 45%; }
    100% { left: 100%; width: 45%; }
}
.ui-progress-stripe-animated { animation: ui-progress-stripe 0.65s linear infinite; }
.ui-progress-indeterminate {
    position: absolute;
    top: 0; bottom: 0;
    width: 45%;
    border-radius: inherit;
    animation: ui-progress-indeterminate 1.6s ease-in-out infinite;
}

@keyframes ui-skeleton-shimmer {
    0%   { background-position: -200% 0; }
    100% { background-position:  200% 0; }
}
.ui-skeleton-shimmer {
    background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
    background-size: 200% 100%;
    animation: ui-skeleton-shimmer 1.6s ease-in-out infinite;
}
.dark .ui-skeleton-shimmer {
    background: linear-gradient(90deg, #27272a 25%, #3f3f46 50%, #27272a 75%);
    background-size: 200% 100%;
}

@keyframes ui-bar-scale {
    0%, 40%, 100% { transform: scaleY(0.35); }
    20%            { transform: scaleY(1); }
}
.ui-spin-bar { animation: ui-bar-scale 1.1s ease-in-out infinite; transform-origin: center bottom; }
</style>
HTML;
    }

    public static function scripts(): string
    {
        return <<<'HTML'
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
></script>
<script>
document.addEventListener('alpine:init', () => {
    if (Alpine.store('toasts')) return;

    Alpine.store('toasts', {
        items: [],
        defaultDuration: window.__uiToastDuration ?? 5000,

        add({ message = '', type = 'default', duration, title = null } = {}) {
            const id = Date.now() + Math.random();
            const d  = duration !== undefined ? duration : this.defaultDuration;
            this.items.push({ id, message, type, duration: d, title, visible: true });
            if (d > 0) setTimeout(() => this.dismiss(id), d);
        },

        dismiss(id) {
            this.items = this.items.map(t => t.id === id ? { ...t, visible: false } : t);
            setTimeout(() => this.remove(id), 300);
        },

        remove(id) {
            this.items = this.items.filter(t => t.id !== id);
        },
    });

    window.toast = (message, options = {}) =>
        Alpine.store('toasts').add(
            typeof options === 'string' ? { message, type: options } : { message, ...options }
        );
});

window.addEventListener('toast', (e) => Alpine.store('toasts')?.add(e.detail));
</script>
<script>
function autocomplete({ items }) {
    return {
        query: '',
        selected: '',
        open: false,
        activeIndex: 0,
        items,

        init() {},

        get filtered() {
            if (!this.query) return this.items;
            return this.items.filter(i => i.toLowerCase().includes(this.query.toLowerCase()));
        },

        onFocus() {
            this.open = true;
            this.$nextTick(() => this.updatePosition());
        },

        updatePosition() {
            if (!this.open) return;
            const trigger = this.$refs.trigger;
            const menu    = this.$refs.dropdown;
            if (!trigger || !menu) return;
            const rect = trigger.getBoundingClientRect();
            menu.style.top   = (rect.bottom + 4) + 'px';
            menu.style.left  = rect.left + 'px';
            menu.style.width = rect.width + 'px';
        },

        select(index) {
            if (!this.filtered[index]) return;
            this.query       = this.filtered[index];
            this.selected    = this.filtered[index];
            this.open        = false;
            this.activeIndex = 0;
            this.$nextTick(() => {
                const el = this.$refs.valueInput;
                if (el) el.dispatchEvent(new Event('input', { bubbles: true }));
            });
        },

        next() {
            if (!this.open) { this.onFocus(); return; }
            if (this.activeIndex < this.filtered.length - 1) this.activeIndex++;
            this.scrollToActive();
        },

        prev() {
            if (this.activeIndex > 0) this.activeIndex--;
            this.scrollToActive();
        },

        scrollToActive() {
            this.$nextTick(() => {
                this.$refs.dropdown
                    ?.querySelector(`[data-index="${this.activeIndex}"]`)
                    ?.scrollIntoView({ block: 'nearest' });
            });
        },
    };
}
</script>
<script>
function datePicker3({ value, format }) {
    return {
        open: false,
        value: value,
        format: format,

        first: '',
        second: '',
        year: '',

        current: new Date(),

        days: ['Su','Mo','Tu','We','Th','Fr','Sa'],

        init() {
            if (this.value) {
                const d = this.parseISO(this.value);
                this.current = d;
                this.setParts(d);
            }
        },

        updatePosition() {
            if (!this.open) return;
            const trigger  = this.$refs.trigger;
            const calendar = this.$refs.calendar;
            if (!trigger || !calendar) return;
            const rect = trigger.getBoundingClientRect();
            calendar.style.top  = (rect.bottom + 4) + 'px';
            calendar.style.left = rect.left + 'px';
        },

        toggleCalendar() {
            this.open = !this.open;
            if (this.open) this.$nextTick(() => this.updatePosition());
        },

        parseISO(val) {
            const [y, m, d] = val.split('-');
            return new Date(y, m - 1, d, 12);
        },

        formatDate(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        },

        setParts(date) {
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            if (this.format === 'mdy') { this.first = m; this.second = d; }
            else                       { this.first = d; this.second = m; }
            this.year = y;
        },

        updateFromParts() {
            if (!this.first || !this.second || !this.year) return;
            const [d, m] = this.format === 'mdy'
                ? [this.second, this.first]
                : [this.first,  this.second];
            const date = new Date(this.year, m - 1, d, 12);
            if (!isNaN(date)) { this.value = this.formatDate(date); this.current = date; }
        },

        get currentYear() { return this.current.getFullYear(); },
        get month()       { return this.current.getMonth(); },
        get monthName()   { return this.current.toLocaleString('default', { month: 'long' }); },
        get monthDays()   { return new Date(this.currentYear, this.month + 1, 0).getDate(); },
        get blanks()      { return Array(new Date(this.currentYear, this.month, 1).getDay()); },

        prev() { this.current = new Date(this.currentYear, this.month - 1, 1, 12); },
        next() { this.current = new Date(this.currentYear, this.month + 1, 1, 12); },

        pick(day) {
            const d = new Date(this.currentYear, this.month, day, 12);
            this.value = this.formatDate(d);
            this.setParts(d);
            this.open = false;
        },

        isSelected(day) {
            return this.value === this.formatDate(new Date(this.currentYear, this.month, day, 12));
        },
    };
}
</script>
<script>
function timePicker({ value, format, withSeconds, step }) {
    return {
        open: false,
        value: value || '',
        format,
        withSeconds,
        step,

        hours:    '',
        minutes:  '00',
        seconds:  '00',
        meridiem: 'AM',

        init() {
            if (this.value) this.parseValue(this.value);
        },

        parseValue(val) {
            const parts = val.split(':');
            let h        = parseInt(parts[0] || 0, 10);
            const m      = String(Math.min(59, parseInt(parts[1] || 0, 10))).padStart(2, '0');
            const s      = String(Math.min(59, parseInt(parts[2] || 0, 10))).padStart(2, '0');
            if (this.format === '12') {
                this.meridiem = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
            }
            this.hours   = String(h).padStart(2, '0');
            this.minutes = m;
            this.seconds = s;
        },

        updateValue() {
            let h       = parseInt(this.hours, 10) || 0;
            const m     = Math.min(59, Math.max(0, parseInt(this.minutes, 10) || 0));
            const s     = Math.min(59, Math.max(0, parseInt(this.seconds, 10) || 0));
            if (this.format === '12') {
                if (this.meridiem === 'PM' && h !== 12) h += 12;
                if (this.meridiem === 'AM' && h === 12) h = 0;
            }
            h = Math.min(23, Math.max(0, h));
            let result = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
            if (this.withSeconds) result += `:${String(s).padStart(2, '0')}`;
            this.value = result;
        },

        onHoursInput() {
            const h = parseInt(this.hours, 10);
            if (!isNaN(h)) {
                const max = this.format === '12' ? 12 : 23;
                if (h > max) this.hours = String(max).padStart(2, '0');
            }
            if (this.hours.length === 2) {
                this.$nextTick(() => { this.$refs.minutesInput?.focus(); this.$refs.minutesInput?.select(); });
            }
            this.updateValue();
        },

        onMinutesInput() {
            const m = parseInt(this.minutes, 10);
            if (!isNaN(m) && m > 59) this.minutes = '59';
            if (this.minutes.length === 2 && this.withSeconds) {
                this.$nextTick(() => { this.$refs.secondsInput?.focus(); this.$refs.secondsInput?.select(); });
            }
            this.updateValue();
        },

        onSecondsInput() {
            const s = parseInt(this.seconds, 10);
            if (!isNaN(s) && s > 59) this.seconds = '59';
            this.updateValue();
        },

        toggleMeridiem() {
            this.meridiem = this.meridiem === 'AM' ? 'PM' : 'AM';
            this.updateValue();
        },

        updatePosition() {
            if (!this.open) return;
            const trigger = this.$refs.triggerGroup;
            const menu    = this.$refs.dropdown;
            if (!trigger || !menu) return;
            const rect = trigger.getBoundingClientRect();
            menu.style.top  = (rect.bottom + 4) + 'px';
            menu.style.left = rect.left + 'px';
        },

        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => {
                    this.updatePosition();
                    const el = this.$refs.dropdown?.querySelector('[data-selected="true"]');
                    el?.scrollIntoView({ block: 'nearest' });
                });
            }
        },

        get timeOptions() {
            const opts = [];
            for (let i = 0; i < 24 * 60; i += this.step) {
                const h24  = Math.floor(i / 60);
                const m    = i % 60;
                const val  = `${String(h24).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
                let label;
                if (this.format === '12') {
                    const h12 = h24 % 12 || 12;
                    const mer = h24 >= 12 ? 'PM' : 'AM';
                    label = `${String(h12).padStart(2, '0')}:${String(m).padStart(2, '0')} ${mer}`;
                } else {
                    label = val;
                }
                opts.push({ value: val, label });
            }
            return opts;
        },

        selectTime(val) {
            this.value = val;
            this.parseValue(val);
            this.open = false;
        },

        isSelected(val) {
            return this.value.slice(0, 5) === val.slice(0, 5);
        },
    };
}
</script>
HTML;
    }
}
