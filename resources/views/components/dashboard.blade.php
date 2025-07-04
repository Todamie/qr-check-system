<div class="dashboard">
    <div class="container">
        <div class="dash__wrapper">
            <div class="dash__logo">
                <a href="https://tyuiu.ru"><img src="{{ asset('logo-small.svg') }}" alt="tyuiu logo"></a>
            </div>
            <div class="user">
                <div class="user__wrapper">
                    <p class="user__name" data-active>{{ Auth::user()->last_name . ' ' . Auth::user()->first_name }}</p>
                    <div class="user__avatar"><img src="{{ asset('user.svg') }}" alt=""></div>
                </div>

                <!-- mobile -->
                <button class="user-menu__btn"><img class="user-menu__icon" src="{{ asset('user-menu.svg') }}"
                        alt=""></button>
                        
                <div class="dash__menu">
                    <ul>
                        <li><a href="/"><span><img class="menu__img" src="{{ asset('home.svg') }}"
                                        alt=""></span>Главная</a></li>
                        <li><a href="/user/{{ auth()->user()->id }}"><span><img class="menu__img" src="{{ asset('user-menu-item.svg') }}"
                                        alt=""></span>Личный кабинет</a></li>
                        @auth
                            @if (Auth::user()->isAdmin())
                                <li><a href="/admin/users"><span><img class="menu__img" src="{{ asset('settings.svg') }}"
                                                alt=""></span>Администрирование</a></li>
                                <li><a href="/admin/attendance"><span><img class="menu__img"
                                                src="{{ asset('calendar.svg') }}" alt=""></span>Посещаемость</a>
                                </li>
                            @endif

                            @if (Auth::user()->isEmployee() && !(Auth::user()->isAdmin()))
                                <li><a href="/employee/attendance"><span><img class="menu__img"
                                                src="{{ asset('calendar.svg') }}" alt=""></span>Посещаемость</a>
                                </li>
                            @endif

                            @if (Auth::user()->isStudent() && !(Auth::user()->isAdmin()))
                                <li><a href="/student/attendance"><span><img class="menu__img"
                                                src="{{ asset('calendar.svg') }}" alt=""></span>Посещаемость</a>
                                </li>
                            @endif

                            <li><a href="/logout"><span><img class="menu__img" src="{{ asset('logout.svg') }}"
                                            alt="" class="logout__img"></span>Выход</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
