<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Blood Sample Circulation System') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex flex-col min-h-screen">

    <!-- Navigation Bar -->
    <header class="w-full bg-white dark:bg-gray-800 shadow-sm relative z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Brand Name -->
                <div class="flex items-center gap-3 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#e2136e" class="w-8 h-8">
                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                    </svg>
                    <span class="font-extrabold text-xl tracking-tight text-gray-900 dark:text-white hidden sm:block">
                        Blood Circulation Inc.
                    </span>
                </div>

                <!-- Right Side Auth Links -->
                @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-gray-900 hover:bg-gray-700 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200 rounded-xl transition shadow-sm">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-[#e2136e] hover:bg-[#c70f61] rounded-xl transition shadow-sm">
                                    Register Account
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Hero Section -->
    <main class="flex-grow flex items-center justify-center relative overflow-hidden">
        
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-pink-100 dark:bg-pink-900/20 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute top-40 -left-20 w-72 h-72 bg-blue-100 dark:bg-blue-900/20 rounded-full blur-3xl opacity-50"></div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-pink-50 dark:bg-pink-900/30 text-[#e2136e] dark:text-pink-400 font-semibold text-sm mb-8 border border-pink-100 dark:border-pink-800/50">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#e2136e] opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-[#e2136e]"></span>
                </span>
                Live Tracking Enabled
            </div>

            <h1 class="text-5xl md:text-6xl font-black tracking-tight text-gray-900 dark:text-white mb-6 leading-tight">
                Advanced Blood Sample <br class="hidden md:block"/> 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#e2136e] to-pink-500">Tracking & Circulation</span>
            </h1>
            
            <p class="mt-4 text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                A highly secure, end-to-end lifecycle management platform. Track samples from patient collection to laboratory testing with complete transparency, integrated bKash lab fees, and instant digital reports.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-[#e2136e] hover:bg-[#c70f61] rounded-2xl transition shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Open My Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-[#e2136e] hover:bg-[#c70f61] rounded-2xl transition shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Get Started as Patient
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-gray-900 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 rounded-2xl transition shadow-sm">
                        Staff & Admin Login
                    </a>
                @endauth
            </div>

            <!-- Trust Badges / Stats -->
            <div class="mt-20 pt-10 border-t border-gray-200 dark:border-gray-800 grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white">100%</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Digital Tracking</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white">Secure</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">QR Code Identity</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white">Instant</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">bKash Payments</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white">24/7</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Status Updates</p>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-8 text-center mt-auto">
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
            &copy; {{ date('Y') }} Blood Circulation Inc. All rights reserved.
        </p>
    </footer>

</body>
</html>