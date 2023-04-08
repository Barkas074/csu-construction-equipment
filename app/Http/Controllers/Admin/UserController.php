<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $users = User::paginate(5);
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param User $user
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function edit(User $user): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $roles = User::ROLES;
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     * @throws ValidationException
     */
    public function update(Request $request, User $user): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
    {
        $this->validator($request->all(), $user->id)->validate();

        if ($request->change_password) {
            $request->merge(['password' => Hash::make($request->password)]);
            $user->update($request->all());
        } else {
            $user->update($request->except(['_token', '_method', 'password']));
        }
        session()->flash('Данные пользователя успешно обновлены');
        return redirect(route('admin.user.index'));
    }

    /**
     * @param array $data
     * @param int $id
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data, int $id): \Illuminate\Validation\Validator
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $id . ',id',
            ],
        ];
        if (isset($data['change_password'])) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }
        return Validator::make($data, $rules);
    }
}
