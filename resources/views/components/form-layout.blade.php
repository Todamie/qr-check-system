<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.svg">
    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/css/reset.css', 'resources/js/app.js'])

</head>
<body class="form__layout">
    {{ $slot }}
</body>
</html>