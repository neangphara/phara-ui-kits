<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class SidebarToc extends Component
{
    public function __construct(
        public array $sections = []
    ) {}

    public function render()
    {
        return view('ui::components.sidebar-toc');
    }
}
