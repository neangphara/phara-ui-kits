@props([
    'paginate'       => null,
    'paginationSize' => 'md',
    'showInfo'       => true,
    'onEachSide'     => 1,
    'selectable'     => false,
])

<div {{ $attributes->only('class')->merge(['class' => 'w-full']) }}
    @if($selectable)
    x-data="{
        selected: [],
        _root: null,
        init() { this._root = this.$el; },
        toggle(id) {
            this.selected.includes(id)
                ? (this.selected = this.selected.filter(s => s !== id))
                : this.selected.push(id);
        },
        toggleAll() {
            const ids = [...this._root.querySelectorAll('[data-row-id]')].map(el => el.dataset.rowId);
            this.selected = this.selected.length === ids.length ? [] : ids;
        },
        allSelected() {
            const total = this._root.querySelectorAll('[data-row-id]').length;
            return total > 0 && this.selected.length === total;
        },
        someSelected() {
            const total = this._root.querySelectorAll('[data-row-id]').length;
            return this.selected.length > 0 && this.selected.length < total;
        }
    }"
    @endif
>
    @if($selectable)
        <div x-show="selected.length > 0"
             x-collapse
             class="mb-2 flex items-center justify-between px-4 py-2 rounded-lg
                    bg-primary-50 dark:bg-primary-900/20
                    border border-primary-200 dark:border-primary-800">
            <span class="text-sm font-medium text-primary-700 dark:text-primary-300"
                  x-text="`${selected.length} item${selected.length === 1 ? '' : 's'} selected`">
            </span>
            <button type="button"
                    @click="selected = []"
                    class="text-xs text-primary-600 dark:text-primary-400
                           hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
                Clear
            </button>
        </div>
    @endif

    <div class="w-full overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="w-full text-sm">
            {{ $slot }}
        </table>
    </div>

    @if($paginate)
        <div class="mt-4">
            <x-ui::pagination
                :paginator="$paginate"
                :size="$paginationSize"
                :show-info="$showInfo"
                :on-each-side="$onEachSide"
            />
        </div>
    @endif
</div>
