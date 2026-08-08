<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            @if(auth()->user()->role === 'patient')

                <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <h3 class="text-lg font-semibold mb-4">
                            My Notifications
                        </h3>

                        @forelse(auth()->user()->notifications as $notification)

                            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 mb-4">

                                <p class="font-semibold">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>

                                <p class="mt-2">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>

                                @if(isset($notification->data['sample_code']))
                                    <p class="mt-2">
                                        <strong>Sample Code:</strong>
                                        {{ $notification->data['sample_code'] }}
                                    </p>
                                @endif

                                @if(isset($notification->data['reason']))
                                    <p class="mt-2">
                                        <strong>Reason:</strong>
                                        {{ $notification->data['reason'] }}
                                    </p>
                                @endif

                                @if($notification->read_at)
                                    <p class="mt-2 text-green-600">
                                        Read
                                    </p>
                                @else
                                    <p class="mt-2 text-red-600">
                                        Unread
                                    </p>
                                @endif

                            </div>

                        @empty

                            <p>
                                You currently have no notifications.
                            </p>

                        @endforelse

                    </div>
                </div>

            @endif

        </div>
    </div>

</x-app-layout>