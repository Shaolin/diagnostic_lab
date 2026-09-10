<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-xl font-semibold text-white">
                    Create User
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Create a new administrator or staff member for your laboratory.
                </p>
            </div>

            <a href="{{ route('users.index') }}"
               class="inline-flex items-center justify-center rounded-md bg-slate-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-slate-700">
                ← Back to Users
            </a>

        </div>
    </x-slot>

    <div class="py-8 bg-slate-900 min-h-screen">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            {{-- Validation Errors --}}
            @if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300 shadow-sm">

        <p class="font-semibold">
            Please correct the following errors:
        </p>

        <ul class="mt-2 list-disc pl-5 text-sm">

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>
@endif

            {{-- User Form --}}
            <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <form action="{{ route('users.store') }}"
          method="POST">

        @csrf

        <div class="p-6">

            {{-- Form Fields Here --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- Full Name --}}
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-300">
            Full Name
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="mb-2 block text-sm font-medium text-slate-300">
            Email Address
        </label>

        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

    {{-- Role --}}
    <div>
        <label for="role" class="mb-2 block text-sm font-medium text-slate-300">
            Role
        </label>

        <select
            id="role"
            name="role"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500"
            required
        {{-- >
            <option value="">Select Role</option>

            @foreach (\App\Enums\UserRole::cases() as $role)
                <option
                    value="{{ $role->value }}"
                    @selected(old('role') == $role->value)
                >
                    {{ $role->label() }}
                </option>
            @endforeach

        </select> --}}

        
<option value="">Select Role</option>

@foreach ($roles as $role)
    <option
        value="{{ $role->value }}"
        @selected(old('role') == $role->value)
    >
        {{ $role->label() }}
    </option>
@endforeach

</select>

    </div>

    {{-- Active --}}
    <div class="flex items-end">
        <label class="inline-flex items-center gap-3 text-slate-300">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', true))
                class="rounded border-slate-500 bg-slate-900 text-indigo-600 focus:ring-indigo-500"
            >

            Active User
        </label>
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="mb-2 block text-sm font-medium text-slate-300">
            Password
        </label>

        <input
            type="password"
            id="password"
            name="password"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

    {{-- Confirm Password --}}
    <div>
        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-300">
            Confirm Password
        </label>

        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2 text-white focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
    </div>

</div>

        </div>

        <div class="flex justify-end gap-3 border-t border-slate-700 p-6">

            <a href="{{ route('users.index') }}"
               class="rounded-md bg-slate-600 px-5 py-2 text-white hover:bg-slate-700">
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-md bg-indigo-600 px-5 py-2 text-white hover:bg-indigo-700">

                Save User

            </button>

        </div>

    </form>

</div>

        </div>
    </div>
</x-app-layout>