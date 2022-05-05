<?php

namespace App\Http\Controllers;

use App\Exports\VarianceNav;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportsController extends Controller
{
    public function exportVariance()
    {
        // session(['data' => $this->data()]);
        return Excel::download(new VarianceNav, 'export.xlsx');
    }

    public function data()
    {
    }
}
