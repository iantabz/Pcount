<?php

namespace App\Http\Controllers;

use \PDF;
use Carbon\Carbon;
use App\Models\TblUnposted;
use App\Exports\VarianceNav;
use Illuminate\Http\Request;
use App\Models\TblAppCountdata;
use App\Models\TblNavCountdata;
use Maatwebsite\Excel\Facades\Excel;

class ExportsController extends Controller
{
    public function exportVariance()
    {
        // session(['data' => $this->data()]);
        return Excel::download(new VarianceNav, 'export.xlsx');
    }

    public function NavUnposted()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        // $domPdfOptions = new Options();
        // dd($domPdfOptions);

        $pdf = PDF::loadView('reports.variance_nav_unposted', ['data' => $this->varianceReportData()]);
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
            $vendors = explode(' , ', $vendors);
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

        $result = $result->groupBy('barcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']);

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

                    $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                        ['itemcode', $c->itemcode],
                        ['business_unit', $bu],
                        ['department', $dept],
                        ['section', $section]
                    ])->groupBy('itemcode');

                    if ($y->exists()) {
                        $c->unposted = $y->first()->unposted;
                    } else {
                        $c->unposted = '-';
                    }

                    return $c;
                });
            }
        }

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
}
