<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        {{ config('app.name', 'Laravel') }}
    </title>

    <!-- Favicon -->
    <link
        rel="icon"
        type="image/svg+xml"
        href="{{ asset('favicon.svg') }}?v=2"
    >

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    >

    <!-- Scripts -->
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="font-sans antialiased">

    <div
        class="min-h-screen"
        style="background: #f5f3ff;"
    >

        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Content -->
        <main>

            {{--
                Support both Laravel component layouts
                and @extends / @section layouts.
            --}}

            @if(isset($slot))

                {{ $slot }}

            @else

                @yield('content')

            @endif

        </main>

    </div>

</body>

</html>