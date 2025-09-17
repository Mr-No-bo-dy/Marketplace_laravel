<nav x-data="{ open: false }" class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-2 sm:px-4 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <div class="flex flex-1 items-center sm:items-stretch">
                <!-- Logo -->
                <div class="flex flex-shrink-0 items-center">
                    <a href="{{ route('index') }}">
                        <x-site-logo class="block h-8 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:ml-6 md:block">
                    <div class="flex gap-x-2">
                    <x-site-link :href="route('index')" :active="request()->routeIs('index')">
                        {{ __('site-nav.home') }}
                    </x-site-link>
                    <x-site-link :href="route('product')" :active="request()->routeIs('product')">
                        {{ __('site-nav.catalog') }}
                    </x-site-link>
                    @if(isset($seller_id))
                        <x-site-link :href="route('product.my_products')" :active="request()->routeIs('product.my_products')">
                            {{ __('site_profile.myProducts') }}
                        </x-site-link>
                        <x-site-link :href="route('order.my_orders')" :active="request()->routeIs('order.my_orders')">
                            {{ __('site_profile.myOrders') }}
                        </x-site-link>
                    @endif
                    </div>
                </div>
            </div>

            <div class="absolute inset-y-0 right-0 hidden md:flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

                <!-- Language switch -->
                <div class="me-2">
                    @foreach(config('app.available_locales') as $locale)
                        <a class="inline-block me-2 text-gray-400 hover:text-white"
                           href="{{ route('locale.switch', $locale) }}">{{ strtoupper($locale) }}</a>
                    @endforeach
                </div>

                <!-- Cart -->
                <a class="inline-block me-4 text-gray-400 hover:text-white relative"
                    href="{{ route('cart') }}">{{ __('site-nav.cart') }}
                    @if(session('cart.total_quantity'))
                        <span class="absolute -bottom-1 -right-3 text-sm text-white font-bold">{{ session('cart.total_quantity') }}</span>
                    @endif
                </a>

                @if(!isset($seller_name) && !isset($client_name))
                <a class="inline-block ms-3 text-gray-400 hover:text-white"
                    href="{{ route('auth') }}">{{ $seller_name ?? $client_name ?? __('site-nav.signIn') }}</a>
                @else
                <!-- Profile dropdown -->
                <div class="relative ml-3">
                    <div class="me-2">
                        <button type="button"
                            class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only"></span>
                            <span
                                class="inline-block text-base text-gray-400 hover:text-white">{{ $seller_name ?? $client_name ?? __('site-nav.profile') }}</span>
                                <div class="ml-1 pt-1">
                                    <svg class="fill-gray-400 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                        </button>
                    </div>

                    <!-- Dropdown menu, show/hide based on menu state. -->
                    <div class="hidden absolute top-7 right-0 z-10 mt-3 w-40 py-1 origin-top-right rounded-md bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <!-- Active: "bg-gray-100", Not Active: "" -->
                        <a href="{{ route('auth') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700"
                            role="menuitem" tabindex="-1" id="user-menu-item-0">{{ __('site-nav.profile') }}</a>
                        <form class="hover:bg-gray-700" action="{{ route('log_out') }}" method="POST">
                            @csrf
                            <button class="block w-full text-left px-4 py-2 text-sm text-gray-300"
                                type="submit">{{ __('site-nav.logout') }}</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="absolute inset-y-0 right-0 flex items-center md:hidden">
                <div class="-me-2 flex items-center md:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="ms-3 pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('index')" :active="request()->routeIs('index')">
                {{ __('site-nav.home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('product')" :active="request()->routeIs('product')">
                {{ __('site-nav.catalog') }}
            </x-responsive-nav-link>
            @if(isset($seller_id))
                <x-responsive-nav-link :href="route('product.my_products')" :active="request()->routeIs('product.my_products')">
                    {{ __('site_profile.myProducts') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('order.my_orders')" :active="request()->routeIs('order.my_orders')">
                    {{ __('site_profile.myOrders') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div>
                @foreach(config('app.available_locales') as $locale)
                    <a class="inline-block pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600"
                       href="{{ route('locale.switch', $locale) }}">{{ strtoupper($locale) }}</a>
                @endforeach
            </div>

            <a class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600"
               href="{{ route('cart') }}">{{ __('site-nav.cart') }} ({{ session('cart.total_quantity') ?? '0' }})</a>

            @if(!isset($seller_name) && !isset($client_name))
                <a class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600"
                   href="{{ route('auth') }}">{{ $seller_name ?? $client_name ?? __('site-nav.signIn') }}</a>
            @else
                <div class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600">
                    <span class="font-medium text-base text-gray-600">{{ $seller_name ?? $client_name ?? __('site-nav.signIn') }}</span>
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('auth')">
                        {{ __('site-nav.profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('log_out') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('log_out')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('site-nav.logout') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endif
        </div>
    </div>
</nav>
