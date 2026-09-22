<x-app-layout>
    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-white">
                    Audit Trail
                </h1>
                <p class="mt-1 text-sm text-slate-400">
                    Track important accounting activities and changes.
                </p>
            </div>

            {{-- Filters --}}
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-900 p-5 shadow-lg">

                <form method="GET"
                      action="{{ route('accounting.audit-trail') }}"
                      class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">

                    {{-- From --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            From
                        </label>

                        <input type="date"
                               name="from"
                               value="{{ request('from') }}"
                               class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- To --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            To
                        </label>

                        <input type="date"
                               name="to"
                               value="{{ request('to') }}"
                               class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- Action --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            Action
                        </label>

                        <select name="action"
                                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">All Actions</option>

                            @foreach($actions as $action)
                                <option value="{{ $action }}"
                                    {{ request('action') === $action ? 'selected' : '' }}>
                                    {{ $action }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Module --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            Module
                        </label>

                        <select name="module"
                                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">All Modules</option>

                            @foreach($modules as $module)
                                <option value="{{ $module }}"
                                    {{ request('module') === $module ? 'selected' : '' }}>
                                    {{ $module }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- User --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            User
                        </label>

                        <select name="user_id"
                                class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">All Users</option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end gap-2 lg:col-span-5">

                        <button type="submit"
                                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            Filter
                        </button>

                        <a href="{{ route('accounting.audit-trail') }}"
                           class="rounded-lg border border-slate-700 bg-slate-800 px-5 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-slate-700 hover:text-white">
                            Clear
                        </a>

                    </div>

                </form>
            </div>

            {{-- Audit Logs --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900 shadow-lg">

                <div class="border-b border-slate-700 px-5 py-4">
                    <h2 class="text-lg font-semibold text-white">
                        Activity History
                    </h2>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Date / Time
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    User
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Branch
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Action
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Module
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Reference
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Description
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @forelse($logs as $log)

                                <tr class="transition hover:bg-slate-800/50">

                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">
                                        {{ $log->created_at->format('d M Y, h:i A') }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-white">
                                        {{ $log->user?->name ?? 'System' }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">
                                        {{ $log->branch?->name ?? 'Head Office' }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="inline-flex rounded-full bg-blue-500/10 px-2.5 py-1 text-xs font-medium text-blue-400">
                                            {{ $log->action }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">
                                        {{ $log->module ?? '—' }}
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">
                                        {{ $log->reference ?? '—' }}
                                    </td>

                                    <td class="max-w-md px-4 py-4 text-sm text-slate-300">
                                        {{ $log->description ?? '—' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">

                                        <div class="text-4xl">
                                            📝
                                        </div>

                                        <p class="mt-3 text-sm font-medium text-slate-300">
                                            No audit records found
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Important accounting activities will appear here.
                                        </p>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                @if($logs->hasPages())
                    <div class="border-t border-slate-700 px-5 py-4">
                        {{ $logs->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>