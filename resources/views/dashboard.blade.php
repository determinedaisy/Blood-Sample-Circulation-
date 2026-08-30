<x-app-layout>

```
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Welcome --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-semibold">
                    Welcome, {{ auth()->user()->name }}!
                </h3>

                <p class="mt-1 text-gray-600 dark:text-gray-400">
                    You are logged in as a {{ ucfirst(auth()->user()->role) }}.
                </p>
            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- DONOR BADGE SECTION --}}
        {{-- ========================================================= --}}

        @if(auth()->user()->donorProfile)

            @php
                $donor = auth()->user()->donorProfile;
            @endphp

            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-semibold mb-4">
                        🩸 My Donor Status
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        {{-- Donation Count --}}
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Successful Donations
                            </p>

                            <p class="text-2xl font-bold mt-1">
                                {{ $donor->donation_count }}
                            </p>
                        </div>


                        {{-- Badge --}}
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Donor Badge
                            </p>

                            <p class="text-2xl font-bold mt-1">
                                {{ $donor->badge_name }}
                            </p>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ ucfirst($donor->donor_badge) }}
                            </p>
                        </div>


                        {{-- Discount --}}
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Shop Discount
                            </p>

                            <p class="text-2xl font-bold mt-1">
                                {{ $donor->shop_discount }}%
                            </p>
                        </div>


                        {{-- Priority --}}
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Donor Priority
                            </p>

                            <p class="text-2xl font-bold mt-1">
                                Level {{ $donor->donor_priority }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- NOTIFICATIONS --}}
        {{-- ========================================================= --}}

        @if(auth()->user()->notifications()->count() > 0)

            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex items-center justify-between mb-4">

                        <h3 class="text-lg font-semibold">
                            🔔 My Notifications
                        </h3>

                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ auth()->user()->unreadNotifications()->count() }} unread
                        </span>

                    </div>


                    @foreach(auth()->user()->notifications()->latest()->get() as $notification)

                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 mb-4">

                            {{-- Notification Title --}}
                            <p class="font-semibold">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>


                            {{-- Message --}}
                            <p class="mt-2">
                                {{ $notification->data['message'] ?? '' }}
                            </p>


                            {{-- Sample Code --}}
                            @if(isset($notification->data['sample_code']))

                                <p class="mt-2 text-sm">
                                    <strong>Sample Code:</strong>
                                    {{ $notification->data['sample_code'] }}
                                </p>

                            @endif


                            {{-- Donation Count --}}
                            @if(isset($notification->data['donation_count']))

                                <p class="mt-2 text-sm">
                                    <strong>Total Successful Donations:</strong>
                                    {{ $notification->data['donation_count'] }}
                                </p>

                            @endif


                            {{-- Badge --}}
                            @if(isset($notification->data['badge_name']))

                                <p class="mt-2 text-sm">
                                    <strong>Donor Badge:</strong>
                                    {{ $notification->data['badge_name'] }}
                                </p>

                            @endif


                            {{-- Discount --}}
                            @if(isset($notification->data['shop_discount']))

                                <p class="mt-2 text-sm">
                                    <strong>Shop Discount:</strong>
                                    {{ $notification->data['shop_discount'] }}%
                                </p>

                            @endif


                            {{-- Rejection Reason --}}
                            @if(isset($notification->data['reason']))

                                <p class="mt-2 text-sm">
                                    <strong>Reason:</strong>
                                    {{ $notification->data['reason'] }}
                                </p>

                            @endif


                            {{-- Read Status --}}
                            @if($notification->read_at)

                                <p class="mt-2 text-sm text-green-600">
                                    ✓ Read
                                </p>

                            @else

                                <p class="mt-2 text-sm text-red-600">
                                    ● Unread
                                </p>

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>

        @else

            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-semibold">
                        🔔 My Notifications
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        You currently have no notifications.
                    </p>

                </div>

            </div>

        @endif

    </div>
</div>
```

</x-app-layout>
