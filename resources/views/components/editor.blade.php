@props([
    'name'        => null,
    'value'       => null,
    'placeholder' => 'Write something...',
    'minHeight'   => '14rem',
])

@php
$isDisabled = $attributes->has('disabled');
$hasError   = ($name && isset($errors)) ? $errors->has($name) : false;

$borderColor = $hasError
    ? 'border-red-400 dark:border-red-500'
    : 'border-zinc-200 dark:border-zinc-700';

// Toolbar button classes (resolved in Alpine via activeBtn / inactiveBtn)
$btn = 'inline-flex items-center justify-center w-8 h-8 rounded-md text-sm transition-colors focus:outline-none select-none';
@endphp


<div
    x-data="{
        showLink: false,
        linkUrl: '',
        copyDone: false,

        s: {
            bold: false, italic: false, underline: false, strike: false,
            sub: false, sup: false, highlight: false,
            block: 'p', align: 'left',
        },

        exec(cmd, val = null) {
            this.$refs.editor.focus();
            document.execCommand(cmd, false, val);
            this.updateStates();
            this.sync();
        },

        format(tag) {
            this.$refs.editor.focus();
            document.execCommand('formatBlock', false, tag);
            this.updateStates();
            this.sync();
        },

        align(dir) {
            const cmds = { left: 'justifyLeft', center: 'justifyCenter', right: 'justifyRight', full: 'justifyFull' };
            this.exec(cmds[dir]);
            this.s.align = dir;
        },

        toggleHighlight() {
            const current = document.queryCommandValue('hiliteColor') || document.queryCommandValue('backColor');
            const isOn = current === 'rgb(254, 240, 138)' || current === '#fef08a';
            const color = isOn ? 'transparent' : '#fef08a';
            if (!document.execCommand('hiliteColor', false, color)) {
                document.execCommand('backColor', false, color);
            }
            this.s.highlight = !isOn;
            this.sync();
        },

        toggleCode() {
            const sel = window.getSelection();
            if (!sel || sel.isCollapsed) return;
            const text = sel.toString();
            this.exec('insertHTML', `<code>${text.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</code>`);
        },

        openLink() {
            const sel = window.getSelection();
            if (!sel) return;
            /* Unlink if cursor is inside an anchor */
            let node = sel.anchorNode;
            while (node && node !== this.$refs.editor) {
                if (node.nodeName === 'A') { this.exec('unlink'); return; }
                node = node.parentNode;
            }
            this.linkUrl  = '';
            this.showLink = true;
            this.$nextTick(() => this.$refs.linkInput?.focus());
        },

        insertLink() {
            if (!this.linkUrl.trim()) { this.showLink = false; return; }
            let url = this.linkUrl.trim();
            if (!/^(https?|mailto|tel):/.test(url)) url = 'https://' + url;
            this.exec('createLink', url);
            this.$refs.editor.querySelectorAll('a[href]').forEach(a => a.setAttribute('target', '_blank'));
            this.showLink = false;
            this.linkUrl  = '';
        },

        copyAll() {
            const sel  = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(this.$refs.editor);
            sel.removeAllRanges();
            sel.addRange(range);
            document.execCommand('copy');
            sel.removeAllRanges();
            this.copyDone = true;
            setTimeout(() => this.copyDone = false, 1500);
        },

        pasteClean() {
            this.$refs.editor.focus();
            navigator.clipboard.readText()
                .then(text => { document.execCommand('insertText', false, text); this.sync(); })
                .catch(() => {
                    const t = window.prompt('Paste your text here:');
                    if (t) { document.execCommand('insertText', false, t); this.sync(); }
                });
        },

        updateStates() {
            try {
                this.s.bold      = document.queryCommandState('bold');
                this.s.italic    = document.queryCommandState('italic');
                this.s.underline = document.queryCommandState('underline');
                this.s.strike    = document.queryCommandState('strikeThrough');
                this.s.sub       = document.queryCommandState('subscript');
                this.s.sup       = document.queryCommandState('superscript');
                const blk        = document.queryCommandValue('formatBlock').toLowerCase();
                this.s.block     = blk || 'p';
                this.s.align     = document.queryCommandState('justifyCenter') ? 'center'
                                 : document.queryCommandState('justifyRight')  ? 'right'
                                 : document.queryCommandState('justifyFull')   ? 'full'
                                 : 'left';
            } catch(e) {}
        },

        sync() {
            const h = this.$refs.hiddenInput;
            if (!h) return;
            h.value = this.$refs.editor.innerHTML;
            h.dispatchEvent(new Event('input',  { bubbles: true }));
            h.dispatchEvent(new Event('change', { bubbles: true }));
        },

        init() {
            @if($value) this.$refs.editor.innerHTML = @js($value); @endif
            this.$refs.editor.addEventListener('input', () => this.sync());
            document.addEventListener('selectionchange', () => {
                if (this.$refs.editor?.contains(document.activeElement) || document.activeElement === this.$refs.editor) {
                    this.updateStates();
                }
            });
        }
    }"
    class="ui-editor rounded-xl border {{ $borderColor }} bg-white dark:bg-zinc-900 overflow-hidden {{ $isDisabled ? 'opacity-60 pointer-events-none' : '' }}"
