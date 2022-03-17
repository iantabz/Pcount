<?php

namespace App\Http\Controllers;

use App\Imports\ImportItemCategoryMasterfile;
use App\Imports\ImportItemMasterfile;
use App\Imports\ImportNavPcount;
use App\Imports\ImportVendorMasterfile;
use App\Models\BusinessUnit;
use App\Models\company;
use App\Models\department;
use App\Models\section;
use App\Models\TblItemCategoryMasterfile;
use App\Models\TblVendorMasterfile;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class FileUploadController extends Controller
{
    public function navPcount()
    {
        // request()->validate(['file' => 'required|file|mimes:xls,xlsx,csv,txt']);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        // dd(request()->all());
        $extension = request()->file('file')->getClientOriginalExtension();
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;
        // Excel::import(new ImportNavPcount, $path);

        $reader = new Csv();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
        $worksheet = $spreadsheet->getActiveSheet();

        $dataArray = $worksheet->toArray();

        $array = explode(' ', $dataArray[2][0]);

        $date = array_splice($array, 2);
        $tempDate = implode(' ', $date);

        $finalDate = Carbon::parse($tempDate)->format('Y-m-d');
        // dd($finalDate);
        $vendor = request()->vendor;
        $category = request()->category;
        $countType = request()->countType;
        $batchDate = request()->date;
        $import = new ImportNavPcount($finalDate, $vendor, $category, $countType, $batchDate);
        $import->import($path);

        // Excel::import(new ImportNavPcount, $finalDate, $vendor, $category);
        // try {
        //     Excel::import($import, $path);
        // } catch (ValidationException $e) {
        //     return response()->json(['success' => 'errorList', 'message' => $e->errors()]);
        // }
        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function getCompany()
    {
        // $get = company::all();
        $get = company::where('company', 'MARCELA FARMS INCORPORATED')
            ->orWhere('company', 'ALTURAS SUPERMARKET CORPORATION')
            ->orWhere('acroname', 'LDI')
            ->orWhere('acroname', 'AGC')
            ->get();
        return $get;
    }

    public function getBU()
    {
        // dd(request()->all());
        // return BusinessUnit::where([
        //     ['company_code', request()->code],
        //     ['acroname', 'ASCMAIN']
        // ])->orWhere([
        //     ['company_code', request()->code],
        //     ['acroname', 'ASC TAL']
        // ])->orWhere([
        //     ['company_code', request()->code],
        //     ['acroname', 'ICM']
        // ])
        //     ->orWhere([
        //         ['company_code', request()->code],
        //         ['acroname', 'ASC TUBIGON']
        //     ])
        //     ->orWhere([
        //         ['company_code', request()->code],
        //         ['acroname', 'ALTA CITTA']
        //     ])
        //     ->orWhere([
        //         ['company_code', request()->code],
        //         ['acroname', 'PM']
        //     ])
        //     ->orWhere([
        //         ['company_code', request()->code],
        //         ['business_unit', 'WHOLESALE DISTRIBUTION GROUP']
        //     ])
        //     ->orWhere([
        //         ['company_code', request()->code]
        //     ])
        //     ->get();
        $get = BusinessUnit::where([
            [
                'company_code',
                request()->code
            ],
            ['status', '=', 'active']
        ])->get();
        return $get;
    }

    public function getDept()
    {
        return department::where([
            ['company_code', request()->code],
            ['bunit_code', request()->bu],
            ['status', '=', 'active']
        ])->get();
    }

    public function getSection()
    {
        return section::where(
            [
                ['company_code', request()->code],
                ['bunit_code', request()->bu],
                ['dept_code', request()->dept],
            ]
        )->get();
    }

    public function importItemMasterfile()
    {
        // dd(request()->all());
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $extension = request()->file('file')->getClientOriginalExtension();
        // dd($extension);
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;


        // dd($path);
        Excel::import(new ImportItemMasterfile, $path);
        return response()->json(['message' => 'uploaded successfully'], 200);
    }

    public function getVendor()
    {
        $vendor = request()->vendor;
        if ($vendor) {

            return TblVendorMasterfile::selectRaw('vendor_name, vendor_code ')
                ->where('vendor_name', 'LIKE', "%$vendor%")
                ->orWhere('vendor_code', $vendor)
                ->get();
        } else {
            return TblVendorMasterfile::take(500)->get();
        }
    }
    public function getCategory()
    {
        $category = request()->category;
        if ($category) {

            return TblItemCategoryMasterfile::selectRaw('category, dept_code')
                ->where('category', 'LIKE', "%$category%")
                ->orWhere('dept_code', $category)
                ->get();
        } else {
            return TblItemCategoryMasterfile::take(500)->get();
        }
    }

    public function importVendorMasterfile()
    {
        $extension = request()->file('file')->getClientOriginalExtension();
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;
        $import = new ImportVendorMasterfile();
        $import->import($path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }
    public function importItemCategoryMasterfile()
    {
        $extension = request()->file('file')->getClientOriginalExtension();
        $path = request()->file->storeAs('temp', time() . '.' . $extension);
        $path = storage_path('app') . '/' . $path;
        $import = new ImportItemCategoryMasterfile();
        $import->import($path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }
}
