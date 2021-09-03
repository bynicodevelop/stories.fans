<x-app-layout>
    <div class="divide-y divide-x-2 divide-gray-100">
        <x-list-tile href="{{ route('invitation.index') }}" class="flex justify-between">
            <span>@lang('navigation.invitation')</span>
            <i class="fas fa-chevron-right text-gray-500"></i>
        </x-list-tile>

        <x-list-tile href="{{ route('profile.show') }}" class="flex justify-between">
            <span>@lang('navigation.profile')</span>
            <i class="fas fa-chevron-right text-gray-500"></i>
        </x-list-tile>

        <x-list-tile href="{{ route('plans') }}" class="flex justify-between">
            <span>@lang('navigation.plan')</span>
            <i class="fas fa-chevron-right text-gray-500"></i>
        </x-list-tile>

        <x-list-tile href="{{ route('card') }}" class="flex justify-between">
            <span>@lang('navigation.card')</span>
            <i class="fas fa-chevron-right text-gray-500"></i>
        </x-list-tile>

        <x-list-tile href="{{ route('subscriptions') }}" class="flex justify-between">
            <span>@lang('navigation.subscriptions')</span>
            <i class="fas fa-chevron-right text-gray-500"></i>
        </x-list-tile>

        <x-list-tile href="{{ route('payments') }}" class="flex justify-between">
            <span>@lang('navigation.payments')</span>
            <i class="fas fa-chevron-right text-gray-500"></i>
        </x-list-tile>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-list-tile href="{{ route('logout') }}" onclick="event.preventDefault();
                this.closest('form').submit();" class="flex justify-between text-red-500">
                <span>@lang('navigation.logout')</span>
                <i class="fas fa-chevron-right text-red-500"></i>
            </x-list-tile>
        </form>
    </div>

    <x-footer-component class="absolute bottom-0 inset-x-0" />
</x-app-layout>
