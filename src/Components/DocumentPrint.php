<?php

namespace Phara\UIKit\Components;

use Illuminate\View\Component;

class DocumentPrint extends Component
{
    public function __construct(
       
    ) {}

    public function render()
    {
        return view('ui::components.document-print');
    }
}