<?php

namespace App\Imports;

use App\Models\TblItemMasterfile;
use App\Models\TblNavCount;
use App\Models\TblNavCountdata;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ImportNavPcount implements ToCollection, WithHeadingRow, WithChunkReading
{
    use Importable;

    private $rowWithDate = 2;
    private $columnWithDate = 0;
    private $finalDate;
    private $batchDatel;

    public function __construct($finalDate, $vendor, $category, $countType, $batchDate)
    {
        $this->finalDate = $finalDate;
        // TblNavCount::create([
        //     'byCategory' =>  $category ? 'True' : 'False',
        //     'categoryName' => $category,
        //     'byVendor' => $vendor ? 'True' : 'False',
        //     'vendorName' => $vendor,
        //     'type' => $countType,
        //     'batchDate' => $batchDate
        // ]);
    }

    public function collection(Collection $rows)
    {
        // Validator::make($rows->toArray(), [
        //     '*.INCODE' => ['required', 'unique:tbl_nav_countdata,itemcode'],
        //     // '*.DESC' => ['required', 'unique:tbl_nav_countdata,description']
        // ], ['*.INCODE.unique' => 'Yoow'])->validate();

        $user = auth()->user()->id;
        $company = auth()->user()->company;
        // $business_unit = auth()->user()->business_unit;
        // $department = auth()->user()->department;
        // $section = auth()->user()->section;
        $business_unit = request()->business_unit;
        $department = request()->department;
        $section = request()->section;
        // dd(request()->all());
        $vendor = request()->vendor;
        $category = request()->category;

        foreach ($rows as $row) {

            $row = $row->values();
            // dd($row);
            $qty = (float) str_replace(',', '', $row[4]);
            $cost_no_vat = (float) str_replace(',', '', $row[5]);
            $amt = (float) str_replace(',', '', $row[6]);
            // $validation = TblNavCountdata::where('itemcode', $row[0])->exists();
            // dd($category);
            // if ($category != null) {
            //     $test = TblItemMasterfile::where([['group', $category], ['item_code', trim($row[0])],])->get();

            //     // if ($test)
            //     //     TblNavCountdata::create([
            //     //         'itemcode' => trim($row[0]),
            //     //         'variant_code' => trim($row[1]),
            //     //         'description' => trim($row[2]),
            //     //         'uom' => trim($row[3]),
            //     //         'qty' => $qty,
            //     //         'cost_no_vat' => $cost_no_vat,
            //     //         'amt' => $amt,
            //     //         'user_id' => $user,
            //     //         'company' => $company,
            //     //         'business_unit' => $business_unit,
            //     //         'department' => $department,
            //     //         'section' => $section,
            //     //         'date' => $this->finalDate
            //     //     ]);
            //     // dump($row[0]);
            //     // foreach ($row[0] as $item_code) {
            //     //     // dd('yes');
            //     //     dd($item_code);
            //     // }
            // } else {
            TblNavCountdata::create([
                'itemcode' => trim($row[0]),
                'variant_code' => trim($row[1]),
                'description' => trim($row[2]),
                'uom' => trim($row[3]),
                'qty' => $qty,
                'cost_no_vat' => $cost_no_vat,
                'amt' => $amt,
                'user_id' => $user,
                'company' => $company,
                'business_unit' => $business_unit,
                'department' => $department,
                'section' => $section,
                'date' => $this->finalDate
            ]);
            // }

            // dd(1);
            // if ($validation === false) {
            // dd(trim($row[1]));
            // }

            // $hasNavCount = TblNavCount::where([['categoryName', $category], ['vendorName', $vendor]])->exists();

            // if ($hasNavCount === false) {

            // TblNavCount::create([
            //     'byCategory' =>  $category ? 'True' : 'False',
            //     'categoryName' => $category,
            //     'byVendor' => $vendor ? 'True' : 'False',
            //     'vendorName' => $vendor
            // ]);
            // }
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
