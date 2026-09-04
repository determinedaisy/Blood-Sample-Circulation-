<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Order Confirmation
        </h2>
    </x-slot>

    <div class="py-10">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">

                {{-- Success Icon --}}
                <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-green-100">
                    <span class="text-3xl text-green-600">
                        ✓
                    </span>
                </div>

                <h3 class="mt-5 text-2xl font-bold text-gray-900 dark:text-white">
                    Order Placed Successfully!
                </h3>

                <p class="mt-3 text-gray-600 dark:text-gray-400">
                    Thank you for your order. Your equipment order has been received.
                </p>

                {{-- Order Information --}}
                <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-5 text-left">

                    <div class="flex justify-between py-2">

                        <span class="text-gray-600 dark:text-gray-300">
                            Order ID
                        </span>

                        <span class="font-semibold text-gray-900 dark:text-white">
                            #{{ $order->id }}
                        </span>

                    </div>

                    <div class="flex justify-between py-2">

                        <span class="text-gray-600 dark:text-gray-300">
                            Order Date
                        </span>

                        <span class="font-semibold text-gray-900 dark:text-white">
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </span>

                    </div>

                    <div class="flex justify-between py-2">

                        <span class="text-gray-600 dark:text-gray-300">
                            Status
                        </span>

                        <span class="font-semibold text-yellow-600">
                            {{ ucfirst($order->status) }}
                        </span>

                    </div>

                    <div class="flex justify-between py-2">

                        <span class="text-gray-600 dark:text-gray-300">
                            Total
                        </span>

                        <span class="font-bold text-gray-900 dark:text-white">
                            ৳{{ number_format($order->total_amount, 2) }}
                        </span>

                    </div>

                </div>

                {{-- Buttons --}}
                <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">

                    <a
                        href="{{ route('equipment.order.show', $order) }}"
                        class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        View Order Details
                    </a>

                    <a
                        href="{{ route('equipment.orders') }}"
                        class="px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                    >
                        My Orders
                    </a>

                    <a
                        href="{{ route('equipment.index') }}"
                        class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600"
                    >
                        Continue Shopping
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>