<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

        <title>{!! config('app.web_name') !!}</title>

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" />

        <!-- Bundles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Global Scripts -->
        <script type="text/javascript" src="{{ Vite::asset('resources/js/modules/capitalize.js') }}"></script>
        <script type="text/javascript" src="{{ Vite::asset('resources/js/modules/ready.js') }}"></script>

        <!-- Stylesheets -->
        <link rel="stylesheet" type="text/css" href="{{ Vite::asset('resources/css/scrollbar.module.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ Vite::asset('resources/css/cloak.module.css') }}" />

        @livewireStyles
        <!-- Styles -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            <livewire:navigation-menu />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <!-- Scripts -->
        @stack('scripts')
    </body>
</html>
