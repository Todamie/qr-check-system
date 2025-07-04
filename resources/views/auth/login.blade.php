<x-form-layout title="Вход">
    <div class="login__wrapper">
        <form method="post" action="/login" class="login__form">
            @csrf
            <h2 class="form__title">Вход</h2>
            <a href="/auth/redirect" class="btn btn-primary">Вход через Корпоративную Учетную запись</a>
            <p style="text-align: center; color: gray;">или</p>
            <input class="input" type="email" placeholder="Почта" name="email" id="email" required >
            <input class="input" type="password" placeholder="Пароль" name="password" id="password" required>
            <div class="form__btns">
                <button class="btn btn-primary" type="submit">Войти</button>
                <a href="/register" style="text-decoration: underline; text-align: center;">Регистрация</a>
            </div>
            <x-messages :success="session('success')" :error="session('error')" />
        </form>
    </div>
</x-form-layout>