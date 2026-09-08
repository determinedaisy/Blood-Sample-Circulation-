
@php
    $unreadNotificationCount = auth()->check()
        ? auth()->user()->unreadNotifications()->count()
        : 0;
@endphp

<style>
    /*
    |--------------------------------------------------------------------------
    | Blood Red Navigation
    |--------------------------------------------------------------------------
    */

    .dashboard-navigation {
        background: #b91c1c !important;
        border-color: #991b1b !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation Links
    |--------------------------------------------------------------------------
    */

    .dashboard-navigation a {
        color: #ffffff !important;
        border-color: transparent !important;
        transition: all 0.2s ease-in-out;
    }

    .dashboard-navigation a:hover {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        border-color: transparent !important;
    }

    .dashboard-navigation a[aria-current="page"] {
        background: #991b1b !important;
        color: #ffffff !important;
        border-color: #dc2626 !important;
        font-weight: 600 !important;
    }

    /*
    |--------------------------------------------------------------------------
    | User Dropdown Button
    |--------------------------------------------------------------------------
    */

    .dashboard-navigation .nav-user-button {
        background: #fff1f4 !important;
        color: #7f1d1d !important;
        border-color: #fecdd3 !important;
    }

    .dashboard-navigation .nav-user-button:hover {
        background: #ffe4e9 !important;
        color: #991b1b !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile Menu Button
    |--------------------------------------------------------------------------
    */

    .dashboard-navigation .mobile-menu-button {
        color: #ffffff !important;
    }

    .dashboard-navigation .mobile-menu-button:hover {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile Navigation
    |--------------------------------------------------------------------------
    */

    .dashboard-navigation .mobile-section {
        background: #b91c1c !important;
        border-color: #991b1b !important;
    }

    .dashboard-navigation .mobile-section a {
        color: #ffffff !important;
    }

    .dashboard-navigation .mobile-section a:hover {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
    }

    .dashboard-navigation .mobile-section a[aria-current="page"] {
        background: #991b1b !important;
        color: #ffffff !important;
        border-color: transparent !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile User Information
    |--------------------------------------------------------------------------
    */

    .dashboard-navigation .mobile-user-name {
        color: #ffffff !important;
    }

    .dashboard-navigation .mobile-user-email {
        color: #fecdd3 !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Notification Badge
    |--------------------------------------------------------------------------
    */

    .dashboard-navigation .notification-badge {
        background: #ffffff !important;
        color: #b91c1c !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    */

    .dashboard-navigation .logo-link svg {
        color: #ffffff !important;
    }
</style>


<div
    class="dashboard-navigation bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700"
>

    {{-- ========================================================= --}}
    {{-- DESKTOP NAVIGATION --}}
    {{-- ========================================================= --}}

    <div class="flex justify-between h-16">

        <div class="flex">

            {{-- Logo --}}
            <div class="shrink-0 flex items-center">

                <a
                    href="{{ route('dashboard') }}"
                    class="logo-link"
                >
                    <x-application-logo
                        class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                    />
                </a>

            </div>


            {{-- Desktop Navigation Links --}}
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                {{-- Dashboard --}}
                <x-nav-link
                    :href="auth()->user()->role === 'admin'
                        ? route('admin.dashboard')
                        : route('dashboard')"
                    :active="request()->routeIs('admin.dashboard') || request()->routeIs('dashboard')"
                >
                    {{ __('Dashboard') }}
                </x-nav-link>


                {{-- Forum --}}
                <x-nav-link
                    :href="route('forum.index')"
                    :active="request()->routeIs('forum.*')"
                >
                    💬 {{ __('Forum') }}
                </x-nav-link>


                {{-- Equipment Store --}}
                <x-nav-link
                    :href="route('equipment.index')"
                    :active="request()->routeIs('equipment.index')"
                >
                    🛒 {{ __('Equipment Store') }}
                </x-nav-link>


                {{-- Cart --}}
                <x-nav-link
                    :href="route('equipment.cart')"
                    :active="
                        request()->routeIs('equipment.cart')
                        || request()->routeIs('equipment.checkout')
                    "
                >
                    🛍️ {{ __('Cart') }}
                </x-nav-link>


                {{-- My Orders --}}
                <x-nav-link
                    :href="route('equipment.orders')"
                    :active="
                        request()->routeIs('equipment.orders')
                        || request()->routeIs('equipment.order.*')
                    "
                >
                    📦 {{ __('My Orders') }}
                </x-nav-link>


                {{-- ================================================= --}}
                {{-- PATIENT --}}
                {{-- ================================================= --}}

                @if(auth()->user()->role === 'patient')

                    {{-- Pharmacy --}}
                    <x-nav-link
                        :href="route('pharmacy.index')"
                        :active="
                            request()->routeIs('pharmacy.index')
                            || request()->routeIs('pharmacy.cart')
                            || request()->routeIs('pharmacy.checkout')
                        "
                    >
                        {{ __('Pharmacy') }}
                    </x-nav-link>


                    {{-- My Pharmacy Orders --}}
                    <x-nav-link
                        :href="route('pharmacy.orders')"
                        :active="
                            request()->routeIs('pharmacy.orders')
                            || request()->routeIs('pharmacy.orders.show')
                        "
                    >
                        {{ __('My Pharmacy Orders') }}
                    </x-nav-link>


                    {{-- Sample Requests --}}
                    <x-nav-link
                        :href="route('sample-requests.patient.index')"
                        :active="
                            request()->routeIs('sample-requests.patient.*')
                            || request()->routeIs('sample-requests.create')
                            || request()->routeIs('sample-requests.tracking')
                            || request()->routeIs('sample-requests.home-collection.*')
                        "
                    >
                        {{ __('Sample Requests') }}
                    </x-nav-link>


                    {{-- Reception Assistance --}}
                    <x-nav-link
                        :href="route('reception-requests.patient.index')"
                        :active="
                            request()->routeIs('reception-requests.patient.*')
                            || request()->routeIs('reception-requests.create')
                        "
                    >
                        {{ __('Reception Assistance') }}
                    </x-nav-link>


                    {{-- My Samples --}}
                    <x-nav-link
                        :href="route('patient.blood-samples.index')"
                        :active="request()->routeIs('patient.blood-samples.*')"
                    >
                        {{ __('My Samples') }}
                    </x-nav-link>


                    {{-- My Reports --}}
                    <x-nav-link
                        :href="route('sample-reports.patient.index')"
                        :active="request()->routeIs('sample-reports.patient.*')"
                    >
                        {{ __('My Reports') }}
                    </x-nav-link>


                    {{-- Notifications --}}
                    <x-nav-link
                        :href="route('patient.notifications.index')"
                        :active="request()->routeIs('patient.notifications.*')"
                    >

                        <span class="inline-flex items-center gap-1.5">

                            {{ __('Notifications') }}

                            @if($unreadNotificationCount > 0)

                                <span
                                    class="notification-badge inline-flex min-w-5 items-center justify-center rounded-full px-1.5 py-0.5 text-xs font-bold"
                                >
                                    {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                                </span>

                            @endif

                        </span>

                    </x-nav-link>


                    {{-- Emergency SOS --}}
                    <x-nav-link
                        :href="route('sos.index')"
                        :active="request()->routeIs('sos.*')"
                    >
                        🚨 {{ __('Emergency SOS') }}
                    </x-nav-link>

                @endif

                @if(auth()->user()->role === 'donor')

    <x-nav-link
        :href="route('donor.requests')"
        :active="request()->routeIs('donor.*')"
    >
        🚨 {{ __('Emergency Blood Requests') }}

        @if($unreadNotificationCount > 0)
            <span
                class="ml-2 inline-flex items-center justify-center min-w-5 h-5 px-1.5 rounded-full bg-red-600 text-white text-xs font-bold"
            >
                {{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}
            </span>
        @endif
    </x-nav-link>

@endif


                {{-- ================================================= --}}
                {{-- RECEPTIONIST --}}
                {{-- ================================================= --}}

                @if(auth()->user()->role === 'receptionist')

                    {{-- Sample Requests --}}
                    <x-nav-link
                        :href="route('sample-requests.receptionist.index')"
                        :active="request()->routeIs('sample-requests.receptionist.*')"
                    >
                        {{ __('Sample Requests') }}
                    </x-nav-link>


                    {{-- Incoming Requests --}}
                    <x-nav-link
                        :href="route('reception-requests.receptionist.index')"
                        :active="
                            request()->routeIs('reception-requests.receptionist.*')
                            || request()->routeIs('reception-requests.process')
                        "
                    >
                        {{ __('Incoming Requests') }}
                    </x-nav-link>

                @endif


                {{-- ================================================= --}}
                {{-- ADMIN --}}
                {{-- ================================================= --}}

                @if(auth()->user()->role === 'admin')

                    {{-- Sample Requests --}}
                    <x-nav-link
                        :href="route('sample-requests.admin.index')"
                        :active="
                            request()->routeIs('sample-requests.admin.*')
                            || request()->routeIs('sample-requests.approve')
                            || request()->routeIs('sample-requests.decline')
                            || request()->routeIs('sample-requests.assign-collector')
                            || request()->routeIs('sample-requests.assign-doctor')
                        "
                    >
                        {{ __('Sample Requests') }}
                    </x-nav-link>


                    {{-- Home Collections --}}
                    <x-nav-link
                        :href="route('home-collections.admin.index')"
                        :active="
                            request()->routeIs('home-collections.admin.*')
                            || request()->routeIs('home-collections.assign')
                        "
                    >
                        🏠 {{ __('Home Collections') }}
                    </x-nav-link>


                    {{-- Lab Capacity --}}
                    <x-nav-link
                        :href="route('laboratory-capacity.index')"
                        :active="request()->routeIs('laboratory-capacity.*')"
                    >
                        {{ __('Lab Capacity') }}
                    </x-nav-link>


                    {{-- Blood Samples --}}
                    <x-nav-link
                        :href="route('blood-samples.index')"
                        :active="request()->routeIs('blood-samples.*')"
                    >
                        {{ __('Blood Samples') }}
                    </x-nav-link>


                    {{-- Inventory --}}
                    <x-nav-link
                        :href="route('inventory.index')"
                        :active="request()->routeIs('inventory.*')"
                    >
                        {{ __('Inventory') }}
                    </x-nav-link>


                    {{-- Transportation --}}
                    <x-nav-link
                        :href="route('transportation.index')"
                        :active="request()->routeIs('transportation.*')"
                    >
                        {{ __('Transportation') }}
                    </x-nav-link>


                    {{-- Reports --}}
                    <x-nav-link
                        :href="route('admin.reports.index')"
                        :active="request()->routeIs('admin.reports.*')"
                    >
                        {{ __('Reports') }}
                    </x-nav-link>

                @endif


                {{-- ================================================= --}}
                {{-- LABORATORY STAFF --}}
                {{-- ================================================= --}}

                @if(auth()->user()->role === 'lab_staff')

                    {{-- Laboratory Workload --}}
                    <x-nav-link
                        :href="route('laboratory-capacity.index')"
                        :active="request()->routeIs('laboratory-capacity.*')"
                    >
                        {{ __('Laboratory Workload') }}
                    </x-nav-link>


                    {{-- Blood Samples --}}
                    <x-nav-link
                        :href="route('blood-samples.index')"
                        :active="request()->routeIs('blood-samples.*')"
                    >
                        {{ __('Blood Samples') }}
                    </x-nav-link>


                    {{-- Inventory --}}
                    <x-nav-link
                        :href="route('inventory.index')"
                        :active="request()->routeIs('inventory.*')"
                    >
                        {{ __('Inventory') }}
                    </x-nav-link>


                    {{-- Transportation --}}
                    <x-nav-link
                        :href="route('transportation.index')"
                        :active="request()->routeIs('transportation.*')"
                    >
                        {{ __('Transportation') }}
                    </x-nav-link>

                @endif


                {{-- ================================================= --}}
                {{-- SAMPLE COLLECTOR --}}
                {{-- ================================================= --}}

                @if(auth()->user()->role === 'sample_collector')

                    {{-- Home Collections --}}
                    <x-nav-link
                        :href="route('home-collections.collector.index')"
                        :active="request()->routeIs('home-collections.collector.*')"
                    >
                        🏠 {{ __('Home Collections') }}
                    </x-nav-link>


                    {{-- Transportation --}}
                    <x-nav-link
                        :href="route('transportation.index')"
                        :active="request()->routeIs('transportation.*')"
                    >
                        {{ __('Transportation') }}
                    </x-nav-link>


                    {{-- Inventory --}}
                    <x-nav-link
                        :href="route('inventory.index')"
                        :active="request()->routeIs('inventory.*')"
                    >
                        {{ __('Inventory') }}
                    </x-nav-link>

                @endif


                {{-- ================================================= --}}
                {{-- DOCTOR --}}
                {{-- ================================================= --}}

                @if(auth()->user()->role === 'doctor')

                    {{-- Assigned Samples --}}
                    <x-nav-link
                        :href="route('sample-requests.doctor.index')"
                        :active="request()->routeIs('sample-requests.doctor.*')"
                    >
                        {{ __('Assigned Samples') }}
                    </x-nav-link>


                    {{-- Blood Samples --}}
                    <x-nav-link
                        :href="route('blood-samples.index')"
                        :active="request()->routeIs('blood-samples.*')"
                    >
                        {{ __('Blood Samples') }}
                    </x-nav-link>


                    {{-- Pharmacy Orders --}}
                    <x-nav-link
                        :href="route('pharmacy.doctor.orders')"
                        :active="request()->routeIs('pharmacy.doctor.*')"
                    >
                        {{ __('Pharmacy Orders') }}
                    </x-nav-link>

                @endif


                {{-- ================================================= --}}
                {{-- EMERGENCY PRIORITY --}}
                {{-- ================================================= --}}

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


        {{-- ========================================================= --}}
        {{-- DESKTOP USER MENU --}}
        {{-- ========================================================= --}}

        <div class="hidden sm:flex sm:items-center sm:ms-6">

            <x-dropdown align="right" width="48">

                <x-slot name="trigger">

                    <button
                        class="nav-user-button inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none transition ease-in-out duration-150"
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
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
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


                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <x-dropdown-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                        >
                            {{ __('Log Out') }}
                        </x-dropdown-link>

                    </form>

                </x-slot>

            </x-dropdown>

        </div>


        {{-- ========================================================= --}}
        {{-- MOBILE MENU BUTTON --}}
        {{-- ========================================================= --}}

        <div class="-me-2 flex items-center sm:hidden">

            <button
                @click="open = ! open"
                class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md focus:outline-none"
            >

                <svg
                    class="h-6 w-6"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                >

                    <path
                        :class="{
                            'hidden': open,
                            'inline-flex': ! open
                        }"
                        class="inline-flex"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />

                    <path
                        :class="{
                            'hidden': ! open,
                            'inline-flex': open
                        }"
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


    {{-- ========================================================= --}}
    {{-- MOBILE NAVIGATION --}}
    {{-- ========================================================= --}}

    <div
        :class="{
            'block': open,
            'hidden': ! open
        }"
        class="mobile-section hidden sm:hidden"
    >

        <div class="pt-2 pb-3 space-y-1">

            {{-- Dashboard --}}
            <x-responsive-nav-link
                :href="
                    auth()->user()->role === 'admin'
                        ? route('admin.dashboard')
                        : route('dashboard')
                "
                :active="
                    request()->routeIs('admin.dashboard')
                    || request()->routeIs('dashboard')
                "
            >
                {{ __('Dashboard') }}
            </x-responsive-nav-link>


            {{-- Forum --}}
            <x-responsive-nav-link
                :href="route('forum.index')"
                :active="request()->routeIs('forum.*')"
            >
                💬 {{ __('Forum') }}
            </x-responsive-nav-link>


            {{-- Equipment Store --}}
            <x-responsive-nav-link
                :href="route('equipment.index')"
                :active="request()->routeIs('equipment.index')"
            >
                🛒 {{ __('Equipment Store') }}
            </x-responsive-nav-link>


            {{-- Cart --}}
            <x-responsive-nav-link
                :href="route('equipment.cart')"
                :active="
                    request()->routeIs('equipment.cart')
                    || request()->routeIs('equipment.checkout')
                "
            >
                🛍️ {{ __('Cart') }}
            </x-responsive-nav-link>


            {{-- My Orders --}}
            <x-responsive-nav-link
                :href="route('equipment.orders')"
                :active="
                    request()->routeIs('equipment.orders')
                    || request()->routeIs('equipment.order.*')
                "
            >
                📦 {{ __('My Orders') }}
            </x-responsive-nav-link>


            {{-- ================================================= --}}
            {{-- PATIENT --}}
            {{-- ================================================= --}}

            @if(auth()->user()->role === 'patient')

                {{-- Pharmacy --}}
                <x-responsive-nav-link
                    :href="route('pharmacy.index')"
                    :active="
                        request()->routeIs('pharmacy.index')
                        || request()->routeIs('pharmacy.cart')
                        || request()->routeIs('pharmacy.checkout')
                    "
                >
                    {{ __('Pharmacy') }}
                </x-responsive-nav-link>


                {{-- My Pharmacy Orders --}}
                <x-responsive-nav-link
                    :href="route('pharmacy.orders')"
                    :active="
                        request()->routeIs('pharmacy.orders')
                        || request()->routeIs('pharmacy.orders.show')
                    "
                >
                    {{ __('My Pharmacy Orders') }}
                </x-responsive-nav-link>


                {{-- Sample Requests --}}
                <x-responsive-nav-link
                    :href="route('sample-requests.patient.index')"
                    :active="
                        request()->routeIs('sample-requests.patient.*')
                        || request()->routeIs('sample-requests.create')
                        || request()->routeIs('sample-requests.tracking')
                        || request()->routeIs('sample-requests.home-collection.*')
                    "
                >
                    {{ __('Sample Requests') }}
                </x-responsive-nav-link>


                {{-- Reception Assistance --}}
                <x-responsive-nav-link
                    :href="route('reception-requests.patient.index')"
                    :active="
                        request()->routeIs('reception-requests.patient.*')
                        || request()->routeIs('reception-requests.create')
                    "
                >
                    {{ __('Reception Assistance') }}
                </x-responsive-nav-link>


                {{-- My Samples --}}
                <x-responsive-nav-link
                    :href="route('patient.blood-samples.index')"
                    :active="request()->routeIs('patient.blood-samples.*')"
                >
                    {{ __('My Samples') }}
                </x-responsive-nav-link>


                {{-- My Reports --}}
                <x-responsive-nav-link
                    :href="route('sample-reports.patient.index')"
                    :active="request()->routeIs('sample-reports.patient.*')"
                >
                    {{ __('My Reports') }}
                </x-responsive-nav-link>


                {{-- Notifications --}}
                <x-responsive-nav-link
                    :href="route('patient.notifications.index')"
                    :active="request()->routeIs('patient.notifications.*')"
                >

                    <span class="inline-flex items-center gap-1.5">

                        {{ __('Notifications') }}

                        @if($unreadNotificationCount > 0)

                            <span
                                class="notification-badge inline-flex min-w-5 items-center justify-center rounded-full px-1.5 py-0.5 text-xs font-bold"
                            >
                                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                            </span>

                        @endif

                    </span>

                </x-responsive-nav-link>


                {{-- Emergency SOS --}}
                <x-responsive-nav-link
                    :href="route('sos.index')"
                    :active="request()->routeIs('sos.*')"
                >
                    🚨 {{ __('Emergency SOS') }}
                </x-responsive-nav-link>

            @endif


            {{-- ================================================= --}}
            {{-- RECEPTIONIST --}}
            {{-- ================================================= --}}

            @if(auth()->user()->role === 'receptionist')

                {{-- Sample Requests --}}
                <x-responsive-nav-link
                    :href="route('sample-requests.receptionist.index')"
                    :active="request()->routeIs('sample-requests.receptionist.*')"
                >
                    {{ __('Sample Requests') }}
                </x-responsive-nav-link>


                {{-- Incoming Requests --}}
                <x-responsive-nav-link
                    :href="route('reception-requests.receptionist.index')"
                    :active="
                        request()->routeIs('reception-requests.receptionist.*')
                        || request()->routeIs('reception-requests.process')
                    "
                >
                    {{ __('Incoming Requests') }}
                </x-responsive-nav-link>

            @endif


            {{-- ================================================= --}}
            {{-- ADMIN --}}
            {{-- ================================================= --}}

            @if(auth()->user()->role === 'admin')

                {{-- Sample Requests --}}
                <x-responsive-nav-link
                    :href="route('sample-requests.admin.index')"
                    :active="
                        request()->routeIs('sample-requests.admin.*')
                        || request()->routeIs('sample-requests.approve')
                        || request()->routeIs('sample-requests.decline')
                        || request()->routeIs('sample-requests.assign-collector')
                        || request()->routeIs('sample-requests.assign-doctor')
                    "
                >
                    {{ __('Sample Requests') }}
                </x-responsive-nav-link>


                {{-- Home Collections --}}
                <x-responsive-nav-link
                    :href="route('home-collections.admin.index')"
                    :active="
                        request()->routeIs('home-collections.admin.*')
                        || request()->routeIs('home-collections.assign')
                    "
                >
                    🏠 {{ __('Home Collections') }}
                </x-responsive-nav-link>


                {{-- Lab Capacity --}}
                <x-responsive-nav-link
                    :href="route('laboratory-capacity.index')"
                    :active="request()->routeIs('laboratory-capacity.*')"
                >
                    {{ __('Lab Capacity') }}
                </x-responsive-nav-link>


                {{-- Blood Samples --}}
                <x-responsive-nav-link
                    :href="route('blood-samples.index')"
                    :active="request()->routeIs('blood-samples.*')"
                >
                    {{ __('Blood Samples') }}
                </x-responsive-nav-link>


                {{-- Inventory --}}
                <x-responsive-nav-link
                    :href="route('inventory.index')"
                    :active="request()->routeIs('inventory.*')"
                >
                    {{ __('Inventory') }}
                </x-responsive-nav-link>


                {{-- Transportation --}}
                <x-responsive-nav-link
                    :href="route('transportation.index')"
                    :active="request()->routeIs('transportation.*')"
                >
                    {{ __('Transportation') }}
                </x-responsive-nav-link>


                {{-- Reports --}}
                <x-responsive-nav-link
                    :href="route('admin.reports.index')"
                    :active="request()->routeIs('admin.reports.*')"
                >
                    {{ __('Reports') }}
                </x-responsive-nav-link>

            @endif


            {{-- ================================================= --}}
            {{-- LABORATORY STAFF --}}
            {{-- ================================================= --}}

            @if(auth()->user()->role === 'lab_staff')

                {{-- Laboratory Workload --}}
                <x-responsive-nav-link
                    :href="route('laboratory-capacity.index')"
                    :active="request()->routeIs('laboratory-capacity.*')"
                >
                    {{ __('Laboratory Workload') }}
                </x-responsive-nav-link>


                {{-- Blood Samples --}}
                <x-responsive-nav-link
                    :href="route('blood-samples.index')"
                    :active="request()->routeIs('blood-samples.*')"
                >
                    {{ __('Blood Samples') }}
                </x-responsive-nav-link>


                {{-- Inventory --}}
                <x-responsive-nav-link
                    :href="route('inventory.index')"
                    :active="request()->routeIs('inventory.*')"
                >
                    {{ __('Inventory') }}
                </x-responsive-nav-link>


                {{-- Transportation --}}
                <x-responsive-nav-link
                    :href="route('transportation.index')"
                    :active="request()->routeIs('transportation.*')"
                >
                    {{ __('Transportation') }}
                </x-responsive-nav-link>

            @endif


            {{-- ================================================= --}}
            {{-- SAMPLE COLLECTOR --}}
            {{-- ================================================= --}}

            @if(auth()->user()->role === 'sample_collector')

                {{-- Home Collections --}}
                <x-responsive-nav-link
                    :href="route('home-collections.collector.index')"
                    :active="request()->routeIs('home-collections.collector.*')"
                >
                    🏠 {{ __('Home Collections') }}
                </x-responsive-nav-link>


                {{-- Transportation --}}
                <x-responsive-nav-link
                    :href="route('transportation.index')"
                    :active="request()->routeIs('transportation.*')"
                >
                    {{ __('Transportation') }}
                </x-responsive-nav-link>


                {{-- Inventory --}}
                <x-responsive-nav-link
                    :href="route('inventory.index')"
                    :active="request()->routeIs('inventory.*')"
                >
                    {{ __('Inventory') }}
                </x-responsive-nav-link>

            @endif


            {{-- ================================================= --}}
            {{-- DOCTOR --}}
            {{-- ================================================= --}}

            @if(auth()->user()->role === 'doctor')

                {{-- Assigned Samples --}}
                <x-responsive-nav-link
                    :href="route('sample-requests.doctor.index')"
                    :active="request()->routeIs('sample-requests.doctor.*')"
                >
                    {{ __('Assigned Samples') }}
                </x-responsive-nav-link>


                {{-- Blood Samples --}}
                <x-responsive-nav-link
                    :href="route('blood-samples.index')"
                    :active="request()->routeIs('blood-samples.*')"
                >
                    {{ __('Blood Samples') }}
                </x-responsive-nav-link>


                {{-- Pharmacy Orders --}}
                <x-responsive-nav-link
                    :href="route('pharmacy.doctor.orders')"
                    :active="request()->routeIs('pharmacy.doctor.*')"
                >
                    {{ __('Pharmacy Orders') }}
                </x-responsive-nav-link>

            @endif


            {{-- ================================================= --}}
            {{-- EMERGENCY PRIORITY --}}
            {{-- ================================================= --}}

            @if(in_array(auth()->user()->role, ['admin', 'doctor']))

                <x-responsive-nav-link
                    :href="route('admin.emergency-priority')"
                    :active="request()->routeIs('admin.emergency-priority*')"
                >
                    {{ __('Emergency Priority') }}
                </x-responsive-nav-link>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- MOBILE USER INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="pt-4 pb-1 border-t border-white/20">

            <div class="px-4">

                <div class="mobile-user-name font-medium text-base">
                    {{ Auth::user()->name }}
                </div>

                <div class="mobile-user-email font-medium text-sm">
                    {{ Auth::user()->email }}
                </div>

            </div>


            <div class="mt-3 space-y-1">

                {{-- Profile --}}
                <x-responsive-nav-link
                    :href="route('profile.edit')"
                >
                    {{ __('Profile') }}
                </x-responsive-nav-link>


                {{-- Logout --}}
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>

                </form>

            </div>

        </div>

    </div>

</div>