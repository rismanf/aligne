<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-fav.webp') }}" media="(prefers-color-scheme: light)"
        type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-fav.webp') }}" media="(prefers-color-scheme: dark)"
        type="image/x-icon" />
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200">
    {{ $slot }}
</body>

</html>
