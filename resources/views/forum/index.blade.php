
<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-950">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="mb-2 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-2xl dark:bg-red-900/30">
                            💬
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                Community Forum
                            </h1>

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Connect, discuss, and share with the community.
                            </p>
                        </div>
                    </div>
                </div>

                <a
                    href="{{ route('forum.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
                >
                    <span class="text-lg">＋</span>
                    Create Post
                </a>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Posts --}}
            @forelse ($posts as $post)
                <article class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">

                    {{-- Post Header --}}
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-5 dark:border-gray-800 sm:px-6">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-100 font-bold text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate font-semibold text-gray-900 dark:text-white">
                                    {{ $post->user->name ?? 'Unknown User' }}
                                </p>

                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-800">
                                        {{ ucfirst(str_replace('_', ' ', $post->user->role ?? 'member')) }}
                                    </span>

                                    <span>•</span>

                                    <span>
                                        {{ $post->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if ($post->user_id === auth()->id())
                            <form
                                action="{{ route('forum.destroy', $post) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this post?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                                >
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Post Content --}}
                    <div class="px-5 py-6 sm:px-6">
                        <a href="{{ route('forum.show', $post) }}" class="group">
                            <h2 class="mb-3 text-xl font-bold text-gray-900 transition group-hover:text-red-600 dark:text-white dark:group-hover:text-red-400">
                                {{ $post->title }}
                            </h2>
                        </a>

                        <p class="whitespace-pre-line leading-7 text-gray-600 dark:text-gray-300">
                            {{ $post->content }}
                        </p>

                        @if ($post->image)
                            <div class="mt-5 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700">
                                <img
                                    src="{{ asset('storage/' . $post->image) }}"
                                    alt="{{ $post->title }}"
                                    class="max-h-[500px] w-full object-cover"
                                >
                            </div>
                        @endif
                    </div>

                    {{-- Reactions --}}
                    @php
                        $currentReaction = $post->reactions
                            ->firstWhere('user_id', auth()->id())?->reaction;

                        $reactionData = [
                            'like' => ['emoji' => '👍', 'label' => 'Like'],
                            'laugh' => ['emoji' => '😂', 'label' => 'Laugh'],
                            'heart' => ['emoji' => '❤️', 'label' => 'Heart'],
                            'sad' => ['emoji' => '😢', 'label' => 'Sad'],
                        ];
                    @endphp

                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach ($reactionData as $type => $reaction)
                                @php
                                    $count = $post->reactions->where('reaction', $type)->count();
                                    $active = $currentReaction === $type;
                                @endphp

                                <form
                                    action="{{ route('forum.reactions.store', $post) }}"
                                    method="POST"
                                >
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="reaction"
                                        value="{{ $type }}"
                                    >

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-sm transition
                                        {{ $active
                                            ? 'border-red-300 bg-red-50 text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-300'
                                            : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}"
                                    >
                                        <span>{{ $reaction['emoji'] }}</span>
                                        <span>{{ $count }}</span>
                                    </button>
                                </form>
                            @endforeach

                            <a
                                href="{{ route('forum.show', $post) }}"
                                class="ml-auto inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                            >
                                💬
                                {{ $post->comments_count }}
                                {{ $post->comments_count === 1 ? 'Comment' : 'Comments' }}
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center dark:border-gray-700 dark:bg-gray-900">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-3xl dark:bg-red-900/30">
                        💬
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        No posts yet
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-gray-500 dark:text-gray-400">
                        Be the first person to start a conversation with the community.
                    </p>

                    <a
                        href="{{ route('forum.create') }}"
                        class="mt-6 inline-flex items-center rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700"
                    >
                        Create the First Post
                    </a>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if ($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>


