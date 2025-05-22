<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heading ?? 'Главная' }}</title>

    @vite(['resources/css/app.css', 'resources/css/reset.css', 'resources/js/app.js'])
</head>

<body class="layout">
    <header>
        <x-dashboard></x-dashboard>
    </header>
    <main>
        <section class="main__section">
            {{ $slot }}
        </section>

        <x-mobile-dashboard></x-mobile-dashboard>
        
    </main>
    <footer class="footer">
        <p>2025 &copy; Отдел веб-разработки ТИУ</p>
    </footer>
</body>

</html>
