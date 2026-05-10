@props([
    'value'           => '',
    'size'            => 160,
    'color'           => '#000000',
    'background'      => '#ffffff',
    'errorCorrection' => 'M',
    'title'           => null,
    'description'     => null,
    'state'           => 'default',
    'copyable'        => false,
    'downloadable'    => false,
])

@php
    $qrParams = [
        'data'    => $value ?: ' ',
        'size'    => "{$size}x{$size}",
        'ecc'     => $errorCorrection,
        'color'   => ltrim($color, '#'),
        'bgcolor' => ltrim($background, '#'),
        'format'  => 'svg',
        'margin'  => 1,
    ];

    $downloadParams        = $qrParams;
    $downloadParams['format'] = 'png';

    $qrUrl       = 'https://api.qrserver.com/v1/create-qr-code/?' . http_build_query($qrParams);
    $downloadUrl = 'https://api.qrserver.com/v1/create-qr-code/?' . http_build_query($downloadParams);
@endphp

<div
    x-data="{ copied: false }"
    {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}
>
    {{-- Card Wrapper --}}
    <div @class([
        'p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 w-full' => $title || $description,
    ])>
        <div
            class="relative mx-auto flex items-center justify-center"
            style="width: {{ $size }}px; height: {{ $size }}px;"
        >
            {{-- Loading overlay --}}
            @if($state === 'loading')
                <div class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/90 dark:bg-gray-800/90 rounded-lg">
                    <svg class="w-8 h-8 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Loading...</span>
                </div>
            @endif

            {{-- Expired overlay --}}
            @if($state === 'expired')
                <div class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/90 dark:bg-gray-800/90 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m13 7-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Expired</span>
                    <button
                        type="button"
                        x-on:click="$dispatch('qr:refresh')"
                        class="mt-1 text-xs text-blue-600 dark:text-blue-500 hover:underline"
                    >
                        Refresh
                    </button>
                </div>
            @endif

            {{-- QR code image --}}
            <img
                src="{{ $qrUrl }}"
                alt="QR Code{{ $value ? ' for ' . $value : '' }}"
                width="{{ $size }}"
                height="{{ $size }}"
                class="rounded"
            />
        </div>

        @if($title || $description)
            <div class="mt-4 text-center">
                @if($title)
                    <h5 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h5>
                @endif
                @if($description)
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                @endif
            </div>
        @endif
    </div>

    {{-- Copy to clipboard --}}
    @if($copyable)
        <div class="mt-4 w-full max-w-sm">
            <div class="relative">
                <label for="qr-value-{{ md5($value) }}" class="sr-only">QR Code Value</label>
                <input
                    id="qr-value-{{ md5($value) }}"
                    type="text"
                    readonly
                    value="{{ $value }}"
                    class="block w-full p-2.5 pr-10 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                <button
                    type="button"
                    x-on:click="navigator.clipboard.writeText('{{ addslashes($value) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="absolute end-2 top-1/2 -translate-y-1/2 p-1.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                >
                    <span x-show="!copied">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2h4a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h4m6 0v3H6V2m6 0a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1M5 5h8m-5 5h5m-8 0h.01M5 14h.01M8 14h5"/>
                        </svg>
                    </span>
                    <span x-show="copied" x-cloak>
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    @endif

    {{-- Download --}}
    @if($downloadable)
        <div class="mt-4">
            <a
                href="{{ $downloadUrl }}"
                download="qrcode.png"
                class="inline-flex items-center gap-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
            >
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
                </svg>
                Download PNG
            </a>
        </div>
    @endif
</div>
