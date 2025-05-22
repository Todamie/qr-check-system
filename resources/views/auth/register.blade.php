<x-form-layout title="Регистрация">
    <div class="login__wrapper">
        <form method="post" action="/register" class="login__form">
            @csrf
            <h2 class="form__title">Регистрация</h2>
            <input class="input" type="text" name="first_name" id="first_name" placeholder="Имя" :value="old('first_name')" required>
            <input class="input" type="text" name="last_name" id="last_name" placeholder="Фамилия" :value="old('last_name')" required>
            <input class="input" type="email" name="email" id="email" placeholder="Почта" :value="old('email')" required>
            <input class="input" type="password" name="password" id="password" placeholder="Пароль" required>
            <input class="input" type="password" name="password_confirmation" id="password_confirmation"
                placeholder="Подтверждение пароля" required>
            <div class="form__btns">
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                <a href="/login" class="btn btn-secondary">Назад</a>
            </div>
            @error('email')
                <p class="error__text">{{ $message }}</p>
            @enderror
        </form>
    </div>
</x-form-layout>