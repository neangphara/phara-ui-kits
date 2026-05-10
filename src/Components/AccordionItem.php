<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class AccordionItem extends Component
{
    public function __construct(
        public string $title = ''
    ) {}

    public function render()
    {
        return view('ui::components.accordion-item');
    }
}