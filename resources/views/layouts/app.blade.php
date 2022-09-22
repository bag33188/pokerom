@php
    function parsePageTitle(): string {
        $_eacute_html_entity = "&eacute;";
        $appName = config('app.name');
        # return preg_replace("/pok[e\xe9]rom/i", $appName, "Pok" . $_eacute_html_entity . "ROM");
        return str_replace('Poke', $appName, "Pok" . $_eacute_html_entity . "ROM");
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="icon" type="image/x-icon" href="/favicon.ico" />

        <title>{!! parsePageTitle() !!}</title>

        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" />

        <!-- Bundles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Modules -->
        <script type="text/javascript" src="{{ Vite::asset('resources/js/modules/capitalize.js') }}" defer></script>
        <script type="text/javascript" src="{{ Vite::asset('resources/js/modules/ready.js') }}" defer></script>

        @livewireStyles
        <!-- Styles -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
