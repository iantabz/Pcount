<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VarianceNav implements FromCollection, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    public function collection()
    {
        $export = json_decode(utf8_encode(base64_decode(request()->export)));
        $test = null;

        $export = collect($export)->map(function ($trans) use ($test) {
            $postingDate = date_format(date_create($trans->postingDate), "m/d/Y");
            $docDate = date_format(date_create($trans->postingDate), "m/d/Y");

            $trans->DocNo = '10000';
            $trans->postingDate = $postingDate;
            $trans->docDate = $docDate;
            $trans->valueEntry = 'Direct Cost';
            $trans->reasonCode = 'PCV';

            return $trans;
        });

        return $export;
    }

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

    public function columnFormats(): array
    {
        return [
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
        ];
    }

    public function headings(): array
    {
        return [
            'Journal Template Name',
            'Journal Batch Name',
            'Line No.',
            'Item No.',
            'Posting Date',
            'Entry Type',
            'Document No.',
            'Description',
            'Location Code',
            'Inventory Posting Group',
            'Quantity',
            'Invoiced Quantity',
            'Unit Amount',
            'Unit Cost',
            'Amount',
            'Source Code',
            'Company Code',
            'Department Code',
            'Reason Code',
            'Gen. Prod. Posting Group',
            'Document Date',
            'External Document No.',
            'Qty. per Unit of Measure',
            'Unit of Measure Code',
            'Quantity (Base)',
            'Invoiced Qty. (Base)',
            'Value Entry Type',
            'Item Division'
        ];
    }
}
