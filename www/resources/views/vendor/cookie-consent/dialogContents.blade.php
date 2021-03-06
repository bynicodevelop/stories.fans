<div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-2 z-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-2 rounded-lg bg-gray-800">
            <div class="flex items-center justify-between flex-wrap">
                <div class="md:w-0 md:flex-1 md:items-center md:inline">
                    <p class="ml-3 text-white cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="flex justify-end mt-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto">
                    <x-default-link-button class="js-cookie-consent-agree cookie-consent__agree">
                        {{ trans('cookie-consent::texts.agree') }}</x-default-link-button>
                </div>
            </div>
        </div>
    </div>
</div>
