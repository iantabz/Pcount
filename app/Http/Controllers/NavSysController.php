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

        // $result = TblAppCountdata::selectRaw('
        //         tbl_app_countdata.id,
        //         tbl_app_countdata.itemcode, 
        //         tbl_app_countdata.barcode, 
        //         tbl_item_masterfile.extended_desc,
        //         tbl_app_countdata.uom, 
        //         SUM(tbl_app_countdata.qty) as app_qty,
        //         SUM(tbl_app_countdata.conversion_qty) as total_conv_qty,
        //         vendor_name,
        //         tbl_item_masterfile.group
        // ')
        //     ->join('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
        //     ->join('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', 'tbl_app_countdata.itemcode')
        //     ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('itemcode');

        $result = TblNavCountdata::selectRaw('
                tbl_nav_countdata.itemcode, 
                tbl_item_masterfile.extended_desc,
                tbl_nav_countdata.uom, 
                vendor_name,
                tbl_item_masterfile.group
        ')
            ->join('tbl_item_masterfile', 'tbl_item_masterfile.item_code', 'tbl_nav_countdata.itemcode')
            ->where('date', $date)
            ->orderBy('itemcode');

        if ($bu != 'null') {
            $result->WHERE('tbl_nav_countdata.business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('tbl_nav_countdata.department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('tbl_nav_countdata.section', 'LIKE', "%$section%");
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

        // dd($result->get());

        // $result = $result->groupBy('barcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']);
        $result = $vendors != null ? $result->groupBy('itemcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']) : $result->groupBy('itemcode')->orderBy('itemcode')->get();
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

    public function getResults()
    {
        // dd(request()->all());
        $user = auth()->user()->id;
        $company = auth()->user()->company;
        $business_unit = auth()->user()->business_unit;
        $department = auth()->user()->department;
        $section = request()->section;
        $vendors = base64_decode(request()->vendors);
        $category = request()->category;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $date2 = Carbon::parse(base64_decode(request()->date2))->endOfDay()->toDateTimeString();
        $bu = request()->bu;
        $dept = request()->dept;

        $result = TblNavCountdata::selectRaw("
        tbl_nav_countdata.itemcode, 
        tbl_item_masterfile.extended_desc,
        tbl_nav_countdata.uom, 
        vendor_name,
        tbl_item_masterfile.group
        ")
            ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.item_code', 'tbl_nav_countdata.itemcode')
            ->where('date', $date)->orderBy('itemcode')
            ->limit(1000);

        // dd($result->limit(10)->get());


        // $result = TblAppCountdata::selectRaw(
        //     'tbl_app_countdata.itemcode, 
        //   tbl_app_countdata.barcode,
        //   tbl_item_masterfile.extended_desc,
        //   tbl_app_countdata.uom, 
        //   SUM(tbl_app_countdata.qty) as app_qty,
        //   SUM(tbl_app_countdata.conversion_qty) as conversion_qty,
        //   vendor_name,
        //   tbl_item_masterfile.group'
        // )
        //     ->JOIN('tbl_item_masterfile', 'tbl_item_masterfile.barcode', 'tbl_app_countdata.barcode')
        //     ->JOIN('tbl_nav_countdata', 'tbl_nav_countdata.itemcode', 'tbl_app_countdata.itemcode')
        //     ->whereBetween('datetime_saved', [$date, $dateAsOf])->orderBy('itemcode');

        if ($bu != 'null') {
            $result->WHERE('tbl_nav_countdata.business_unit',  'LIKE', "%$bu%");
        }

        if ($dept != 'null') {
            $result->WHERE('tbl_nav_countdata.department', 'LIKE', "%$dept%");
        }

        if ($section != 'null') {
            $result->WHERE('tbl_nav_countdata.section', 'LIKE', "%$section%");
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

        // $result = $result->groupBy('barcode')->orderBy('itemcode')->get()->groupBy(['vendor_name', 'group']);
        // dd($result);
        $x = $result->groupByRaw('itemcode')->orderBy('itemcode')->get();
        // dd($x);

        $query = $x->map(function ($c) use ($bu, $dept, $section) {
            // dd($c->itemcode);
            $x = TblNavCountdata::selectRaw("cost_vat, cost_no_vat, amt, SUM(qty) as nav_qty")->where([
                ['itemcode', $c->itemcode],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $section]
            ])->groupBy('itemcode');

            $y = TblUnposted::selectRaw("cost_no_vat, tot_cost_no_vat, SUM(qty) as unposted")->where([
                ['itemcode', $c->itemcode],
                ['business_unit', $bu],
                ['department', $dept],
                ['section', $section]
            ])->groupBy('itemcode');

            // dd($y->get());
            if ($x->exists()) {
                $c->nav_qty = $x->first()->nav_qty;
                $c->cost_vat = $x->first()->cost_vat;
                $c->cost_no_vat = $x->first()->cost_no_vat;
                $c->amt = $x->first()->amt;
            } else {
                $c->nav_qty = '-';
                $c->cost_vat = '-';
                $c->cost_no_vat = '-';
                $c->amt = '-';
            }

            if ($y->exists()) {
                $c->unposted = $y->first()->unposted;
            } else {
                $c->unposted = '-';
            }

            return $c;
        });

        // dd($result->whereBetween('date', [$date, $dateAsOf])
        //     ->groupByRaw('tbl_nav_countdata.itemcode')
        //     ->orderBy('itemcode')->limit(5)->get());
        $data['data'] = $query;
        return $data;
    }
}
