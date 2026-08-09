<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Donate Blood Sample
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg">

                    <p class="font-semibold mb-2">
                        Please correct the following errors:
                    </p>

                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-2xl font-bold mb-2">
                        Donate a Blood Sample
                    </h3>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Submit your blood sample for laboratory review.
                        Your sample will remain pending until it has been reviewed
                        by authorized laboratory staff.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('patient.blood-samples.store') }}"
                    >

                        @csrf

                        <!-- Sample Type -->

                        <div class="mb-6">

                            <label
                                for="sample_type"
                                class="block font-semibold mb-2"
                            >
                                Sample Type
                            </label>

                            <select
                                id="sample_type"
                                name="sample_type"
                                required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm"
                            >

                                <option value="">
                                    Select sample type
                                </option>

                                <option
                                    value="Whole Blood"
                                    {{ old('sample_type') === 'Whole Blood' ? 'selected' : '' }}
                                >
                                    Whole Blood
                                </option>

                                <option
                                    value="Serum"
                                    {{ old('sample_type') === 'Serum' ? 'selected' : '' }}
                                >
                                    Serum
                                </option>

                                <option
                                    value="Plasma"
                                    {{ old('sample_type') === 'Plasma' ? 'selected' : '' }}
                                >
                                    Plasma
                                </option>

                            </select>

                        </div>

                        <!-- Collection Date -->

                        <div class="mb-6">

                            <label
                                for="collected_at"
                                class="block font-semibold mb-2"
                            >
                                Collection Date and Time
                            </label>

                            <input
                                type="datetime-local"
                                id="collected_at"
                                name="collected_at"
                                value="{{ old('collected_at') }}"
                                required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm"
                            >

                        </div>

                        <!-- Information -->

                        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">

                            <p class="font-semibold mb-1">
                                Important
                            </p>

                            <p class="text-sm">
                                Your donation will initially be marked as
                                <strong>Pending</strong>.
                                Laboratory staff must review and approve the
                                sample before it becomes available in the inventory.
                            </p>

                        </div>

                        <!-- Buttons -->

                        <div class="flex items-center gap-4">

                            <button
                                type="submit"
                                class="inline-flex items-center px-5 py-2.5 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Submit Donation
                            </button>

                            <a
                                href="{{ route('patient.blood-samples.index') }}"
                                class="inline-flex items-center px-5 py-2.5 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600"
                            >
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>