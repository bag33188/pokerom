<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }}"/>

        <title>{!! config('app.web_name') !!}</title>

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap"/>

        <!-- Bundles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Global Scripts -->
        <script type="text/javascript" src="{{ Vite::asset('resources/js/modules/capitalize.js') }}"></script>
        <script type="text/javascript" src="{{ Vite::asset('resources/js/modules/ready.js') }}"></script>

        <!-- Stylesheets -->
        <link rel="stylesheet" type="text/css" href="{{ Vite::asset('resources/css/scrollbar.module.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ Vite::asset('resources/css/cloak.module.css') }}" />

        <!-- Styles -->
        @stack('styles')
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        <!-- Scripts -->
        @stack('scripts')
    </body>
</html>
