<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Equipment Orders
            </h2>

            <a
                href="{{ route('equipment.index') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
                Equipment Store
            </a>

        </div>

    </x-slot>

    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if ($orders->isEmpty())

                {{-- No Orders --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center">

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        No orders found
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        You have not placed any equipment orders yet.
                    </p>

                    <a
                        href="{{ route('equipment.index') }}"
                        class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Start Shopping
                    </a>

                </div>

            @else

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead class="bg-gray-100 dark:bg-gray-700">

                                <tr>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Order
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Date
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Items
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Total
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                @foreach ($orders as $order)

                                    <tr>

                                        <td class="px-6 py-5 font-semibold text-gray-900 dark:text-white">
                                            #{{ $order->id }}
                                        </td>

                                        <td class="px-6 py-5 text-gray-600 dark:text-gray-300">
                                            {{ $order->created_at->format('d M Y, h:i A') }}
                                        </td>

                                        <td class="px-6 py-5 text-gray-600 dark:text-gray-300">
                                            {{ $order->items->sum('quantity') }}
                                        </td>

                                        <td class="px-6 py-5 font-semibold text-gray-900 dark:text-white">
                                            ৳{{ number_format($order->total_amount, 2) }}
                                        </td>

                                        <td class="px-6 py-5">

                                            @if ($order->status === 'pending')

                                                <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>

                                            @elseif ($order->status === 'processing')

                                                <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                                                    Processing
                                                </span>

                                            @elseif ($order->status === 'completed')

                                                <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                                                    Completed
                                                </span>

                                            @elseif ($order->status === 'cancelled')

                                                <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">
                                                    Cancelled
                                                </span>

                                            @else

                                                <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">
                                                    {{ ucfirst($order->status) }}
                                                </span>

                                            @endif

                                        </td>

                                        <td class="px-6 py-5 text-right">

                                            <a
                                                href="{{ route('equipment.order.show', $order) }}"
                                                class="text-blue-600 hover:text-blue-800 font-medium"
                                            >
                                                View Details
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>