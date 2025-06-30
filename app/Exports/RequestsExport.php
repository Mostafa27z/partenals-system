<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RequestsExport implements FromView
{
    protected $requests;

    public function __construct($requests)
    {
        $this->requests = $requests;
    }

    public function view(): View
    {
        return view('exports.requests', ['requests' => $this->requests]);
    }
}
