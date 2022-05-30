<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PcountAppCountData implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('reports.pcount_app_excel', ['data' => session()->get('data')]);
    }
}
