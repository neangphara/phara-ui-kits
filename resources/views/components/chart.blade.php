@props([
    'type'        => 'line',    // line | area | bar | bar-horizontal | doughnut | pie
    'labels'      => [],        // X-axis labels
    'datasets'    => [],        // [['label' => '…', 'data' => […], 'color' => '#…'?], …]
    'height'      => '300px',
    'aspectRatio' => null,      // e.g. '16/9' — overrides height
    'showGrid'    => true,
    'showLegend'  => true,
    'smooth'      => true,      // Bezier tension for line/area
    'stacked'     => false,
    'yMin'        => null,
    'yMax'        => null,
    'yPrefix'     => null,      // e.g. '$'
    'ySuffix'     => null,      // e.g. '%'
    'colors'      => null,      // Override colour palette (array of hex)
])

@php
// ── Normalise type ────────────────────────────────────────────────────────────
$isArea       = $type === 'area';
$isHorizontal = $type === 'bar-horizontal';
$isDonut      = in_array($type, ['doughnut', 'pie']);
$chartJsType  = match ($type) { 'area' => 'line', 'bar-horizontal' => 'bar', default => $type };

// ── Default palette ───────────────────────────────────────────────────────────
$palette = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316','#84cc16'];
$resolvedColors = $colors ?? $palette;

// ── Legend items (server-rendered for hydration) ───────────────────────────────
$legendItems = [];
if ($showLegend) {
    if ($isDonut) {
        foreach ($labels as $i => $lbl) {
            $legendItems[] = ['label' => $lbl, 'color' => $resolvedColors[$i % count($resolvedColors)]];
        }
    } elseif (count($datasets) > 1) {
        foreach ($datasets as $i => $ds) {
            $legendItems[] = ['label' => $ds['label'] ?? '', 'color' => $ds['color'] ?? $resolvedColors[$i % count($resolvedColors)]];
        }
    }
}

// ── Container sizing ──────────────────────────────────────────────────────────
$containerStyle = $aspectRatio ? "aspect-ratio:{$aspectRatio}" : "height:{$height}";
@endphp

