<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AuthController extends Controller
{
    public function showLoginForm(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function login(Request $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $data = $request->validate([
            "email" => ["required", "email", "string"],
            "password" => ["required"]
        ]);

        if (auth("web")->attempt($data)) {
            session()->flash('success', 'Вы успешно вошли в личный кабинет');
            return redirect(route('catalog.index'));
        }

        return redirect(route('auth.login'))->withErrors(["email" => "Ошибка авторизации, пользователь не найден"]);
    }

    public function logout(): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        auth("web")->logout();
        session()->flash('success', 'Вы вышли из личного кабинета');

        return redirect(route('catalog.index'));
    }
}
