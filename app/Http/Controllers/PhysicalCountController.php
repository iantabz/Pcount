<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\TblAppNfitem;
use App\Exports\ItemsNotFound;
use App\Exports\CountByBackend;
use App\Models\TblAppCountdata;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Exports\PcountAppCountData;
use App\Exports\PcountDamageExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Facade\Ignition\QueryRecorder\Query;

class PhysicalCountController extends Controller
{
    public function getResults()
    {
        $company = auth()->user()->company;
        $user = auth()->user()->id;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $countType = request()->countType;
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $key = implode('-', [$bu, $dept, $section, $date, 'CountData']);


        $query = TblAppCountdata::selectRaw('
        tbl_app_countdata.id,
        tbl_app_countdata.itemcode, 
        tbl_app_countdata.barcode, 
        tbl_app_countdata.description, 
        tbl_item_masterfile.extended_desc,
        tbl_app_countdata.uom, 
        tbl_nav_countdata.uom as nav_uom,
        SUM(tbl_app_countdata.qty) as qty,
        SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
        tbl_app_countdata.rack_desc,
        tbl_app_countdata.empno,
        datetime_scanned,
        datetime_saved,
        datetime_exported,
        date_expiry,
        vendor_name, 
        tbl_item_masterfile.group,
        tbl_app_user.name AS app_user,
        tbl_app_user.position AS app_user_position,
        tbl_app_audit.name AS audit_user,
        tbl_app_audit.position AS audit_position
        ')
            ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.barcode', '=', 'tbl_app_countdata.barcode')
            ->JOIN('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_countdata.location_id')
            ->JOIN('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_countdata.location_id')
            ->LEFTJOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode');

        // dd($query->get());

        if ($bu != 'null') {
            $query->WHERE('tbl_app_countdata.business_unit',  'LIKE', "%$bu%");
        }

        if ($dept != 'null') {
            $query->WHERE('tbl_app_countdata.department', 'LIKE', "%$dept%");
        }

        if ($section != 'null') {
            $query->WHERE('tbl_app_countdata.section', 'LIKE', "%$section%");
        } else {
            $query->WHERE('tbl_app_countdata.section', 'LIKE', "%null%");
        }

        if ($vendors) {
            $vendors = explode(' , ', $vendors);
            $vendors = str_replace("'", "", $vendors);
            $query = $query->whereIn('vendor_name', $vendors);
        }
        if ($category) {
            $category = explode(" , ", $category);
            $category = str_replace("'", "", $category);
            $query = $query->whereIn('group', $category);
        }

        $query = $query->whereBetween('datetime_saved', [$date, $dateAsOf])
            ->groupBy('barcode')
            ->orderBy('itemcode');

        if (request()->has('forExport')) {
            return Cache::remember($key, now()->addMinutes(15), function () use (
                $date,
                $dateAsOf,
                $bu,
                $dept,
                $section,
                $vendors,
                $category,
                $company,
                $printDate,
                $countType,
                $runDate,
                $runTime,
                $query
            ) {

                $data = $query->get()
                    ->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])
                    ->toArray();
                // dd($data);

                $query = array(
                    'company' => $company,
                    'business_unit' => $bu,
                    'department' => $dept,
                    'section' => $section,
                    'vendors' => $vendors,
                    'category' => $category,
                    'date' => $printDate,
                    'countType' => $countType,
                    'user' => auth()->user()->name,
                    'user_position' => auth()->user()->position,
                    'runDate'   => $runDate,
                    'runTime'    => $runTime,
                    'data' => $data
                );
                return $query;
            });
        }