<div
    x-data="{
        chart: null,

        /* ── Config ──────────────────────────────────────────────── */
        type:         '{{ $chartJsType }}',
        isArea:       {{ $isArea       ? 'true' : 'false' }},
        isHorizontal: {{ $isHorizontal ? 'true' : 'false' }},
        isDonut:      {{ $isDonut      ? 'true' : 'false' }},
        smooth:       {{ $smooth       ? 'true' : 'false' }},
        stacked:      {{ $stacked      ? 'true' : 'false' }},
        showGrid:     {{ $showGrid     ? 'true' : 'false' }},
        yMin:         @js($yMin),
        yMax:         @js($yMax),
        yPrefix:      @js($yPrefix),
        ySuffix:      @js($ySuffix),
        rawLabels:    @js($labels),
        rawDatasets:  @js($datasets),
        palette:      @js($resolvedColors),

        /* ── Theme ───────────────────────────────────────────────── */
        get isDark() { return document.documentElement.classList.contains('dark'); },
        get tc() {
            const d = this.isDark;
            return {
                grid:     d ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)',
                tick:     d ? '#71717a' : '#9ca3af',
                ttBg:     d ? '#27272a' : '#ffffff',
                ttBorder: d ? '#3f3f46' : '#e5e7eb',
                ttTitle:  d ? '#e4e4e7' : '#18181b',
                ttBody:   d ? '#a1a1aa' : '#6b7280',
            };
        },

        /* ── Dataset builder ─────────────────────────────────────── */
        color(idx) { return this.palette[idx % this.palette.length]; },

        buildDataset(ds, idx) {
            const c    = ds.color ?? this.color(idx);
            const base = { label: ds.label ?? '', data: ds.data ?? [] };

            if (this.isDonut) {
                return { ...base,
                    backgroundColor: this.palette.map(p => p + 'dd'),
                    borderColor:     this.isDark ? '#18181b' : '#ffffff',
                    borderWidth: 2, hoverOffset: 6,
                };
            }
            if (this.type === 'bar') {
                return { ...base,
                    backgroundColor: c + 'cc',
                    borderColor:     c,
                    borderWidth:     0,
                    borderRadius:    this.stacked ? 0 : 4,
                    borderSkipped:   false,
                };
            }
            return { ...base,                         /* line / area */
                borderColor:           c,
                backgroundColor:       this.isArea ? c + '22' : 'transparent',
                fill:                  this.isArea,
                tension:               this.smooth ? 0.4 : 0,
                borderWidth:           2,
                pointRadius:           0,
                pointHoverRadius:      5,
                pointHoverBackgroundColor: c,
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2,
            };
        },

        /* ── Chart.js config ─────────────────────────────────────── */
        buildConfig() {
            const tc = this.tc;
            return {
                type: this.type,
                data: {
                    labels:   this.rawLabels,
                    datasets: this.rawDatasets.map((ds, i) => this.buildDataset(ds, i)),
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: false,
                    indexAxis:           this.isHorizontal ? 'y' : 'x',
                    interaction:         { mode: 'index', intersect: false },

                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled:         true,
                            backgroundColor: tc.ttBg,
                            borderColor:     tc.ttBorder,
                            borderWidth:     1,
                            titleColor:      tc.ttTitle,
                            bodyColor:       tc.ttBody,
                            padding:         10,
                            boxPadding:      4,
                            callbacks: {
                                label: (ctx) => {
                                    const v      = this.isDonut ? ctx.parsed : (this.isHorizontal ? ctx.parsed.x : ctx.parsed.y);
                                    const pre    = this.yPrefix ?? '';
                                    const suf    = this.ySuffix ?? '';
                                    const num    = typeof v === 'number' ? v.toLocaleString() : v;
                                    return ` ${ctx.dataset.label}: ${pre}${num}${suf}`;
                                },
                            },
                        },
                    },

                    scales: this.isDonut ? {} : {
                        x: {
                            stacked: this.stacked,
                            grid:    { display: false },
                            border:  { display: false },
                            ticks:   { color: tc.tick, font: { size: 11 }, maxRotation: 0 },
                        },
                        y: {
                            stacked: this.stacked,
                            min:     this.yMin  !== null ? this.yMin  : undefined,
                            max:     this.yMax  !== null ? this.yMax  : undefined,
                            grid:    { display: this.showGrid, color: tc.grid, drawBorder: false },
                            border:  { display: false, dash: [4, 4] },
                            ticks: {
                                color:    tc.tick,
                                font:     { size: 11 },
                                callback: (v) => `${this.yPrefix ?? ''}${v.toLocaleString()}${this.ySuffix ?? ''}`,
                            },
                        },
                    },
                },
            };
        },

        /* ── Lifecycle ───────────────────────────────────────────── */
        createChart() {
            const canvas = this.$refs.canvas;
            if (!canvas) return;
            const existing = Chart.getChart(canvas);
            if (existing) existing.destroy();
            this.chart = new Chart(canvas, this.buildConfig());
        },

        updateTheme() {
            if (!this.chart) return;
            const tc  = this.tc;
            const opt = this.chart.options;
            if (opt.plugins?.tooltip) {
                Object.assign(opt.plugins.tooltip, {
                    backgroundColor: tc.ttBg, borderColor: tc.ttBorder,
                    titleColor: tc.ttTitle, bodyColor: tc.ttBody,
                });
            }
            if (opt.scales?.x) opt.scales.x.ticks.color = tc.tick;
            if (opt.scales?.y) {
                opt.scales.y.ticks.color = tc.tick;
                if (opt.scales.y.grid) opt.scales.y.grid.color = tc.grid;
            }
            this.chart.update('none');
        },

        updateData(labels, datasets) {
            if (!this.chart) return;
            if (labels)   this.rawLabels   = labels;
            if (datasets) this.rawDatasets = datasets;
            this.chart.data.labels   = this.rawLabels;
            this.chart.data.datasets = this.rawDatasets.map((ds, i) => this.buildDataset(ds, i));
            this.chart.update();
        },

        init() {
            const boot = () => {
                if (typeof Chart === 'undefined') { setTimeout(boot, 50); return; }
                this.createChart();
                /* Dark-mode watcher */
                new MutationObserver(() => this.updateTheme())
                    .observe(document.documentElement, { attributeFilter: ['class'] });
                /* External update event: $el.dispatchEvent(new CustomEvent('chart:update', {detail:{labels,datasets}})) */
                this.$el.addEventListener('chart:update', (e) => {
                    this.updateData(e.detail?.labels, e.detail?.datasets);
                });
            };
            this.$nextTick(boot);
        },
    }"
    {{ $attributes->merge(['class' => 'w-full']) }}
>
    {{-- ── Legend ─────────────────────────────────────────────────────────── --}}
    @if($legendItems)
        <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 mb-4">
            @foreach($legendItems as $item)
                <div class="flex items-center gap-2">
                    @if($isDonut)
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $item['color'] }}"></span>
                    @elseif($type === 'bar')
                        <span class="w-3 h-3 rounded-sm shrink-0" style="background:{{ $item['color'] }}cc"></span>
                    @else
                        <span class="w-5 h-0.5 rounded-full shrink-0" style="background:{{ $item['color'] }}"></span>
                    @endif
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $item['label'] }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── Canvas ──────────────────────────────────────────────────────────── --}}
    <div style="{{ $containerStyle }};position:relative;width:100%">
        <canvas x-ref="canvas"></canvas>
    </div>
</div>
