<footer {{ $attributes }}>
    <div class="max-w-3xl mx-auto flex flex-col md:flex-row md:items-center justify-center text-center h-32">
        <a class="text-sm mx-3 hover:text-pink-500 hover:underline transition duration-500 ease-in-out mb-2"
            href="{{ route('index') }}">
            © {{ date('Y') }} {{ config('app.name') }}
        </a>
        <a class="text-sm mx-3 hover:text-pink-500 hover:underline transition duration-500 ease-in-out mb-2"
            href="{{ route('terms.show') }}" target="_blank" title="{{ __('footer.terms_of_service') }}">
            @lang('footer.terms_of_service')
        </a>
        <a class="text-sm mx-3 hover:text-pink-500 hover:underline transition duration-500 ease-in-out mb-2"
            href="{{ route('policy.show') }}" target="_blank" title="{{ __('footer.privacy_policy') }}">
            @lang('footer.privacy_policy')
        </a>
    </div>
</footer>
