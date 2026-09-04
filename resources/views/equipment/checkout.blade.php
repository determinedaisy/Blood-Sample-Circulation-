<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Checkout
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Error Message --}}
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">

                    <ul class="list-disc list-inside">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Order Summary --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Order Summary
                    </h3>

                    <div class="mt-6 space-y-5">

                        @foreach ($cartItems as $item)

                            <div class="flex justify-between gap-4 border-b border-gray-200 dark:border-gray-700 pb-4">

                                <div>

                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $item['equipment']->name }}
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Quantity: {{ $item['quantity'] }}
                                    </p>

                                </div>

                                <p class="font-semibold text-gray-900 dark:text-white">
                                    ৳{{ number_format($item['subtotal'], 2) }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-between">

                        <span class="text-lg font-semibold text-gray-900 dark:text-white">
                            Total
                        </span>

                        <span class="text-2xl font-bold text-blue-600">
                            ৳{{ number_format($total, 2) }}
                        </span>

                    </div>

                </div>

                {{-- Delivery Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Delivery Information
                    </h3>

                    <form
                        action="{{ route('equipment.order.store') }}"
                        method="POST"
                        class="mt-6"
                    >

                        @csrf

                        <div>

                            <label
                                for="delivery_address"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Delivery Address
                            </label>

                            <textarea
                                id="delivery_address"
                                name="delivery_address"
                                rows="6"
                                required
                                minlength="10"
                                maxlength="1000"
                                class="mt-2 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Enter your complete delivery address"
                            >{{ old('delivery_address') }}</textarea>

                            @error('delivery_address')

                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                        <div class="mt-6">

                            <button
                                type="submit"
                                class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700"
                            >
                                Place Order
                            </button>

                            <a
                                href="{{ route('equipment.cart') }}"
                                class="block mt-3 text-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600"
                            >
                                Back to Cart
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>