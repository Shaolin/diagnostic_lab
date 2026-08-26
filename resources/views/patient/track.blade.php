<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Track Your Result - Diagnostic Laboratory</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 text-slate-100">

    <div class="min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="bg-slate-900 border-b border-slate-800">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    {{-- Laboratory Branding --}}
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-3">

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

                    </a>


                    {{-- Back Button --}}
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 text-sm text-slate-400 border border-slate-700 rounded-lg hover:text-white hover:border-slate-600 transition">
                        ← Back to Home
                    </a>

                </div>

            </div>
        </header>


        {{-- Main Content --}}
        <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-10 sm:py-12 lg:py-16">

            <div class="w-full max-w-lg">

                {{-- Icon --}}
                <div class="flex justify-center mb-5 sm:mb-6">

                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-500/10 rounded-2xl flex items-center justify-center text-3xl sm:text-4xl">
                        🔍
                    </div>

                </div>


                {{-- Heading --}}
                <div class="text-center mb-6 sm:mb-8">

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white leading-tight">
                        Track Your Laboratory Result
                    </h2>

                    <p class="mt-3 text-sm sm:text-base text-slate-400 leading-relaxed">
                        Enter the tracking ID provided by the laboratory to check the
                        current status of your test and access your result when ready.
                    </p>

                </div>


                {{-- Tracking Form --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-lg p-5 sm:p-8">

                    @if (session('error'))
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg">
        {{ session('error') }}
    </div>
@endif

                   <form action="{{ route('patient.track.search') }}" method="GET">

                        <div>

                            <label for="tracking_code"
                                   class="block text-sm font-medium text-slate-300 mb-2">
                                Tracking ID
                            </label>

                            <input
                                type="text"
                                name="tracking_code"
                                id="tracking_code"
                                placeholder="Enter your tracking ID"
                                class="w-full bg-slate-950 border border-slate-700 text-white rounded-lg px-4 py-3.5 sm:py-4 text-base placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >

                        </div>


                        <button
                            type="submit"
                            class="w-full mt-5 sm:mt-6 px-5 py-3.6 sm:py-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition"
                        >
                            Track My Result
                        </button>

                    </form>


                    {{-- Help Text --}}
                    <div class="mt-5 sm:mt-6 pt-5 sm:pt-6 border-t border-slate-800 text-center">

                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Your tracking ID was provided to you by the laboratory.
                        </p>

                    </div>

                </div>

            </div>

        </main>


        {{-- Footer --}}
        <footer class="bg-slate-900 border-t border-slate-800">

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-6 text-center text-xs sm:text-sm text-slate-500">

                &copy; {{ date('Y') }} Diagnostic Laboratory Management System.
                All rights reserved.

            </div>

        </footer>

    </div>

</body>
</html>