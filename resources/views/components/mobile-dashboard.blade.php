<div class="user__menu__mobile">
    <div class="mobile__dashboard">
        <p>{{ Auth::user()->last_name . ' ' . Auth::user()->first_name }}</p>
        <button class="btn__close"><img src="{{ asset('close.svg') }}" alt=""></button>
    </div>
    <ul class="menu__list__mobile">
        <li class="list__item__mobile"><a href="/"><span><img class="menu__img" src="{{ asset('home.svg') }}"
                        alt=""></span>Главная</a></li>
        <li class="list__item__mobile"><a href="/user/{{ auth()->user()->id }}"><span><img class="menu__img"
                        src="{{ asset('user-menu-item.svg') }}" alt=""></span>Личный кабинет</a></li>

        @auth
            @if (auth()->user()->admin)
                <li class="list__item__mobile"><a href="/admin/users"><span><img class="menu__img"
                                src="{{ asset('settings.svg') }}" alt=""></span>Администрирование</a></li>
                <li class="list__item__mobile"><a href="/admin/attendance"><span><img class="menu__img"
                                src="{{ asset('calendar.svg') }}" alt=""></span>Посещаемость</a></li>
            @endif

            @if (auth()->user()->employee && !auth()->user()->admin)
                <li class="list__item__mobile"><a href="/employee/attendance"><span><img class="menu__img"
                                src="{{ asset('calendar.svg') }}" alt=""></span>Посещаемость</a></li>
            @endif

            @if (auth()->user()->student && !auth()->user()->admin)
                <li class="list__item__mobile"><a href="/student/attendance"><span><img class="menu__img"
                                src="{{ asset('calendar.svg') }}" alt=""></span>Посещаемость</a></li>
            @endif
        @endauth
        <li class="list__item__mobile"><a href="/logout"><img class="menu__img" src="{{ asset('logout.svg') }}"
                    alt="" class="logout__img">Выход</a></li>
    </ul>
</div>
