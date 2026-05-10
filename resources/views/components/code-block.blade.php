@props([
    'language'        => null,
    'title'           => null,
    'showLineNumbers' => false,
    'showCopyButton'  => true,
])

@php
$langMap = [
    'Blade' => 'php', 'blade' => 'php',
    'PHP'        => 'php',
    'JavaScript' => 'javascript', 'JS' => 'javascript',
    'TypeScript' => 'typescript', 'TS' => 'typescript',
    'CSS'        => 'css',
    'HTML'       => 'html',
    'JSON'       => 'json',
    'Bash'       => 'bash', 'Shell' => 'bash',
    'SQL'        => 'sql',
    'Vue'        => 'html',
    'YAML'       => 'yaml',
    'Markdown'   => 'markdown',
    'Python'     => 'python',
    'Ruby'       => 'ruby',
    'Go'         => 'go',
    'Rust'       => 'rust',
    'XML'        => 'xml',
    'Diff'       => 'diff',
];

$hljsLang    = $language ? ($langMap[$language] ?? strtolower($language)) : '';
$langClass   = $hljsLang ? 'language-' . $hljsLang : '';

// Decoded plain-text version of the code — passed directly into x-data so
// the copy button is fully self-contained (no DOM traversal needed).
$codeContent = html_entity_decode(trim((string) $slot), ENT_QUOTES | ENT_HTML5, 'UTF-8');
@endphp


{{-- ── Shared copy-button x-data — reused in both header and floating variants --}}
@php
$copyXData = "
    copied: false,
    copyText: " . json_encode($codeContent) . ",
    async copy() {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(this.copyText);
            } else {
                const ta = document.createElement('textarea');
                ta.value = this.copyText;
                ta.style.cssText = 'position:fixed;left:-9999px;top:-9999px;opacity:0';
                document.body.appendChild(ta);
                ta.focus(); ta.select();
                try { document.execCommand('copy'); } catch(e) {}
                ta.remove();
            }
            this.copied = true;
            setTimeout(() => this.copied = false, 1500);
        } catch(e) { console.error('Copy failed', e); }
    }
";
@endphp

<div
    {{ $attributes->merge(['class' => 'relative rounded-xl bg-black overflow-hidden border border-[#30363d] font-mono text-sm']) }}
