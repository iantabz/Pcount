<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class LocationData implements FromView, ShouldAutoSize, WithColumnWidths
{

    public function view(): View
    {
        return view('reports.locationdata_excel', ['data' => session()->get('data')]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'D' => 30
        ];
    }
}
