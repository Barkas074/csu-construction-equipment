<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     * @param Request $request
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function __invoke(Request $request): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('index');
    }
}
