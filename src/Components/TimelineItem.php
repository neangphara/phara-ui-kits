<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class TimelineItem extends Component
{
    public function __construct(
        public ?string $date = null,
    ) {}

    public function render()
    {
        return view('ui::components.timeline-item');
    }
}
