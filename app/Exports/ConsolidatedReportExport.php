<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ConsolidatedReportExport implements FromView, WithStyles, WithColumnWidths
{

    public function setPassword(Worksheet $sheet)
    {
        return [

            $protection = $sheet->getProtection(),
            $protection->setPassword('Pcount2021', true),
            $protection->setSheet(true),
            $protection->setAlgorithm(Protection::ALGORITHM_SHA_512),
            $protection->setSpinCount(20000),
            $protection->setSort(true),
            $protection->setInsertRows(true),
            $protection->setFormatCells(true)
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // $this->excelEnableProtection($sheet);
        $protection = $sheet->getProtection();
        $protection->setPassword('Pcount2021', 'true');
        $protection->setAlgorithm(Protection::ALGORITHM_SHA_512);
        $protection->setSpinCount(20000);
        $protection->setSheet(true);
        $protection->setSort(true);
        $protection->setInsertRows(true);
        $protection->setFormatCells(true);
        // $protection->isProtectionEnabled(true);

        return [
            // Style the first row as bold text.
            'A' => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            'B' => ['font' => ['bold' => true]]

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }


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
