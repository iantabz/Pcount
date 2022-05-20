<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryValuation implements WithHeadings, FromView, ShouldAutoSize
{
    use Exportable;

    private $columns = [
        'Item Code',
        'Variant Code',
        'Description',
        'Uom',
        'Qty'
    ];

    public function headings(): array
    {
        return $this->columns;
    }

    public function view(): View
    {
        $export = json_decode(base64_decode(request()->export), true);
        return view('reports.inventory_valuation_variance_report',  ['data' => $export]);
        // foreach ($export['data'] as $key => $value) {
        //     $this->columns[0] = $value['itemcode'];
        //     $this->columns[1] = $value['variant_code'];
        //     $this->columns[2] = $value['description'];
        //     $this->columns[3] = $value['uom'];
        //     $this->columns[4] = $value['qty'];
        // }
        // return $export;
        // dd($export);
    }
}
