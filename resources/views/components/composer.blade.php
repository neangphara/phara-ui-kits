@props([
    'name'        => null,
    'placeholder' => 'Message…',
    'maxLength'   => null,
    'maxRows'     => 8,
    'sendOnEnter' => true,
    'loading'     => false,
    'value'       => '',
])

@php
$isDisabled = $attributes->has('disabled') || $loading;

$borderColor = 'border-zinc-200 dark:border-zinc-700';
$focusRing   = 'focus-within:border-zinc-400 dark:focus-within:border-zinc-500 focus-within:ring-2 focus-within:ring-zinc-900/5 dark:focus-within:ring-zinc-100/5';
@endphp

<div
    x-data="{
        value: @js($value),
        focused: false,

        get isEmpty() { return !this.value.trim(); },
        get charCount() { return this.value.length; },
        get isOverLimit() {
            return {{ $maxLength ? 'true' : 'false' }} && this.value.length > {{ $maxLength ?? 0 }};
        },

        autoResize() {
            const ta   = this.$refs.textarea;
            ta.style.height = 'auto';
            const lh   = parseFloat(getComputedStyle(ta).lineHeight) || 20;
            const maxH = lh * {{ $maxRows }};
            ta.style.height      = Math.min(ta.scrollHeight, maxH) + 'px';
            ta.style.overflowY   = ta.scrollHeight > maxH ? 'auto' : 'hidden';
        },

        handleKeydown(event) {
            const meta = event.metaKey || event.ctrlKey;
            if (meta && event.key === 'Enter') {
                event.preventDefault(); this.send(); return;
            }
            if ({{ $sendOnEnter ? 'true' : 'false' }} && event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault(); this.send();
            }
        },

        send() {
            if (this.isEmpty || this.isOverLimit || {{ $loading ? 'true' : 'false' }}) return;

            const msg = this.value.trim();

            /* Sync to hidden input so wire:model receives the value before the event */
            const h = this.$refs.hiddenInput;
            if (h) {
                h.value = msg;
                h.dispatchEvent(new Event('input',  { bubbles: true }));
                h.dispatchEvent(new Event('change', { bubbles: true }));
            }

            /* Dispatch global event — listeners can read detail.message */
            this.$nextTick(() => {
                window.dispatchEvent(new CustomEvent('composer-send', {
                    detail: { message: msg, name: '{{ $name ?? '' }}' },
                    bubbles: true,
                }));

                /* Clear */
                this.value = '';
                if (h) { h.value = ''; h.dispatchEvent(new Event('input', { bubbles: true })); }
                this.$refs.textarea.style.height = 'auto';
                this.$refs.textarea.focus();
            });
        },

        init() {
            this.$nextTick(() => this.autoResize());
        }
    }"
    {{ $attributes->except(['wire:model', 'wire:model.live', 'wire:model.lazy', 'disabled', 'x-model']) }}
    class="rounded-2xl border {{ $borderColor }} {{ $focusRing }} bg-white dark:bg-zinc-900 transition-all duration-150 overflow-hidden {{ $isDisabled ? 'opacity-60 pointer-events-none' : '' }}"
>
    {{-- ── Prepend slot (file previews, reply indicator, etc.) ──────────────── --}}
    @if(isset($prepend) && $prepend->isNotEmpty())
        <div class="px-4 pt-3 pb-2 border-b border-zinc-100 dark:border-zinc-800">
            {{ $prepend }}
        </div>
    @endif

    {{-- ── Textarea ────────────────────────────────────────────────────────── --}}
    <textarea
        x-ref="textarea"
        x-model="value"
        @input="autoResize()"
        @keydown="handleKeydown($event)"
        @focus="focused = true"
        @blur="focused = false"
        rows="1"
        placeholder="{{ $placeholder }}"
        @if($name) name="{{ $name }}" @endif
        @if($isDisabled) disabled @endif
        class="block w-full resize-none bg-transparent px-4 pt-4 pb-2 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none overflow-hidden leading-relaxed"
        style="min-height: 2.75rem; overflow-y: hidden;"
    ></textarea>

    {{-- ── Toolbar ─────────────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 px-3 pb-3 pt-1">

        {{-- Left: action buttons slot --}}
        <div class="flex items-center gap-0.5 flex-1">
            @if(isset($actions) && $actions->isNotEmpty())
                {{ $actions }}
            @endif
        </div>

        {{-- Character count --}}
        @if($maxLength)
            <span
                class="text-xs tabular-nums transition-colors shrink-0"
                :class="isOverLimit
                    ? 'text-red-500 font-semibold'
                    : charCount > {{ (int)($maxLength * 0.85) }}
                        ? 'text-yellow-500'
                        : 'text-zinc-400 dark:text-zinc-500'"
            >
                <span x-text="charCount"></span>/{{ $maxLength }}
            </span>
        @endif

        {{-- Send button --}}
        <button
            type="button"
            @click="send()"
            :disabled="isEmpty || isOverLimit || {{ $loading ? 'true' : 'false' }}"
            :class="isEmpty || isOverLimit
                ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400 dark:text-zinc-500 cursor-not-allowed'
                : 'bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 cursor-pointer shadow-sm'"
            class="flex shrink-0 w-8 h-8 items-center justify-center rounded-xl transition-all duration-150 focus:outline-none"
            title="Send ({{ $sendOnEnter ? 'Enter' : 'Cmd+Enter' }})"
        >
            @if($loading)
                {{-- Spinner --}}
                <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z"/>
                </svg>
            @else
                <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd" d="M8 14a.75.75 0 0 0 .75-.75V4.56l1.47 1.47a.75.75 0 1 0 1.06-1.06L8.53 2.22a.75.75 0 0 0-1.06 0L4.72 4.97a.75.75 0 0 0 1.06 1.06L7.25 4.56v8.69c0 .414.336.75.75.75Z" clip-rule="evenodd"/>
                </svg>
            @endif
        </button>

    </div>

    {{-- ── Hidden input: wire:model / form name ────────────────────────────── --}}
    <input
        type="hidden"
        x-ref="hiddenInput"
        @if($name) name="{{ $name }}_hidden" @endif
        {{ $attributes->whereStartsWith(['wire:', 'x-model']) }}
    />
</div>
