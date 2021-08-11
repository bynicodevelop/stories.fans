<div class="overflow-hiddens space-y-6">

    @if (count($users) == 0)
        <div class="bg-white rounded p-6 text-center">
            <p>{{ __('subscription.empty') }}</p>
        </div>
    @endif

    @foreach ($users as $user)
        <div class="bg-white p-2 rounded">
            <div class="flex p-1">
                <a href="{{ route('profiles-slug', ['slug' => $user['user']['slug']]) }}">
                    <img class="w-12 h-12 rounded-full" src="{{ $user['user']['profile_photo_url'] }}" />
                </a>
                <div class="flex-1 ml-3 mt-1">
                    <a href="{{ route('profiles-slug', ['slug' => $user['user']['slug']]) }}">
                        <span class="block font-medium text-base leading-snug text-black dark:text-gray-100">
                            {{ $user['user']['name'] }}
                        </span>
                    </a>
                    <span class="block font-medium text-sm text-gray-500">
                        {{ Str::of($user['user']['bio'])->limit(40) }}
                    </span>
                    @if ($user['userSubscription']['cancelled'])
                        <span class="block font-medium text-xs italic">
                            ({!! __('plan.end-period', ['date' => \Carbon\Carbon::parse($user['userSubscription']['ends_at'])->diffForHumans()]) !!})
                        </span>
                    @endif

                    @if (!empty($user['userSubscription']['trial_ends_at']) && !$user['userSubscription']['cancelled'])
                        <span class="block font-medium text-xs italic">
                            ({!! __('plan.trial-period', ['date' => \Carbon\Carbon::parse($user['userSubscription']['trial_ends_at'])->diffForHumans()]) !!})
                        </span>
                    @endif
                </div>

                @if (!$user['userSubscription']['cancelled'])
                    <div class="pt-1 mr-3 text-right">
                        @if ($user['userSubscription']['price_period'] == \App\Models\Plan::PRICE_MONTHLY)
                            @price([
                            'price' => $user['plan'][$user['userSubscription']['price_period']],
                            'period' => \App\Models\Plan::PRICE_MONTHLY
                            ])
                            {{ __('plan.per-month') }}
                            <div class="text-xs italic">
                                {{ __('plan.pay-monthly') }}
                            </div>
                        @endif

                        @if ($user['userSubscription']['price_period'] == \App\Models\Plan::PRICE_QUARTERLY)
                            @price([
                            'price' => $user['plan'][$user['userSubscription']['price_period']],
                            'period' => \App\Models\Plan::PRICE_QUARTERLY
                            ])
                            {{ __('plan.per-month') }}
                            <div class="text-xs italic">
                                {{ __('plan.pay-quarterly') }}
                            </div>
                        @endif

                        @if ($user['userSubscription']['price_period'] == \App\Models\Plan::PRICE_ANNUALLY)
                            @price([
                            'price' => $user['plan'][$user['userSubscription']['price_period']],
                            'period' => \App\Models\Plan::PRICE_ANNUALLY
                            ])
                            {{ __('plan.per-month') }}
                            <div class="text-xs italic">
                                {{ __('plan.pay-annually') }}
                            </div>
                        @endif
                    </div>
                @endif


                <div class="pt-2">
                    @if ($user['userSubscription']['cancelled'])
                        <x-default-link-button class="mt-2"
                            href="{{ route('plans-show', ['userId' => $user['user']['id']]) }}">
                            {{ __('plan.renew') }}
                        </x-default-link-button>
                    @else
                        @livewire('plan.unsubscribe-button', [ 'subscription' => $user['userSubscription'] ])
                    @endif

                </div>
            </div>
        </div>
    @endforeach
</div>
