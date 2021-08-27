<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body class="bg-gray-100 text-gray-900">
    <main class="font-sans antialiased px-5">
        {{ $slot }}
    </main>
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
</body>

</html>
