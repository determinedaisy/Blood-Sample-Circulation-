<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Blood Bank System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#101B24', inksoft: '#55636D', surface: '#FFFFFF', bg: '#F5F8F9',
                        line: '#DEE5E8', linesoft: '#EDF1F2', blood: '#C41E3A', blooddark: '#9E1730',
                        cold: '#0E7C86', coldsoft: '#E3F1F1', amber: '#B5730C', ambersoft: '#FBF0DF',
                    },
                    fontFamily: {
                        mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
                        sans: ['"IBM Plex Sans"', 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
    <style>
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
        .select-chevron {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2355636D' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.1rem;
        }
    </style>
</head>
<body class="bg-bg text-ink font-sans antialiased min-h-screen flex flex-col">

    <header class="border-b border-line bg-surface">
        <div class="mx-auto max-w-5xl px-5 sm:px-8 py-4 flex items-center gap-3">
            <a href="{{ url('/') }}" class="text-inksoft hover:text-ink transition-colors" aria-label="Back to home">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <div class="leading-tight">
                <p class="font-mono text-[11px] tracking-[0.2em] text-cold uppercase font-semibold">Blood Bank System</p>
                <h1 class="text-lg font-semibold tracking-tight text-ink -mt-0.5">Sample Circulation</h1>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-5 py-12">
        <div class="w-full max-w-md">
            <div class="rounded-lg border border-line bg-surface p-8 sm:p-10">
                <div class="text-center mb-7">
                    <svg class="w-9 h-9 mx-auto text-blood" viewBox="0 0 24 24" fill="none"><path d="M12 2.625c-1.42 2.14-7.5 10.975-7.5 14.625a7.5 7.5 0 0015 0c0-3.65-6.08-12.485-7.5-14.625z" stroke="currentColor" stroke-width="1.6"/></svg>
                    <h2 class="text-2xl font-bold tracking-tight text-ink mt-3">Create an account</h2>
                    <p class="text-sm text-inksoft mt-1">Register for the blood sample circulation system.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-ink mb-1">Full name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="block w-full rounded-md border px-3 py-2.5 text-sm text-ink shadow-sm focus:outline-none focus:ring-1
                                      {{ $errors->has('name') ? 'border-blood focus:border-blood focus:ring-blood' : 'border-line focus:border-cold focus:ring-cold' }}">
                        @error('name')
                            <p class="mt-1.5 text-xs font-medium text-blood">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-ink mb-1">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="block w-full rounded-md border px-3 py-2.5 text-sm text-ink shadow-sm focus:outline-none focus:ring-1
                                      {{ $errors->has('email') ? 'border-blood focus:border-blood focus:ring-blood' : 'border-line focus:border-cold focus:ring-cold' }}">
                        @error('email')
                            <p class="mt-1.5 text-xs font-medium text-blood">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-ink mb-1">Register as</label>
                        <select id="role" name="role" required
                                class="select-chevron appearance-none block w-full rounded-md border px-3 py-2.5 pr-9 text-sm text-ink shadow-sm focus:outline-none focus:ring-1
                                       {{ $errors->has('role') ? 'border-blood focus:border-blood focus:ring-blood' : 'border-line focus:border-cold focus:ring-cold' }}">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your account type</option>
                            <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient / Donor</option>
                            <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Medical Staff / Doctor</option>
                        </select>
                        @error('role')
                            <p class="mt-1.5 text-xs font-medium text-blood">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-ink mb-1">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   class="block w-full rounded-md border px-3 py-2.5 pr-10 text-sm text-ink shadow-sm focus:outline-none focus:ring-1
                                          {{ $errors->has('password') ? 'border-blood focus:border-blood focus:ring-blood' : 'border-line focus:border-cold focus:ring-cold' }}">
                            <button type="button" data-toggle-password="password" aria-label="Show password"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-inksoft hover:text-ink">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs font-medium text-blood">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-ink mb-1">Confirm password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   class="block w-full rounded-md border border-line px-3 py-2.5 pr-10 text-sm text-ink shadow-sm focus:border-cold focus:outline-none focus:ring-1 focus:ring-cold">
                            <button type="button" data-toggle-password="password_confirmation" aria-label="Show password"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-inksoft hover:text-ink">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full inline-flex justify-center items-center rounded-md bg-blood py-2.5 px-4 text-sm font-semibold text-white shadow-sm hover:bg-blooddark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blood transition-colors">
                        Register
                    </button>

                    <p class="text-center text-sm text-inksoft">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-blood hover:text-blooddark">Log in here</a>
                    </p>
                </form>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-sm text-inksoft hover:text-ink">&larr; Back to home</a>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.getAttribute('data-toggle-password'));
                if (!input) return;
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        });
    </script>
</body>
</html>