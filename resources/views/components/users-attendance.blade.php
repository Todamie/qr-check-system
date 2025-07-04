<x-layout>
    <x-slot:heading>Посещаемость</x-slot:heading>
    <div class="container__admin">
        <section class="admin">
            <div class="admin__wrapper">
                <aside class="admin__aside">
                    <ul>
                        @if (Auth::user()->isAdmin())
                            <li><a href="/admin/users">Управление пользователями</a></li>
                        @endif
                        @if (Auth::user()->isAdmin())
                            <li><a href="/admin/attendance">Просмотр общей посещаемости</a></li>
                        @endif

                        @if (Auth::user()->isEmployee())
                            <li><a href="/employee/attendance">Просмотр посещаемости студентов</a></li>
                        @endif

                        @if (Auth::user()->hasRole('manual_attendance') || Auth::user()->isAdmin())
                            <li><a href="/employee/manual-attendance">Ручная отметка о посещаемости</a></li>
                        @endif

                        @if (Auth::user()->isStudent())
                            <li><a href="/student/attendance">Просмотр посещаемости</a></li>
                        @endif

                        @if (Auth::user()->isAdmin())
                            <li><a href="/admin/update-groups">Обновление групп</a></li>
                        @endif
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
                        @if (Auth::user()->isAdmin())
                            <li class="accordion__list__item"><a href="/admin/users">Управление пользователями</a></li>
                        @endif

                        @if (Auth::user()->isAdmin())
                            <li class="accordion__list__item"><a href="/admin/attendance">Просмотр посещаемости</a></li>
                        @endif

                        @if (Auth::user()->isEmployee())
                            <li class="accordion__list__item"><a href="/employee/attendance">Просмотр посещаемости
                                    студентов</a></li>
                        @endif

                        @if (Auth::user()->hasRole('manual_attendance') || Auth::user()->isAdmin())
                            <li class="accordion__list__item"><a href="/employee/manual-attendance">Ручная отметка о
                                    посещаемости</a></li>
                        @endif

                        @if (Auth::user()->isStudent())
                            <li class="accordion__list__item"><a href="/student/attendance">Просмотр посещаемости</a>
                            </li>
                        @endif

                        @if (Auth::user()->isAdmin())
                            <li class="accordion__list__item"><a href="/admin/update-groups">Обновление групп</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="admin__content">
                {{ $slot }}
            </div>
        </section>
    </div>
</x-layout>
