
<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Shopping Cart
            </h2>

            <a
                href="{{ route('pharmacy.index') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
                Continue Shopping
            </a>

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


            @if($errors->any())

                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">

                    <ul class="list-disc list-inside">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Empty Cart --}}

            @if(empty($cart))

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center">

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        Your cart is empty
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Add some medicines to your cart before checking out.
                    </p>

                    <a
                        href="{{ route('pharmacy.index') }}"
                        class="inline-block mt-6 px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Browse Medicines
                    </a>

                </div>

            @else

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Cart Items --}}

                    <div class="lg:col-span-2">

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                            <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700">

                                <h3 class="font-semibold text-gray-800 dark:text-gray-200">
                                    Cart Items
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
                                                Price
                                            </th>

                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                Quantity
                                            </th>

                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                Subtotal
                                            </th>

                                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                Action
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                        @foreach($cart as $item)

                                            <tr>

                                                <td class="px-6 py-5">

                                                    <div class="font-semibold text-gray-900 dark:text-white">
                                                        {{ $item['name'] }}
                                                    </div>

                                                </td>


                                                <td class="px-6 py-5 text-gray-700 dark:text-gray-300">
                                                    ৳{{ number_format($item['price'], 2) }}
                                                </td>


                                                <td class="px-6 py-5">

                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'pharmacy.cart.update',
                                                            $item['medicine_id']
                                                        ) }}"
                                                        class="flex gap-2"
                                                    >

                                                        @csrf
                                                        @method('PATCH')

                                                        <input
                                                            type="number"
                                                            name="quantity"
                                                            value="{{ $item['quantity'] }}"
                                                            min="1"
                                                            class="w-20 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                                            required
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="px-3 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm"
                                                        >
                                                            Update
                                                        </button>

                                                    </form>

                                                </td>


                                                <td class="px-6 py-5 font-semibold text-gray-900 dark:text-white">
                                                    ৳{{ number_format(
                                                        $item['price'] * $item['quantity'],
                                                        2
                                                    ) }}
                                                </td>


                                                <td class="px-6 py-5 text-right">

                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'pharmacy.cart.remove',
                                                            $item['medicine_id']
                                                        ) }}"
                                                    >

                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="text-red-600 hover:text-red-800 font-medium"
                                                            onclick="return confirm('Remove this medicine from your cart?')"
                                                        >
                                                            Remove
                                                        </button>

                                                    </form>

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>


                    {{-- Order Summary --}}

                    <div>

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                            <div class="p-6">

                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Order Summary
                                </h3>


                                <div class="mt-5 flex justify-between">

                                    <span class="text-gray-600 dark:text-gray-400">
                                        Items
                                    </span>

                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ count($cart) }}
                                    </span>

                                </div>


                                <div class="border-t border-gray-200 dark:border-gray-700 mt-5 pt-5">

                                    <div class="flex items-center justify-between">

                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Total
                                        </span>

                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                            ৳{{ number_format($total, 2) }}
                                        </span>

                                    </div>

                                </div>


                                <a
                                    href="{{ route('pharmacy.checkout') }}"
                                    class="block mt-6 w-full px-6 py-3 bg-green-600 text-white text-center rounded-md hover:bg-green-700"
                                >
                                    Proceed to Checkout
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>

