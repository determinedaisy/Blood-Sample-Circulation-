<div class="flex justify-between h-16">

    <div class="flex">

        <!-- Logo -->
        <div class="shrink-0 flex items-center">

            <a href="{{ route('dashboard') }}">

                <x-application-logo
                    class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                />

            </a>

        </div>


        <!-- Desktop Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">


            <!-- Dashboard -->
            <x-nav-link
                :href="auth()->user()->role === 'admin'
                    ? route('admin.dashboard')
                    : route('dashboard')"
                :active="request()->routeIs('admin.dashboard') || request()->routeIs('dashboard')"
            >
                {{ __('Dashboard') }}
            </x-nav-link>


            <!-- Blood Samples -->
            <x-nav-link
                :href="route('blood-samples.index')"
                :active="request()->routeIs('blood-samples.*')"
            >
                {{ __('Blood Samples') }}
            </x-nav-link>


            <!-- Inventory -->
            <x-nav-link
                :href="route('inventory.index')"
                :active="request()->routeIs('inventory.*')"
            >
                {{ __('Inventory') }}
            </x-nav-link>


            <!-- Transportation -->
            <x-nav-link
                :href="route('transportation.index')"
                :active="request()->routeIs('transportation.*')"
            >
                {{ __('Transportation') }}
            </x-nav-link>


            <!-- Emergency SOS - Patient Only -->
            @if(auth()->user()->role === 'patient')

                <x-nav-link
                    :href="route('sos.index')"
                    :active="request()->routeIs('sos.*')"
                >
                    🚨 {{ __('Emergency SOS') }}
                </x-nav-link>

            @endif


            <!-- Emergency Priority - Admin & Doctor -->
            @if(in_array(auth()->user()->role, ['admin', 'doctor']))

                <x-nav-link
                    :href="route('admin.emergency-priority')"
                    :active="request()->routeIs('admin.emergency-priority*')"
                >
                    {{ __('Emergency Priority') }}
                </x-nav-link>

            @endif


        </div>

    </div>


    <!-- Settings Dropdown -->
    <div class="hidden sm:flex sm:items-center sm:ms-6">

        <x-dropdown align="right" width="48">

            <x-slot name="trigger">

                <button
                    class="inline-flex items-center px-3 py-2
                           border border-transparent
                           text-sm leading-4 font-medium
                           rounded-md
                           text-gray-500 dark:text-gray-400
                           bg-white dark:bg-gray-800
                           hover:text-gray-700 dark:hover:text-gray-300
                           focus:outline-none
                           transition ease-in-out duration-150"
                >

                    <div>
                        {{ Auth::user()->name }}
                    </div>

                    <div class="ms-1">

                        <svg
                            class="fill-current h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >

                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10
                                10.586l3.293-3.293a1 1
                                0 111.414 1.414l-4 4a1 1
                                01-1.414 0l-4-4a1 1
                                010-1.414z"
                                clip-rule="evenodd"
                            />

                        </svg>

                    </div>

                </button>

            </x-slot>


            <x-slot name="content">

                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>


                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <x-dropdown-link
                        :href="route('logout')"
                        onclick="
                            event.preventDefault();
                            this.closest('form').submit();
                        "
                    >
                        {{ __('Log Out') }}
                    </x-dropdown-link>

                </form>

            </x-slot>

        </x-dropdown>

    </div>


    <!-- Hamburger -->
    <div class="-me-2 flex items-center sm:hidden">

        <button
            @click="open = ! open"
            class="inline-flex items-center justify-center
                   p-2 rounded-md
                   text-gray-400 dark:text-gray-500
                   hover:text-gray-500 dark:hover:text-gray-400"
        >

            <svg
                class="h-6 w-6"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 24 24"
            >

                <path
                    :class="{'hidden': open, 'inline-flex': ! open}"
                    class="inline-flex"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />

                <path
                    :class="{'hidden': ! open, 'inline-flex': open}"
                    class="hidden"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />

            </svg>

        </button>

    </div>

</div>



<!-- Responsive Navigation Menu -->

<div
    :class="{'block': open, 'hidden': ! open}"
    class="hidden sm:hidden"
>


    <div class="pt-2 pb-3 space-y-1">


        <!-- Dashboard -->
        <x-responsive-nav-link
            :href="auth()->user()->role === 'admin'
                ? route('admin.dashboard')
                : route('dashboard')"
            :active="request()->routeIs('admin.dashboard') || request()->routeIs('dashboard')"
        >
            {{ __('Dashboard') }}
        </x-responsive-nav-link>


        <!-- Blood Samples -->
        <x-responsive-nav-link
            :href="route('blood-samples.index')"
            :active="request()->routeIs('blood-samples.*')"
        >
            {{ __('Blood Samples') }}
        </x-responsive-nav-link>


        <!-- Inventory -->
        <x-responsive-nav-link
            :href="route('inventory.index')"
            :active="request()->routeIs('inventory.*')"
        >
            {{ __('Inventory') }}
        </x-responsive-nav-link>


        <!-- Transportation -->
        <x-responsive-nav-link
            :href="route('transportation.index')"
            :active="request()->routeIs('transportation.*')"
        >
            {{ __('Transportation') }}
        </x-responsive-nav-link>


        <!-- Emergency SOS - Patient Only -->
        @if(auth()->user()->role === 'patient')

            <x-responsive-nav-link
                :href="route('sos.index')"
                :active="request()->routeIs('sos.*')"
            >
                🚨 {{ __('Emergency SOS') }}
            </x-responsive-nav-link>

        @endif


        <!-- Emergency Priority - Admin & Doctor -->
        @if(in_array(auth()->user()->role, ['admin', 'doctor']))

            <x-responsive-nav-link
                :href="route('admin.emergency-priority')"
                :active="request()->routeIs('admin.emergency-priority*')"
            >
                {{ __('Emergency Priority') }}
            </x-responsive-nav-link>

        @endif


    </div>



    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">


        <div class="px-4">

            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                {{ Auth::user()->name }}
            </div>


            <div class="font-medium text-sm text-gray-500">
                {{ Auth::user()->email }}
            </div>

        </div>



        <div class="mt-3 space-y-1">


            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>



            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <x-responsive-nav-link
                    :href="route('logout')"
                    onclick="
                        event.preventDefault();
                        this.closest('form').submit();
                    "
                >
                    {{ __('Log Out') }}
                </x-responsive-nav-link>

            </form>


        </div>


    </div>


</div>