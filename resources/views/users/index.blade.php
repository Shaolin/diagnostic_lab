<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                
                <h2 class="text-xl font-semibold text-white">
                 User Management
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Manage staff and administrators for your laboratory.
                </p>
            </div>

            <a
                href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                + New User
            </a>
        </div>
    </x-slot>

    
    <div class="py-8 bg-slate-900 min-h-screen">
        
 <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-white">

            <!-- Flash Messages -->
            @if (session('success'))
    
    <div class="mb-6 rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-green-300 shadow-sm">
        <div class="flex items-center">
            <svg class="mr-2 h-5 w-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 13l4 4L19 7" />
            </svg>

            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if ($errors->any())
    
    <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300 shadow-sm">
        <div class="flex items-start">
            <svg class="mr-2 mt-0.5 h-5 w-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
            </svg>

            <div>
                <p class="font-semibold">Please fix the following errors:</p>

                <ul class="mt-2 list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

            <!-- Search & Filters -->
            
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">
    <form method="GET" action="{{ route('users.index') }}">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="mb-2 block text-sm font-medium text-slate-300">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="mb-2 block text-sm font-medium text-slate-300">
                    Role
                </label>

                <select
                    name="role"
                    id="role"
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                {{-- >
                    <option value="">All Roles</option>

                     @foreach (\App\Enums\UserRole::cases() as $role)
                   
                        <option
                            value="{{ $role->value }}"
                            @selected(request('role') === $role->value)
                        >
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select> --}}

                
<option value="">All Roles</option>

@foreach ($roles as $role)
    <option
        value="{{ $role->value }}"
        @selected(request('role') === $role->value)
    >
        {{ $role->label() }}
    </option>
@endforeach
</select>

            </div>

            <!-- Status -->
            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-300">
                    Status
                </label>

                <select
                    name="status"
                    id="status"
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">All Status</option>

                    <option value="1" @selected(request('status') === '1')>
                        Active
                    </option>

                    <option value="0" @selected(request('status') === '0')>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
            >
                Filter
            </button>

            <a
                href="{{ route('users.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-900  px-5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-700"
            >
                Reset
            </a>
        </div>
    </form>
</div>


            <!-- Users Table -->
        
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            
            <thead class="bg-slate-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Name
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Email
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Role
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Status
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Created
                    </th>

                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-300">
                        Actions
                    </th>
                </tr>
            </thead>

            
            <tbody class="divide-y divide-slate-700 bg-slate-800">

                @forelse ($users as $user)

                    <tr class="hover:bg-slate-700/50">

                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="font-semibold text-white">
                                {{ $user->name }}
                            </div>
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-slate-300">
                            {{ $user->email }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">
                                {{ $user->role->label() }}
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-6 py-4">

                            @if ($user->is_active)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Inactive
                                </span>
                            @endif

                        </td>
                        

                        <td class="whitespace-nowrap px-6 py-4 text-slate-300">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        <td class="whitespace-nowrap px-6 py-4 text-right">

                            <div class="flex justify-end gap-2">

                                <a
                                    href="{{ route('users.show', $user) }}"
                                    class="rounded-lg bg-blue-100 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-200"
                                >
                                    View
                                </a>

                                <a
                                    href="{{ route('users.edit', $user) }}"
                                    class="rounded-lg bg-amber-100 px-3 py-2 text-sm font-medium text-amber-700 hover:bg-amber-200"
                                >
                                    Edit
                                </a>

                               @if ($user->is_active && auth()->id() !== $user->id)

    <form
        action="{{ route('users.destroy', $user) }}"
        method="POST"
        onsubmit="return confirm('Are you sure you want to deactivate this user?');"
    >
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700"
        >
            Deactivate
        </button>
    </form>

@endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            No users found.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>
</div>

            <!-- Pagination -->
            @if ($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endif

        </div>
    </div>
</x-app-layout>
