<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class TimelineIndicator extends Component
{
    public function __construct(
        public string $color = 'zinc',
        public ?string $icon = null,
        public string $iconVariant = 'solid',
        public string $variant = 'solid',
    ) {}

    public function render()
    {
        return view('ui::components.timeline-indicator');
    }
}
