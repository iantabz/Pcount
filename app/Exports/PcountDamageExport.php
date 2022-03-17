<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PcountDamageExport implements FromView
{

    public function view(): View
    {
        return view('reports.pcount_damages_excel', ['data' => session()->get('dataCountDamages')]);
    }
}
