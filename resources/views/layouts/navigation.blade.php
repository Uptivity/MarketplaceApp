@php
    use App\Models\SiteSetting;
    $navbarColor = SiteSetting::get('navbar_color', '#3b82f6');
    $textColor = '#ffffff'; // White text for contrast
@endphp

<nav x-data="{ open: false }" style="background-color: {{ $navbarColor }};" class="border-b border-gray-100 shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('public.products.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

          <!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-white/20">
        {{ __('Dashboard') }}
    </x-nav-link>
    
    @auth
        <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.index')" class="text-white hover:bg-white/20 flex items-center">
            <span>{{ __('Notifications') }}</span>
            @if(Auth::user()->unread_notifications_count > 0)
                <span class="ml-1 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full">
                    {{ Auth::user()->unread_notifications_count }}
                </span>
            @endif
        </x-nav-link>
    @endauth

    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')" class="text-white hover:bg-white/20">
        {{ __('My Products') }}
    </x-nav-link>

    @if(Auth::check() && Auth::user()->role === 'seller')
        <x-nav-link :href="route('seller.orders.index')" :active="request()->routeIs('seller.orders.index')" class="text-white hover:bg-white/20">
            {{ __('Seller Orders') }}
        </x-nav-link>
    @endif

    @if(Auth::check() && Auth::user()->role === 'buyer')
        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index')" class="text-white hover:bg-white/20">
            {{ __('My Orders') }}
        </x-nav-link>
    @endif

    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')" class="text-white hover:bg-white/20">
        {{ __('Cart') }}
    </x-nav-link>

    @if(Auth::check() && Auth::user()->role === 'admin')
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:bg-white/20">
            {{ __('Admin') }}
        </x-nav-link>
    @endif
</div>


            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <@auth
								<div>{{ Auth::user()->name }}</div>
							@endauth

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
			@auth
				<x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.index')">
					{{ __('My Orders') }}
				</x-nav-link>
			@endauth
			@auth
				@if(Auth::user()->role === 'seller')
					<x-nav-link :href="route('seller.orders.index')" :active="request()->routeIs('seller.orders.index')">
						{{ __('Seller Orders') }}
			</x-nav-link>
    @endif
@endauth

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
				@auth
					<div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
					<div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
					<x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
					{{ __('View Cart') }}
					</x-nav-link>
                    <x-nav-link :href="route('seller.orders.index')" :active="request()->routeIs('seller.orders.index')">
                    {{ __('Seller Orders') }}
					</x-nav-link>

		
				@endauth
			</div>


            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