>
    {{-- ── Toolbar ──────────────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-0.5 px-2 py-1.5 border-b {{ $borderColor }} bg-zinc-50 dark:bg-zinc-800/60">

        @php
        $sepClass  = 'w-px h-5 bg-zinc-200 dark:bg-zinc-600 mx-0.5 shrink-0';
        $baseBtn   = $btn . ' text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 hover:text-zinc-900 dark:hover:text-zinc-100';
        $onBtn     = $btn . ' bg-zinc-200 dark:bg-zinc-600 text-zinc-900 dark:text-zinc-100';
        @endphp

        {{-- Block format ─────────────────────────────────────────────────── --}}
        <select
            :value="s.block"
            @change="format($event.target.value)"
            class="h-8 rounded-md border-0 bg-transparent text-xs font-medium text-zinc-700 dark:text-zinc-200 focus:ring-0 focus:outline-none cursor-pointer pr-6 pl-1 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors"
        >
            <option value="p">Paragraph</option>
            <option value="h1">Heading 1</option>
            <option value="h2">Heading 2</option>
            <option value="h3">Heading 3</option>
        </select>

        <span class="{{ $sepClass }}"></span>

        {{-- Inline format ────────────────────────────────────────────────── --}}
        <button type="button" title="Bold (Ctrl+B)"
            :class="s.bold ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="exec('bold')">
            <strong class="font-extrabold text-[13px]">B</strong>
        </button>

        <button type="button" title="Italic (Ctrl+I)"
            :class="s.italic ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="exec('italic')">
            <em class="font-semibold text-[13px] not-italic" style="font-style:italic">I</em>
        </button>

        <button type="button" title="Underline (Ctrl+U)"
            :class="s.underline ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="exec('underline')">
            <span class="text-[13px] font-semibold underline">U</span>
        </button>

        <button type="button" title="Strikethrough"
            :class="s.strike ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="exec('strikeThrough')">
            <span class="text-[13px] font-semibold line-through">S</span>
        </button>

        <span class="{{ $sepClass }}"></span>

        {{-- Subscript / Superscript ──────────────────────────────────────── --}}
        <button type="button" title="Subscript"
            :class="s.sub ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="exec('subscript')">
            <span class="text-[11px] font-medium leading-none">X<sub>2</sub></span>
        </button>

        <button type="button" title="Superscript"
            :class="s.sup ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="exec('superscript')">
            <span class="text-[11px] font-medium leading-none">X<sup>2</sup></span>
        </button>

        <span class="{{ $sepClass }}"></span>

        {{-- Highlight ────────────────────────────────────────────────────── --}}
        <button type="button" title="Highlight"
            :class="s.highlight ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="toggleHighlight()">
            <span class="text-[12px] font-bold px-0.5 rounded-sm leading-none" style="background:#fef08a;color:#1c1917">A</span>
        </button>

        {{-- Inline code ──────────────────────────────────────────────────── --}}
        <button type="button" title="Inline Code"
            class="{{ $baseBtn }}"
            @mousedown.prevent="toggleCode()">
            <x-ui::icon name="code-bracket" class="w-3.5 h-3.5" />
        </button>

        {{-- Link ────────────────────────────────────────────────────────── --}}
        <button type="button" title="Insert / Remove Link"
            class="{{ $baseBtn }}"
            @mousedown.prevent="openLink()">
            <x-ui::icon name="link" class="w-3.5 h-3.5" />
        </button>

        <span class="{{ $sepClass }}"></span>

        {{-- Alignment ────────────────────────────────────────────────────── --}}
        <button type="button" title="Align Left"
            :class="s.align === 'left' ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="align('left')">
            <svg viewBox="0 0 14 14" fill="currentColor" class="w-3.5 h-3.5">
                <rect x="0" y="0"  width="14" height="2" rx="1"/>
                <rect x="0" y="4"  width="9"  height="2" rx="1"/>
                <rect x="0" y="8"  width="14" height="2" rx="1"/>
                <rect x="0" y="12" width="7"  height="2" rx="1"/>
            </svg>
        </button>

        <button type="button" title="Align Center"
            :class="s.align === 'center' ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="align('center')">
            <svg viewBox="0 0 14 14" fill="currentColor" class="w-3.5 h-3.5">
                <rect x="0" y="0"  width="14" height="2" rx="1"/>
                <rect x="2" y="4"  width="10" height="2" rx="1"/>
                <rect x="0" y="8"  width="14" height="2" rx="1"/>
                <rect x="3" y="12" width="8"  height="2" rx="1"/>
            </svg>
        </button>

        <button type="button" title="Align Right"
            :class="s.align === 'right' ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="align('right')">
            <svg viewBox="0 0 14 14" fill="currentColor" class="w-3.5 h-3.5">
                <rect x="0" y="0"  width="14" height="2" rx="1"/>
                <rect x="5" y="4"  width="9"  height="2" rx="1"/>
                <rect x="0" y="8"  width="14" height="2" rx="1"/>
                <rect x="7" y="12" width="7"  height="2" rx="1"/>
            </svg>
        </button>

        <button type="button" title="Justify"
            :class="s.align === 'full' ? '{{ $onBtn }}' : '{{ $baseBtn }}'"
            @mousedown.prevent="align('full')">
            <svg viewBox="0 0 14 14" fill="currentColor" class="w-3.5 h-3.5">
                <rect x="0" y="0"  width="14" height="2" rx="1"/>
                <rect x="0" y="4"  width="14" height="2" rx="1"/>
                <rect x="0" y="8"  width="14" height="2" rx="1"/>
                <rect x="0" y="12" width="14" height="2" rx="1"/>
            </svg>
        </button>

        <span class="{{ $sepClass }}"></span>

        {{-- Lists & Blockquote ───────────────────────────────────────────── --}}
        <button type="button" title="Bullet List"
            class="{{ $baseBtn }}"
            @mousedown.prevent="exec('insertUnorderedList')">
            <x-ui::icon name="list-bullet" class="w-3.5 h-3.5" />
        </button>

        <button type="button" title="Ordered List"
            class="{{ $baseBtn }}"
            @mousedown.prevent="exec('insertOrderedList')">
            {{-- numbered list SVG --}}
            <svg viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5">
                <path d="M3 3.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1H4v3h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H3V3.5z"/>
                <path d="M3 9.5a.5.5 0 0 1 .5-.5h1.75a.75.75 0 0 1 .75.75V11a.5.5 0 0 1-.5.5H4v.5h1.5a.5.5 0 0 1 0 1H3.5a.5.5 0 0 1-.5-.5V12a.5.5 0 0 1 .5-.5H4.5V11H3.5a.5.5 0 0 1-.5-.5z"/>
                <path d="M7 4h7a.5.5 0 0 0 0-1H7a.5.5 0 0 0 0 1zm0 4h7a.5.5 0 0 0 0-1H7a.5.5 0 0 0 0 1zm0 4h7a.5.5 0 0 0 0-1H7a.5.5 0 0 0 0 1z"/>
            </svg>
        </button>

        <button type="button" title="Blockquote"
            class="{{ $baseBtn }}"
            @mousedown.prevent="format('blockquote')">
            <svg viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5">
                <path d="M2.5 4a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H3.5v.5a1 1 0 0 0 1 1H5a.5.5 0 0 1 0 1h-.5a2 2 0 0 1-2-2V4zm6 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5H9.5v.5a1 1 0 0 0 1 1H11a.5.5 0 0 1 0 1h-.5a2 2 0 0 1-2-2V4z"/>
            </svg>
        </button>

        <span class="{{ $sepClass }}"></span>

        {{-- Undo / Redo ──────────────────────────────────────────────────── --}}
        <button type="button" title="Undo (Ctrl+Z)"
            class="{{ $baseBtn }}"
            @mousedown.prevent="exec('undo')">
            <x-ui::icon name="arrow-uturn-left" class="w-3.5 h-3.5" />
        </button>

        <button type="button" title="Redo (Ctrl+Y)"
            class="{{ $baseBtn }}"
            @mousedown.prevent="exec('redo')">
            <x-ui::icon name="arrow-uturn-right" class="w-3.5 h-3.5" />
        </button>

        <span class="{{ $sepClass }}"></span>

        {{-- Copy All / Paste Clean ───────────────────────────────────────── --}}
        <button type="button"
            :title="copyDone ? 'Copied!' : 'Copy All'"
            class="{{ $baseBtn }}"
            @mousedown.prevent="copyAll()">
            <x-ui::icon x-show="!copyDone" name="clipboard-document"       class="w-3.5 h-3.5" />
            <x-ui::icon x-show="copyDone"  name="clipboard-document-check" class="w-3.5 h-3.5 text-green-500" />
        </button>

        <button type="button" title="Paste Without Formatting"
            class="{{ $baseBtn }}"
            @mousedown.prevent="pasteClean()">
            <span class="relative inline-flex">
                <x-ui::icon name="clipboard" class="w-3.5 h-3.5" />
                <span class="absolute -bottom-0.5 -right-1 text-[8px] font-bold leading-none">T</span>
            </span>
        </button>

    </div>

    {{-- ── Link input bar ───────────────────────────────────────────────────── --}}
    <div
        x-show="showLink"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="flex items-center gap-2 px-3 py-2 border-b {{ $borderColor }} bg-sky-50 dark:bg-sky-950/30"
        style="display:none"
        @keydown.escape.window="showLink = false"
    >
        <x-ui::icon name="link" class="w-3.5 h-3.5 text-sky-500 shrink-0" />
        <input
            x-ref="linkInput"
            x-model="linkUrl"
            type="url"
            placeholder="https://example.com"
            @keydown.enter.prevent="insertLink()"
            @keydown.escape="showLink = false"
            class="flex-1 text-sm bg-transparent border-0 outline-none text-zinc-800 dark:text-zinc-100 placeholder-zinc-400"
        />
        <button type="button" @click="insertLink()"
            class="text-xs font-medium text-sky-600 dark:text-sky-400 hover:text-sky-700 dark:hover:text-sky-300 transition-colors px-2 py-0.5 rounded hover:bg-sky-100 dark:hover:bg-sky-900/40">
            Add
        </button>
        <button type="button" @click="showLink = false"
            class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors">
            Cancel
        </button>
    </div>

    {{-- ── Editor area ──────────────────────────────────────────────────────── --}}
    <div
        x-ref="editor"
        contenteditable="{{ $isDisabled ? 'false' : 'true' }}"
        data-placeholder="{{ $placeholder }}"
        class="ui-editor-body w-full px-4 py-3 text-sm text-zinc-800 dark:text-zinc-100 focus:outline-none overflow-y-auto"
        style="min-height: {{ $minHeight }}"
        @focus="updateStates()"
        @keydown.tab.prevent="exec('insertHTML', '&nbsp;&nbsp;&nbsp;&nbsp;')"
    ></div>

    {{-- ── Footer: word count ───────────────────────────────────────────────── --}}
    <div class="flex justify-end px-3 py-1.5 border-t {{ $borderColor }} bg-zinc-50 dark:bg-zinc-800/40">
        <span
            class="text-xs text-zinc-400 dark:text-zinc-500 tabular-nums"
            x-text="(() => {
                const text = $refs.editor?.innerText?.trim() ?? '';
                const words = text ? text.split(/\s+/).length : 0;
                return words + (words === 1 ? ' word' : ' words');
            })()"
        ></span>
    </div>

    {{-- ── Hidden input for wire:model / form submission ────────────────────── --}}
    <input
        type="hidden"
        x-ref="hiddenInput"
        @if($name) name="{{ $name }}" @endif
        {{ $attributes->whereStartsWith(['wire:', 'x-model']) }}
    />

    {{-- Validation error ──────────────────────────────────────────────────── --}}
    @if($hasError && $name)
        <p class="px-3 pb-2 text-sm text-red-600 dark:text-red-400">{{ $errors->first($name) }}</p>
    @endif
</div>
