<x-admin>
    <h3 class="admin__section__title text-[9.9px]">Управление пользователями</h3>
    <form action="{{ url('/admin/users') }}" method="GET">
        <div class="search">
            <input type="search" name="query" placeholder="Поиск пользователей..." value="{{ request('query') }}">
            <button type="submit" class="btn btn-primary">Найти</button>
        </div>
    </form>
    <p class="user__counter">Всего пользователей в системе: <span style="font-weight: 600;">{{ $userCount }}</span>
    </p>
    <br>
    <div class="admin__section__content">
        <table class="table users__table">
            <thead>
                <tr>
                    <th><x-sortBy name="last_name" sortBy="last_name" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Фамилия</x-sortBy> /
                        <x-sortBy name="first_name" sortBy="first_name" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Имя</x-sortBy></th>
                    <!-- <th>Фамилия</th> -->
                    <th><x-sortBy name="email" sortBy="email" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Email</x-sortBy></th>
                    <th><x-sortBy name="group" sortBy="group" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Группа / Отдел</x-sortBy></th>
                    <th><x-sortBy name="department" sortBy="department" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Подразделение</x-sortBy></th>
                    <th><x-sortBy name="type" sortBy="type" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Аутентификация</x-sortBy></th>
                    <th><x-sortBy name="employee" sortBy="employee" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Роль</x-sortBy></th>
                    <th><x-sortBy name="admin" sortBy="admin" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Админ</x-sortBy></th>
                    <th><x-sortBy name="last_login" sortBy="last_login" sortOrder="{{ $sortOrder }}" page="{{ $page }}">Последний вход</x-sortBy></th>

                </tr>
            </thead>
            <tbody>


                @foreach ($users as $user)
                    <tr>
                        <th>{{ $user->last_name . ' ' . $user->first_name}}</th>
                        <th>{{ $user->email }}</a></th>
                        <th>{{ $user->group }}</th>
                        <th>{{ $user->department }}</th>
                        
                        @if ($user->type == true)
                        <th>SSO</th>
                        @else
                        <th>Локальная</th>
                        @endif

                        @if ($user->student)
                            <th>Студент</th>
                        @elseif ($user->employee)
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

    <div style="margin-top: 10px;">
        {{ $users->links() }}
    </div>
    
</x-admin>
