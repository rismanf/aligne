<!DOCTYPE html>
<html lang="en" style="background-color: rgb(186, 32, 37);">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <script>
        "http" == window.location.href.substr(0, 4) && "/" != window.location.href.slice(-1) && window.location.replace(
            window.location.href + "/");
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&amp;display=swap" rel="stylesheet">
    <title>{{ $title }} Digital Business Card</title>
    <style>
        #body {
            font-family: sans-serif;
        }

        input[type='range']::-moz-range-track {
            background: none;
        }

        input[type='range']::-moz-range-thumb {
            -moz-appearance: none;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 100%;
            border: none;
            background: #ba2025;
            z-index: 3;
            cursor: pointer;
        }

        input[type='range']::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 100%;
            border: none;
            background: #ba2025;
            z-index: 3;
            cursor: pointer;
        }

        .closeColor {
            filter: invert(1)
        }

        .topAction {}

        .iconColor {
            color: #eee;
        }

        .cardColor {
            color: #222 !important
        }

        .textColor {
            color: #222 !important
        }

        .seekbarColor {
            background: #ba202580 !important
        }

        .certificate {

            height: 80px;
        }
    </style> <!---->
    <style>
        #body {}
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/vcard/style.min.css') }}">
</head>

<body id="body">
     {{ $slot }}
</body>

</html>
