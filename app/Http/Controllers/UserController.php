<?php

namespace App\Http\Controllers;

use App\Models\User;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

use LdapRecord\Container;
use LdapRecord\Connection;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

class UserController extends Controller
{
    public function create()
    {
        return view("auth.register");
    }

    public function store()
    {
        // валидация
        $attributes = request()->validate(
            [
                'first_name' => ['required'],
                'last_name' => ['required'],
                'email' => ['required', 'email', 'max:254', 'unique:' . User::class],
                'password' => ['required', Password::min(8), 'confirmed'], // password_confirmation
            ],
            [
                'email.unique' => 'Пользователь с таким email уже существует'
            ]
        );

        // Добавляем student = 1
        $attributes['student'] = 1;

        // create user
        $user = User::create($attributes);

        // log in
        Auth::login($user);

        // redirect somewhere
        return redirect('/');
    }

    public function index(Request $request)
    {
        // Получаем значение из строки запроса
        $query = $request->input('query');
        $sortBy = $request->input('sortBy') ?? 'last_name';
        $sortOrder = $request->input('sortOrder') ?? 'asc';
        $page = $request->input('page') ?? 1;

        // Если есть запрос, фильтруем пользователей
        $users = User::when($query, function ($q) use ($query) {
            return $q->where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->orWhere('group', 'LIKE', "%{$query}%");
        })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(20);

        $userCount = User::count();

        return view('admin.users', [
            'users' => $users,
            'userCount' => $userCount,
            'page' => $page,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        if ($user->type == true && !empty(($request->only(['password']))['password'])) {
            throw ValidationException::withMessages([
                'password' => 'Невозможно обновить пароль у учетной записи SSO',
            ]);
        } else {
            $user->update(request()->only(['first_name', 'last_name', 'email', 'student', 'employee', 'admin', 'password', 'group', 'department']));
        }
        return redirect('/admin/users/')->with('success', 'Пользователь обновлен!');
    }

    public function updateGroups()
    {
        return view('admin.update-groups');
    }

    // Запускается по кнопке "Обновить привязку к группам в админке"
    public function updateGroupsPost()
    {
        $users = User::where('student', 1)
            ->where('type', 1)
            ->get();

        foreach ($users as $user) {
            $group = $this->getLdapUserGroupStudent($user->email);
            $user->department = explode('=', explode(",", $group[0])[2])[1];
            $user->group = explode('=', explode(",", $group[0])[0])[1];
            $user->save();
        }

        $employers = User::where('employee', 1)
            ->where('type', 1)
            ->get();

        $pattern = '/(?<![^\p{L}])\d+(?![^\p{L}])|\(\d+\)/u';
        foreach ($employers as $employee) {
            $group = $this->getLdapUserGroupEmployee($employee->email);
            $employee->department = preg_replace($pattern, '', explode('=', explode(",", $group[0])[2])[1]);
            $employee->group = preg_replace($pattern, '', explode('=', explode(",", $group[0])[0])[1]);
            $employee->save();
        }

        return redirect('/admin/users');
    }

    // Используется в SSOController при входе в учетку впервые
    public function updateGroupsPostByEmail($email)
    {
        $user = User::where('student', 1)
            ->where('email', $email)
            ->where('type', 1)
            ->first();

        if (!empty($user)) {
            $group = $this->getLdapUserGroupStudent($user->email);
            $user->department = explode('=', explode(",", $group[0])[2])[1];
            $user->group = explode('=', explode(",", $group[0])[0])[1];
            $user->save();
        }


        $employee = User::where('employee', 1)
            ->where('email', $email)
            ->where('type', 1)
            ->first();

        if (!empty($employee)) {
            $pattern = '/(?<![^\p{L}])\d+(?![^\p{L}])|\(\d+\)/u';

            $group = $this->getLdapUserGroupEmployee($employee->email);
            $employee->department = preg_replace($pattern, '', explode('=', explode(",", $group[0])[2])[1]);
            $employee->group = preg_replace($pattern, '', explode('=', explode(",", $group[0])[0])[1]);
            $employee->save();
        }

        return redirect('/admin/users');
    }

    private function getLdapUserGroupStudent($email)
    {
        try {
            // Параметры подключения
            $ldap_host = env('LDAP_HOST');
            $ldap_port = env('LDAP_PORT', 389);
            $ldap_dn = env('LDAP_BASE_DN');
            $ldap_username = env('LDAP_USERNAME');
            $ldap_password = env('LDAP_PASSWORD');

            // Подключение к LDAP серверу
            $ldap_conn = ldap_connect($ldap_host, $ldap_port);

            if (!$ldap_conn) {
                throw new \Exception('Не удалось подключиться к LDAP серверу');
            }

            // Установка версии протокола
            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

            // Привязка к LDAP серверу
            $bind = ldap_bind($ldap_conn, $ldap_username, $ldap_password);

            if (!$bind) {
                throw new \Exception('Не удалось выполнить привязку к LDAP серверу');
            }

            // Поиск пользователя по email
            $filter = "(mail=$email)";
            $result = ldap_search($ldap_conn, $ldap_dn, $filter);

            if (!$result) {
                return null;
            }

            $entries = ldap_get_entries($ldap_conn, $result);

            if ($entries['count'] == 0) {
                return null;
            }

            // Получаем группы пользователя
            $memberOf = $entries[0]['memberof'] ?? null;

            // Закрываем соединение
            ldap_close($ldap_conn);

            return $memberOf;
        } catch (\Exception $e) {
            Log::error('LDAP Error: ' . $e->getMessage());
            return null;
        }
    }

    private function getLdapUserGroupEmployee($email)
    {
        try {
            // Параметры подключения
            $ldap_host = env('LDAP_HOST_EMPLOYEE');
            $ldap_port = env('LDAP_PORT', 389);
            $ldap_dn = env('LDAP_BASE_DN_EMPLOYEE');
            $ldap_username = env('LDAP_USERNAME_EMPLOYEE');
            $ldap_password = env('LDAP_PASSWORD');

            // Подключение к LDAP серверу
            $ldap_conn = ldap_connect($ldap_host, $ldap_port);

            if (!$ldap_conn) {
                throw new \Exception('Не удалось подключиться к LDAP серверу');
            }

            // Установка версии протокола
            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

            // Привязка к LDAP серверу
            $bind = ldap_bind($ldap_conn, $ldap_username, $ldap_password);

            if (!$bind) {
                throw new \Exception('Не удалось выполнить привязку к LDAP серверу');
            }

            $email = explode('@', $email)[0];

            // Поиск пользователя по email
            $filter = "(sAMAccountName=$email)";
            $result = ldap_search($ldap_conn, $ldap_dn, $filter);

            if (!$result) {
                return null;
            }

            $entries = ldap_get_entries($ldap_conn, $result);

            if ($entries['count'] == 0) {
                return null;
            }

            // Получаем группы пользователя
            $memberOf = $entries[0]['memberof'] ?? null;

            // Закрываем соединение
            ldap_close($ldap_conn);

            return $memberOf;
        } catch (\Exception $e) {
            Log::error('LDAP Error: ' . $e->getMessage());
            return null;
        }
    }

    public function show($id)
    {
        if ($id == Auth::user()->id || Auth::user()->admin) {
            $user = User::where('id', $id);
            return view('user.show', ['user' => $user]);
        } else {
            abort(403);
        }
    }
}
