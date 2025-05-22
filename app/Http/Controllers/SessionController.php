<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view("auth.login");
    }

    public function store()
    {
        // validate
        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        // отправляем данные для логина, делаем проверку
        if (!Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Не правильная почта или пароль',
            ]);
        }

        // генерируем новый токен авторизации - для безопасности
        request()->session()->regenerate();

        $userid = Auth::user()->id;
        $user = User::findOrFail($userid);
        $loginTime = Carbon::now()->setTimezone('Asia/Yekaterinburg');
        
        $user->update([
            'last_login' => $loginTime,
        ]);

        return redirect('/');
    }

    public function destroy()
    {
        Auth::logout();
        return redirect("/");
    }
}
