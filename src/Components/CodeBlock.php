<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class CodeBlock extends Component
{
    public function __construct(
        public ?string $language = null,
        public ?string $title = null,
        public bool $showLineNumbers = false,
        public bool $showCopyButton = true
    ) {}

    public function render()
    {
        return view('ui::components.code-block');
    }
}
