<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')
        @yield('content')
        @include('layouts.partials.rightsidebar')
        @include('layouts.partials.footer')
        @include('layouts.partials.javascript')
    </div>
</body>
</html>
