<?php

namespace App\Imports;

use App\Models\TblItemCategoryMasterfile;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');


class ImportItemCategoryMasterfile implements ToCollection, WithChunkReading
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $row = $row->values();
            TblItemCategoryMasterfile::create([
                'dept_code' => trim($row[0]),
                'category' => trim($row[1])
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 10000;
    }
}
