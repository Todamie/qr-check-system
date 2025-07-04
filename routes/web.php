<?php

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ManualAttendanceController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SSOController;
use App\Http\Controllers\UserController;
use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    if (! Auth::user()) {
        return redirect("/login");
    }

    if (Auth::user()->roles()->where('name', 'employee')->exists()) {
        return view("qr", [
            'user' => Auth::user(),
            'date' => Carbon::now()->startOfDay()->timestamp
        ]);
    }

    if (Auth::user()->roles()->where('name', 'student')->exists()) {
        return view("scanner");
    }
});

Route::get("/login", function () {
    if (! Auth::user()) {
        return view('auth.login');
    } else {
        return redirect('/');
    }
});

Route::get('/register', function () {
    if (! Auth::user()) {
        return view('auth.register');
    } else {
        return redirect('/');
    }
});

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [SessionController::class, 'store'])->name('login');

Route::get('/auth/redirect', function () {
    return Socialite::driver('keycloak')->redirect();
});

Route::get('/auth/callback', [SSOController::class, 'login']);
Route::get('/logout', [SSOController::class, 'logout']);

Route::get('/lesson/confirm/{teacherid}', [LessonController::class, 'confirm'])->middleware('auth');
// Маршрут для отметки посещения
Route::post('/lesson/mark/{teacherid}', [LessonController::class, 'store'])->middleware('auth');

// Отображение пользователя
Route::get('/user/{id}', [UserController::class,  'show'])->middleware('auth');


// АДМИНКА - РАБОТА С ПОЛЬЗОВАТЕЛЯМИ
Route::get('/admin', function(){
    return redirect('/admin/users');
})->middleware('auth', 'admin');

Route::get('/admin/users', [UserController::class, 'index'])->middleware('auth', 'admin');
Route::get('/admin/attendance', [LessonController::class, 'index'])->middleware('auth', 'admin');

Route::get('/admin/users/edit/{id}', function ($id) {
    $user = User::findOrFail($id);
    return view('admin.users.edit', [
        'user' => $user,
        'last_login' => date('d-m-Y, H:i', strtotime($user->last_login)) ?? 'Никогда'
    ]);
})->middleware('auth', 'admin');

Route::post('/admin/users/update/{id}', [UserController::class, 'update'])->middleware('auth', 'admin');

Route::delete('/admin/users/delete/{id}', function ($id) {
    $user = User::findOrFail($id);
    if ($user->email == 'admin@tyuiu.ru') {
        abort(403);
    }
    $user->delete();
    return redirect('/admin/users')->with('success', 'Пользователь удален!');
})->middleware('auth', 'admin');

Route::get('/admin/update-groups', [UserController::class, 'updateGroups'])->middleware('auth', 'admin');

Route::post('/admin/update-groups', [UserController::class, 'updateGroupsPost'])->middleware('auth', 'admin');


// ДЛЯ СОТРУДНИКОВ
Route::get('/employee/attendance', [LessonController::class, 'index_employee'])->middleware('auth');

Route::get('/employee/manual-attendance', [ManualAttendanceController::class, 'manual_attend'])->middleware('auth');
Route::post('/employee/manual-attendance', [ManualAttendanceController::class, 'manual_attend_post'])->middleware('auth');
Route::get('/manual-attendance/get-lessons/{student_id}', [ManualAttendanceController::class, 'getLessonsByStudent']);

// ДЛЯ СТУДЕНТОВ
Route::get('/student/attendance', [LessonController::class, 'index_student'])->middleware('auth', 'student');
