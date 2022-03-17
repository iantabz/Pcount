<?php

namespace App\Imports;

use App\Models\TblVendorMasterfile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ImportVendorMasterfile implements ToCollection, WithHeadingRow, WithChunkReading
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $row = $row->values();
            TblVendorMasterfile::create([
                'vendor_code' => trim($row[0]),
                'vendor_name' => trim($row[1])
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}
