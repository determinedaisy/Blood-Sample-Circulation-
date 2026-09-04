
<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Pharmacy Order #{{ $order->id }}
            </h2>

            <a
                href="{{ route('pharmacy.orders') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
            >
                My Orders
            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}

            @if(session('success'))

                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>

            @endif


            {{-- Order Information --}}

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">

                <div class="p-6">

                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                        <div>

                            <span class="inline-block px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                                Pharmacy Order
                            </span>

                            <h1 class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">
                                Order #{{ $order->id }}
                            </h1>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Placed {{ $order->created_at->format('d M Y, h:i A') }}
                            </p>

                        </div>


                        <div>

                            @if($order->status === 'accepted')

                                <span class="px-4 py-2 text-sm rounded-full bg-green-100 text-green-800">
                                    ✓ Accepted
                                </span>

                            @else

                                <span class="px-4 py-2 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                    ⏳ Pending Confirmation
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6">

                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Delivery Address
                        </h3>

                        <div class="mt-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-300">
                            {{ $order->delivery_address }}
                        </div>

                    </div>


                    @if($order->status === 'accepted')

                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">

                            <h4 class="font-semibold text-green-800">
                                ✓ Order Accepted
                            </h4>

                            <p class="mt-1 text-sm text-green-700">

                                Your order was confirmed by

                                @if($order->doctor)

                                    <strong>Dr. {{ $order->doctor->name }}</strong>

                                @else

                                    <strong>a doctor</strong>

                                @endif

                                on

                                {{ $order->accepted_at
                                    ? $order->accepted_at->format('d M Y, h:i A')
                                    : 'the confirmation date'
                                }}.

                            </p>

                        </div>

                    @else

                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">

                            <h4 class="font-semibold text-yellow-800">
                                ⏳ Waiting for Doctor Confirmation
                            </h4>

                            <p class="mt-1 text-sm text-yellow-700">
                                Your pharmacy order has been submitted successfully.
                                A doctor must confirm the order before it is accepted.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Medicines --}}

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700">

                    <h3 class="font-semibold text-gray-800 dark:text-gray-200">
                        Medicines in This Order
                    </h3>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-50 dark:bg-gray-700">

                            <tr>

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    Medicine
                                </th>

                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    Unit Price
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

                            @foreach($order->items as $item)

                                <tr>

                                    <td class="px-6 py-5">

                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $item->medicine->name }}
                                        </div>

                                        @if($item->medicine->category)

                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $item->medicine->category }}
                                            </div>

                                        @endif

                                    </td>


                                    <td class="px-6 py-5 text-gray-700 dark:text-gray-300">
                                        ৳{{ number_format($item->unit_price, 2) }}
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


                <div class="p-6 bg-gray-50 dark:bg-gray-700">

                    <div class="flex items-center justify-between">

                        <span class="text-lg font-semibold text-gray-900 dark:text-white">
                            Order Total
                        </span>

                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            ৳{{ number_format($order->total_amount, 2) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>



