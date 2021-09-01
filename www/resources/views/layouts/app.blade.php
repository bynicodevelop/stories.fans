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
            websocket_url: "{{ config('app.websocket_url') }}"
        }
    </script>

    @livewireStyles

    @stack('styles')

    <!-- Scripts -->
    <script src="{{ config('app.websocket_url') }}/socket.io/socket.io.js"></script>

    <script src="{{ mix('js/app.js') }}" defer></script>

    @if (!empty(config('tabmanager.tag_manager_id')))
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ config('tabmanager.tag_manager_id') }}');
        </script>
        <!-- End Google Tag Manager -->
    @endif
</head>

<body class="font-sans antialiased">
    @if (!empty(config('tabmanager.tag_manager_id')))
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('tabmanager.tag_manager_id') }}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

    <x-jet-banner />

    @livewire('alert-component')

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

        @include('cookie-consent::index')

        <footer>
            <div class="max-w-3xl mx-auto flex items-center justify-center h-32">
                <a class="text-sm mx-3 hover:text-pink-500 hover:underline transition duration-500 ease-in-out"
                    href="{{ route('index') }}">
                    © {{ date('Y') }} {{ config('app.name') }}
                </a>
                <a class="text-sm mx-3 hover:text-pink-500 hover:underline transition duration-500 ease-in-out"
                    href="{{ route('terms.show') }}" target="_blank" title="{{ __('footer.terms_of_service') }}">
                    @lang('footer.terms_of_service')
                </a>
                <a class="text-sm mx-3 hover:text-pink-500 hover:underline transition duration-500 ease-in-out"
                    href="{{ route('policy.show') }}" target="_blank" title="{{ __('footer.privacy_policy') }}">
                    @lang('footer.privacy_policy')
                </a>
            </div>
        </footer>
    </div>

    @stack('modals')

    @stack('scripts')

    @livewireScripts
</body>

</html>
