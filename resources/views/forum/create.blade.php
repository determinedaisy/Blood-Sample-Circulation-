
<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-8 dark:bg-gray-950">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a
                    href="{{ route('forum.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400"
                >
                    ← Back to Forum
                </a>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

                <div class="border-b border-gray-100 px-6 py-6 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-2xl dark:bg-red-900/30">
                            ✏️
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Create a Post
                            </h1>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Share something with the community.
                            </p>
                        </div>
                    </div>
                </div>

                <form
                    action="{{ route('forum.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-6 p-6"
                >
                    @csrf

                    {{-- Validation --}}
                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                            <p class="mb-2 font-semibold text-red-800 dark:text-red-300">
                                Please fix the following:
                            </p>

                            <ul class="list-inside list-disc space-y-1 text-sm text-red-700 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Title --}}
                    <div>
                        <label
                            for="title"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200"
                        >
                            Post Title
                        </label>

                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title') }}"
                            maxlength="255"
                            required
                            placeholder="What would you like to discuss?"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                        >
                    </div>

                    {{-- Content --}}
                    <div>
                        <label
                            for="content"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200"
                        >
                            Your Message
                        </label>

                        <textarea
                            id="content"
                            name="content"
                            rows="8"
                            maxlength="10000"
                            required
                            placeholder="Write your message here..."
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                        >{{ old('content') }}</textarea>

                        <p class="mt-2 text-xs text-gray-400">
                            Maximum 10,000 characters.
                        </p>
                    </div>

                    {{-- Image --}}
                    <div>
                        <label
                            for="image"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200"
                        >
                            Add a Photo
                            <span class="font-normal text-gray-400">(optional)</span>
                        </label>

                        <input
                            id="image"
                            name="image"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full cursor-pointer rounded-xl border border-gray-300 bg-gray-50 text-sm text-gray-600 file:mr-4 file:border-0 file:bg-red-600 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-white hover:file:bg-red-700 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-400"
                        >

                        <p class="mt-2 text-xs text-gray-400">
                            JPG, JPEG, PNG or WEBP. Maximum size: 5 MB.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end dark:border-gray-800">
                        <a
                            href="{{ route('forum.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                        >
                            Publish Post
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>

