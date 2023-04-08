<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class RegisterController extends Controller
{
    public function showRegisterForm(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('auth.register');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function register(Request $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $data = $request->validate([
            "name" => ["required", "string"],
            "email" => ["required", "email", "string", "unique:users,email"],
            "password" => ["required", "confirmed"]
        ]);

        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => bcrypt($data["password"])
        ]);

        if ($user) {
            auth("web")->login($user);
            session()->flash('success', 'Регистрация на сайте прошла успешно');
        }

        return redirect(route('catalog.index'));
    }
}
