<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TblNavCountdata extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tbl_nav_countdata';

    public function AppCount()
    {
        return $this->hasMany(TblAppCountdata::class, 'itemcode', 'itemcode');
    }

    public function NotInCount()
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


        $result = $this->hasMany(TblAppCountdata::class, 'itemcode', 'itemcode');
        if ($bu != 'null') {
            $result->WHERE('business_unit',  'LIKE', "%$bu%");
        }
        if ($dept != 'null') {
            $result->WHERE('department', 'LIKE', "%$dept%");
        }
        if ($section != 'null') {
            $result->WHERE('section', 'LIKE', "%$section%");
        }

        $result = $result->whereBetween('datetime_saved', [$date, $dateAsOf]);

        return $result;
    }
}
