<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Shopping Cart
            </h2>

            <a
                href="{{ route('equipment.index') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
                Continue Shopping
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

            {{-- Empty Cart --}}
            @if (empty($cartItems))

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center">

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        Your cart is empty
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Add some equipment to your cart before checking out.
                    </p>

                    <a
                        href="{{ route('equipment.index') }}"
                        class="inline-block mt-6 px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Browse Equipment
                    </a>

                </div>

            @else

                {{-- Update Cart Form --}}
                <form
                    id="update-cart-form"
                    action="{{ route('equipment.cart.update') }}"
                    method="POST"
                >
                    @csrf
                    @method('PATCH')
                </form>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

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

                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Subtotal
                                    </th>

                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                @foreach ($cartItems as $item)

                                    <tr>

                                        <td class="px-6 py-5">

                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $item['equipment']->name }}
                                            </div>

                                            @if ($item['equipment']->description)
                                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $item['equipment']->description }}
                                                </div>
                                            @endif

                                        </td>

                                        <td class="px-6 py-5 text-gray-700 dark:text-gray-300">
                                            ৳{{ number_format($item['equipment']->price, 2) }}
                                        </td>

                                        <td class="px-6 py-5">

                                            <input
                                                type="number"
                                                name="quantities[{{ $item['equipment']->id }}]"
                                                value="{{ $item['quantity'] }}"
                                                min="1"
                                                max="{{ $item['equipment']->stock }}"
                                                form="update-cart-form"
                                                class="w-24 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                                required
                                            >

                                        </td>

                                        <td class="px-6 py-5 font-semibold text-gray-900 dark:text-white">
                                            ৳{{ number_format($item['subtotal'], 2) }}
                                        </td>

                                        <td class="px-6 py-5 text-right">

                                            <form
                                                action="{{ route('equipment.cart.remove', $item['equipment']) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-red-600 hover:text-red-800 font-medium"
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

                    {{-- Cart Footer --}}
                    <div class="p-6 bg-gray-50 dark:bg-gray-700">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">

                            <button
                                type="submit"
                                form="update-cart-form"
                                class="px-5 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                            >
                                Update Cart
                            </button>

                            <div class="text-right">

                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Total
                                </p>

                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    ৳{{ number_format($total, 2) }}
                                </p>

                                <a
                                    href="{{ route('equipment.checkout') }}"
                                    class="inline-block mt-3 px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700"
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