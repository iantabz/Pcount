<?php

namespace App\Http\Controllers;

use \PDF;
use Carbon\Carbon;
use App\Models\TblUnposted;
use Illuminate\Http\Request;
use App\Models\TblAppCountdata;
use App\Models\TblNavCountdata;

class NavSysController extends Controller
{
    public function NetNavSys()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        // $domPdfOptions = new Options();
        // dd($domPdfOptions);
        // dd(request()->all());

        $pdf = PDF::loadView('reports.net_nav_sys_report', ['data' => $this->varianceReportData()]);
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
        $reportType = request()->type;

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

        // dd($vendors);
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

        // $result = $result->groupBy('barcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']);
        $result = $vendors != null ? $result->groupBy('barcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']) : $result->groupBy('barcode')->orderBy('itemcode')->get();
        // dd($result);

        if ($vendors) {
            $arr = [];

            foreach ($result as $vendor_name => $categories) {
                foreach ($categories as $category => $items) {
                    $arr[$vendor_name][$category] = $items->map(function ($c) use ($bu, $dept, $section, $reportType) {
                        $query = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                            ['itemcode', $c->itemcode],
                            ['business_unit', $bu],
                            ['department', $dept],
                            ['section', $section]
                        ])->groupBy('itemcode');
                        // dd($query->get());

                        $queryResult = $query->exists();
                        $navQty = $queryResult ? $query->first()->nav_qty : '-';
                        $c->nav_qty = $navQty;

                        $y = TblUnposted::selectRaw("SUM(qty) as unposted")->where([
                            ['itemcode', $c->itemcode],
                            ['business_unit', $bu],
                            ['department', $dept],
                            ['section', $section]
                        ])->groupBy('itemcode');

                        $yResult = $y->exists();
                        $unposted = $yResult ? $y->first()->unposted : '-';
                        $c->unposted = $unposted;

                        if ($reportType == 'Variance') {
                            $temp1 = $navQty === '-' ? 0 : $navQty;
                            $temp2 = $unposted === '-' ? 0 : $unposted;

                            $res = $temp1 + $temp2;

                            if ($res < 0) {
                                return $c;
                            }
                            return [];
                        }
                        return $c;
                    })->filter(function ($ar) {
                        return $ar;
                    });
                }
            }

            if ($reportType == 'Variance') {
                $arr = collect($arr)->filter(function ($a) {
                    foreach ($a as $key => $b) {
                        $res[] = !$b->isEmpty();
                    };

                    return in_array(true, $res);
                })->map(function ($b) {
                    // dump($b);
                    return collect($b)->filter(function ($c) {
                        return !$c->isEmpty();
                    });
                })->all();
            }

            $header = array(
                'company'       => $company,
                'business_unit' => $bu,
                'department'    => $dept,
                'section'       => $section,
                'vendors'       => $vendors,
                'category'      => $category,
                'date'          => $printDate,
                'user'          => auth()->user()->name,
                'userBu'        =>  auth()->user()->business_unit,
                'userDept'      => auth()->user()->department,
                'userSection'   => auth()->user()->section,
                'user_position' => auth()->user()->position,
                'runDate'       => $runDate,
                'runTime'       => $runTime,
                'report'        => $reportType,
                'data'          => $arr
            );
        } else {
            $report = 'All';
            $arr = [];

            foreach ($result as $items => $c) {

                $query = TblNavCountdata::selectRaw("SUM(qty) as nav_qty")->where([
                    ['itemcode', $c->itemcode],
                    ['business_unit', $bu],
                    ['department', $dept],
                    ['section', $section]
                ])->groupBy('itemcode');

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

                // return $c;
                // });
            }

            $header = array(
                'company'       => $company,
                'business_unit' => $bu,
                'department'    => $dept,
                'section'       => $section,
                'vendors'       => $vendors,
                'category'      => $category,
                'date'          => $printDate,
                'user'          => auth()->user()->name,
                'userBu'        =>  auth()->user()->business_unit,
                'userDept'      => auth()->user()->department,
                'userSection'   => auth()->user()->section,
                'user_position' => auth()->user()->position,
                'runDate'       => $runDate,
                'runTime'       => $runTime,
                'report'        => $report,
                'data'          => $result
            );
        }

        // dd($header);
        return $header;
    }
}
