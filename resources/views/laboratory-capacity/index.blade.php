

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    Laboratory Capacity Management
                </h2>
                <p class="text-sm text-gray-500">
                    Monitor scheduled workload and available testing slots.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('laboratory-capacity.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Testing Date
                        </label>
                        <input
                            id="date"
                            name="date"
                            type="date"
                            value="{{ $selectedDate }}"
                            class="mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                    </div>
                    <button class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                       Apply Date
                    </button>
                </form>
            </div>

            <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Laboratory</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-500">Daily capacity</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-500">Scheduled</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-500">Available</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-500">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-500">Workload</th>
                                @if(auth()->user()->role === 'admin')
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Update capacity</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($laboratories as $laboratory)
                                @php
                                    $capacity = (int) $laboratory->daily_capacity;
                                    $scheduled = (int) $laboratory->scheduled_count;
                                    $available = max($capacity - $scheduled, 0);
                                    $isFull = $available === 0;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4">
                                        <a
                                            href="{{ route('laboratory-capacity.workload', $laboratory) }}"
                                            class="font-semibold text-blue-700 hover:text-blue-900 hover:underline dark:text-blue-300"
                                        >
                                            {{ $laboratory->name }}
                                        </a>
                                        <div class="text-xs text-gray-500">{{ $laboratory->address }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-gray-700 dark:text-gray-200">{{ $capacity }}</td>
                                    <td class="px-6 py-4 text-center font-semibold">
                                        <a
                                            href="{{ route('laboratory-capacity.workload', ['laboratory' => $laboratory, 'date' => $selectedDate]) }}"
                                            class="text-blue-700 hover:underline dark:text-blue-300"
                                        >
                                            {{ $scheduled }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold {{ $isFull ? 'text-red-600' : 'text-green-600' }}">{{ $available }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $isFull ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $isFull ? 'Full' : 'Available' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a
                                            href="{{ route('laboratory-capacity.workload', $laboratory) }}"
                                            class="inline-flex rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700"
                                        >
                                            View Samples
                                        </a>
                                    </td>
                                    @if(auth()->user()->role === 'admin')
                                        <td class="px-6 py-4">
                                            <form method="POST" action="{{ route('laboratory-capacity.update', $laboratory) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input
                                                    type="number"
                                                    name="daily_capacity"
                                                    min="1"
                                                    max="10000"
                                                    value="{{ $capacity }}"
                                                    required
                                                    class="w-24 rounded-lg border-gray-300 text-sm"
                                                >
                                                <button class="rounded-lg bg-gray-800 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-900">
                                                    Save
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">No laboratories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
