<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/website.css','resources/js/website.js'])
</head>

<body>

<!-- Progress Bar -->
<div id="progress-bar"></div>


@include('partials.website.navbar')

<main>
    @yield('content')
</main>

@include('partials.website.footer')

</body>
</html>