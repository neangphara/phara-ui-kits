@props([
    'name'        => 'otp',
    'label'       => null,
    'length'      => 6,
    'size'        => 'md',
    'type'        => 'numeric',   // numeric | alphanumeric
    'placeholder' => '○',
    'mask'        => false,       // show bullets (password input)
    'separator'   => false,       // dash at midpoint for even lengths
    'autoSubmit'  => false,       // submit form when all digits filled
])

@php
$sizeMap = [
    'sm' => ['box' => 'w-8  h-8  text-sm   rounded-lg',  'gap' => 'gap-1.5', 'text' => 'text-sm'],
    'md' => ['box' => 'w-10 h-10 text-base  rounded-xl',  'gap' => 'gap-2',   'text' => 'text-sm'],
    'lg' => ['box' => 'w-12 h-12 text-lg   rounded-xl',   'gap' => 'gap-2.5', 'text' => 'text-base'],
];
$sc = $sizeMap[$size] ?? $sizeMap['md'];

$isDisabled = $attributes->has('disabled');
$hasError   = ($name && isset($errors)) ? $errors->has($name) : false;

// Border & focus ring switch on error
$borderClass = $hasError
    ? 'border-red-400 dark:border-red-500'
    : 'border-zinc-200 dark:border-zinc-700';

$focusClass = $hasError
    ? 'focus:border-red-500 dark:focus:border-red-400 focus:ring-2 focus:ring-red-500/20'
    : 'focus:border-primary-500 dark:focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20';

$disabledClass = $isDisabled
    ? 'opacity-50 cursor-not-allowed bg-zinc-50 dark:bg-zinc-800/60'
    : '';

$digitClass = trim(implode(' ', [
    'otp-digit block font-mono font-semibold text-center caret-transparent outline-none',
    'bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100',
    'border-2 transition-all duration-150',
    'placeholder-zinc-300 dark:placeholder-zinc-600',
    $sc['box'],
    $borderClass,
    $focusClass,
    $disabledClass,
]));

$midpoint     = (int) floor($length / 2);
$showSeparator = $separator && $length % 2 === 0 && $length >= 4;
@endphp

<div
    x-data="{
        digits: Array({{ $length }}).fill(''),
        inputs: [],

        init() {
            this.inputs = Array.from(this.$el.querySelectorAll('.otp-digit'));
        },

        get fullValue() {
            return this.digits.join('');
        },

        get isComplete() {
            return this.fullValue.length === {{ $length }};
        },

        filter(str) {
            return '{{ $type }}' === 'numeric'
                ? str.replace(/[^0-9]/g, '')
                : str.replace(/[^a-zA-Z0-9]/g, '');
        },

        focusAt(index) {
            const el = this.inputs[index];
            if (!el) return;
            el.focus();
            this.$nextTick(() => el.select());
        },

        handleInput(index, event) {
            const raw   = event.target.value;
            const clean = this.filter(raw);
            const char  = clean.slice(-1);

            this.digits[index] = char;
            event.target.value = char;

            if (char && index < {{ $length }} - 1) {
                this.focusAt(index + 1);
            }

            this.sync();
        },

        handleKeydown(index, event) {
            if (event.key === 'Backspace') {
                if (this.digits[index]) {
                    this.digits[index]   = '';
                    event.target.value   = '';
                } else if (index > 0) {
                    this.digits[index - 1]       = '';
                    this.inputs[index - 1].value = '';
                    this.focusAt(index - 1);
                }
                this.sync();
                return;
            }

            if (event.key === 'Delete') {
                this.digits[index] = '';
                event.target.value = '';
                this.sync();
                return;
            }

            if (event.key === 'ArrowLeft')  { event.preventDefault(); this.focusAt(index - 1); return; }
            if (event.key === 'ArrowRight') { event.preventDefault(); this.focusAt(index + 1); return; }
            if (event.key === 'Home')       { event.preventDefault(); this.focusAt(0); return; }
            if (event.key === 'End')        { event.preventDefault(); this.focusAt({{ $length }} - 1); return; }

            {{-- Block non-allowed characters on keydown for numeric mode --}}
            if ('{{ $type }}' === 'numeric' && event.key.length === 1 && !/[0-9]/.test(event.key)) {
                event.preventDefault();
            }
        },

        handlePaste(event) {
            event.preventDefault();
            const text  = (event.clipboardData || window.clipboardData).getData('text');
            const clean = this.filter(text).split('').slice(0, {{ $length }});

            clean.forEach((c, i) => {
                this.digits[i] = c;
                if (this.inputs[i]) this.inputs[i].value = c;
            });

            this.focusAt(Math.min(clean.length, {{ $length }} - 1));
            this.sync();
        },

        sync() {
            const el = this.$refs.hiddenInput;
            if (!el) return;
            el.value = this.fullValue;
            el.dispatchEvent(new Event('input',  { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));

            @if($autoSubmit)
            if (this.isComplete) {
                this.$nextTick(() => this.$el.closest('form')?.requestSubmit());
            }
            @endif
        }
    }"
    class="space-y-2 w-fit"
>
    {{-- Label --}}
    @if($label)
        <label class="{{ $sc['text'] }} font-medium text-zinc-700 dark:text-zinc-300">
            {{ $label }}
        </label>
    @endif

    {{-- Digit boxes --}}
    <div class="flex items-center {{ $sc['gap'] }}">
        @for($i = 0; $i < $length; $i++)

            @if($showSeparator && $i === $midpoint)
                <span class="text-zinc-300 dark:text-zinc-600 select-none px-0.5" aria-hidden="true">—</span>
            @endif

            <input
                type="{{ $mask ? 'password' : 'text' }}"
                maxlength="1"
                class="{{ $digitClass }}"
                placeholder="{{ $placeholder }}"
                inputmode="{{ $type === 'numeric' ? 'numeric' : 'text' }}"
                @if($i === 0) autocomplete="one-time-code" @else autocomplete="off" @endif
                @if($isDisabled) disabled @endif
                @input="handleInput({{ $i }}, $event)"
                @keydown="handleKeydown({{ $i }}, $event)"
                @paste.prevent="handlePaste($event)"
                @focus="$el.select()"
                @click="$el.select()"
                aria-label="Digit {{ $i + 1 }} of {{ $length }}"
            />

        @endfor
    </div>

    {{-- Hidden input: holds the combined value for wire:model / form name --}}
    <input
        type="text"
        x-ref="hiddenInput"
        :value="fullValue"
        @if($name) name="{{ $name }}" @endif
        {{ $attributes->whereStartsWith(['wire:', 'x-model']) }}
        class="sr-only"
        tabindex="-1"
        aria-hidden="true"
        readonly
    />

    {{-- Validation error --}}
    @if($hasError && $name)
        <p class="{{ $sc['text'] }} text-red-600 dark:text-red-400">{{ $errors->first($name) }}</p>
    @endif
</div>
