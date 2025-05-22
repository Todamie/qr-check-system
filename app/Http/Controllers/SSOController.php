<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SSOController extends Controller
{
    private $userController = null;

    public function __construct()
    {
        $this->userController = new UserController;
    }


    public function login()
    {

        if (Auth::check()) {
            return redirect('/');
        }

        try {
            // Отключаем проверку SSL для тестового окружения
            $socialite = Socialite::driver('keycloak');
            $socialite->setHttpClient(new Client([
                'verify' => false
            ]));

            // Получаем пользователя из Keycloak
            $keycloakUser = $socialite->user();

            // разделяем name на имя и фамилию
            $kcuser_name = $keycloakUser->getName();
            $kcuser_arr = explode(' ', $kcuser_name);
            $kcuser_first_name = $kcuser_arr[0];
            $kcuser_last_name = $kcuser_arr[1];

            // Ищем пользователя в базе данных или создаем нового
            // проверяем кто заходит, студент или преподаватель

            if (str_contains($keycloakUser->getEmail(), '@tyuiu.ru')) {
                $user = User::firstOrCreate([
                    'first_name' => $kcuser_first_name,
                    'last_name' => $kcuser_last_name,
                    'email' => $keycloakUser->getEmail(),
                    'type' => true,
                    'employee' => 1
                ]);
            } else {
                $user = User::firstOrCreate([
                    'first_name' => $kcuser_first_name,
                    'last_name' => $kcuser_last_name,
                    'email' => $keycloakUser->getEmail(),
                    'type' => true,
                    'student' => 1
                ]);
            }

            // Логиним пользователя в Laravel
            Auth::login($user);

            // Текущее время входа
            $loginTime = Carbon::now()->setTimezone('Asia/Yekaterinburg');

            $user->update([
                'last_login' => $loginTime,
            ]);

            if (empty($user->department) || empty($user->group)) {
                $this->userController->updateGroupsPostByEmail($user->email);
            }

            // Перенаправляем на /
            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Ошибка авторизации: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout(); // Разлогиниваем пользователя из Laravel
        $home_url = url('/');
        // URL выхода из Keycloak
        $keycloakLogoutUrl = "https://sso.tyuiu.ru/realms/master/protocol/openid-connect/logout?post_logout_redirect_uri=$home_url&client_id=educon";

        return redirect($keycloakLogoutUrl);
    }
}
