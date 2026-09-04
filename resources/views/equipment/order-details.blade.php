<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Order #{{ $order->id }}
            </h2>

            <a
                href="{{ route('equipment.orders') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
            >
                Back to Orders
            </a>

        </div>

    </x-slot>

    <div class="py-8">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Order Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Order Information
                    </h3>

                    <div class="mt-5 space-y-5">

                        <div>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Order ID
                            </p>

                            <p class="font-semibold text-gray-900 dark:text-white">
                                #{{ $order->id }}
                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Order Date
                            </p>

                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Status
                            </p>

                            @if ($order->status === 'pending')

                                <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>

                            @elseif ($order->status === 'processing')

                                <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                                    Processing
                                </span>

                            @elseif ($order->status === 'completed')

                                <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>

                            @elseif ($order->status === 'cancelled')

                                <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">
                                    Cancelled
                                </span>

                            @else

                                <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($order->status) }}
                                </span>

                            @endif

                        </div>

                        <div>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Delivery Address
                            </p>

                            <p class="mt-1 font-semibold text-gray-900 dark:text-white whitespace-pre-line">
                                {{ $order->delivery_address }}
                            </p>

                        </div>

                    </div>

                </div>

                {{-- Ordered Items --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">

                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Ordered Equipment
                        </h3>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead class="bg-gray-100 dark:bg-gray-700">

                                <tr>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Equipment
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Price
                                    </th>

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Quantity
                                    </th>

                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Subtotal
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                @foreach ($order->items as $item)

                                    <tr>

                                        <td class="px-6 py-5">

                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ $item->equipment->name }}
                                            </p>

                                            @if ($item->equipment->description)

                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $item->equipment->description }}
                                                </p>

                                            @endif

                                        </td>

                                        <td class="px-6 py-5 text-gray-700 dark:text-gray-300">
                                            ৳{{ number_format($item->price, 2) }}
                                        </td>

                                        <td class="px-6 py-5 text-gray-700 dark:text-gray-300">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="px-6 py-5 text-right font-semibold text-gray-900 dark:text-white">
                                            ৳{{ number_format($item->subtotal, 2) }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    {{-- Total --}}
                    <div class="p-6 bg-gray-50 dark:bg-gray-700">

                        <div class="flex justify-between items-center">

                            <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                Total Amount
                            </span>

                            <span class="text-2xl font-bold text-blue-600">
                                ৳{{ number_format($order->total_amount, 2) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>