        return  $query->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])
            ->paginate(10);
        // return $query;

        // return $query->whereBetween('datetime_saved', [$date, $dateAsOf])
        //     ->groupBy('barcode')
        //     ->orderBy('itemcode')
        //     // ->get()
        //     ->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])
        //     // ->toArray();
        //     ->paginate(10);
    }

    public function getNotFound()
    {
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();

        // dd($date, $dateAsOf, request()->all());
        return TblAppNfitem::where([
            ['business_unit', 'LIKE', request()->bu],
            ['department', 'LIKE', request()->dept],
            ['section', 'LIKE', request()->section]
        ])
            ->whereBetween('datetime_scanned', [$date, $dateAsOf])->groupBy('barcode')->get();
    }

    public function generate()
    {
        // dd(request()->all());
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $key = implode('-', [$bu, $dept, $section, $date, 'CountData']);
        $data = Cache::get($key);

        // dd($data);

        // $export = json_decode(base64_decode(request()->export), true);
        // dd($export);
        // $export = collect($export)->all();

        // dd(collect($export['data'])->flatten());
        $data['data'] = collect($data['data'])->map(function ($trans) {
            // dd($trans->all());

            $res = array_map(function ($x) {
                return array_map(function ($y) {
                    return array_map(function ($z) {
                        $newArr = [];
                        foreach ($z as $index => $xyz) {
                            if ($index === 0) {
                                $res = TblAppCountdata::where('itemcode', $xyz['itemcode'])->first();
                                $xyz['user_signature'] = $res->user_signature;
                                $xyz['audit_signature'] = $res->audit_signature;
                                $newArr[] = $xyz;
                            } else {
                                $newArr[] = $xyz;
                            }
                        }
                        return $newArr;
                    }, $y);
                }, $x);
            }, $trans);

            return $res;
        })->all();

        // dd($data);


        $pdf = PDF::loadView('reports.pcount_app', ['data' => $data]);
        return $pdf->setPaper('legal', 'landscape')->download('PCount From App.pdf');
    }

    public function generateAppDataExcel()
    {
        // dd(request()->all());
        session(['data' => $this->data()]);
        return Excel::download(new PcountAppCountData, 'invoices.xlsx');
    }

    public function olddata()
    {
        // $trans = request()->data;
        // $array = explode(',', $trans);
        // $data_res = [];

        // foreach ($array as $key => $id) {
        //     $result = TblAppCountdata::where('id', $id)->get();
        //     foreach ($result as $datahead) {
        //         // dd($result);

        //         $data_res[] = array(
        //             "id"  => $datahead->id,
        //             "itemcode"  => $datahead->itemcode,
        //             "barcode"  => $datahead->barcode,
        //             "description"  => $datahead->description,
        //             "uom"  => $datahead->uom,
        //             "qty"  => $datahead->qty,
        //             "business_unit"  => $datahead->business_unit,
        //             "department"  => $datahead->department,
        //             "section"  => $datahead->section,
        //             "rack_desc"  => $datahead->rack_desc,
        //             "empno"  => $datahead->empno,
        //             "datetime_scanned"  => $datahead->datetime_scanned,
        //             "datetime_saved"  => $datahead->datetime_saved,
        //             "datetime_exported"  => $datahead->datetime_exported
        //         );
        //     }
        // }

        // $data = array(
        //     'header' => 'Header',
        //     'data' => $data_res
        // );
        // return $data;
    }

    public function data()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $countType = request()->countType;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
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
                SUM(tbl_app_countdata.qty) as qty,
                SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
                tbl_app_countdata.rack_desc,
                tbl_app_countdata.empno,
                tbl_app_countdata.datetime_scanned,
                tbl_app_countdata.datetime_saved,
                tbl_app_countdata.datetime_exported,
                tbl_app_countdata.date_expiry,
                tbl_app_user.name AS app_user,
                tbl_app_user.position AS app_user_position,
                tbl_app_audit.name AS audit_user,
                tbl_app_audit.position AS audit_position,
                vendor_name, 
                tbl_item_masterfile.group
                ')
            ->join('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
            ->LEFTJOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode')
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
            // $vendors = explode('|', $vendors);
            $vendors = explode(" , ", $vendors);
            $vendors = str_replace("'", "", $vendors);
            $result = $result->whereIn('vendor_name', $vendors);
            $vendors = implode(", ", $vendors);
        }

        if ($category) {
            $category = explode(" , ", $category);
            $category = str_replace("'", "", $category);
            $result = $result->whereIn('group', $category);
            $category = implode(", ", $category);
        }

        // dd($result->groupBy('itemcode')->get()->groupBy(['app_user', 'audit_user']))->toArray();

        $result = $result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray();

        // $appCount = TblAppCountdata::whereIn('itemcode', $result->pluck('itemcode'));
        // $appCount->get()->groupBy('itemcode')->map(function ($data) use ($appCount) {
        //     foreach ($appCount as $key => $value) {
        //         return array(
        //             "id"  => $data->id,
        //             "itemcode"  => $data->itemcode,
        //             "barcode"  => $data->barcode,
        //             "description"  => $data->description,
        //             "uom"  => $data->uom,
        //             "qty"  => $data->total_qty,
        //             "rack_desc"  => $data->rack_desc,
        //             "empno"  => $data->empno,
        //             "datetime_scanned"  => $data->datetime_scanned,
        //             "datetime_saved"  => $data->datetime_saved,
        //             "datetime_exported"  => $data->datetime_exported
        //         );
        //     }
        //     // dump($data);

        //     // return $data;
        // })->toArray();

        // dd($result);

        $header = array(
            'company' => $company,
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            'vendors' => $vendors,
            'category' => $category,
            'date' => $printDate,
            'countType' => $countType,
            'user' => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'data' => $result
        );

        // dd($header);

        return $header;
    }

    public function generateCountDamages()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('reports.pcount_damages', ['data' => $this->dataCountDamages()]);
        return $pdf->setPaper('legal', 'landscape')->download('PCount From App.pdf');
    }

    public function generateCountDamagesEXCEL()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        session(['dataCountDamages' => $this->dataCountDamages()]);
        return Excel::download(new PcountDamageExport, 'invoices.xlsx');
    }

    public function dataCountDamages()
    {
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $countType = request()->countType;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $result = TblAppCountdata::selectRaw('
                tbl_app_countdata.id,
                tbl_app_countdata.itemcode, 
                tbl_app_countdata.barcode, 
                tbl_app_countdata.description,
                tbl_app_countdata.uom, 
                SUM(tbl_app_countdata.qty) as total_qty,
                SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
                tbl_app_countdata.rack_desc,
                tbl_app_countdata.empno,
                tbl_app_countdata.datetime_scanned,
                tbl_app_countdata.datetime_saved,
                tbl_app_countdata.datetime_exported,
                tbl_app_countdata.date_expiry,
                tbl_app_user.name AS app_user,
                tbl_app_user.position AS app_user_position,
                tbl_app_countdata.user_signature as app_user_sign,
                tbl_app_audit.name AS audit_user,
                tbl_app_audit.position AS audit_position,
                tbl_app_countdata.audit_signature AS audit_user_sign
                ')
            // tbl_nav_countdata.uom as nav_uom,
            // vendor_name
            // tbl_item_masterfile.extended_desc,
            // tbl_item_masterfile.group
            ->join('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_countdata.location_id')
            // ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
            // ->join('tbl_item_masterfile', function ($join) {
            //     $join->on('tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode');
            //     $join->orOn('tbl_item_masterfile.extended_desc', 'tbl_app_countdata.description');
            //     // $join->orOn('tbl_item_masterfile.uom', 'tbl_app_countdata.uom');
            // })
            // ->LEFTJOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode')
            ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('itemcode');

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'rack_desc'])->toArray());

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

        $result = $result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'rack_desc'])->toArray();
        $header = array(
            'company' => $company,
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            // 'vendors' => $vendors,
            'category' => $category,
            'date' => $printDate,
            'countType' => $countType,
            'user' => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'data' => $result
        );
        // dd($header['data']['Apa-ap, Bendion Paul Pocot']);

        return $header;
    }

    public function generateNotFound()
    {
        $type = request()->report;
        // dd($type);
        session(['data' => $this->itemsNotFoundData($type)]);
        $data = session()->get('data');
        $pdf = PDF::loadView('reports.pcount_app_notfound', ['data' => $data]);

        // return $type == "ExcelReport" ? Excel::download(new ItemsNotFound, 'invoices.xlsx') : (new ItemsNotFound)->download('invoices.pdf',);
        return $type == "Excel" ? Excel::download(new ItemsNotFound, 'notfound.xlsx') : $pdf->setPaper('legal', 'landscape')->download('notfound.pdf',);
        // return Excel::download(new ItemsNotFound, 'invoices.xlsx');
    }

    public function itemsNotFoundData($type)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $countType = request()->countType;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $result = TblAppNfitem::selectRaw('
        tbl_app_nfitem.barcode,
        tbl_app_nfitem.uom, 
        SUM(tbl_app_nfitem.qty) as total_qty,
        business_unit,
        department,
        tbl_app_nfitem.section,
        datetime_scanned,
        datetime_exported,
        rack_desc,
        tbl_app_user.name AS app_user,
        tbl_app_user.position AS app_user_position,
        tbl_app_nfitem.user_signature as app_user_sign,
        tbl_app_audit.name AS audit_user,
        tbl_app_audit.position AS audit_position,
        tbl_app_nfitem.audit_signature AS audit_user_sign,
        description,
        vendor_name, 
        tbl_item_masterfile.group')
            ->join('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_nfitem.location_id')
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_nfitem.location_id')
            ->leftjoin('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_nfitem.barcode')
            ->whereBetween('datetime_scanned', [$date, $dateAsOf])->orderBy('datetime_scanned');

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray());

        if ($bu != 'null') {
            $result->WHERE('tbl_app_nfitem.business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('tbl_app_nfitem.department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('tbl_app_nfitem.section', 'LIKE', "%$section%");
        }
        // if ($vendors) {
        //     $vendors = explode('|', $vendors);
        //     $result = $result->whereIn('vendor_name', $vendors);
        //     $vendors = implode(", ", $vendors);
        // }

        // if ($category) {
        //     $category = explode('|', $category);
        //     $result = $result->whereIn('group', $category);
        //     $category = implode(", ", $category);
        // }

        // dd($result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group']))->toArray();

        $result = $result->groupBy('barcode')->get()->groupBy(['app_user', 'audit_user', 'vendor_name', 'group'])->toArray();

        // $appCount = TblAppCountdata::whereIn('itemcode', $result->pluck('itemcode'));
        // $appCount->get()->groupBy('itemcode')->map(function ($data) use ($appCount) {
        //     foreach ($appCount as $key => $value) {
        //         return array(
        //             "id"  => $data->id,
        //             "itemcode"  => $data->itemcode,
        //             "barcode"  => $data->barcode,
        //             "description"  => $data->description,
        //             "uom"  => $data->uom,
        //             "qty"  => $data->total_qty,
        //             "rack_desc"  => $data->rack_desc,
        //             "empno"  => $data->empno,
        //             "datetime_scanned"  => $data->datetime_scanned,
        //             "datetime_saved"  => $data->datetime_saved,
        //             "datetime_exported"  => $data->datetime_exported
        //         );
        //     }
        //     // dump($data);

        //     // return $data;
        // })->toArray();

        // dd($result);

        $header = array(
            'company'       => $company,
            'business_unit' => $bu,
            'department'    => $dept,
            'section'       => $section,
            'vendors'       => $vendors,
            'category'      => $category,
            'date'          => $printDate,
            'countType'     => $countType,
            'user'          => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'       => $runDate,
            'runTime'       => $runTime,
            'type'          => $type,
            'data'          => $result
        );

        return $header;
    }

    public function backendCount()
    {
        $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $countType = request()->countType;
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        // $query = TblAppCountdata::selectRaw('
        // tbl_app_countdata.itemcode, 
        // tbl_app_countdata.barcode, 
        // tbl_app_countdata.uom, 
        // tbl_app_countdata.description, 
        // tbl_item_masterfile.extended_desc,
        // SUM(tbl_app_countdata.qty) as qty,
        // SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
        // tbl_app_countdata.rack_desc,
        // tbl_app_countdata.empno,
        // datetime_scanned,
        // datetime_saved,
        // datetime_exported,
        // date_expiry,
        // vendor_name, 
        // tbl_item_masterfile.group,
        // location_id
        // ')
        //     ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.barcode', '=', 'tbl_app_countdata.barcode');

        $query = TblAppNfitem::selectRaw('
            itemcode,
            barcode, 
            description,
            uom, 
            qty,
            nav_qty
        ');

        // dd($query->get());

        if ($bu != 'null')
            $query->WHERE('business_unit',  'LIKE', "%$bu%");


        if ($dept != 'null')
            $query->WHERE('department', 'LIKE', "%$dept%");


        if ($section != 'null') {
            $query->WHERE('section', 'LIKE', "%$section%");
        } else {
            $query->WHERE('section', 'LIKE', "%null%");
        }

        // $query = $query
        //     ->where([['location_id', '=', 0], ['rack_desc', 'LIKE', "%SETUP BY BACKEND%"]])
        //     ->whereBetween('datetime_saved', [$date, $dateAsOf])
        //     ->groupBy('barcode')
        //     ->orderBy('itemcode');
        $query = $query->whereBetween('datetime_scanned', [$date, $dateAsOf]);
        $query = $query->get()
            ->toArray();


        // dd($query);

        return $query;
    }

    public function generateBackendCount()
    {
        // dd(request()->all());
        // $company = auth()->user()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $countType = request()->countType;
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');
        $type = request()->report;

        $export = json_decode(base64_decode(request()->export), true);
        $headers = array(
            'business_unit' => $bu,
            'department' => $dept,
            'section' => $section,
            'vendors' => $vendors,
            'category' => $category,
            'date' => $printDate,
            'countType' => $countType,
            'user' => auth()->user()->name,
            'user_position' => auth()->user()->position,
            'runDate'   => $runDate,
            'runTime'    => $runTime,
            'report' => $type,
            'data' => $export
        );
        session(['data' => $headers]);
        $data = session()->get('data');
        $pdf = PDF::loadView('reports.countdata_backend', ['data' => $data]);

        // return $type == "ExcelReport" ? Excel::download(new ItemsNotFound, 'invoices.xlsx') : (new ItemsNotFound)->download('invoices.pdf',);
        return $type == "Excel" ? Excel::download(new CountByBackend, 'Count by Backend.xlsx') : $pdf->setPaper('legal', 'landscape')->download('Count by Backend.pdf',);
    }
}
