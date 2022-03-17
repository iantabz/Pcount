<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function dashboard()
    {
        return view('layouts.master');
    }

    public function allowWhat()
    {
        return view('layouts.master');
    }
}
