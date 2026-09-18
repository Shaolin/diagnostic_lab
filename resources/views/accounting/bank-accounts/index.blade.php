<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-white">
                    Bank Accounts
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Manage the laboratory's bank accounts.
                </p>
            </div>

            <a href="{{ route('accounting.bank-accounts.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">
                <span>+</span>
                Add Bank Account
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Bank Accounts --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900">

                <div class="border-b border-slate-700 px-5 py-4">
                    <h3 class="font-semibold text-white">
                        Bank Accounts
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-800">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Bank
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Account Name
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Account Number
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Branch
                                </th>

                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Opening Balance
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @forelse ($bankAccounts as $bankAccount)

                                <tr class="hover:bg-slate-800/50">

                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-white">
                                        {{ $bankAccount->bank_name }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-300">
                                        {{ $bankAccount->account_name }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-300">
                                        {{ $bankAccount->account_number ?? '—' }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-300">
                                       {{ $bankAccount->branch?->name ?? 'Whole Laboratory' }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-white">
                                        ₦{{ number_format((float) $bankAccount->opening_balance, 2) }}
                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        @if ($bankAccount->is_active)
                                            <span class="inline-flex rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-700 px-3 py-1 text-xs font-medium text-slate-400">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">

                                        <div class="text-4xl">🏦</div>

                                        <p class="mt-3 text-sm font-medium text-slate-300">
                                            No bank accounts found.
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Add the laboratory's first bank account to get started.
                                        </p>

                                        <a href="{{ route('accounting.bank-accounts.create') }}"
                                           class="mt-4 inline-flex rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                            Add Bank Account
                                        </a>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>

                @if ($bankAccounts->hasPages())
                    <div class="border-t border-slate-700 px-5 py-4">
                        {{ $bankAccounts->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>