@props([
    'text' => '',
    'size' => 'md',
    'variant' => 'default',
    'style' => 'button',
    'successMessage' => 'Copied!',
    'icon' => 'clipboard-document',
    'successIcon' => 'check',
])

@php
// Size configurations
$sizes = [
    'sm' => [
        'button' => 'px-3 py-1 text-sm',
        'icon' => 'w-8 h-8',
        'iconSize' => 'sm',
    ],
    'md' => [
        'button' => 'px-4 py-2',
        'icon' => 'w-10 h-10',
        'iconSize' => 'md',
    ],
    'lg' => [
        'button' => 'px-6 py-3 text-lg',
        'icon' => 'w-12 h-12',
        'iconSize' => 'lg',
    ],
];

// Variant configurations for button style
$variants = [
    'default' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 dark:bg-primary-600 dark:hover:bg-primary-500',
    'ghost' => 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800',
    'outline' => 'border-1 border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800',
];

// Icon variant - simpler styling
$iconVariants = [
    'default' => 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800',
    'primary' => 'text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-950',
    'ghost' => 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800',
];

$sizeConfig = $sizes[$size] ?? $sizes['md'];

// Build classes based on style
if ($style === 'icon') {
    $classes = 'inline-flex items-center justify-center rounded-full transition-all duration-200 '
        . $sizeConfig['icon'] . ' '
        . ($iconVariants[$variant] ?? $iconVariants['default']);
} else {
    $classes = 'inline-flex items-center justify-center gap-2 rounded-full font-medium transition-all duration-200 '
        . $sizeConfig['button'] . ' '
        . ($variants[$variant] ?? $variants['default']);
}
@endphp

<button
    x-data="{
        copied: false,
        copyText: @js($text),
        async copy() {
            try {
                let textToCopy = this.copyText;
                if (!textToCopy) {
                    const slotText = this.$el.querySelector('[data-copy-text]')?.textContent || this.$el.textContent.trim();
                    textToCopy = slotText;
                }

                // Try modern Clipboard API first
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(textToCopy);
                } else {
                    // Fallback for non-secure contexts (http://)
                    const textArea = document.createElement('textarea');
                    textArea.value = textToCopy;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();

                    try {
                        document.execCommand('copy');
                        textArea.remove();
                    } catch (err) {
                        textArea.remove();
                        throw err;
                    }
                }

                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            } catch (err) {
                console.error('Failed to copy:', err);
                alert('Failed to copy to clipboard. Please copy manually.');
            }
        }
    }"
    @click="copy()"
    type="button"
    {{ $attributes->merge(['class' => $classes]) }}
    :title="copied ? '{{ $successMessage }}' : 'Copy to clipboard'"
>
    @if($style === 'icon')
        {{-- Icon-only style --}}
        <span x-show="!copied" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-75"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-75">
            <x-ui::icon :name="$icon" :size="$sizeConfig['iconSize']" />
        </span>
        <span x-show="copied" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-75"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-75"
            class="text-green-600 dark:text-green-400">
            <x-ui::icon :name="$successIcon" :size="$sizeConfig['iconSize']" />
        </span>
    @else
        {{-- Button style with text --}}
        <span x-show="!copied" x-transition class="inline-flex items-center gap-2">
            <x-ui::icon :name="$icon" :size="$sizeConfig['iconSize']" />
            @if($slot->isEmpty())
                Copy
            @else
                {{ $slot }}
            @endif
        </span>
        <span x-show="copied" x-transition class="inline-flex items-center gap-2 text-green-600 dark:text-green-400">
            <x-ui::icon :name="$successIcon" :size="$sizeConfig['iconSize']" />
            {{ $successMessage }}
        </span>
    @endif
</button>
