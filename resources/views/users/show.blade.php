<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-xl font-semibold text-white">
                    User Details
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    View information about this user.
                </p>
            </div>

            <a href="{{ route('users.index') }}"
               class="inline-flex items-center justify-center rounded-md bg-slate-600 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
                ← Back to Users
            </a>

        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            {{-- User Details Card --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">
        <h3 class="text-lg font-semibold text-white">
            User Information
        </h3>
    </div>

    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

        {{-- Full Name --}}
        <div>
            <p class="mb-1 text-sm font-medium text-slate-400">
                Full Name
            </p>

            <p class="text-white">
                {{ $user->name }}
            </p>
        </div>

        {{-- Email --}}
        <div>
            <p class="mb-1 text-sm font-medium text-slate-400">
                Email Address
            </p>

            <p class="text-white">
                {{ $user->email }}
            </p>
        </div>

        {{-- Laboratory --}}
        <div>
            <p class="mb-1 text-sm font-medium text-slate-400">
                Laboratory
            </p>

            <p class="text-white">
                {{ $user->laboratory->name }}
            </p>
        </div>

        {{-- Role --}}
        <div>
            <p class="mb-1 text-sm font-medium text-slate-400">
                Role
            </p>

            <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">
                {{ $user->role->label() }}
            </span>
        </div>

        {{-- Status --}}
        <div>
            <p class="mb-1 text-sm font-medium text-slate-400">
                Status
            </p>

            @if ($user->is_active)

                <span class="rounded-full bg-green-600 px-3 py-1 text-xs font-semibold text-white">
                    Active
                </span>

            @else

                <span class="rounded-full bg-red-600 px-3 py-1 text-xs font-semibold text-white">
                    Inactive
                </span>

            @endif
        </div>

        {{-- Created --}}
        <div>
            <p class="mb-1 text-sm font-medium text-slate-400">
                Date Created
            </p>

            <p class="text-white">
                {{ $user->created_at->format('d M Y h:i A') }}
            </p>
        </div>

        {{-- Updated --}}
        <div>
            <p class="mb-1 text-sm font-medium text-slate-400">
                Last Updated
            </p>

            <p class="text-white">
                {{ $user->updated_at->format('d M Y h:i A') }}
            </p>
        </div>

    </div>

    <div class="flex justify-end gap-3 border-t border-slate-700 p-6">

        <a href="{{ route('users.edit', $user) }}"
           class="rounded-md bg-yellow-500 px-5 py-2 text-white hover:bg-yellow-600">
            Edit User
        </a>

        <a href="{{ route('users.index') }}"
           class="rounded-md bg-slate-600 px-5 py-2 text-white hover:bg-slate-700">
            Back
        </a>

    </div>

</div>

        </div>
    </div>
</x-app-layout>