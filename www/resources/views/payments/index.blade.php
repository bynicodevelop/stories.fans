<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('payments.title') }}
        </h2>
    </x-slot>


    <div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white py-10 px-10 rounded divide-y">
            <div class="flex p-1 mb-5">
                <div class="flex-1 ml-3 mt-1 text-center">
                    <h2 class="text-3xl pb-2 uppercase text-gray-500">Total gains</h2>
                    <h3 class="text-6xl font-black">
                        @price([
                        'price' =>
                        $amount,
                        'period' => \App\Models\Plan::PRICE_MONTHLY
                        ]) €
                    </h3>
                </div>
            </div>
            <div class="flex p-1 pt-5">
                <div class="flex-1 ml-3 mt-1 text-center">
                    <h2 class="text-base pb-1 uppercase text-gray-500">Gains du mois</h2>
                    <h3 class="text-3xl font-black">
                        @price([
                        'price' =>
                        $profitCurrentPeriod,
                        'period' => \App\Models\Plan::PRICE_MONTHLY
                        ]) €
                    </h3>
                </div>
                <div class="flex-1 ml-3 mt-1 text-center">
                    <h2 class="text-base pb-1 uppercase text-gray-500">Gains payable</h2>
                    <h3 class="text-3xl font-black">
                        @price([
                        'price' =>
                        $payableAmount,
                        'period' => \App\Models\Plan::PRICE_MONTHLY
                        ]) €
                    </h3>
                </div>
            </div>
        </div>

        @if (count($payments) == 0)
            <div class="bg-white rounded p-6 text-center">
                <p>{{ __('payments.empty') }}</p>
            </div>
        @else

            @foreach ($payments as $payment)
                <div class="bg-white p-2 rounded">
                    <div class="flex p-1">
                        <div class="flex-1 ml-3 mt-1">
                            <span class="block font-medium text-base leading-snug text-black dark:text-gray-100">
                                {{ __('payments.profite') }} @price([
                                'price' =>
                                $payment['net_price'],
                                'period' => $payment['userSubscription']['price_period']
                                ]) €
                            </span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400 font-light leading-snug italic">
                                {{ __('payments.plan_price') }} @price([
                                'price' =>
                                $payment['userSubscription']['plan'][$payment['userSubscription']['price_period']],
                                'period' => $payment['userSubscription']['price_period']
                                ]) € - {{ __('payments.fees') }} @price([
                                'price' =>
                                $payment['userSubscription']['plan'][$payment['userSubscription']['price_period']] -
                                $payment['net_price'],
                                'period' => $payment['userSubscription']['price_period']
                                ]) €
                            </span>
                        </div>

                        <div class="pt-3">
                            <span class="block text-sm text-gray-500 dark:text-gray-400 font-light leading-snug">
                                {{ $payment['created_at']->diffForHumans() }}
                            </span>
                        </div>

                    </div>
                </div>
            @endforeach
        @endempty
</div>
</x-app-layout>
