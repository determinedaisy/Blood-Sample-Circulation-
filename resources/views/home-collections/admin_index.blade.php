@php
    $routePlannerStops = $homeCollections
        ->filter(fn ($collection) =>
            $collection->assigned_collector_id
            && $collection->latitude !== null
            && $collection->longitude !== null
            && $collection->status !== 'cancelled'
        )
        ->map(fn ($collection) => [
            'id' => $collection->id,
            'collectorId' => (int) $collection->assigned_collector_id,
            'collectorName' => $collection->assignedCollector?->name ?? 'Assigned collector',
            'date' => $collection->preferred_date->format('Y-m-d'),
            'time' => $collection->preferred_time,
            'patient' => $collection->patient?->name ?? 'Patient',
            'address' => $collection->address,
            'lat' => (float) $collection->latitude,
            'lng' => (float) $collection->longitude,
            'routeOrder' => $collection->route_order ? (int) $collection->route_order : null,
            'status' => $collection->status,
        ])
        ->values();

    $routePlannerDates = $routePlannerStops
        ->pluck('date')
        ->unique()
        ->sort()
        ->values();
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    .route-number-icon {
        align-items: center;
        background: #e11d48;
        border: 3px solid #fff;
        border-radius: 9999px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .3);
        color: #fff;
        display: flex;
        font-size: 13px;
        font-weight: 800;
        height: 32px;
        justify-content: center;
        width: 32px;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Home Collections</h2>
            <p class="mt-1 text-sm text-gray-600">
                Manage each home sample from request approval through doctor assignment.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-100 py-8">
        <div class="mx-auto w-full max-w-[1800px] px-3 sm:px-5 lg:px-6">
            @if(session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 bg-gradient-to-r from-rose-50 via-white to-indigo-50 px-5 py-5 sm:px-6">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                        <div>
                            <h3 class="mt-1 text-xl font-bold text-gray-900">Daily Collection Route Planner</h3>
                            <p class="mt-1 text-sm text-gray-500">Choose a date and collector, optimize stops using real roads, then save the order.</p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 xl:min-w-[560px]">
                            <div>
                                <label for="route-date-filter" class="text-xs font-bold uppercase tracking-wide text-gray-500">Route date</label>
                                <select id="route-date-filter" class="mt-1 block w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500">
                                    @forelse($routePlannerDates as $date)
                                        <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</option>
                                    @empty
                                        <option value="">No GPS appointments</option>
                                    @endforelse
                                </select>
                            </div>

                            <div>
                                <label for="route-collector-filter" class="text-xs font-bold uppercase tracking-wide text-gray-500">Collector</label>
                                <select id="route-collector-filter" class="mt-1 block w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500">
                                    <option value="">Select a route date first</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid xl:grid-cols-12">
                    <div class="border-b border-gray-200 p-4 sm:p-5 xl:col-span-8 xl:border-b-0 xl:border-r">
                        <div id="admin-route-map"
                             data-save-url="{{ route('home-collections.route-order') }}"
                             class="h-[500px] w-full overflow-hidden rounded-xl bg-slate-100"></div>
                    </div>

                    <aside class="flex min-h-[500px] flex-col p-5 xl:col-span-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="text-xs font-bold uppercase tracking-wide text-gray-400">Stops</div>
                                <div id="route-stop-count" class="mt-1 text-xl font-bold text-gray-900">0</div>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="text-xs font-bold uppercase tracking-wide text-gray-400">Road estimate</div>
                                <div id="route-road-estimate" class="mt-1 text-sm font-bold text-gray-900">—</div>
                            </div>
                        </div>

                        <div id="route-planner-message" class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                            Select a date and collector to load their appointments.
                        </div>

                        <div id="route-stop-list" class="mt-4 flex-1 space-y-2 overflow-y-auto xl:max-h-[280px]"></div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-1 2xl:grid-cols-2">
                            <button type="button" id="optimize-route-button" disabled
                                    class="rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                                Optimize road route
                            </button>
                            <button type="button" id="save-route-button" disabled
                                    class="rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-rose-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                                Save stop order
                            </button>
                        </div>
                    </aside>
                </div>
            </section>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 bg-gray-50 px-5 py-4 sm:px-6">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900">Home Collection Requests</h3>
                            <p class="text-sm text-gray-500">All workflow controls are kept inside one full-width panel.</p>
                        </div>
                        <div class="text-sm font-semibold text-gray-600">
                            {{ $homeCollections->count() }} {{ $homeCollections->count() === 1 ? 'request' : 'requests' }}
                        </div>
                    </div>
                </div>

                @forelse($homeCollections as $homeCollection)
                    @php
                        $sampleRequest = $homeCollection->sampleRequest;
                        $bloodSample = $sampleRequest?->bloodSample;
                        $transportation = $bloodSample?->transportations?->sortByDesc('id')?->first();
                        $statusClass = match ($homeCollection->status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'assigned', 'on_the_way' => 'bg-blue-100 text-blue-800',
                            'arrived' => 'bg-purple-100 text-purple-800',
                            'collected' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-700',
                        };
                    @endphp

                    <article id="home-collection-card-{{ $homeCollection->id }}" class="border-b border-gray-200 last:border-b-0">
                        <header class="bg-white px-5 py-5 sm:px-6">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $homeCollection->patient->name ?? 'Unknown Patient' }}
                                        </h3>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $homeCollection->status)) }}
                                        </span>
                                    </div>
                                    <p class="mt-1 break-all text-sm text-gray-500">
                                        {{ $homeCollection->patient->email ?? 'No patient email' }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-x-8 gap-y-2 rounded-xl bg-indigo-50 px-4 py-3">
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Sample code</div>
                                        <div class="mt-1 font-bold text-indigo-900">{{ $bloodSample?->sample_code ?? 'Not created' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Sample</div>
                                        <div class="mt-1 font-semibold text-indigo-900">
                                            {{ $sampleRequest?->sample_type ?? 'Unavailable' }}
                                            @if($sampleRequest?->blood_type)
                                                · {{ $sampleRequest->blood_type }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </header>

                        <div class="grid bg-white xl:grid-cols-12">
                            <section class="border-t border-gray-200 p-5 xl:col-span-3">
                                <h4 class="text-xs font-bold uppercase tracking-wide text-gray-500">Collection details</h4>

                                <dl class="mt-4 space-y-4">
                                    <div>
                                        <dt class="text-xs font-semibold text-gray-500">Appointment</dt>
                                        <dd class="mt-1 font-semibold text-gray-900">
                                            {{ $homeCollection->preferred_date->format('d M Y') }}
                                        </dd>
                                        <dd class="text-sm text-gray-600">{{ $homeCollection->preferred_time }}</dd>
                                    </div>

                                    <div>
                                        <dt class="text-xs font-semibold text-gray-500">Address</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-800">{{ $homeCollection->address }}</dd>
                                    </div>

                                    @if($homeCollection->instructions)
                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-600">
                                            <dt class="font-semibold text-gray-700">Instructions</dt>
                                            <dd class="mt-1">{{ $homeCollection->instructions }}</dd>
                                        </div>
                                    @endif

                                    <div>
                                        <dt class="text-xs font-semibold text-gray-500">Location</dt>
                                        @if($homeCollection->latitude && $homeCollection->longitude)
                                            <dd class="mt-1 text-sm text-gray-700">
                                                {{ $homeCollection->latitude }}, {{ $homeCollection->longitude }}
                                            </dd>
                                            <dd>
                                                <a href="https://www.google.com/maps?q={{ $homeCollection->latitude }},{{ $homeCollection->longitude }}"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="mt-2 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-800">
                                                    Open in Google Maps
                                                </a>
                                            </dd>
                                        @else
                                            <dd class="mt-1 text-sm text-gray-400">No GPS coordinates</dd>
                                        @endif
                                    </div>
                                </dl>
                            </section>

                            <section class="border-t border-gray-200 p-5 xl:col-span-2 xl:border-l">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-xs font-bold text-green-700">1</span>
                                    <h4 class="text-sm font-bold text-gray-900">Request approval</h4>
                                </div>

                                <div class="mt-4">
                                    @if(!$sampleRequest)
                                        <p class="text-sm text-red-600">No linked sample request.</p>
                                    @elseif($sampleRequest->status === 'pending')
                                        <div class="rounded-lg bg-yellow-50 p-3 text-sm text-yellow-800">
                                            Approve before assigning a collector.
                                        </div>
                                        <div class="mt-3 grid gap-2">
                                            <form method="POST" action="{{ route('sample-requests.approve', $sampleRequest) }}" class="js-home-workflow-form">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:bg-green-700">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('sample-requests.decline', $sampleRequest) }}" class="js-home-workflow-form">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" onclick="return confirm('Decline this home collection request?')"
                                                        class="w-full rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                                    Decline
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($sampleRequest->status === 'approved')
                                        <div class="rounded-lg bg-green-50 p-3 text-sm text-green-800">
                                            <div class="font-semibold">✓ Approved</div>
                                            @if($sampleRequest->approved_at)
                                                <div class="mt-1 text-xs">{{ $sampleRequest->approved_at->format('d M Y, h:i A') }}</div>
                                            @endif
                                        </div>
                                    @elseif($sampleRequest->status === 'declined')
                                        <div class="rounded-lg bg-red-50 p-3 text-sm font-semibold text-red-800">Declined</div>
                                    @else
                                        <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $sampleRequest->status)) }}</p>
                                    @endif
                                </div>
                            </section>

                            <section class="border-t border-gray-200 p-5 xl:col-span-2 xl:border-l">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 text-xs font-bold text-purple-700">2</span>
                                    <h4 class="text-sm font-bold text-gray-900">Home collector</h4>
                                </div>

                                <div class="mt-4">
                                    @if(!$sampleRequest || $sampleRequest->status !== 'approved')
                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500">Waiting for approval.</div>
                                    @elseif($homeCollection->assignedCollector)
                                        <div class="rounded-lg bg-green-50 p-3 text-sm text-green-800">
                                            <div class="font-semibold">✓ Collector assigned</div>
                                            <div class="mt-1 font-medium text-gray-900">{{ $homeCollection->assignedCollector->name }}</div>
                                            @if($homeCollection->assigned_at)
                                                <div class="mt-1 text-xs text-gray-500">{{ $homeCollection->assigned_at->format('d M Y, h:i A') }}</div>
                                            @endif
                                        </div>
                                    @elseif($homeCollection->status === 'pending')
                                        <form method="POST" action="{{ route('home-collections.assign', $homeCollection) }}" class="js-home-workflow-form space-y-3">
                                            @csrf
                                            <select name="assigned_collector_id" required class="w-full rounded-lg border-gray-300 text-sm">
                                                <option value="">Select Collector</option>
                                                @foreach($collectors as $collector)
                                                    <option value="{{ $collector->id }}">{{ $collector->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="w-full rounded-lg bg-purple-600 px-3 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                                                Assign Collector
                                            </button>
                                        </form>
                                    @else
                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500">No collector assigned.</div>
                                    @endif
                                </div>
                            </section>

                            <section class="border-t border-gray-200 p-5 xl:col-span-3 xl:border-l">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">3</span>
                                    <h4 class="text-sm font-bold text-gray-900">Laboratory transport</h4>
                                </div>

                                <div class="mt-4">
                                    @if($transportation?->status === 'delivered')
                                        <div class="rounded-lg bg-green-50 p-3 text-sm text-green-800">
                                            <div class="font-semibold">✓ Delivered to laboratory</div>
                                            <div class="mt-1 font-medium text-gray-900">{{ $transportation->laboratory->name ?? 'Laboratory' }}</div>
                                            @if($transportation->arrival_time)
                                                <div class="mt-1 text-xs text-gray-500">{{ $transportation->arrival_time->format('d M Y, h:i A') }}</div>
                                            @endif
                                        </div>
                                    @elseif($transportation?->status === 'in_transit')
                                        <div class="rounded-lg bg-blue-50 p-3 text-sm text-blue-800">
                                            <div class="font-semibold">In transit</div>
                                            <div class="mt-1 font-medium text-gray-900">{{ $transportation->laboratory->name ?? 'Laboratory' }}</div>
                                            @if($transportation->scheduled_test_date)
                                                <div class="mt-1 text-xs">Test date: {{ $transportation->scheduled_test_date->format('d M Y') }}</div>
                                            @endif
                                            @if($transportation->transporter)
                                                <div class="mt-1 text-xs">Transporter: {{ $transportation->transporter->name }}</div>
                                            @endif
                                        </div>
                                    @elseif($transportation)
                                        <div class="rounded-lg bg-yellow-50 p-3 text-sm text-yellow-800">
                                            <div class="font-semibold">Transportation ready</div>
                                            <div class="mt-1 font-medium text-gray-900">{{ $transportation->laboratory->name ?? 'Laboratory' }}</div>
                                            <div class="mt-1 text-xs text-gray-500">Waiting for transport to begin.</div>
                                        </div>
                                    @elseif($homeCollection->status === 'collected')
                                        <form method="POST" action="{{ route('home-collections.send-to-laboratory', $homeCollection) }}" class="js-home-workflow-form space-y-3">
                                            @csrf
                                            <select name="laboratory_id" required class="w-full rounded-lg border-gray-300 text-sm">
                                                <option value="">Select Laboratory</option>
                                                @foreach($laboratories as $laboratory)
                                                    <option value="{{ $laboratory->id }}">
                                                        {{ $laboratory->name }} (capacity: {{ $laboratory->daily_capacity ?? 20 }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div>
                                                <label class="mb-1 block text-xs font-semibold text-gray-700">Scheduled testing date</label>
                                                <input type="date" name="scheduled_test_date"
                                                       value="{{ old('scheduled_test_date', now()->toDateString()) }}"
                                                       min="{{ now()->toDateString() }}" required
                                                       class="w-full rounded-lg border-gray-300 text-sm">
                                            </div>
                                            <button type="submit" class="w-full rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                                Send to Laboratory
                                            </button>
                                        </form>
                                    @elseif($homeCollection->status === 'cancelled')
                                        <div class="rounded-lg bg-red-50 p-3 text-sm text-red-700">Collection cancelled.</div>
                                    @else
                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500">Waiting for sample collection.</div>
                                    @endif
                                </div>
                            </section>

                            <section class="border-t border-gray-200 p-5 xl:col-span-2 xl:border-l">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">4</span>
                                    <h4 class="text-sm font-bold text-gray-900">Doctor review</h4>
                                </div>

                                <div class="mt-4">
                                    @if($sampleRequest?->assignedDoctor)
                                        <div class="rounded-lg bg-indigo-50 p-3 text-sm text-indigo-800">
                                            <div class="font-semibold">✓ Doctor assigned</div>
                                            <div class="mt-1 font-semibold text-gray-900">{{ $sampleRequest->assignedDoctor->name }}</div>
                                            @if($sampleRequest->assignedDoctor->doctorProfile?->specialization)
                                                <div class="mt-1 text-xs">{{ $sampleRequest->assignedDoctor->doctorProfile->specialization }}</div>
                                            @endif
                                        </div>
                                    @elseif($transportation?->status === 'delivered' && $sampleRequest?->status === 'approved')
                                        @if($availableDoctors->isEmpty())
                                            <div class="rounded-lg bg-yellow-50 p-3 text-sm text-yellow-800">No doctor accounts are available.</div>
                                        @else
                                            <form method="POST" action="{{ route('sample-requests.assign-doctor', $sampleRequest) }}" class="js-home-workflow-form space-y-3">
                                                @csrf
                                                <select name="assigned_doctor_id" required class="w-full rounded-lg border-gray-300 text-sm">
                                                    <option value="">Select Doctor</option>
                                                    @foreach($availableDoctors as $doctor)
                                                        <option value="{{ $doctor->id }}">
                                                            {{ $doctor->name }}@if($doctor->doctorProfile?->specialization) — {{ $doctor->doctorProfile->specialization }}@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="w-full rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                                    Assign Doctor
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <div class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500">
                                            Available after laboratory delivery.
                                        </div>
                                    @endif
                                </div>
                            </section>
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-16 text-center">
                        <div class="text-4xl">🏠</div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">No home collection requests</h3>
                        <p class="mt-2 text-sm text-gray-500">Patient home collection requests will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script type="application/json" id="admin-route-data">@json($routePlannerStops)</script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/home-route-planner-admin.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showWorkflowMessage(text, isError) {
                const existing = document.getElementById('home-workflow-toast');
                if (existing) existing.remove();

                const toast = document.createElement('div');
                toast.id = 'home-workflow-toast';
                toast.className = 'fixed right-5 top-20 z-[1000] max-w-sm rounded-xl px-5 py-4 text-sm font-semibold text-white shadow-xl '
                    + (isError ? 'bg-red-600' : 'bg-green-600');
                toast.textContent = text;
                document.body.appendChild(toast);
                window.setTimeout(() => toast.remove(), 4000);
            }

            document.addEventListener('submit', async function (event) {
                const form = event.target.closest('.js-home-workflow-form');
                if (!form) return;
                event.preventDefault();

                const article = form.closest('article[id]');
                const button = form.querySelector('button[type="submit"]');
                const originalText = button?.textContent;

                if (button) {
                    button.disabled = true;
                    button.textContent = 'Saving…';
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: new FormData(form)
                    });
                    const html = await response.text();
                    const parsed = new DOMParser().parseFromString(html, 'text/html');
                    const replacement = article ? parsed.getElementById(article.id) : null;

                    if (!response.ok || !replacement) {
                        const pageError = parsed.querySelector('.border-red-200')?.textContent.trim();
                        throw new Error(pageError || 'The action could not be completed.');
                    }

                    article.replaceWith(replacement);
                    const success = parsed.querySelector('.mb-5.border-green-200')?.textContent.trim();
                    showWorkflowMessage(success || 'Updated successfully without refreshing the page.', false);
                } catch (error) {
                    if (button) {
                        button.disabled = false;
                        button.textContent = originalText;
                    }
                    showWorkflowMessage(error.message || 'The action could not be completed.', true);
                }
            });
        });
    </script>
</x-app-layout>
