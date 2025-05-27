<x-admin>
    <h3 class="admin__section__title text-[9.9px]">Редактирование пользователя</h3>
    <div class="admin__section__content">

        @error('password')
            <p class="error__text">{{ $message }}</p>
        @enderror

        <form action="{{ url('/admin/users/update', $user->id) }}" method="POST">
            @csrf
            <div class="user__info">

                <label class="label" for="first_name">Имя:</label>
                <input class="input" type="text" name="first_name" id="first_name" placeholder="Имя"
                    value="{{ $user->first_name }}">

                <label class="label" for="last_name">Фамилия:</label>
                <input class="input" type="text" name="last_name" id="last_name" placeholder="Фамилия"
                    value="{{ $user->last_name }}">

                <label class="label" for="email">Email:</label>
                <input class="input" type="email" name="email" id="email" placeholder="Email"
                    value="{{ $user->email }}">

                <label class="label" for="password">Пароль:</label>
                <div class=password>
                    <input class="input" type="text" name="password" id="password" placeholder="Новый пароль">
                    <!-- <img src="{{ asset('show.png') }}" alt=""> -->
                </div>

                <label class="label" for="group">Группа:</label>
                <input class="input" type="text" name="group" id="group" placeholder="Группа"
                    value="{{ $user->group }}">

                <label class="label" for="department">Подразделение:</label>
                <input class="input" type="text" name="department" id="department" placeholder="Подразделение"
                    value="{{ $user->department }}">

                <label class="label">Последний вход:</label>
                @if (date('d-m-Y, H:i', strtotime($user->last_login)) == date('d-m-Y, H:i', strtotime('1970-01-01 00:00')))
                    <th>Никогда</th>
                @else
                    <th>{{ date('d-m-Y, H:i', strtotime($user->last_login)) }}</th>
                @endif
            </div>
            <p>Роль пользователя:</p>
            <ul class="roles">
                <li>
                    <input type="hidden" name="employee" value="0">
                    <input type="checkbox" name="employee" id="employee" value="1"
                        {{ $user->employee ? 'checked' : '' }}>
                    <label for="employee">Сотрудник</label>
                </li>
                <li>
                    <input type="hidden" name="student" value="0">
                    <input type="checkbox" name="student" id="student" value="1"
                        {{ $user->student ? 'checked' : '' }}>
                    <label for="student">Студент</label>
                </li>
                <li>
                    <input type="hidden" name="admin" value="0">
                    <input type="checkbox" name="admin" id="admin" value="1"
                        {{ $user->admin ? 'checked' : '' }}>
                    <label for="admin">Администратор</label>
                </li>
            </ul>
            <div class="btns">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ url('/admin/users') }}" class="btn btn-secondary" style="display: flex; align-items: center;">Назад</a>

                {{-- Чтобы пользователь не мог удалить сам себя --}}
                @auth
                    @if (auth()->user()->email != $user->email && !($user->email == 'admin@tyuiu.ru'))
                        <button form="delete_user_form" type="submit" class="btn btn-delete">Удалить пользователя</button>
                    @endif
                @endauth

            </div>
        </form>
        <form action="{{ url('/admin/users/delete', $user->id) }}" method="POST" name="delete_user_form"
            id="delete_user_form" onsubmit="return confirm('Вы уверены, что хотите удалить пользователя?');">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        // РАБОТА С ЧЕКБОКСАМИ В РЕДАКТОРЕ ПОЛЬЗОВАТЕЛЯ
        const studentCheckbox = document.getElementById("student");
        const employeeCheckbox = document.getElementById("employee");

        function toggleCheckboxes() {
            if (studentCheckbox.checked) {
                employeeCheckbox.disabled = true;
            } else if (employeeCheckbox.checked) {
                studentCheckbox.disabled = true;
            } else {
                studentCheckbox.disabled = false;
                employeeCheckbox.disabled = false;
            }
        }

        studentCheckbox.addEventListener("change", toggleCheckboxes);
        employeeCheckbox.addEventListener("change", toggleCheckboxes);

        toggleCheckboxes();
    </script>

</x-admin>
