<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <script>
        window.configJS = {
            websocket_url: "{{ config('app.websocket_url') }}",
            disable_context_menu: "{{ config('app.disable_context_menu') }}",
        }
    </script>

    @livewireStyles

    @stack('styles')

    <!-- Scripts -->
    <script src="{{ mix('js/videojs.js') }}"></script>
    <script src="{{ config('app.websocket_url') }}/socket.io/socket.io.js" defer></script>

    <script src="{{ mix('js/app.js') }}" defer></script>

    @if (!empty(config('app.analytics')))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.analytics') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', '{{ config('app.analytics') }}');
        </script>
    @endif
</head>

<body class="font-sans antialiased">
    <x-jet-banner />

    @livewire('alert-component')

    <div class="min-h-screen bg-gray-100">
        {{-- design settings page condition --}}
        <div class="{{ Route::currentRouteName() == 'settings' ? 'mb-0' : 'mb-10' }}">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
        </div>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        @include('cookie-consent::index')

        <x-footer-component class="hidden md:block" />
    </div>

    @stack('modals')

    @stack('scripts')

    @livewireScripts
</body>

</html>