>
    {{-- ── Header ───────────────────────────────────────────────────────────── --}}
    @if($title || $language || $showCopyButton)
        <div class="flex items-center justify-between gap-4 px-4 py-2.5 bg-[#161b22] border-b border-[#30363d]">

            <div class="flex items-center gap-2.5 min-w-0">
                @if($title)
                    <span class="truncate text-xs font-medium font-sans text-[#e6edf3]">{{ $title }}</span>
                @endif
                @if($language)
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold font-sans uppercase tracking-widest bg-[#21262d] text-[#8b949e] border border-[#30363d]">
                        {{ $language }}
                    </span>
                @endif
            </div>

            @if($showCopyButton)
                {{-- Self-contained copy button: x-data on the element itself --}}
                <button
                    x-data="{ {{ $copyXData }} }"
                    @click="copy()"
                    type="button"
                    :title="copied ? 'Copied!' : 'Copy code'"
                    class="shrink-0 inline-flex items-center gap-1.5 px-2 py-1 rounded-md font-sans font-medium text-[10px] transition-colors focus:outline-none text-[#8b949e] hover:text-[#e6edf3] hover:bg-[#21262d]"
                >
                    <span x-show="!copied" class="inline-flex items-center gap-1.5">
                        <svg viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5 shrink-0">
                            <path d="M0 6.75C0 5.784.784 5 1.75 5h1.5a.75.75 0 0 1 0 1.5h-1.5a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-1.5a.75.75 0 0 1 1.5 0v1.5A1.75 1.75 0 0 1 9.25 16h-7.5A1.75 1.75 0 0 1 0 14.25Z"/>
                            <path d="M5 1.75C5 .784 5.784 0 6.75 0h7.5C15.216 0 16 .784 16 1.75v7.5A1.75 1.75 0 0 1 14.25 11h-7.5A1.75 1.75 0 0 1 5 9.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"/>
                        </svg>
                        Copy
                    </span>
                    <span x-show="copied" class="inline-flex items-center gap-1.5 text-green-400" style="display:none">
                        <svg viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5 shrink-0">
                            <path d="M13.78 4.22a.75.75 0 0 1 0 1.06l-7.25 7.25a.75.75 0 0 1-1.06 0L2.22 9.28a.75.75 0 0 1 1.06-1.06L6 10.94l6.72-6.72a.75.75 0 0 1 1.06 0Z"/>
                        </svg>
                        Copied!
                    </span>
                </button>
            @endif
        </div>

    @elseif($showCopyButton)
        {{-- Floating copy button (no header) --}}
        <button
            x-data="{ {{ $copyXData }} }"
            @click="copy()"
            type="button"
            :title="copied ? 'Copied!' : 'Copy code'"
            class="absolute top-2.5 right-2.5 z-10 inline-flex items-center justify-center w-7 h-7 rounded-lg transition-colors focus:outline-none text-[#8b949e] hover:text-[#e6edf3] hover:bg-[#21262d]"
        >
            <span x-show="!copied" class="inline-flex">
                <svg viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5">
                    <path d="M0 6.75C0 5.784.784 5 1.75 5h1.5a.75.75 0 0 1 0 1.5h-1.5a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-1.5a.75.75 0 0 1 1.5 0v1.5A1.75 1.75 0 0 1 9.25 16h-7.5A1.75 1.75 0 0 1 0 14.25Z"/>
                    <path d="M5 1.75C5 .784 5.784 0 6.75 0h7.5C15.216 0 16 .784 16 1.75v7.5A1.75 1.75 0 0 1 14.25 11h-7.5A1.75 1.75 0 0 1 5 9.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"/>
                </svg>
            </span>
            <span x-show="copied" class="inline-flex text-green-400" style="display:none">
                <svg viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5">
                    <path d="M13.78 4.22a.75.75 0 0 1 0 1.06l-7.25 7.25a.75.75 0 0 1-1.06 0L2.22 9.28a.75.75 0 0 1 1.06-1.06L6 10.94l6.72-6.72a.75.75 0 0 1 1.06 0Z"/>
                </svg>
            </span>
        </button>
    @endif

    {{-- ── Code with line numbers ───────────────────────────────────────────── --}}
    @if($showLineNumbers)
        <div
            x-data="{
                init() {
                    const code = this.$refs.code;
                    const raw  = code?.textContent ?? '';
                    const n    = raw.trimEnd().split('\n').length;
                    this.$refs.nums.textContent = Array.from({ length: n }, (_, i) => i + 1).join('\n');
                    if (typeof hljs !== 'undefined' && code) hljs.highlightElement(code);
                }
            }"
            class="flex overflow-x-auto ui-code-scroll"
        >
            <div class="shrink-0 select-none border-r border-[#21262d]" style="background:#0d1117">
                <pre class="px-3 py-4 text-right leading-relaxed" style="margin:0;background:transparent"><code x-ref="nums" style="background:transparent;padding:0;color:#3d444d;font-size:inherit"></code></pre>
            </div>
            <div class="flex-1 min-w-0">
                <pre class="px-4 py-4 leading-relaxed" style="margin:0;background:transparent"><code x-ref="code" class="{{ $langClass }}">{{ $slot }}</code></pre>
            </div>
        </div>

    {{-- ── Plain code block ─────────────────────────────────────────────────── --}}
    @else
        <div class="overflow-x-auto ui-code-scroll">
            <pre class="px-4 py-4 leading-relaxed" style="margin:0;background:transparent"><code
                class="{{ $langClass }}"
                x-init="typeof hljs !== 'undefined' && hljs.highlightElement($el)"
            >{{ $slot }}</code></pre>
        </div>
    @endif

</div>
