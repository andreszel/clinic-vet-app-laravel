<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', $appTitle)</title>
</head>

<body class="antialiased">

    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
    <h1>Page 1</h1>
    <div>
        Helo - file PDF
    </div>

    <div class="page-break"></div>

    <h1>Page 2</h1>
    <div>
        Helo - file PDF
    </div>
</body>

</html>