<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Test Result Status - Diagnostic Laboratory</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 text-slate-100">

    <div class="min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="bg-slate-900 border-b border-slate-800">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <a href="{{ route('home') }}"
                       class="flex items-center gap-3">

                        <div class="w-11 h-11 sm:w-12 sm:h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white text-xl sm:text-2xl">
                            🧪
                        </div>

                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-white">
                                Diagnostic Laboratory
                            </h1>

                            <p class="text-xs sm:text-sm text-slate-400">
                                Laboratory Management System
                            </p>
                        </div>

                    </a>

                    <a href="{{ route('patient.track') }}"
                       class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2.5 text-sm text-slate-400 border border-slate-700 rounded-lg hover:text-white hover:border-slate-600 transition">
                        ← Track Another Result
                    </a>

                </div>

            </div>
        </header>


        {{-- Main Content --}}
        <main class="flex-1 px-4 sm:px-6 py-10 sm:py-12">

            <div class="max-w-4xl mx-auto">

                {{-- Page Heading --}}
                <div class="text-center mb-8">

                    <div class="flex justify-center mb-5">

                        <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center text-3xl">
                            🧪
                        </div>

                    </div>

                    <h2 class="text-2xl sm:text-3xl font-bold text-white">
                        Laboratory Test Status
                    </h2>

                    <p class="mt-3 text-sm sm:text-base text-slate-400">
                        Here is the current status of your laboratory test request.
                    </p>

                </div>


                {{-- Tracking Information --}}
                <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-lg overflow-hidden">

                    <div class="p-5 sm:p-8">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            {{-- Tracking ID --}}
                            <div>
                                <p class="text-sm text-slate-500">
                                    Tracking ID
                                </p>

                                <p class="mt-1 text-base font-semibold text-white">
                                    {{ $testRequest->tracking_code }}
                                </p>
                            </div>


                            {{-- Request Date --}}
                            <div>
                                <p class="text-sm text-slate-500">
                                    Request Date
                                </p>

                                <p class="mt-1 text-base font-semibold text-white">
                                    {{ $testRequest->created_at->format('d M Y') }}
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Overall Status --}}
                    <div class="border-t border-slate-800 p-5 sm:p-8">

                        <p class="text-sm text-slate-500">
                            Overall Status
                        </p>

                        <div class="mt-3">

                            @if ($testRequest->overall_status === 'Completed')

                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                    ✓ Completed
                                </span>

                            @elseif ($testRequest->overall_status === 'In Progress')

                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    ⟳ In Progress
                                </span>

                            @elseif ($testRequest->overall_status === 'Partially Completed')

                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                    ◐ Partially Completed
                                </span>

                            @else

                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                    Pending
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Test Items --}}
                    <div class="border-t border-slate-800">

                        <div class="p-5 sm:p-8">

                            <h3 class="text-lg font-semibold text-white mb-5">
                                Tests
                            </h3>

                            <div class="space-y-4">

                                @foreach ($testRequest->items as $item)

                                    <div class="bg-slate-950 border border-slate-800 rounded-xl p-4 sm:p-5">

                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                            <div>

                                                <h4 class="font-semibold text-white">
                                                    {{ $item->test_name }}
                                                </h4>

                                                <p class="mt-1 text-sm text-slate-500">
                                                    Sample:
                                                    {{ $item->sample_status }}
                                                </p>

                                            </div>


                                           <div class="flex flex-wrap gap-2">

    {{-- Test Status --}}
    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400">
        {{ $item->status }}
    </span>


    {{-- Result Status --}}
    @if ($item->result_status === 'Ready' || $item->result_status === 'Sent')

        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400">
            Result Ready
        </span>

    @else

        <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-500/10 text-slate-400">
            {{ $item->result_status }}
        </span>

    @endif

</div>

@if ($item->result && $item->result->pdf_path)

    <div class="mt-4 flex flex-col sm:flex-row gap-3">

        {{-- View Result --}}
       <a href="{{ route('patient.result.view', [
        'trackingCode' => $testRequest->tracking_code,
        'result' => $item->result->id
    ]) }}"
   target="_blank"
   class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
    View Result
</a>


        {{-- Download Result --}}
        <a href="{{ route('patient.result.download', [
        'trackingCode' => $testRequest->tracking_code,
        'result' => $item->result->id
    ]) }}"
   class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
    Download Result
</a>

    </div>

@endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

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