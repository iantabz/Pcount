<?php

namespace App\Http\Controllers;

use \PDF;
use App\Exports\PcountAppCountData;
use App\Exports\PcountDamageExport;
use App\Models\TblAppCountdata;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PhysicalCountController extends Controller
{
    public function getResults()
    {
        $user = auth()->user()->id;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();

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
        date_expiry
        ')
            ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.barcode', '=', 'tbl_app_countdata.barcode')
            ->LEFTJOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode');

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
            $vendors = explode('|', $vendors);
            $query = $query->whereIn('vendor_name', $vendors);
        }
        if ($category) {
            $category = explode('|', $category);
            $query = $query->whereIn('group', $category);
        }

        // dd($dateAsOf);

        return $query->whereBetween('datetime_saved', [$date, $dateAsOf])->groupBy('barcode')->orderBy('itemcode')->paginate(10);
        // return TblAppCountdata::where([
        //     ['business_unit', 'LIKE', "%$bu%"],
        //     ['department', 'LIKE', "%$dept%"]
        //     // ['section', '=', $section]
        // ])
        //     ->whereBetween('datetime_saved', [$date, $date2])
        //     ->paginate(10);
    }

    public function generate()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $pdf = PDF::loadView('reports.pcount_app', ['data' => $this->data()]);
        return $pdf->setPaper('legal', 'landscape')->download('PCount From App.pdf');
    }

    public function generateAppDataExcel()
    {
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
                tbl_app_countdata.audit_signature AS audit_user_sign,
                vendor_name, 
                tbl_item_masterfile.group
                ')
            ->join('tbl_app_user', 'tbl_app_user.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_app_audit', 'tbl_app_audit.location_id', 'tbl_app_countdata.location_id')
            ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
            ->LEFTJOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', '=', 'tbl_app_countdata.itemcode')
            ->LEFTJOIN('tbl_app_nfitem', 'tbl_app_nfitem.barcode', 'tbl_app_countdata.barcode')
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

        dd($result);

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
}
