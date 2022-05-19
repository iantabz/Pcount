<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Models\TblNavCountdata;

class InventoryValuationController extends Controller
{
    public function getResults()
    {
        $company = Request()->company;
        $bu = request()->bu;
        $dept = request()->dept;
        $section = request()->section;
        $date = Carbon::parse(base64_decode(request()->date))->startOfDay()->toDateTimeString();
        $dateAsOf = Carbon::parse(base64_decode(request()->date))->endOfDay()->toDateTimeString();
        $printDate = Carbon::parse(base64_decode(request()->date))->toFormattedDateString();
        $runDate = Carbon::parse(now())->toFormattedDateString();
        $runTime =  Carbon::parse(now())->format('h:i A');

        $query = TblNavCountdata::where([
            ['company', $company],
            ['business_unit', $bu],
            ['department', $dept],
            ['section', $section],
            ['date', $date],
            ['qty', '<=', 0]
        ])->orderBy('itemcode');

        if (request()->has('forExport')) {
            set_time_limit(0);
            ini_set('memory_limit', '-1');

            $data = $query->get()->all();;
            $query = array(
                'company' => $company,
                'business_unit' => $bu,
                'department' => $dept,
                'section' => $section,
                'date' => $printDate,
                'user' => auth()->user()->name,
                'user_position' => auth()->user()->position,
                'runDate'   => $runDate,
                'runTime'    => $runTime,
                'data' => $data
            );
        } else {
            $query = $query->paginate(10);
        }

        return $query;
    }

    public function generate()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $export = json_decode(base64_decode(request()->export), true);
        // dd($export);

        $pdf = PDF::loadView('reports.inventory_valuation_variance_report', ['data' => $export]);
        return $pdf->setPaper('legal', 'landscape')->download('PCount From App.pdf');
    }
}
