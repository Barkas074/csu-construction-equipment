<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('admin.index');
    }

    /**
     * @param Request $request
     * @param $user
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    protected function authenticated(Request $request, $user): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {

        if ($user->admin) {
            $route = 'admin.index';
            $message = 'Вы успешно вошли в панель управления';
        } else {
            $route = 'user.index';
            $message = 'Вы успешно вошли в личный кабинет';
        }
        session()->flash('success', $message);
        return redirect(route($route));
    }
}
