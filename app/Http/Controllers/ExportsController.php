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
}
