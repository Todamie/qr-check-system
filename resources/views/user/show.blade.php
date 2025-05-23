<x-layout>
    @php
        $user = auth()->user();
    @endphp
    <div class="container__admin">
        <section class="admin">
            <div class="header__admin">
                <h2>Личный кабинет</h2>
            </div>

            <form action="" method="POST">
                <div class="user__info">
                    <label class="label" for="first_name">Имя:</label>
                    <input readonly class="input" type="text" name="first_name" id="first_name" placeholder="Имя"
                        value="{{ $user->first_name }}">

                    <label class="label" for="last_name">Фамилия:</label>
                    <input readonly class="input" type="text" name="last_name" id="last_name" placeholder="Фамилия"
                        value="{{ $user->last_name }}">

                    <label class="label" for="email">Email:</label>
                    <input readonly class="input" type="email" name="email" id="email" placeholder="Email"
                        value="{{ $user->email }}">

                    <label class="label" for="group">Группа:</label>
                    <input readonly class="input" type="text" name="group" id="group" placeholder="Группа"
                        value="{{ $user->group }}">

                    <label class="label" for="department">Подразделение:</label>
                    <input readonly class="input" type="text" name="department" id="department"
                        placeholder="Подразделение" value="{{ $user->department }}">
                </div>

                <p style="font-size: .9rem; font-weight: 600; margin-bottom: 1rem;">Роль:</p>
                <ul class="role__list" style="display: flex; gap: .5rem; flex-wrap: wrap;">
                    @if (auth()->user()->employee)
                        <li style="background-color: #2b67ea; color: #fff; padding: .7rem 1rem;
                            border-radius: .5rem;">Сотрудник</li>
                    @endif
                    @if (auth()->user()->student)
                        <li style="background-color:rgb(12, 148, 57); color: #fff; padding: .7rem 1rem;
                                border-radius: .5rem;">Студент</li>
                    @endif
                    @if (auth()->user()->admin)
                        <li style="background-color:rgb(0, 0, 0); color: #fff; padding: .7rem 1rem;
                            border-radius: .5rem;">Администратор</li>
                    @endif
                </ul>

            </form>
        </section>
    </div>
    
    <div class="container__admin__mobile">
        <section class="admin">
            <div class="header__admin">
                <h2>Личный кабинет</h2>
            </div>

            <form action="" method="POST">
                <div class="user__info">
                    <label class="label" for="first_name">Имя:</label>
                    <input readonly class="input" type="text" name="first_name" id="first_name" placeholder="Имя"
                        value="{{ $user->first_name }}">

                    <label class="label" for="last_name">Фамилия:</label>
                    <input readonly class="input" type="text" name="last_name" id="last_name" placeholder="Фамилия"
                        value="{{ $user->last_name }}">

                    <label class="label" for="email">Email:</label>
                    <input readonly class="input" type="email" name="email" id="email" placeholder="Email"
                        value="{{ $user->email }}">

                    <label class="label" for="group">Группа:</label>
                    <input readonly class="input" type="text" name="group" id="group" placeholder="Группа"
                        value="{{ $user->group }}">

                    <label class="label" for="department">Подразделение:</label>
                    <input readonly class="input" type="text" name="department" id="department"
                        placeholder="Подразделение" value="{{ $user->department }}">
                </div>

                <p style="font-size: .9rem; font-weight: 600; margin-bottom: 1rem;">Роль:</p>
                <ul class="role__list" style="display: flex; gap: .5rem; flex-wrap: wrap;">
                    @if (auth()->user()->employee)
                        <li style="background-color: #2b67ea; color: #fff; padding: .7rem 1rem;
                            border-radius: .5rem;">Сотрудник</li>
                    @endif
                    @if (auth()->user()->student)
                        <li style="background-color:rgb(12, 148, 57); color: #fff; padding: .7rem 1rem;
                                border-radius: .5rem;">Студент</li>
                    @endif
                    @if (auth()->user()->admin)
                        <li style="background-color:rgb(0, 0, 0); color: #fff; padding: .7rem 1rem;
                            border-radius: .5rem;">Администратор</li>
                    @endif
                </ul>

            </form>
        </section>
    </div>
</x-layout>