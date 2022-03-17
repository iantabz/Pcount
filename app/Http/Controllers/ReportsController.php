<?php

namespace App\Http\Controllers;

use \PDF;
use App\Exports\PcountAppCountCost;
use App\Models\BusinessUnit;
use App\Models\TblAppCountdata;
use App\Models\TblNavCountdata;
use Carbon\Carbon;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function getResults()
    {
        return TblNavCountdata::paginate(10);
    }

    public function generate()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $pdf = PDF::loadView('reports.consolidated_report_cost', ['data' => $this->data()]);
        return $pdf->setPaper('legal', 'landscape')->download('Consolidated-Reports-w-Cost.pdf');
    }

    public function data()
    {
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $bu = explode(',', $bu);
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $stores = TblNavCountdata::select('tbl_nav_countdata.business_unit', 'acroname')
            ->join('business_unit', 'business_unit.business_unit', 'tbl_nav_countdata.business_unit')
            ->whereIn('tbl_nav_countdata.business_unit', $bu)
            ->distinct()
            ->get()
            ->toArray();

        $query = TblNavCountdata::whereBetween('date', [$date, $dateAsOf]);
        if ($bu != 'null') {

            $query->whereIn('business_unit', $bu);
            $bu = implode(", ", $bu);
        }

        if ($dept != 'null') {
            $query->WHERE('department', $dept);
        }

        if ($section != 'null') {
            $query->WHERE('section', $section);
        }
        // dd($query->get());
        $masterFiles = DB::table('tbl_item_masterfile')
            ->SelectRaw('item_code, extended_desc, tbl_item_masterfile.uom, vendor_name, cost_vat, cost_no_vat, tbl_item_masterfile.group')
            ->whereIn('item_code', $query->limit(2000)->pluck('itemcode'))
            ->whereIn('tbl_item_masterfile.uom', $query->pluck('uom'))
            ->JOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', 'tbl_item_masterfile.item_code');
        // ->where('item_code', 100458)
        // ->get();

        if ($vendors) {
            $vendors = explode('|', $vendors);
            $masterFiles = $masterFiles->whereIn('vendor_name', $vendors);
            $vendors = implode(", ", $vendors);
        }
        if ($category) {
            $category = explode('|', $category);
            $masterFiles = $masterFiles->whereIn('group', $category);
            $category = implode(", ", $category);
        }

        $masterFiles = $masterFiles->get()->groupBy(['vendor_name', 'group']);
        // dd($masterFiles);

        foreach ($masterFiles as $vendor_name => $categories) {
            // dd($vendor_name);
            foreach ($categories as $category => $items) {
                // dd($key);

                $test = $items->map(function ($item) use ($stores) {

                    // dd($item);

                    $itemStores = [];
                    $totalQty = 0;

                    foreach ($stores as $store) {
                        $storeName = array_values($store)[0];
                        $storeAcroname = $store['acroname'];
                        // dd($item->uom);
                        $qty = TblNavCountdata::where([
                            ['itemcode', $item->item_code],
                            ['uom', 'LIKE', "%$item->uom%"],
                            ['business_unit', $storeName]
                        ])->sum('qty');

                        $itemStores[$storeAcroname] = $qty;
                        $totalQty += $qty;
                    }
                    // }

                    $cost_vat = TblNavCountdata::where('itemcode', $item->item_code)->first()->cost_vat;
                    $cost_no_vat = TblNavCountdata::where('itemcode', $item->item_code)->first()->cost_no_vat;
                    $item->totalQty = $totalQty;
                    $item->stores = $itemStores;
                    $item->totalCostVat = $totalQty * $cost_vat;
                    $item->totalCostNoVat = $totalQty * $cost_no_vat;


                    return $item;
                });
            }
        }

        // $test = $masterFiles->map(function ($item) use ($stores) {

        //     $itemStores = [];

        //     $totalQty = 0;
        //     foreach ($stores as $store) {

        //         $storeName = array_values($store)[0];
        //         $storeAcroname = $store['acroname'];

        //         $qty = TblNavCountdata::where([
        //             ['itemcode', $item->item_code],
        //             ['business_unit', $storeName]
        //         ])->sum('qty');

        //         $itemStores[$storeAcroname] = $qty;
        //         $totalQty += $qty;
        //     }

        //     // $uom = TblNavCountdata::where('itemcode', $item->item_code)->first()->uom;
        //     // $cost_vat = TblNavCountdata::where('itemcode', $item->item_code)->first()->cost_vat;
        //     // $cost_no_vat = TblNavCountdata::where('itemcode', $item->item_code)->first()->cost_no_vat;

        //     // $item->uom = $uom;
        //     // $item->cost_vat = $cost_vat;
        //     // $item->cost_no_vat = $cost_no_vat;
        //     // $item->totalQty = $totalQty;
        //     // $item->totalCostVat = $totalQty * $cost_vat;
        //     // $item->totalCostNoVat = $totalQty * $cost_no_vat;
        //     // $item->stores = $itemStores;

        //     return $item;
        // });

        $header = array(
            'user' => auth()->user()->name,
            'userBu' =>  auth()->user()->business_unit,
            'userDept' => auth()->user()->department,
            'userSection' => auth()->user()->section,
            'user_position' => auth()->user()->position,
            'category' => $category,
            'date' => $printDate,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'data' => $masterFiles
        );
        // dd($header);
        return $header;
    }

    public function getResultVarianceCost()
    {
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $vendors = request()->vendors;
        $category = request()->category;
        $bu = request()->bu;
        $dept = request()->dept;
        // $section = request()->section;

        // tbl_app_countdata.uom, 
        // SUM(tbl_app_countdata.qty) as app_qty,
        // SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
        // tbl_nav_countdata.uom as nav_uom,
        // tbl_nav_countdata.qty as nav_qty, 

        $result = TblNavCountdata::selectRaw('tbl_nav_countdata.itemcode, 
        tbl_app_countdata.barcode, 
        tbl_nav_countdata.description, 
        tbl_item_masterfile.extended_desc,
        tbl_app_countdata.uom, 
        SUM(tbl_app_countdata.qty) as app_qty,
        tbl_app_countdata.conversion_qty,
        tbl_nav_countdata.uom as nav_uom,
        tbl_nav_countdata.qty as nav_qty, 
        cost_vat,
        cost_no_vat, 
        amt,
        date')
            ->JOIN('tbl_app_countdata', 'tbl_app_countdata.itemcode', '=', 'tbl_nav_countdata.itemcode')
            ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.item_code', '=', 'tbl_nav_countdata.itemcode');
        // ->WHERE([
        //     // ['tbl_nav_countdata.company', $company],
        //     ['tbl_nav_countdata.business_unit', $bu],
        //     ['tbl_nav_countdata.department', $dept],
        //     ['tbl_nav_countdata.section', $section]
        // ]);

        if ($bu != 'null') {
            // dd($bu);
            $result->WHERE('tbl_nav_countdata.business_unit', $bu);
        }

        if ($dept != 'null') {
            $result->WHERE('tbl_nav_countdata.department', $dept);
        }

        if ($section != 'null') {
            $result->WHERE('tbl_nav_countdata.section', $section);
        }

        if ($vendors) {
            $vendors = explode('|', $vendors);
            $result->whereIn('vendor_name', $vendors);
        }
        if ($category) {
            $category = explode('|', $category);
            $result->whereIn('group', $category);
        }

        return $result
            ->whereBetween('date', [$date, $dateAsOf])
            ->groupByRaw('tbl_nav_countdata.itemcode')
            ->orderBy('itemcode')
            ->paginate(10);
    }

    public function generateVarianceReportCost()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $pdf = PDF::loadView('reports.variance_report_cost', ['data' => $this->dataVarianceReportCost()]);
        return $pdf->setPaper('legal', 'landscape')->download('PCountCost.pdf');
    }

    public function dataVarianceReportCost()
    {
        $company = auth()->user()->company;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        // $data = TblNavCountdata::selectRaw('tbl_nav_countdata.itemcode, 
        // tbl_app_countdata.barcode, 
        // tbl_nav_countdata.description, 
        // tbl_nav_countdata.uom, 
        // cost_no_vat, 
        // amt,
        // tbl_nav_countdata.qty as nav_qty, 
        // SUM(tbl_app_countdata.qty) as app_qty, 
        // (SUM(tbl_nav_countdata.qty) - SUM(tbl_app_countdata.qty)) * cost_vat AS tot_vat,
        // (SUM(tbl_nav_countdata.qty) - SUM(tbl_app_countdata.qty)) * cost_no_vat AS tot_novat,
        // vendor_name')
        //     ->JOIN('tbl_app_countdata', 'tbl_app_countdata.itemcode', '=', 'tbl_nav_countdata.itemcode')
        //     ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.item_code', '=', 'tbl_nav_countdata.itemcode');

        // if ($bu != 'null') {
        //     $data->WHERE('tbl_nav_countdata.business_unit', $bu);
        // }

        // if ($dept != 'null') {
        //     $data->WHERE('tbl_nav_countdata.department', $dept);
        // }

        // if ($section != 'null') {
        //     $data->WHERE('tbl_nav_countdata.section', $section);
        // }

        // if ($vendors) {
        //     $vendors = explode('|', $vendors);
        //     $data = $data->whereIn('vendor_name', $vendors);
        //     $vendors = implode(", ", $vendors);
        // }
        // if ($category) {
        //     $category = explode('|', $category);
        //     $data = $data->whereIn('group', $category);
        //     $category = implode(", ", $category);
        // }

        // $data = $data->whereBetween('date', [$date, $dateAsOf])
        //     ->groupByRaw('tbl_nav_countdata.itemcode')
        //     ->cursor()->map(function ($countData) {
        //         $variance = (float) $countData->nav_qty - (float) $countData->app_qty;
        //         return array(
        //             'itemcode' => $countData->itemcode,
        //             'barcode' => $countData->barcode,
        //             'description' => $countData->description,
        //             'uom' => $countData->uom,
        //             'cost_vat' => $countData->cost_vat,
        //             'cost_no_vat' => $countData->cost_no_vat,
        //             'amt' => $countData->amt,
        //             // 'nav_qty' => number_format($countData->nav_qty, 2),
        //             // 'app_qty' => number_format($countData->app_qty, 2),
        //             'nav_qty' => intval($countData->nav_qty),
        //             'app_qty' => intval($countData->app_qty),
        //             // 'variance' => number_format($variance, 2),
        //             'variance' => intval($variance),
        //             'tot_vat' => $countData->tot_vat,
        //             'tot_novat' => $countData->tot_novat
        //         );
        //     })->toArray();

        // dd($data);

        // tbl_app_countdata.barcode, 
        // tbl_nav_countdata.description, 
        // tbl_nav_countdata.uom, 
        // cost_no_vat, 
        // amt,
        // tbl_nav_countdata.qty as nav_qty, 
        // SUM(tbl_app_countdata.qty) as app_qty, 
        // (SUM(tbl_nav_countdata.qty) - SUM(tbl_app_countdata.qty)) * cost_vat AS tot_vat,
        // (SUM(tbl_nav_countdata.qty) - SUM(tbl_app_countdata.qty)) * cost_no_vat AS tot_novat,
        // vendor_name

        $result = TblAppCountdata::selectRaw('
        tbl_app_countdata.id,
                tbl_app_countdata.itemcode, 
                tbl_app_countdata.barcode, 
                tbl_item_masterfile.extended_desc, 
                tbl_app_countdata.uom, 
                SUM(tbl_app_countdata.qty) as app_qty,
                SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
                tbl_nav_countdata.uom as nav_uom,
                cost_vat,
                cost_no_vat,
                vendor_name,
                tbl_item_masterfile.group
                ')
            ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
            ->join('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', 'tbl_app_countdata.itemcode')
            ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('itemcode');

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name'])->toArray());

        if ($bu != 'null') {
            $result->WHERE('tbl_app_countdata.business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('tbl_app_countdata.department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('tbl_app_countdata.section', 'LIKE', "%$section%");
        }
        if ($vendors) {
            $vendors = explode('|', $vendors);
            $result = $result->whereIn('vendor_name', $vendors);
            $vendors = implode(", ", $vendors);
        }

        if ($category) {
            $category = explode('|', $category);
            $result = $result->whereIn('group', $category);
            $category = implode(", ", $category);
        }

        // dd($result->groupBy('itemcode')->get()->groupBy(['app_user', 'audit_user']));

        // $result = $result->groupBy('barcode')->get()->groupBy(['vendor_name', 'group'])->toArray();
        $result = $result->groupBy('barcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']);

        // dd($result);

        $arr = [];

        foreach ($result as $vendor_name => $categories) {
            foreach ($categories as $category => $items) {
                $arr[$vendor_name][$category] = $items->map(function ($c) use ($bu, $dept, $section) {
                    $query = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                        ['itemcode', $c->itemcode],
                        ['business_unit', $bu],
                        ['department', $dept],
                        ['section', $section]
                    ])->groupBy('itemcode');
                    // dd($query->get());

                    if ($query->exists()) {
                        $c->nav_qty = $query->first()->nav_qty;
                    } else {
                        $c->nav_qty = '-';
                    }

                    return $c;
                });
            }
        }

        // dd($arr);

        $header = array(
            'company' => $company,
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            'date' => $printDate,
            'user' => auth()->user()->name,
            'userBu' =>  auth()->user()->business_unit,
            'userDept' => auth()->user()->department,
            'userSection' => auth()->user()->section,
            'user_position' => auth()->user()->position,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'data' => $result
        );
        // dd($header);
        return $header;
    }

    public function getResultsVariance()
    {
        // dd(request()->all());
        $user = auth()->user()->id;
        $company = auth()->user()->company;
        $business_unit = auth()->user()->business_unit;
        $department = auth()->user()->department;
        $section = request()->section;
        $vendors = request()->vendors;
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $bu = request()->bu;
        $dept = request()->dept;

        // $result = TblNavCountdata::selectRaw("tbl_nav_countdata.itemcode, 
        // tbl_app_countdata.barcode,
        // tbl_item_masterfile.extended_desc,
        //  tbl_nav_countdata.uom, 
        //  SUM(tbl_app_countdata.qty) as app_qty,
        //  tbl_app_countdata.conversion_qty as conversion_qty,
        //  tbl_nav_countdata.uom as nav_uom,
        //  tbl_nav_countdata.qty as nav_qty")
        //     ->JOIN('tbl_app_countdata', 'tbl_app_countdata.itemcode', '=', 'tbl_nav_countdata.itemcode')
        //     ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.item_code', '=', 'tbl_nav_countdata.itemcode');


        $result = TblAppCountdata::selectRaw(
            "tbl_app_countdata.itemcode, 
        tbl_app_countdata.barcode,
        tbl_item_masterfile.extended_desc,
         tbl_app_countdata.uom, 
         SUM(tbl_app_countdata.qty) as app_qty,
         SUM(tbl_app_countdata.conversion_qty) as conversion_qty,
         SUM(tbl_nav_countdata.qty) as nav_qty"
        )
            ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.barcode', '=', 'tbl_app_countdata.barcode')
            ->leftJoin('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode');

        if ($bu != 'null') {
            $result->WHERE('tbl_app_countdata.business_unit', $bu);
            // $result->orwhere('tbl_nav_countdata.business_unit', $bu);
        }

        if ($dept != 'null') {
            $result->WHERE('tbl_app_countdata.department', $dept);
            // $result->orwhere('tbl_nav_countdata.department', $dept);
        }

        if ($section != 'null') {
            $result->WHERE('tbl_app_countdata.section', $section);
            // $result->orwhere('tbl_nav_countdata.section', $section);
        }

        $x = $result->groupByRaw('tbl_app_countdata.itemcode')->orderBy('itemcode')->limit(5)->get();

        // dd($x);

        $xx = $x->map(function ($c) use ($bu, $dept, $section) {
            // dd($c->itemcode);
            $x = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                ['itemcode', $c->itemcode],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $section]
            ])->groupBy('itemcode');

            // dd($x);
            if ($x->exists()) {
                $c->nav_qty = $x->nav_qty;
            } else {
                $c->nav_qty = null;
            }

            return $c;
        });

        dd($xx);

        if ($vendors) {
            $vendors = explode('|', $vendors);
            $result->whereIn('vendor_name', $vendors);
        }
        if ($category) {
            $category = explode('|', $category);
            $result->whereIn('group', $category);
        }

        // dd($result->whereBetween('date', [$date, $dateAsOf])
        //     ->groupByRaw('tbl_nav_countdata.itemcode')
        //     ->orderBy('itemcode')->limit(5)->get());

        return $result->whereBetween('date', [$date, $dateAsOf])
            ->groupByRaw('tbl_nav_countdata.itemcode')
            ->orderBy('itemcode')
            ->paginate(10);
    }

    public function generateVariance()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $domPdfOptions = new Options();
        // dd($domPdfOptions);

        $pdf = PDF::loadView('reports.variance_report', ['data' => $this->varianceReportData()]);
        return $pdf->setPaper('legal', 'landscape')->download('Variance Report.pdf');
    }

    public function varianceReportData()
    {
        $company = auth()->user()->company;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $bu = request()->bu;
        $dept = request()->dept;
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        // $data = TblNavCountdata::selectRaw('tbl_nav_countdata.itemcode, 
        // tbl_app_countdata.barcode, 
        // tbl_nav_countdata.description, 
        // tbl_nav_countdata.uom, 
        // tbl_nav_countdata.qty as nav_qty, 
        // SUM(tbl_app_countdata.qty) as app_qty,
        // vendor_name')
        //     ->JOIN('tbl_app_countdata', 'tbl_app_countdata.itemcode', '=', 'tbl_nav_countdata.itemcode')
        //     ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.item_code', '=', 'tbl_nav_countdata.itemcode')
        //     ->whereBetween('datetime_saved', [$date, $dateAsOf]);
        // // dd($vendors);
        // if ($bu != 'null') {
        //     $data->WHERE('tbl_nav_countdata.business_unit', $bu);
        // }

        // if ($dept != 'null') {
        //     $data->WHERE('tbl_nav_countdata.department', $dept);
        // }

        // if ($section != 'null') {
        //     $data->WHERE('tbl_nav_countdata.section', $section);
        // }

        // if ($vendors) {
        //     $vendors = explode('|', $vendors);
        //     $data = $data->whereIn('vendor_name', $vendors);
        //     $vendors = implode(", ", $vendors);
        // }
        // if ($category) {
        //     $category = explode('|', $category);
        //     $data = $data->whereIn('group', $category);
        //     $category = implode(", ", $category);
        // }

        // $data = $data->get()->groupBy('vendor_name');
        // DUMP($data);

        // $data = $data->groupByRaw('tbl_nav_countdata.itemcode')
        //     ->cursor()->map(function ($countData) {
        //         $variance = (float) $countData->nav_qty - (float) $countData->app_qty;
        //         return array(
        //             'itemcode' => $countData->itemcode,
        //             'barcode' => $countData->barcode,
        //             'description' => $countData->description,
        //             'uom' => $countData->uom,
        //             'nav_qty' => (float) $countData->nav_qty,
        //             'app_qty' => (float) $countData->app_qty,
        //             'variance' => $variance
        //             // 'nav_qty' => number_format($countData->nav_qty, 2),
        //             // 'app_qty' => number_format($countData->app_qty, 2),
        //             // 'variance' => number_format($variance, 2)
        //         );
        //     })->toArray();

        //  tbl_nav_countdata.uom as nav_uom,
        //  tbl_nav_countdata.qty as nav_qty, 
        $result = TblAppCountdata::selectRaw('
        tbl_app_countdata.id,
                tbl_app_countdata.itemcode, 
                tbl_app_countdata.barcode, 
                tbl_item_masterfile.extended_desc,
                tbl_app_countdata.uom, 
                SUM(tbl_app_countdata.qty) as app_qty,
                SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
                vendor_name,
                tbl_item_masterfile.group
                ')
            ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
            ->join('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', 'tbl_app_countdata.itemcode')
            ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('itemcode');

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name'])->toArray());

        if ($bu != 'null') {
            $result->WHERE('tbl_app_countdata.business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('tbl_app_countdata.department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('tbl_app_countdata.section', 'LIKE', "%$section%");
        }
        if ($vendors) {
            $vendors = explode('|', $vendors);
            $result = $result->whereIn('vendor_name', $vendors);
            $vendors = implode(", ", $vendors);
        }

        if ($category) {
            $category = explode('|', $category);
            $result = $result->whereIn('group', $category);
            $category = implode(", ", $category);
        }

        // dd($result->groupBy('itemcode')->get()->groupBy(['app_user', 'audit_user']));
        // $result = $result->groupBy('barcode')->get()->groupBy(['vendor_name', 'group'])->toArray();

        // $result = $result->groupBy('barcode')->orderBy('itemcode')->limit(5)->get()->groupBy(['vendor_name', 'group']);
        $result = $result->groupBy('barcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']);

        // dd($result);

        $arr = [];

        foreach ($result as $vendor_name => $categories) {
            foreach ($categories as $category => $items) {
                $arr[$vendor_name][$category] = $items->map(function ($c) use ($bu, $dept, $section) {
                    $query = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                        ['itemcode', $c->itemcode],
                        ['business_unit', $bu],
                        ['department', $dept],
                        ['section', $section]
                    ])->groupBy('itemcode');
                    // dd($query->get());

                    if ($query->exists()) {
                        $c->nav_qty = $query->first()->nav_qty;
                    } else {
                        $c->nav_qty = '-';
                    }

                    return $c;
                });
            }
        }



        // dd($arr);

        $header = array(
            'company' => $company,
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            'vendors' => $vendors,
            'category' => $category,
            'date' => $printDate,
            'user' => auth()->user()->name,
            'userBu' =>  auth()->user()->business_unit,
            'userDept' => auth()->user()->department,
            'userSection' => auth()->user()->section,
            'user_position' => auth()->user()->position,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'data' => $arr
        );
        // dd($header);
        return $header;
    }

    public function generateConsolidateReport()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('reports.consolidated_report', ['data' => $this->getResultsConsolidateReport()]);

        return $pdf->setPaper('legal', 'landscape')->download('Consolidatedreport.pdf');
    }

    public function getResultsConsolidateReport()
    {
        // dd(request()->all(), base64_decode(request()->date), base64_decode(request()->date2));
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        // $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $bu = explode(',', $bu);
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');
        // dd($vendors);
        $stores = TblNavCountdata::select('tbl_nav_countdata.business_unit', 'acroname')
            ->join('business_unit', 'business_unit.business_unit', 'tbl_nav_countdata.business_unit')
            ->whereIn('tbl_nav_countdata.business_unit', $bu)
            ->distinct()
            ->get()
            ->toArray();

        // dd($stores);

        $query = TblNavCountdata::whereBetween('date', [$date, $dateAsOf]);
        if ($bu != 'null') {

            $query->whereIn('business_unit', $bu);
            $bu = implode(", ", $bu);
        }

        if ($dept != 'null') {
            $query->WHERE('department', $dept);
        }

        if ($section != 'null') {
            $query->WHERE('section', $section);
        }

        $masterFiles = DB::table('tbl_item_masterfile')
            ->SelectRaw('item_code, extended_desc, uom, vendor_name, tbl_item_masterfile.group')
            ->whereIn('uom', $query->pluck('uom'))
            ->whereIn('item_code', $query->limit(2000)->pluck('itemcode'));
        // ->where('item_code', 100458)
        // ->get();

        if ($vendors) {
            $masterFiles->whereIn('vendor_name', explode('|', $vendors));
        }


        if ($category) {
            $category = explode('|', $category);
            $masterFiles->whereIn('group', $category);
            $category = implode(", ", $category);
        }

        $masterFiles = $masterFiles->get()->groupBy(['vendor_name', 'group']);

        // dd($masterFiles);

        foreach ($masterFiles as $vendor => $categories) {
            // dd($items);

            foreach ($categories as $category => $items) {
                // dd($items);

                $test = $items->map(function ($item) use ($stores) {

                    // dd($item);

                    $itemStores = [];
                    $totalQty = 0;

                    foreach ($stores as $store) {
                        $storeName = array_values($store)[0];
                        $storeAcroname = $store['acroname'];
                        // dd($item->uom);
                        $qty = TblNavCountdata::where([
                            ['itemcode', $item->item_code],
                            ['uom', 'LIKE', "%$item->uom%"],
                            ['business_unit', $storeName]
                        ])->sum('qty');

                        $itemStores[$storeAcroname] = $qty;
                        $totalQty += $qty;
                    }
                    // }




                    // $uom = TblNavCountdata::where('itemcode', $item->item_code)->first()->uom;

                    // $item->uom = $uom;
                    $item->totalQty = $totalQty;
                    $item->stores = $itemStores;


                    return $item;
                });
            }
        }


        // dd($masterFiles);
        $header = array(
            // 'vendors' => $vendors,
            'category' => $category,
            'user' => auth()->user()->name,
            'userBu' =>  auth()->user()->business_unit,
            'userDept' => auth()->user()->department,
            'userSection' => auth()->user()->section,
            'user_position' => auth()->user()->position,
            'date' => $printDate,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'data' => $masterFiles
        );
        // dd($header);


        return $header;


        // $itemStores = [];
        // $totalQty = 0;
        // foreach ($stores as $store) {

        //     $storeName = array_values($store)[0];
        //     $storeAcroname = $store['acroname'];

        //     $qty = TblNavCountdata::where([
        //         ['itemcode', $item->item_code],
        //         ['business_unit', $storeName]
        //     ])->sum('qty');

        //     $itemStores[$storeAcroname] = $qty;
        //     $totalQty += $qty;
        // }
        // $item->totalQty = $totalQty;
        // $item->stores = $itemStores;

        // return $item;
    }

    public function getResultPcountCost()
    {
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();

        $query = TblAppCountdata::selectRaw('tbl_app_countdata.itemcode, 
        tbl_app_countdata.barcode,
        tbl_app_countdata.description, 
        tbl_item_masterfile.extended_desc,
        tbl_app_countdata.uom, 
        tbl_nav_countdata.uom as nav_uom,
        SUM(tbl_app_countdata.qty) as qty,
        SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
        rack_desc, 
        empno,
        datetime_scanned,
        datetime_saved,
        tbl_nav_countdata.cost_no_vat as cost_novat,
        tbl_nav_countdata.cost_vat as cost_vat,
        tbl_app_countdata.qty * cost_no_vat as tot_novat')
            ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.barcode', '=', 'tbl_app_countdata.barcode')
            ->JOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode');

        // dd(1);

        if ($bu != 'null') {
            $query->WHERE('tbl_app_countdata.business_unit',  'LIKE', "%$bu%");
        }

        if ($dept != 'null') {
            $query->WHERE('tbl_app_countdata.department', 'LIKE', "%$dept%");
        }

        if ($section != 'null') {
            $query->WHERE('tbl_app_countdata.section', 'LIKE', "%$section%");
        }
        if ($vendors) {
            $vendors = explode('|', $vendors);
            $query = $query->whereIn('vendor_name', $vendors);
        }
        if ($category) {
            $category = explode('|', $category);
            $query = $query->whereIn('group', $category);
        }

        return $query->whereBetween('datetime_saved', [$date, $dateAsOf])->groupBy('barcode')->orderBy('itemcode')->paginate(10);
    }

    public function generatePcountCost()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $pdf = PDF::loadView('reports.pcount_cost', ['data' => $this->dataPcountCost()]);
        return $pdf->setPaper('legal', 'landscape')->download('PCountCost.pdf');
    }

    public function generatePcountCostExcel()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        session(['data' => $this->dataPcountCost()]);
        return Excel::download(new PcountAppCountCost, 'invoices.xlsx');
    }

    public function dataPcountCost()
    {
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $result = TblAppCountdata::selectRaw('
        tbl_app_countdata.id,
        tbl_app_countdata.itemcode, 
        tbl_app_countdata.barcode, 
        tbl_app_countdata.description, 
        tbl_item_masterfile.extended_desc,
        tbl_app_countdata.uom, 
        tbl_nav_countdata.uom as nav_uom,
        SUM(tbl_app_countdata.qty) as total_qty,
        SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
        rack_desc,
        empno,
        datetime_scanned,
        datetime_saved,
        datetime_exported,
        tbl_nav_countdata.cost_vat as cost_vat,
        tbl_nav_countdata.cost_no_vat as cost_novat,
        tbl_app_user.name AS app_user,
        tbl_app_user.position AS app_user_position,
        tbl_app_countdata.user_signature as app_user_sign,
        tbl_app_audit.name AS audit_user,
        tbl_app_audit.position AS audit_position,
        tbl_app_countdata.audit_signature AS audit_user_sign,
        vendor_name, 
        tbl_item_masterfile.group
        ')
            ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.barcode', '=', 'tbl_app_countdata.barcode')
            ->JOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode')
            ->join('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_countdata.location_id')
            ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('id');

        if ($bu != 'null') {
            $result->WHERE('tbl_app_countdata.business_unit',  'LIKE', "%$bu%");
        }

        if ($dept != 'null') {
            $result->WHERE('tbl_app_countdata.department', 'LIKE', "%$dept%");
        }

        if ($section != 'null') {
            $result->WHERE('tbl_app_countdata.section', 'LIKE', "%$section%");
        }

        if ($vendors) {
            $vendors = explode('|', $vendors);
            $result = $result->whereIn('vendor_name', $vendors);
            $vendors = implode(", ", $vendors);
        }

        if ($category) {
            $category = explode('|', $category);
            $result = $result->whereIn('group', $category);
            $category = implode(", ", $category);
        }

        $result = $result->GroupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray();
        // dd($result);
        // ->cursor()->map(function ($data) {
        //     // dd($countData);
        //     // dd($data);
        //     return array(
        //         "id"  => $data->id,
        //         "itemcode"  => $data->itemcode,
        //         "barcode"  => $data->barcode,
        //         "description"  => $data->description,
        //         "uom"  => $data->uom,
        //         "qty"  => $data->total_qty,
        //         "rack_desc"  => $data->rack_desc,
        //         "cost_vat" => $data->cost_vat,
        //         "cost_novat" => $data->cost_novat,
        //         "tot_novat" => $data->tot_novat,
        //         "empno"  => $data->empno,
        //         "datetime_scanned"  => $data->datetime_scanned,
        //         "datetime_saved"  => $data->datetime_saved,
        //         "datetime_exported"  => $data->datetime_exported
        //     );
        // })->toArray();

        $header = array(
            'company' => $company,
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            'vendors' => $vendors,
            'category' => $category,
            'date' => $printDate,
            'user' => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'data' => $result
        );

        // dd($header);
        return $header;
    }
}
