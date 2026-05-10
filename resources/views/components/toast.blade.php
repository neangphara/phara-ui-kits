@props([
    'position' => 'bottom-right', // bottom-right | bottom-left | bottom-center | top-right | top-left | top-center
    'duration' => 5000,
])

@php
$posClass = match ($position) {
    'bottom-left'   => 'bottom-4 left-4',
    'bottom-center' => 'bottom-4 left-1/2 -translate-x-1/2',
    'top-right'     => 'top-4 right-4',
    'top-left'      => 'top-4 left-4',
    'top-center'    => 'top-4 left-1/2 -translate-x-1/2',
    default         => 'bottom-4 right-4',
};
// top positions stack newest-first (col-reverse); bottom positions stack newest-last (col)
$flexDir = str_starts_with($position, 'top') ? 'flex-col-reverse' : 'flex-col';
@endphp

@if($duration !== 5000)
<script>if (window.__uiToastDuration === undefined) window.__uiToastDuration = {{ $duration }};</script>
@endif

{{-- Container — place once in your layout --}}
<div
    x-data
    class="pointer-events-none fixed z-[9999] flex max-w-sm gap-2 {{ $posClass }} {{ $flexDir }}"
    aria-live="polite"
    aria-atomic="false"
>
    <template x-for="toast in $store.toasts.items" :key="toast.id">
        <div
            x-show="toast.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="pointer-events-auto w-full overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5 dark:bg-zinc-800 dark:ring-white/10"
        >
            <div class="flex items-start gap-3 p-4">

                {{-- Type icon --}}
                <div
                    class="mt-0.5 shrink-0"
                    :class="{
                        'text-green-500': toast.type === 'success',
                        'text-yellow-400': toast.type === 'warning',
                        'text-red-500':   toast.type === 'danger' || toast.type === 'error',
                        'text-blue-500':  !toast.type || toast.type === 'info' || toast.type === 'default',
                    }"
                >
                    <svg x-show="toast.type === 'success'" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" />
                    </svg>
                    <svg x-show="toast.type === 'warning'" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                    </svg>
                    <svg x-show="toast.type === 'danger' || toast.type === 'error'" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" />
                    </svg>
                    <svg x-show="!toast.type || toast.type === 'info' || toast.type === 'default'" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" />
                    </svg>
                </div>

                {{-- Content --}}
                <div class="min-w-0 flex-1">
                    <p
                        x-show="toast.title"
                        x-text="toast.title"
                        class="text-sm font-semibold text-zinc-900 dark:text-white"
                    ></p>
                    <p
                        x-text="toast.message"
                        class="text-sm text-zinc-500 dark:text-zinc-400"
                        :class="{ 'mt-0.5': toast.title }"
                    ></p>
                </div>

                {{-- Close --}}
                <button
                    type="button"
                    @click="$store.toasts.dismiss(toast.id)"
                    class="-mr-0.5 -mt-0.5 shrink-0 rounded-md p-0.5 text-zinc-400 transition-colors hover:text-zinc-600 dark:hover:text-zinc-200 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    aria-label="Dismiss"
                >
                    <svg class="size-4" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M5.28 4.22a.75.75 0 0 0-1.06 1.06L6.94 8l-2.72 2.72a.75.75 0 1 0 1.06 1.06L8 9.06l2.72 2.72a.75.75 0 1 0 1.06-1.06L9.06 8l2.72-2.72a.75.75 0 0 0-1.06-1.06L8 6.94 5.28 4.22Z" />
                    </svg>
                </button>
            </div>

            {{-- Progress bar (auto-dismiss only) --}}
            <div
                x-show="toast.duration > 0"
                class="h-0.5 w-full origin-left"
                :class="{
                    'bg-green-400': toast.type === 'success',
                    'bg-yellow-400': toast.type === 'warning',
                    'bg-red-400':   toast.type === 'danger' || toast.type === 'error',
                    'bg-blue-400':  !toast.type || toast.type === 'info' || toast.type === 'default',
                }"
                :style="`animation: toast-progress ${toast.duration}ms linear forwards`"
            ></div>
        </div>
    </template>
</div>

