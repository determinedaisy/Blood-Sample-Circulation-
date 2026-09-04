<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-indigo-600">Inventory Management</p>
                <h2 class="text-2xl font-bold text-gray-900">Register Stored Sample</h2>
            </div>
            <a href="{{ route('inventory.index') }}"
               class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                ← Back to Inventory
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                    <p class="font-semibold">Please correct the following:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-5 lg:items-start">
                <form action="{{ route('inventory.store') }}" method="POST"
                      class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-3">
                    @csrf

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Sample identity</h3>
                        <p class="mt-1 text-sm text-gray-500">Only accepted blood samples can be registered in inventory.</p>

                        <label for="blood_sample_id" class="mt-5 block text-sm font-semibold text-gray-800">Blood sample</label>
                        <select id="blood_sample_id" name="blood_sample_id" required
                                class="mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select an accepted blood sample</option>
                            @foreach($bloodSamples as $sample)
                                <option value="{{ $sample->id }}"
                                        data-code="{{ $sample->sample_code }}"
                                        @selected(old('blood_sample_id') == $sample->id)>
                                    {{ $sample->sample_code }} - {{ $sample->blood_type ?? 'Unknown blood type' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900">Storage assignment</h3>
                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="refrigerator" class="block text-sm font-semibold text-gray-800">Refrigerator unit</label>
                                <input id="refrigerator" name="refrigerator" value="{{ old('refrigerator') }}" required placeholder="R-02"
                                       class="mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="shelf" class="block text-sm font-semibold text-gray-800">Shelf number</label>
                                <input id="shelf" name="shelf" value="{{ old('shelf') }}" required placeholder="B"
                                       class="mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="rack" class="block text-sm font-semibold text-gray-800">Rack identifier</label>
                                <input id="rack" name="rack" value="{{ old('rack') }}" required placeholder="3"
                                       class="mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="storage_location" class="block text-sm font-semibold text-gray-800">Storage location</label>
                                <input id="storage_location" name="storage_location" value="{{ old('storage_location') }}" required placeholder="Central Laboratory"
                                       class="mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3 border-t border-gray-200 pt-6">
                        <a href="{{ route('inventory.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Save to Inventory</button>
                    </div>
                </form>

                <aside class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm lg:sticky lg:top-6 lg:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Storage Label Preview</p>
                    <div class="mt-4 rounded-xl border border-indigo-100 bg-white p-5">
                        <p class="text-xs uppercase text-gray-500">Sample</p>
                        <p id="previewId" class="mt-1 text-xl font-bold text-gray-900">Not selected</p>

                        <div class="mt-5 grid grid-cols-3 gap-2 text-center">
                            <div class="rounded-lg bg-blue-50 p-2"><p class="text-xs text-gray-500">Fridge</p><p id="previewFridge" class="mt-1 font-semibold text-gray-900">—</p></div>
                            <div class="rounded-lg bg-blue-50 p-2"><p class="text-xs text-gray-500">Shelf</p><p id="previewShelf" class="mt-1 font-semibold text-gray-900">—</p></div>
                            <div class="rounded-lg bg-blue-50 p-2"><p class="text-xs text-gray-500">Rack</p><p id="previewRack" class="mt-1 font-semibold text-gray-900">—</p></div>
                        </div>

                        <div class="mt-5 border-t border-dashed border-gray-300 pt-4">
                            <p class="text-xs uppercase text-gray-500">Location</p>
                            <p id="previewLocation" class="mt-1 font-semibold text-indigo-700">—</p>
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-indigo-700">The preview updates while you enter the storage information.</p>
                </aside>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sampleSelect = document.getElementById('blood_sample_id');
            const updateSample = () => {
                const option = sampleSelect?.selectedOptions[0];
                document.getElementById('previewId').textContent = option?.dataset.code || 'Not selected';
            };
            sampleSelect?.addEventListener('change', updateSample);
            updateSample();

            const bind = (inputId, previewId) => {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                const update = () => preview.textContent = input.value.trim() || '—';
                input?.addEventListener('input', update);
                if (input && preview) update();
            };

            bind('refrigerator', 'previewFridge');
            bind('shelf', 'previewShelf');
            bind('rack', 'previewRack');
            bind('storage_location', 'previewLocation');
        });
    </script>
</x-app-layout>
