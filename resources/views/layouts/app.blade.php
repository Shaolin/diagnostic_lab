<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Diagnostic Lab System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-900">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex flex-1 flex-col ml-64">

            {{-- Top Navigation --}}
            @include('layouts.topbar')

            {{-- Page Heading --}}
            @isset($header)
                <header class="border-b border-slate-700 bg-slate-800">
                    <div class="px-8 py-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Main Content --}}
            <main class="flex-1 bg-slate-900 p-8">
                {{ $slot }}
            </main>

        </div>

    </div>
@stack('scripts')
</body>
</html>