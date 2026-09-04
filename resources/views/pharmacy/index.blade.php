
<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Pharmacy
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Online Medicine Store
                </p>
            </div>

            <div class="flex gap-2">

                <a
                    href="{{ route('pharmacy.orders') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                >
                    📋 My Orders
                </a>

                <a
                    href="{{ route('pharmacy.cart') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                >
                    🛒 Cart

                    @if(count(session('pharmacy_cart', [])) > 0)

                        <span class="ml-2 px-2 py-0.5 bg-white text-blue-600 rounded-full text-xs">
                            {{ count(session('pharmacy_cart', [])) }}
                        </span>

                    @endif

                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Introduction --}}

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Medicines & Healthcare
                    </h1>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Find the medicines you need and order them directly from our pharmacy.
                    </p>

                </div>

            </div>


            {{-- Alerts --}}

            @if(session('success'))

                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">

                    <div class="font-semibold">
                        Success!
                    </div>

                    <div class="mt-1">
                        {{ session('success') }}
                    </div>

                </div>

            @endif


            @if(session('error'))

                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">

                    <div class="font-semibold">
                        Error
                    </div>

                    <div class="mt-1">
                        {{ session('error') }}
                    </div>

                </div>

            @endif


            @if($errors->any())

                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">

                    <div class="font-semibold">
                        Please check the following:
                    </div>

                    <ul class="mt-2 list-disc list-inside">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Search --}}

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">

                <div class="p-6">

                    <form
                        method="GET"
                        action="{{ route('pharmacy.index') }}"
                    >

                        <div class="flex flex-col sm:flex-row gap-3">

                            <div class="flex-1">

                                <label
                                    for="search"
                                    class="sr-only"
                                >
                                    Search medicines
                                </label>

                                <div class="relative">

                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">

                                        <span class="text-gray-400">
                                            🔎
                                        </span>

                                    </div>

                                    <input
                                        id="search"
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Search for medicines, categories..."
                                        class="w-full pl-11 pr-4 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >

                                </div>

                            </div>


                            <button
                                type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition"
                            >
                                Search
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Store Header --}}

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

                <div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Available Medicines
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Choose a medicine and select the quantity you need.
                    </p>

                </div>


                @if($medicines->total() > 0)

                    <div class="text-sm text-gray-500 dark:text-gray-400">

                        {{ $medicines->total() }}

                        medicine{{ $medicines->total() !== 1 ? 's' : '' }}
                        available

                    </div>

                @endif

            </div>


            {{-- Medicines Grid --}}

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @forelse($medicines as $medicine)

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col">

                        <div class="p-6 flex flex-col flex-1">

                            {{-- Category --}}

                            <div class="mb-4">

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    {{ $medicine->category }}
                                </span>

                            </div>


                            {{-- Medicine Name --}}

                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ $medicine->name }}
                            </h3>


                            {{-- Description --}}

                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 min-h-[48px]">

                                {{ $medicine->description ?: 'Quality healthcare medicine available through our pharmacy.' }}

                            </p>


                            {{-- Price and Availability --}}

                            <div class="mt-5 flex items-end justify-between gap-4">

                                <div>

                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Price
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">
                                        ৳{{ number_format($medicine->price, 2) }}
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Availability
                                    </p>

                                    <div class="mt-1">

                                        @if($medicine->stock > 0)

                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                In Stock
                                            </span>

                                        @else

                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                Out of Stock
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- Stock --}}

                            <div class="mt-4">

                                @if($medicine->stock > 0)

                                    <p class="text-sm text-gray-500 dark:text-gray-400">

                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $medicine->stock }}
                                        </span>

                                        unit{{ $medicine->stock !== 1 ? 's' : '' }}
                                        available

                                    </p>

                                @else

                                    <p class="text-sm text-red-600">
                                        This medicine is currently unavailable.
                                    </p>

                                @endif

                            </div>


                            {{-- Cart Action --}}

                            <div class="mt-auto pt-5">

                                @if($medicine->stock > 0)

                                    <form
                                        method="POST"
                                        action="{{ route('pharmacy.cart.add', $medicine) }}"
                                    >

                                        @csrf

                                        <label
                                            for="quantity-{{ $medicine->id }}"
                                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2"
                                        >
                                            Quantity
                                        </label>


                                        <div class="flex">

                                            <input
                                                id="quantity-{{ $medicine->id }}"
                                                type="number"
                                                name="quantity"
                                                value="1"
                                                min="1"
                                                max="{{ $medicine->stock }}"
                                                required
                                                class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            >


                                            <button
                                                type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-r-md hover:bg-blue-700 transition whitespace-nowrap"
                                            >
                                                Add to Cart
                                            </button>

                                        </div>

                                    </form>

                                @else

                                    <button
                                        type="button"
                                        disabled
                                        class="w-full px-4 py-2 bg-gray-200 text-gray-500 rounded-md font-semibold cursor-not-allowed"
                                    >
                                        Currently Unavailable
                                    </button>

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-span-full">

                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                            <div class="p-10 text-center">

                                <div class="text-5xl mb-4">
                                    💊
                                </div>

                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    No medicines found
                                </h3>

                                <p class="mt-2 text-gray-500 dark:text-gray-400">
                                    We couldn't find any medicines matching your search.
                                </p>


                                @if(request('search'))

                                    <a
                                        href="{{ route('pharmacy.index') }}"
                                        class="inline-block mt-5 px-5 py-2 bg-gray-600 text-white rounded-md font-semibold hover:bg-gray-700"
                                    >
                                        Clear Search
                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                @endforelse

            </div>


            {{-- Pagination --}}

            @if($medicines->hasPages())

                <div class="mt-8">

                    {{ $medicines->links() }}

                </div>

            @endif

        </div>

    </div>

</x-app-layout>

