<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Equipment Store
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Browse and purchase medical equipment.
                </p>
            </div>

            <div class="flex items-center gap-3">

                <a
                    href="{{ route('equipment.cart') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                    Shopping Cart
                </a>

                <a
                    href="{{ route('equipment.orders') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                >
                    My Orders
                </a>

            </div>

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

            {{-- Page Introduction --}}
            <div class="mb-8">

                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Medical Equipment
                </h3>

                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Find the medical equipment you need and place your order directly from our store.
                </p>

            </div>

            @if ($equipment->isEmpty())

                {{-- No Equipment --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center">

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        No equipment available
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        There is currently no medical equipment available in the store.
                    </p>

                </div>

            @else

                {{-- Equipment Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($equipment as $item)

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden flex flex-col">

                            {{-- Equipment Information --}}
                            <div class="p-6 flex-1">

                                <div class="flex items-start justify-between gap-4">

                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $item->name }}
                                    </h3>

                                    @if ($item->stock > 0)

                                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            In Stock
                                        </span>

                                    @else

                                        <span class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>

                                    @endif

                                </div>

                                @if ($item->description)

                                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                        {{ $item->description }}
                                    </p>

                                @else

                                    <p class="mt-3 text-sm text-gray-400 dark:text-gray-500 italic">
                                        No description available.
                                    </p>

                                @endif

                                <div class="mt-6">

                                    <p class="text-2xl font-bold text-blue-600">
                                        ৳{{ number_format($item->price, 2) }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">

                                        @if ($item->stock > 0)

                                            {{ $item->stock }} units available

                                        @else

                                            Currently unavailable

                                        @endif

                                    </p>

                                </div>

                            </div>

                            {{-- Add to Cart --}}
                            <div class="p-6 pt-0">

                                @if ($item->stock > 0)

                                    <form
                                        action="{{ route('equipment.cart.add') }}"
                                        method="POST"
                                    >

                                        @csrf

                                        <input
                                            type="hidden"
                                            name="equipment_id"
                                            value="{{ $item->id }}"
                                        >

                                        <div class="flex items-end gap-3">

                                            <div class="flex-1">

                                                <label
                                                    for="quantity-{{ $item->id }}"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                                >
                                                    Quantity
                                                </label>

                                                <input
                                                    id="quantity-{{ $item->id }}"
                                                    type="number"
                                                    name="quantity"
                                                    value="1"
                                                    min="1"
                                                    max="{{ $item->stock }}"
                                                    required
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                                >

                                            </div>

                                            <button
                                                type="submit"
                                                class="px-5 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium whitespace-nowrap"
                                            >
                                                Add to Cart
                                            </button>

                                        </div>

                                    </form>

                                @else

                                    <button
                                        type="button"
                                        disabled
                                        class="w-full px-5 py-2.5 bg-gray-400 text-white rounded-md cursor-not-allowed font-medium"
                                    >
                                        Out of Stock
                                    </button>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

</x-app-layout>