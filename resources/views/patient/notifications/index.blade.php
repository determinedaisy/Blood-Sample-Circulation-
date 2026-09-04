<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">My Notifications</h2>
                <p class="mt-1 text-sm text-gray-500">Updates about your blood sample requests.</p>
            </div>

            @if($unreadCount > 0)
                <form method="POST" action="{{ route('patient.notifications.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-4 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = $notification->read_at === null;
                @endphp

                <article class="rounded-2xl border p-5 shadow-sm {{ $isUnread ? 'border-indigo-200 bg-indigo-50' : 'border-gray-200 bg-white' }}">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-gray-900">{{ $data['title'] ?? 'Notification' }}</h3>
                                @if($isUnread)
                                    <span class="rounded-full bg-indigo-600 px-2 py-0.5 text-xs font-semibold text-white">New</span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-700">{{ $data['message'] ?? 'You have a new update.' }}</p>
                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                                @if(!empty($data['sample_code']))
                                    <span>Sample: {{ $data['sample_code'] }}</span>
                                @endif
                                <span>{{ $notification->created_at?->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('patient.notifications.read', $notification) }}" class="shrink-0">
                            @csrf
                            @method('PATCH')
                            <button class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                {{ $data['action_label'] ?? (!empty($data['sample_request_id']) ? 'Track Request' : 'Mark as Read') }}
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white px-6 py-14 text-center shadow-sm">
                    <div class="text-4xl">🔔</div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No notifications yet</h3>
                    <p class="mt-2 text-sm text-gray-500">Updates will appear here when an administrator approves your sample request.</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="pt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
