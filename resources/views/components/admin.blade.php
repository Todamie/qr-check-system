<x-layout>
    <x-slot:heading>Администрирование</x-slot:heading>
    <div class="container__admin">
        <section class="admin">
            <div class="header__admin">
                <h2>Администрирование</h2>
            </div>
            <div class="admin__wrapper">
                <aside class="admin__aside">
                    <ul>
                        <li><a href="/admin/users">Управление пользователями</a></li>
                        <li><a href="/admin/attendance">Просмотр посещаемости</a></li>
                        
                        @if (Auth::user()->employee)
                            <li><a href="/employee/attendance">Просмотр посещаемости как сотрудника</a></li>
                        @endif

                        @if (Auth::user()->student)
                            <li><a href="/student/attendance">Просмотр посещаемости как студента</a></li>
                        @endif
                        
                        <li><a href="/admin/update-groups">Обновление групп</a></li>
                    </ul>
                </aside>
                <section class="admin__section">
                    {{ $slot }}
                </section>
            </div>
        </section>
    </div>
</x-layout>