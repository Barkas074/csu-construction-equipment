<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $roots = DB::table('brands')->get();
        return view('brand.index', compact('roots'));
    }
}
