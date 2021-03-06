<x-app-layout>
    <div class="px-5">
        <div id="top" class="flex items-center min-h-screen">
            <div class="max-w-3xl mx-auto mb-20">
                <div class="mb-10">
                    <h1 class="text-5xl font-semibold leading-tight">@lang('home.main-title')</h1>
                    <h2 class="text-3xl font-semibold text-right">@lang('home.secondary-title')</h2>
                </div>

                <div class="mb-10 text-center">
                    <p class="text-xl mb-3">@lang('home.launch-invitation')</p>
                    <p class="text-xl leading-relaxed">@lang('home.do-you-want-invitation')</p>
                </div>

                {{-- <div class="flex flex-wrap justify-evenly">
                    <a class="inline mb-10 md:mb-3" href="https://instagram.com/bystoriesfans" target="_blank">
                        <span class="flex items-center">
                            <svg class="w-9 h-9 inline mr-2" enable-background="new 0 0 24 24" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <linearGradient id="SVGID_1_"
                                    gradientTransform="matrix(0 -1.982 -1.844 0 -132.522 -51.077)"
                                    gradientUnits="userSpaceOnUse" x1="-37.106" x2="-26.555" y1="-72.705" y2="-84.047">
                                    <stop offset="0" stop-color="#fd5" />
                                    <stop offset=".5" stop-color="#ff543e" />
                                    <stop offset="1" stop-color="#c837ab" />
                                </linearGradient>
                                <path
                                    d="m1.5 1.633c-1.886 1.959-1.5 4.04-1.5 10.362 0 5.25-.916 10.513 3.878 11.752 1.497.385 14.761.385 16.256-.002 1.996-.515 3.62-2.134 3.842-4.957.031-.394.031-13.185-.001-13.587-.236-3.007-2.087-4.74-4.526-5.091-.559-.081-.671-.105-3.539-.11-10.173.005-12.403-.448-14.41 1.633z"
                                    fill="url(#SVGID_1_)" />
                                <path
                                    d="m11.998 3.139c-3.631 0-7.079-.323-8.396 3.057-.544 1.396-.465 3.209-.465 5.805 0 2.278-.073 4.419.465 5.804 1.314 3.382 4.79 3.058 8.394 3.058 3.477 0 7.062.362 8.395-3.058.545-1.41.465-3.196.465-5.804 0-3.462.191-5.697-1.488-7.375-1.7-1.7-3.999-1.487-7.374-1.487zm-.794 1.597c7.574-.012 8.538-.854 8.006 10.843-.189 4.137-3.339 3.683-7.211 3.683-7.06 0-7.263-.202-7.263-7.265 0-7.145.56-7.257 6.468-7.263zm5.524 1.471c-.587 0-1.063.476-1.063 1.063s.476 1.063 1.063 1.063 1.063-.476 1.063-1.063-.476-1.063-1.063-1.063zm-4.73 1.243c-2.513 0-4.55 2.038-4.55 4.551s2.037 4.55 4.55 4.55 4.549-2.037 4.549-4.55-2.036-4.551-4.549-4.551zm0 1.597c3.905 0 3.91 5.908 0 5.908-3.904 0-3.91-5.908 0-5.908z"
                                    fill="#fff" />
                            </svg>
                            <span class="inline text-3xl">
                                @lang('home.follow-us')
                            </span>
                        </span>
                    </a>
                    <a class="inline mb-10 md:mb-3" href="https://twitter.com/bystoriesfans">
                        <span class="flex items-center">
                            <svg class="w-9 h-9 inline mr-2" version="1.1" id="Capa_1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                xml:space="preserve">
                                <path style="fill:#03A9F4;" d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016
         c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992
         c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056
         c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152
         c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792
         c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44
         C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568
         C480.224,136.96,497.728,118.496,512,97.248z" />
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                            </svg>
                            <span class="inline text-3xl">
                                @lang('home.follow-us')
                            </span>
                        </span>
                    </a>
                </div> --}}
                {{-- <p class="italic text-center mb-14 mt-5">@lang('home.surprise-in-bio')</p> --}}
                <p class="text-center">
                    <x-default-link-button class="py-3 px-5" href="#next-0">
                        @lang('home.stories-fans-what-it-is')
                    </x-default-link-button>
                </p>
            </div>
        </div>

        <div id="next-0" class="flex items-center md:min-h-screen mb-20">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 mb-10">
                    <div>
                        <h2 class="text-3xl md:text-5xl font-semibold leading-tight mb-6 text-center md:text-left">
                            @lang('home.next-0-title')</h2>
                        <p class="text-lg text-center md:text-left">@lang('home.next-0-sentence-1')</p>
                        <p class="text-lg mb:mb-10 text-center md:text-left">@lang('home.next-0-sentence-2')</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -top-2/4 left-2/4 w-2/4 h-3/4 overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                            style="background-image: url('/images/video-setup.jpg')">
                        </div>

                        <div class="absolute top-2/4 left-2/3 w-2/4 h-3/4 overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                            style="background-image: url('/images/cooking.jpg')">
                        </div>

                        <div class="absolute top-2 left-1/4 w-2/4 h-3/4 overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                            style="background-image: url('/images/coach.jpg')">
                        </div>
                    </div>
                </div>
                <div class="h-60 my-10 md:hidden">
                    <div class="relative">
                        <div class="absolute top-0 right-20 w-32 h-32 overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                            style="background-image: url('/images/video-setup.jpg')">

                        </div>

                        <div class="absolute top-28 right-4 w-32 h-32 overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                            style="background-image: url('/images/cooking.jpg')">

                        </div>

                        <div class="absolute top-16 left-4 w-32 h-32 overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                            style="background-image: url('/images/coach.jpg')">
                        </div>
                    </div>
                </div>
                <p class="text-center">
                    <x-default-link-button class="py-3 px-3" href="#next-1">
                        @lang('home.more')
                    </x-default-link-button>
                </p>
            </div>
        </div>

        <div id="next-1" class="flex items-center md:min-h-screen mb-20">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 md:mb-10">
                    <div class="md:col-span-2 pr-5">
                        <h2 class="text-3xl md:text-5xl font-semibold leading-tight mb-6 text-center md:text-left">
                            @lang('home.next-1-title')</h2>
                        <p class="text-lg text-center md:text-left">@lang('home.next-1-sentence-1')</p>
                        <p class="text-lg md:mb-10 text-center md:text-left">@lang('home.next-1-sentence-2')</p>
                    </div>
                    <div class="flex justify-center">
                        <div class="w-3/4 h-full overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                            style="background-image: url('/images/content.jpg')">
                        </div>
                    </div>
                </div>
                <div class="flex justify-center h-60 my-10 md:hidden">
                    <div class="w-3/4 h-full overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                        style="background-image: url('/images/content.jpg')">
                    </div>
                </div>


                <p class="text-center">
                    <x-default-link-button class="py-3 px-3" href="#next-2">
                        @lang('home.more')
                    </x-default-link-button>
                </p>
            </div>
        </div>

        <div id="next-2" class="flex items-center md:min-h-screen mb-20">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 md:mb-10">
                    <div class="md:col-span-2 pr-5">
                        <h2 class="text-3xl md:text-5xl font-semibold leading-tight mb-6 text-center md:text-left">
                            @lang('home.next-2-title')</h2>
                        <p class="text-lg text-center md:text-left">@lang('home.next-2-sentence-1')</p>
                        <p class="text-lg md:mb-10 text-center md:text-left">@lang('home.next-2-sentence-2')</p>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 w-3/4 h-full bg-contain bg-no-repeat bg-center"
                            style="background-image: url('/images/invitation.png')">

                        </div>
                    </div>
                </div>
                <div class="flex justify-center h-60 my-10 md:hidden">
                    <div class="w-3/4 h-full bg-contain bg-no-repeat bg-center"
                        style="background-image: url('/images/invitation.png')">

                    </div>
                </div>

                <p class="text-center">
                    <x-default-link-button class="py-3 px-3" href="#next-3">
                        @lang('home.more')
                    </x-default-link-button>
                </p>
            </div>
        </div>

        <div id="next-3" class="flex items-center md:min-h-screen mb-20">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-5xl font-semibold leading-tight mb-6 text-center">
                    @lang('home.next-3-title')
                </h2>
                <p class="text-lg text-center">@lang('home.next-3-sentence-1')</p>
                <p class="text-lg mb-10 text-center">@lang('home.next-3-sentence-2')</p>
                <p class="text-center">
                    <x-default-link-button class="py-3 px-3"
                        href="{{ route('register', ['slug' => 'storiesfans']) }}">
                        @lang('home.invitation')
                    </x-default-link-button>
                </p>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (this.getAttribute('href').length > 1) {
                        document.querySelector(this.getAttribute('href')).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }

                });
            });
        </script>

    @endpush
</x-app-layout>
