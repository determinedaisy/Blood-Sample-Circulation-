
<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-8 dark:bg-gray-950">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            {{-- Back --}}
            <div class="mb-6">
                <a
                    href="{{ route('forum.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400"
                >
                    ← Back to Forum
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Main Post --}}
            <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                {{-- Header --}}
                <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-6 py-6 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 font-bold text-red-700 dark:bg-red-900/30 dark:text-red-300">
                            {{ strtoupper(substr($forumPost->user->name ?? 'U', 0, 1)) }}
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $forumPost->user->name ?? 'Unknown User' }}
                            </p>

                            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <span class="rounded-full bg-gray-100 px-2 py-1 dark:bg-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $forumPost->user->role ?? 'member')) }}
                                </span>

                                <span>•</span>

                                <span>
                                    {{ $forumPost->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($forumPost->user_id === auth()->id())
                        <form
                            action="{{ route('forum.destroy', $forumPost) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this post?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="rounded-lg px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                            >
                                Delete Post
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Content --}}
                <div class="px-6 py-7">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                        {{ $forumPost->title }}
                    </h1>

                    <p class="mt-5 whitespace-pre-line leading-8 text-gray-600 dark:text-gray-300">
                        {{ $forumPost->content }}
                    </p>

                    @if ($forumPost->image)
                        <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700">
                            <img
                                src="{{ asset('storage/' . $forumPost->image) }}"
                                alt="{{ $forumPost->title }}"
                                class="max-h-[650px] w-full object-cover"
                            >
                        </div>
                    @endif
                </div>

                {{-- Reactions --}}
                @php
                    $currentReaction = $forumPost->reactions
                        ->firstWhere('user_id', auth()->id())?->reaction;

                    $reactionData = [
                        'like' => ['emoji' => '👍', 'label' => 'Like'],
                        'laugh' => ['emoji' => '😂', 'label' => 'Laugh'],
                        'heart' => ['emoji' => '❤️', 'label' => 'Heart'],
                        'sad' => ['emoji' => '😢', 'label' => 'Sad'],
                    ];
                @endphp

                <div class="border-t border-gray-100 px-6 py-5 dark:border-gray-800">
                    <p class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                        React to this post
                    </p>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($reactionData as $type => $reaction)
                            @php
                                $count = $forumPost->reactions->where('reaction', $type)->count();
                                $active = $currentReaction === $type;
                            @endphp

                            <form
                                action="{{ route('forum.reactions.store', $forumPost) }}"
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
                                    class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-sm font-medium transition
                                    {{ $active
                                        ? 'border-red-300 bg-red-50 text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-300'
                                        : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800' }}"
                                >
                                    <span class="text-lg">{{ $reaction['emoji'] }}</span>
                                    <span>{{ $reaction['label'] }}</span>
                                    <span class="font-bold">{{ $count }}</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </article>

            {{-- Comments --}}
            <section class="mt-6 rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Comments
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Join the conversation.
                    </p>
                </div>

                {{-- Add Comment --}}
                <form
                    action="{{ route('forum.comments.store', $forumPost) }}"
                    method="POST"
                    class="border-b border-gray-100 p-6 dark:border-gray-800"
                >
                    @csrf

                    <label
                        for="content"
                        class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200"
                    >
                        Write a comment
                    </label>

                    <textarea
                        id="content"
                        name="content"
                        rows="4"
                        maxlength="5000"
                        required
                        placeholder="Share your thoughts..."
                        class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                    >{{ old('content') }}</textarea>

                    <div class="mt-3 flex justify-end">
                        <button
                            type="submit"
                            class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700"
                        >
                            Add Comment
                        </button>
                    </div>
                </form>

                {{-- Comment List --}}
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($forumPost->comments as $comment)
                        <div class="px-6 py-5">
                            <div class="flex gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $comment->user->name ?? 'Unknown User' }}
                                        </p>

                                        <span class="text-xs text-gray-400">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <p class="mt-2 whitespace-pre-line leading-7 text-gray-600 dark:text-gray-300">
                                        {{ $comment->content }}
                                    </p>

                                    @if ($comment->user_id === auth()->id())
                                        <form
                                            action="{{ route('forum.comments.destroy', $comment) }}"
                                            method="POST"
                                            class="mt-3"
                                            onsubmit="return confirm('Delete this comment?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-xs font-medium text-red-600 hover:underline dark:text-red-400"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-xl dark:bg-gray-800">
                                💬
                            </div>

                            <p class="font-medium text-gray-700 dark:text-gray-300">
                                No comments yet.
                            </p>

                            <p class="mt-1 text-sm text-gray-400">
                                Be the first to comment.
                            </p>
                        </div>
                    @endforelse
                </div>

            </section>

        </div>
    </div>
</x-app-layout>
