<?php

namespace App\Imports;

use App\Models\TblUnposted;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ImportItemUnposted implements ToCollection, WithHeadingRow, WithChunkReading
{
    use Importable;

    private $rowWithDate = 2;
    private $columnWithDate = 0;
    private $finalDate;
    private $batchDatel;

    public function __construct($finalDate)
    {
        $this->finalDate = $finalDate;
    }

    public function collection(Collection $rows)
    {
        $user = auth()->user()->id;
        $company = auth()->user()->company;
        $business_unit = request()->business_unit;
        $department = request()->department;
        $section = request()->section;
        // dd(request()->all());

        foreach ($rows as $row) {

            $row = $row->values();
            $qty = (float) str_replace(',', '', $row[4]);
            $cost_no_vat = (float) str_replace(',', '', $row[5]);
            $tot_cost_no_vat = (float) str_replace(',', '', $row[6]);

            // dd($row);
            TblUnposted::create([
                'itemcode' => trim($row[0]),
                'variant_code' => trim($row[1]),
                'description' => trim($row[2]),
                'uom' => trim($row[3]),
                'qty' => $qty,
                'cost_no_vat' => $cost_no_vat,
                'tot_cost_no_vat' => $tot_cost_no_vat,
                'user_id' => $user,
                'company' => $company,
                'business_unit' => $business_unit,
                'department' => $department,
                'section' => $section,
                'date' => $this->finalDate
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 10000;
    }

    public function headingRow(): int
    {
        return 4;
    }
}
