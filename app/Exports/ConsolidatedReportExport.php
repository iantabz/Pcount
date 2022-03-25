<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ConsolidatedReportExport implements FromView, WithColumnWidths
{
    public function view(): View
    {
        return view('reports.consolidated_report_excel', ['data' => session()->get('data')]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 20,
            'D' => 30,
            'E' => 20
        ];
    }
}
