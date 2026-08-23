<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Request Blood Sample
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Submit a blood sample request for admin approval.
            </p>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

                <form method="POST" action="{{ route('sample-requests.store') }}">
                    @csrf

                    <div class="space-y-5">

                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Sample Type
                            </label>

                            <select
                                name="sample_type"
                                required
                                class="w-full rounded-xl border-gray-300"
                            >
                                <option value="">Select sample type</option>
                                <option value="Whole Blood">Whole Blood</option>
                                <option value="Plasma">Plasma</option>
                                <option value="Serum">Serum</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Blood Type
                            </label>

                            <select
                                name="blood_type"
                                class="w-full rounded-xl border-gray-300"
                            >
                                <option value="">Select blood type</option>
                                <option>A+</option>
                                <option>A-</option>
                                <option>B+</option>
                                <option>B-</option>
                                <option>AB+</option>
                                <option>AB-</option>
                                <option>O+</option>
                                <option>O-</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                rows="4"
                                class="w-full rounded-xl border-gray-300"
                                placeholder="Optional notes..."
                            >{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3">

                            <a
                                href="{{ route('sample-requests.patient.index') }}"
                                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-semibold"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold"
                            >
                                Submit Request
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>