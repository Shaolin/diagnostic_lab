<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Diagnostic Laboratory</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 text-slate-100">

    <div class="min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="bg-slate-900 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    {{-- Laboratory Branding --}}
                    <div class="flex items-center gap-3">

                        <div class="w-11 h-11 sm:w-12 sm:h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white text-xl sm:text-2xl flex-shrink-0">
                            🧪
                        </div>

                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-white leading-tight">
                                Diagnostic Laboratory
                            </h1>

                            <p class="text-xs sm:text-sm text-slate-400">
                                Laboratory Management System
                            </p>
                        </div>

                    </div>


                    {{-- Navigation --}}
                    @auth

                        <a href="{{ route('dashboard') }}"
                           class="w-full sm:w-auto text-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Dashboard
                        </a>

                    @else

                        <div class="grid grid-cols-2 gap-3 sm:flex sm:items-center sm:gap-3">

                            <a href="{{ route('login') }}"
                               class="text-center px-3 sm:px-4 py-2.5 text-blue-400 font-medium hover:text-blue-300 transition">
                                Staff Login
                            </a>

                            <a href="{{ route('register') }}"
                               class="text-center px-3 sm:px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Register Lab
                            </a>

                        </div>

                    @endauth

                </div>

            </div>
        </header>


        {{-- Main Content --}}
        <main class="flex-1 bg-slate-950">

            {{-- Hero Section --}}
            <section class="py-12 sm:py-16 lg:py-20">

                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                    <div class="inline-flex items-center px-3 sm:px-4 py-2 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-full text-xs sm:text-sm font-medium mb-5 sm:mb-6">
                        🧪 Simple • Secure • Reliable
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                        Laboratory Management Made Simple
                    </h2>

                    <p class="mt-5 sm:mt-6 text-base sm:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                        Manage laboratory operations efficiently and allow patients
                        to conveniently track the progress of their test results.
                    </p>

                </div>

            </section>


            {{-- Access Cards --}}
            <section class="pb-12 sm:pb-16 lg:pb-20">

                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 lg:gap-8">

                        {{-- Staff Card --}}
                        <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-6 sm:p-8 hover:border-slate-700 hover:shadow-xl transition">

                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-5 sm:mb-6">
                                👨‍⚕️
                            </div>

                            <h3 class="text-xl sm:text-2xl font-bold text-white">
                                Laboratory Staff
                            </h3>

                            <p class="mt-3 text-sm sm:text-base text-slate-400 leading-relaxed">
                                Access the laboratory management system to manage patients,
                                test requests, samples, results, and payments.
                            </p>

                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center w-full mt-6 sm:mt-8 px-5 py-3.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Staff Login
                            </a>

                        </div>


                        {{-- Patient Card --}}
                        <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-6 sm:p-8 hover:border-slate-700 hover:shadow-xl transition">

                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-green-500/10 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mb-5 sm:mb-6">
                                🔍
                            </div>

                            <h3 class="text-xl sm:text-2xl font-bold text-white">
                                Patient Result Tracking
                            </h3>

                            <p class="mt-3 text-sm sm:text-base text-slate-400 leading-relaxed">
                                Enter your tracking ID to check the current status
                                of your laboratory test and access your result when ready.
                            </p>

                            <a href="{{ route('patient.track') }}"
                               class="inline-flex items-center justify-center w-full mt-6 sm:mt-8 px-5 py-3.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                                Track My Result
                            </a>

                        </div>

                    </div>

                </div>

            </section>

        </main>


        {{-- Footer --}}
        <footer class="bg-slate-900 border-t border-slate-800">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6 text-center text-xs sm:text-sm text-slate-500">
                &copy; {{ date('Y') }} Diagnostic Laboratory Management System.
                All rights reserved.
            </div>

        </footer>

    </div>

</body>
</html>