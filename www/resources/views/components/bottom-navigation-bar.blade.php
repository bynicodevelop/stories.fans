<section id="bottom-navigation" class="z-40 block fixed inset-x-0 bottom-0 bg-white shadow md:hidden">
    <div id="tabs" class="flex justify-between">
        <a href="{{ route('home') }}"
            class="w-full focus:text-teal-500 hover:text-teal-500 justify-center inline-block text-center pt-2 pb-1 {{ Route::currentRouteName() == 'home' ? 'text-pink-500' : '' }}">
            <i class="fas fa-home text-base"></i>
            <span class="tab tab-account block text-sm">@lang('bottom-navigation-bar.home')</span>
        </a>
        <a href="{{ route('editor') }}"
            class="w-full focus:text-teal-500 hover:text-teal-500 justify-center inline-block text-center pt-2 pb-1 {{ Route::currentRouteName() == 'editor' ? 'text-pink-500' : '' }}">
            <i class="fas fa-plus-square text-base"></i>
            <span class="tab tab-account block text-sm">@lang('bottom-navigation-bar.create')</span>
        </a>
        <a href="{{ route('profiles') }}"
            class="w-full focus:text-teal-500 hover:text-teal-500 justify-center inline-block text-center pt-2 pb-1 {{ Route::currentRouteName() == 'profiles' ? 'text-pink-500' : '' }}">
            <i class="fas fa-search text-base"></i>
            <span class="tab tab-account block text-sm">@lang('bottom-navigation-bar.search')</span>
        </a>
    </div>
</section>
