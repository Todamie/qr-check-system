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
                        <li><a href="/employee/manual-attendance">Ручная отметка о посещаемости</a></li>

                        @if (Auth::user()->isEmployee())
                            <li><a href="/employee/attendance">Просмотр посещаемости как сотрудника</a></li>
                        @endif

                        @if (Auth::user()->isStudent())
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
    <div class="container__admin__mobile">
        <section class="admin__mobile">
            <div class="accordion">
                <div class="accordion__header">
                    <p>Администрирование</p>
                </div>
                <div class="accordion__content">
                    <ul class="accordion__list">
                        <li class="accordion__list__item"><a href="/admin/users">Управление пользователями</a></li>
                        <li class="accordion__list__item"><a href="/admin/attendance">Просмотр посещаемости</a></li>
                        <li class="accordion__list__item"><a href="/employee/manual-attendance">Ручная отметка о посещаемости</a></li>

                        @if (Auth::user()->isEmployee())
                            <li class="accordion__list__item"><a href="/employee/attendance">Просмотр посещаемости как
                                    сотрудника</a></li>
                        @endif

                        @if (Auth::user()->isStudent())
                            <li class="accordion__list__item"><a href="/student/attendance">Просмотр посещаемости как
                                    студента</a></li>
                        @endif

                        <li class="accordion__list__item"><a href="/admin/update-groups">Обновление групп</a></li>
                    </ul>
                </div>
            </div>
            <div class="admin__content">
                {{ $slot }}
            </div>
        </section>
    </div>
</x-layout>