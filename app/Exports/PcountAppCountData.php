<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PcountAppCountData implements FromView
{
    public function view(): View
    {
        return view('reports.pcount_app_excel', ['data' => session()->get('data')]);
    }
}
