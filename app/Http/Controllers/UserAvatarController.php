<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
    /**
     * @param Request $request
     * @return bool|string
     */
    public function update(Request $request): bool|string
    {
        // будет сохранен как storage/app/avatars/L6ceL...xzXFw.jpeg
//            $path = $request->file('avatar')->store('avatars');
        return $request->file('avatar')->storeAs(
            'avatars', // директория, куда сохранять
            $request->user()->id, // имя файла
            'public' // диск, куда сохранять
        );
    }
}
