
<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Pharmacy Checkout
            </h2>

            <a
                href="{{ route('pharmacy.cart') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
            >
                Back to Cart
            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Validation Errors --}}

            @if($errors->any())

                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">

                    <strong>Please correct the following:</strong>

                    <ul class="mt-2 list-disc list-inside">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Delivery Information --}}

                <div class="lg:col-span-2">

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                        <div class="p-6">

                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Delivery Information
                            </h3>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Enter the address where your medicines should be delivered.
                            </p>


                            <form
                                method="POST"
                                action="{{ route('pharmacy.orders.store') }}"
                                class="mt-6"
                            >

                                @csrf


                                <div>

                                    <label
                                        for="delivery_address"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                    >
                                        Delivery Address
                                    </label>

                                    <textarea
                                        id="delivery_address"
                                        name="delivery_address"
                                        rows="7"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        placeholder="House/Flat, Road, Area, City, Postal Code"
                                        required
                                    >{{ old('delivery_address') }}</textarea>

                                    @error('delivery_address')

                                        <p class="mt-2 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                <div class="mt-5 p-4 bg-blue-50 border border-blue-200 rounded-lg">

                                    <p class="text-sm text-blue-800">

                                        <strong>Important:</strong>
                                        Your order will first be sent for doctor confirmation.
                                        Stock will be deducted only after the order is accepted.

                                    </p>

                                </div>


                                <button
                                    type="submit"
                                    class="mt-6 w-full px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium"
                                >
                                    Place Pharmacy Order
                                </button>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- Order Summary --}}

                <div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                        <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700">

                            <h3 class="font-semibold text-gray-800 dark:text-gray-200">
                                Order Summary
                            </h3>

                        </div>


                        <div class="p-6">

                            @foreach($cart as $item)

                                <div class="py-4 border-b border-gray-200 dark:border-gray-700">

                                    <div class="flex justify-between gap-4">

                                        <div>

                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ $item['name'] }}
                                            </p>

                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Quantity: {{ $item['quantity'] }}
                                            </p>

                                        </div>


                                        <p class="font-semibold text-gray-900 dark:text-white whitespace-nowrap">

                                            ৳{{ number_format(
                                                $item['price'] * $item['quantity'],
                                                2
                                            ) }}

                                        </p>

                                    </div>

                                </div>

                            @endforeach


                            <div class="flex items-center justify-between mt-5">

                                <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Total
                                </span>

                                <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                    ৳{{ number_format($total, 2) }}
                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-5">

                        <div class="flex gap-3">

                            <span class="text-lg">
                                ℹ️
                            </span>

                            <p class="text-sm text-gray-600 dark:text-gray-300">

                                After submitting, your order will appear under
                                <strong>My Orders</strong> with a pending status
                                until a doctor confirms it.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>

