<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Admin Panel')</title>
    @vite(['resources/css/admin.css','resources/js/admin.js'])
</head>

<body class="bg-gray-100">

@include('partials.admin.sidebar')

<div class="ml-64">

    @include('partials.admin.topbar')

    <main class="p-6">
        @yield('content')
    </main>

</div>

</body>
</html>