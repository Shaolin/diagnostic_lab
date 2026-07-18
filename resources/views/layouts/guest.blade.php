<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Diagnostic Lab System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 font-sans antialiased">

    <div class="flex min-h-screen items-center justify-center px-6 py-10">

        <div class="w-full max-w-5xl">

            <div class="mb-8 text-center">

                <a href="{{ url('/') }}">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-xl bg-blue-600 text-3xl">
                        🧪
                    </div>

                    <h1 class="mt-4 text-3xl font-bold text-white">
                        Diagnostic Lab System
                    </h1>

                    <p class="mt-2 text-slate-400">
                        Multi-Tenant Laboratory Management Platform
                    </p>

                </a>

            </div>

            {{ $slot }}

        </div>

    </div>

</body>
</html>