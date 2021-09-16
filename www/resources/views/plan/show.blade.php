<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10 px-5 py-5 bg-white shadow-md shadow-gray-400 rounded">
        <div class="flex p-1">
            <img class="w-12 h-12 rounded-full" src="{{ $user['profile_photo_url'] }}" />
            <div class="flex-1 ml-3 mt-1">
                <span class="block font-medium text-sm leading-snug dark:text-gray-100">
                    {{ $user['name'] }}
                </span>
                <span class="block font-medium text-base">
                    @if (!empty($plan['content']))
                        {{ $plan['content'] }}
                    @else
                        {{ $user['bio'] }}
                    @endif
                </span>
            </div>
        </div>
    </div>
    <div class="px-5 py-5">
        <div class="w-full mx-auto px-5 pt-8 rounded">
            <div class="max-w-5xl mx-auto md:flex justify-center">
                <div class="md:w-1/4 md:flex md:flex-col pt-5">
                    <div class="text-left w-full flex-grow md:pr-5">
                        <h1 class="text-4xl font-bold mb-5">{{ __('plan.show-title') }}</h1>
                        <h3 class="text-md font-medium mb-5">{!! __('plan.show-description') !!}</h3>
                        <div class="mt-10 flex justify-center">
                            <img src="/logo-stripe.png" alt="{{ __('card.stripe-alt') }}" class="w-80">
                        </div>
                    </div>
                </div>
                <div class="md:w-3/4">
                    <div class="max-w-4xl mx-auto md:flex">
                        <div
                            class="w-full md:w-1/{{ !empty($plan['price_quarterly']) && !empty($plan['price_annually']) ? '3' : '2' }} md:max-w-none bg-white px-8 md:px-10 py-8 md:py-10 mb-3 mx-auto md:my-2 rounded-md shadow-lg shadow-gray-600 md:flex md:flex-col">
                            <div class="w-full flex-grow">
                                <h2 class="text-center font-bold uppercase mb-4">{{ __('plan.monthly') }}</h2>
                                @if (!empty($plan['price_quarterly']) || !empty($plan['price_annually']))
                                    <h3 class="text-center font-bold text-4xl mb-5">
                                        {{ number_format($plan['price_monthly'] / 100, 2) }} €<span
                                            class="text-sm">{{ __('plan.per-month') }}</span>
                                    </h3>
                                @else
                                    <h3 class="text-center font-bold text-4xl md:text-5xl mb-5">
                                        {{ number_format($plan['price_monthly'] / 100, 2) }} €<span
                                            class="text-sm">{{ __('plan.per-month') }}</span>
                                    </h3>
                                @endif

                                <ul class="text-sm mb-8">
                                    <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                        {{ __('plan.full-access') }}
                                    </li>
                                    @if (!empty($plan['day_trial']))
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.day-trial-number', ['days' => $plan['day_trial']]) }}
                                        </li>
                                    @endif
                                    <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                        {{ __('plan.no-engagement') }}
                                    </li>
                                </ul>
                            </div>
                            <div class="w-full">
                                @livewire('plan.subscribe-button', [
                                'plan' => $plan,
                                'period' => \App\Http\Livewire\Plan\SubscribeButton::MONTHLY])
                            </div>
                        </div>
                        @if (!empty($plan['price_quarterly']))
                            <div
                                class="w-full md:w-1/{{ empty($plan['price_annually']) ? '2' : '3' }} md:max-w-none bg-white px-8 md:px-10 py-8 md:py-10 mb-3 mx-auto md:-mx-3 md:mb-0 rounded-md shadow-lg shadow-gray-600 md:relative md:z-50 md:flex md:flex-col">
                                <div class="w-full flex-grow">
                                    <h2 class="text-center font-bold uppercase mb-4">{{ __('plan.quarterly') }}</h2>
                                    <h3 class="text-center font-bold text-4xl md:text-5xl mb-5">
                                        {{ number_format($plan['price_quarterly'] / 100 / 3, 2) }} €<span
                                            class="text-sm">{{ __('plan.per-month') }}</span></h3>
                                    <ul class="text-sm mb-8">
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.full-access') }}
                                        </li>
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.pay-one', ['price' => number_format($plan['price_quarterly'] / 100, 2) . ' €', 'per' => __('common.quarterly')]) }}
                                        </li>
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.offer', ['offer' => number_format(100 - ($plan['price_quarterly'] / ($plan['price_monthly'] * 3)) * 100, 2)]) }}
                                        </li>
                                        @if (!empty($plan['day_trial']))
                                            <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                                {{ __('plan.day-trial-number', ['days' => $plan['day_trial']]) }}
                                            </li>
                                        @endif
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.no-engagement') }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="w-full">
                                    <div class="w-full">
                                        @livewire('plan.subscribe-button', [
                                        'plan' => $plan,
                                        'period' => \App\Http\Livewire\Plan\SubscribeButton::QUARTERLY])
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!empty($plan['price_annually']))
                            <div
                                class="w-full md:w-1/{{ empty($plan['price_quarterly']) ? '2' : '3' }} md:max-w-none bg-white px-8 md:px-10 py-8 md:py-10 mb-3 mx-auto md:my-2 rounded-md shadow-lg shadow-gray-600 md:flex md:flex-col">
                                <div class="w-full flex-grow">
                                    <h2 class="text-center font-bold uppercase mb-4">{{ __('plan.annually') }}</h2>

                                    @if (!empty($plan['price_quarterly']))
                                        <h3 class="text-center font-bold text-4xl mb-5">
                                            {{ number_format($plan['price_annually'] / 100 / 12, 2) }} €<span
                                                class="text-sm">{{ __('plan.per-month') }}</span>
                                        </h3>
                                    @else
                                        <h3 class="text-center font-bold text-4xl md:text-5xl mb-5">
                                            {{ number_format($plan['price_annually'] / 100 / 12, 2) }} €<span
                                                class="text-sm">{{ __('plan.per-month') }}</span>
                                        </h3>
                                    @endif

                                    <ul class="text-sm mb-8">
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.full-access') }}
                                        </li>
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.pay-one', ['price' => number_format($plan['price_annually'] / 100, 2) . ' €', 'per' => __('common.annually')]) }}
                                        </li>
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.offer', ['offer' => number_format(100 - ($plan['price_annually'] / ($plan['price_monthly'] * 12)) * 100, 2)]) }}
                                        </li>
                                        @if (!empty($plan['day_trial']))
                                            <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                                {{ __('plan.day-trial-number', ['days' => $plan['day_trial']]) }}
                                            </li>
                                        @endif
                                        <li class="leading-tight"><i class="fas fa-check text-base"></i>
                                            {{ __('plan.no-engagement') }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="w-full">
                                    <div class="w-full">
                                        @livewire('plan.subscribe-button', [
                                        'plan' => $plan,
                                        'period' => \App\Http\Livewire\Plan\SubscribeButton::ANNUALLY])
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto mt-10 px-5 py-5 text-xs text-gray-700 text-center rounded">
        {!! __('plan.cgv', [
    'policy' => '<a href="' . route('terms.show') . '" target="_blank">' . __('common.terms_of_service') . '</a>',
]) !!}
    </div>
</x-app-layout>
