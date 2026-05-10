<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class StatsCard extends Component
{
    public string $computedTrend;

    public function __construct(
        public string $title = '',
        public string $value = '',
        public ?string $change = null,
        public string $changeLabel = '',
        public ?string $trend = null,
        public ?string $icon = null,
        public string $iconColor = 'blue',
        public ?string $description = null,
        public string $variant = 'default',
        public bool $loading = false,
    ) {
        if ($this->trend !== null) {
            $this->computedTrend = $this->trend;
        } elseif ($this->change !== null) {
            $first = $this->change[0] ?? '';
            $this->computedTrend = match ($first) {
                '+' => 'up',
                '-' => 'down',
                default => 'neutral',
            };
        } else {
            $this->computedTrend = 'neutral';
        }
    }

    public function render()
    {
        return view('ui::components.stats-card');
    }
}
