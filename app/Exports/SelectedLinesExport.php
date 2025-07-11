<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SelectedLinesExport implements FromView
{
    protected $lines;

    public function __construct($lines)
    {
        $this->lines = $lines;
    }

    public function view(): View
    {
        return view('admin.lines.selected-lines', [
            'lines' => $this->lines
        ]);
    }
}
