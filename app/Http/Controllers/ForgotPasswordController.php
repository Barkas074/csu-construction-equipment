<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('auth.forgot');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function forgotPassword(Request $request): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $data = $request->validate([
            "email" => ["required", "email", "string", "exists:users"]
        ]);

        $user = User::where(["email" => $data["email"]])->first();

        $password = uniqid();

        $user->password = bcrypt($password);
        $user->save();

        Mail::to($user)->send(new ForgotPassword($password));

        return redirect(route('auth.completeForgotPassword'));
    }

    public function showCompleteForgotPassword(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('auth.completeForgot');
    }
}
