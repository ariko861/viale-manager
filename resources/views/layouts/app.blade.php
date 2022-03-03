<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->

        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

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
            <livewire:offline-indicator >
            <livewire:information-display >

        </div>

        @stack('modals')

        @livewireScripts
        @livewireCalendarScripts

        <div id="alert-popup" class="fixed top-0 left-0 right-0 invisible text-center mt-10">
            <span></span>
        </div>
        <script>
        Livewire.on('showAlert', message => {

            var alertPopup = document.getElementById("alert-popup");
            alertPopup.getElementsByTagName('span')[0].innerHTML = message[0];
            alertPopup.getElementsByTagName('span')[0].className = message[1] + " p-2 rounded-sm";
            alertPopup.style.visibility = "visible";
            setTimeout(() => alertPopup.style.visibility = "hidden", 3000 );


        });
        </script>
    </body>
</html>
