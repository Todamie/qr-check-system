<x-admin>
    <h3 class="admin__section__title text-[9.9px]">Управление пользователями</h3>

    <x-form-search target='/admin/users/' sortBy="{{ $sortBy }}"
        sortOrder="{{ $sortOrder }}">
        <input type="search" name="query" placeholder="Поиск по всем полям" value="{{ request('query') }}">
        <input type="search" name="searchLastName" placeholder="Фамилия"
            value="{{ request('searchLastName') }}">
        <input type="search" name="searchFirstName" placeholder="Имя"
            value="{{ request('searchFirstName') }}">
        <input type="search" name="searchEmail" placeholder="Email" value="{{ request('searchEmail') }}">
        <input type="search" name="searchGroup" placeholder="Группа" value="{{ request('searchGroup') }}">
        <input type="search" name="searchDepartment" placeholder="Подразделение"
            value="{{ request('searchDepartment') }}">
    </x-form-search>

    <p class="user__counter">Всего пользователей в системе: <span style="font-weight: 600;">{{ $userCount }}</span>
    </p>
    <br>
    <div class="admin__section__content">
        <table class="table users__table">
            <thead>
                <tr>
                    <th><x-sortBy target='/admin/users/' name='last_name' sortBy='last_name'
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Фамилия</x-sortBy> /
                        <x-sortBy target='/admin/users/' name='first_name' sortBy='first_name'
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Имя</x-sortBy>
                    </th>

                    <th><x-sortBy target='/admin/users/' name="email" sortBy="email" sortOrder="{{ $sortOrder }}"
                            page="{{ $page }}" :params="$params">Email</x-sortBy></th>
                    <th><x-sortBy target='/admin/users/' name="group" sortBy="group" sortOrder="{{ $sortOrder }}"
                            page="{{ $page }}" :params="$params">Группа / Отдел</x-sortBy></th>
                    <th><x-sortBy target='/admin/users/' name="department" sortBy="department"
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Подразделение</x-sortBy></th>
                    <th><x-sortBy target='/admin/users/' name="type" sortBy="type" sortOrder="{{ $sortOrder }}"
                            page="{{ $page }}" :params="$params">Аутентификация</x-sortBy></th>
                    <th><x-sortBy target='/admin/users/' name="employee" sortBy="employee"
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Роль</x-sortBy></th>
                    <th><x-sortBy target='/admin/users/' name="admin" sortBy="admin" sortOrder="{{ $sortOrder }}"
                            page="{{ $page }}" :params="$params">Админ</x-sortBy></th>
                    <th><x-sortBy target='/admin/users/' name="last_login" sortBy="last_login"
                            sortOrder="{{ $sortOrder }}" page="{{ $page }}" :params="$params">Последний вход</x-sortBy></th>

                </tr>
            </thead>
            <tbody>


                @foreach ($users as $user)
                    <tr>
                        <th>{{ $user->last_name . ' ' . $user->first_name }}</th>
                        <th>{{ $user->email }}</a></th>
                        <th>{{ $user->group }}</th>
                        <th>{{ $user->department }}</th>

                        @if ($user->type == true)
                            <th>SSO</th>
                        @else
                            <th>Локальная</th>
                        @endif

                        @if ($user->isStudent())
                            <th>Студент</th>
                        @elseif ($user->isEmployee())
                            <th>Сотрудник</th>
                        @else
                            <th>Не назначена</th>
                        @endif
                        @if ($user->admin)
                            <th>
                                <p
                                    style="color: white; font-weight:600; background-color: rgb(96, 140, 236); width: 80%; border-radius: .5rem; margin-inline: auto; padding: 5px 0;">
                                    Да</p>
                            </th>
                        @else
                            <th>Нет</th>
                        @endif
                        @if (date('d-m-Y, H:i', strtotime($user->last_login)) == date('d-m-Y, H:i', strtotime('1970-01-01 00:00')))
                            <th>Никогда</th>
                        @else
                            <th>{{ date('d-m-Y, H:i', strtotime($user->last_login)) }}</th>
                        @endif
                        <th><a href="{{ url('/admin/users/edit', $user->id) }}">
                                <img src="{{ asset('edit.svg') }}" alt="" id="menuBtn"></a></th>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>

    <div class="desktop__paginate" style="margin-top: 10px;">
        {{ $users1->paginate(20)->onEachSide(1) }}
    </div>

    <div class="mobile__paginate" style="margin-top: 10px;">
        {{ $users1->simplePaginate() }}
    </div>

</x-admin>
