<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PcountAppCountCost implements FromView
{

    public function view(): View
    {
        return view('reports.pcount_cost_excel', ['data' => session()->get('data')]);
    }
}
