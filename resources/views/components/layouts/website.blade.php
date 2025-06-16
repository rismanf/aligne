<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="keywords" content="NeutraDC" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-fav.webp') }}" media="(prefers-color-scheme: light)"
        type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-fav.webp') }}" media="(prefers-color-scheme: dark)"
        type="image/x-icon" />

    <!-- title -->
    <!--Same as title field on CMS-->
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <!--Same as title field on CMS-->
    <meta name="title" content="NeutraDC" />
    <!--Same as title field on CMS-->
    <meta property="og:title" content="NeutraDC" />
    <!--Same as title field on CMS-->
    <meta property="twitter:title" content="NeutraDC" />

    <!-- description -->
    <!--Same as description field on CMS-->
    <meta name="description" content="{{ $description }}" />
    <!--Same as description field on CMS-->
    <meta property="og:description" content="{{ $description }}" />
    <!--Same as description field on CMS-->
    <meta property="twitter:description" content="{{ $description }}" />

    <!-- image -->
    <!--Same as featured image url-->
    <meta property="og:image" content="{{ env('APP_URL') }}/assets/img/og-neutra.webp" />
    <!--Same as featured image url-->
    <meta property="twitter:image" content="{{ env('APP_URL') }}/assets/img/og-neutra.webp') }}" />

    <!-- slug url -->
    <!--Same as slug url-->
    <meta property="og:url" content="{{ env('APP_URL') }}" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />

    <!-- gtm -->

    <script type="module" crossorigin src="{{ asset('assets/js/scripts-C8WMqR8i.js') }}"></script>
    <link rel="stylesheet" crossorigin href="{{ asset('assets/css/styles-BcoVOL7o.css') }}" />
</head>

<body>
    <main id="page-home" class="page-home">

        @include('livewire.partials.svg')
        @livewire('public.menu')
        {{ $slot }}

        <!-- gtm noscript -->

        @include('livewire.partials.footer')
    </main>

    <x-toast />
</body>

</html>
