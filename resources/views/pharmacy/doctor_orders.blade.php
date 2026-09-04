
<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Pharmacy Orders
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Doctor Portal
                </p>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerts --}}

            @if(session('success'))

                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>

            @endif


            @if(session('error'))

                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>

            @endif


            @if($orders->count())

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

                    <div>

                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Orders Awaiting Review
                        </h3>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Review the order information before confirming it.
                        </p>

                    </div>


                    <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                        {{ $orders->total() }} Pending
                    </span>

                </div>

            @endif


            {{-- Orders --}}

            @forelse($orders as $order)

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">

                    <div class="p-6">

                        {{-- Order Header --}}

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div>

                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Pharmacy Order
                                </span>

                                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-white">
                                    Order #{{ $order->id }}
                                </h3>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Placed {{ $order->created_at->format('d M Y, h:i A') }}
                                </p>

                            </div>


                            <span class="px-4 py-2 text-sm rounded-full bg-yellow-100 text-yellow-800">
                                ⏳ Pending Review
                            </span>

                        </div>


                        {{-- Patient Information --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">

                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Patient
                                </p>

                                <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                                    {{ $order->patient->name }}
                                </p>

                            </div>


                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Delivery Address
                                </p>

                                <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                                    {{ $order->delivery_address }}
                                </p>

                            </div>

                        </div>


                        {{-- Medicines --}}

                        <div class="mt-6">

                            <div class="flex items-center justify-between mb-3">

                                <div>

                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Ordered Medicines
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Review the requested medicines and quantities.
                                    </p>

                                </div>


                                <span class="text-sm text-gray-500 dark:text-gray-400">

                                    {{ $order->items->sum('quantity') }}
                                    item{{ $order->items->sum('quantity') !== 1 ? 's' : '' }}

                                </span>

                            </div>


                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">

                                <div class="overflow-x-auto">

                                    <table class="w-full">

                                        <thead class="bg-gray-100 dark:bg-gray-700">

                                            <tr>

                                                <th class="px-5 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                    Medicine
                                                </th>

                                                <th class="px-5 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                    Quantity
                                                </th>

                                                <th class="px-5 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                    Unit Price
                                                </th>

                                                <th class="px-5 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                    Subtotal
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                            @foreach($order->items as $item)

                                                <tr>

                                                    <td class="px-5 py-4">

                                                        <div class="font-semibold text-gray-900 dark:text-white">
                                                            {{ $item->medicine->name }}
                                                        </div>

                                                        @if($item->medicine->category)

                                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $item->medicine->category }}
                                                            </div>

                                                        @endif

                                                    </td>


                                                    <td class="px-5 py-4 text-gray-700 dark:text-gray-300">
                                                        {{ $item->quantity }}
                                                    </td>


                                                    <td class="px-5 py-4 text-gray-700 dark:text-gray-300">
                                                        ৳{{ number_format($item->unit_price, 2) }}
                                                    </td>


                                                    <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                                        ৳{{ number_format($item->subtotal, 2) }}
                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>


                        {{-- Footer --}}

                        <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                                <div>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Order Total
                                    </p>

                                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                                        ৳{{ number_format($order->total_amount, 2) }}
                                    </p>

                                </div>


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'pharmacy.doctor.orders.accept',
                                        $order
                                    ) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium"
                                        onclick="return confirm('Confirm this pharmacy order for the patient?')"
                                    >
                                        ✓ Accept Order
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>


            @empty

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center">

                    <div class="text-4xl mb-4">
                        ✓
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        No Pending Pharmacy Orders
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        There are currently no medicine orders waiting for your confirmation.
                    </p>

                </div>

            @endforelse


            {{-- Pagination --}}

            @if($orders->hasPages())

                <div class="mt-6">

                    {{ $orders->links() }}

                </div>

            @endif

        </div>

    </div>

</x-app-layout>

