
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Record Insurance Settlement
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Record a payment actually received from the insurance company.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Claim Summary --}}
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-white">
                        {{ $insuranceClaim->insurance_company }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-400">
                        Insurance claim summary
                    </p>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-slate-400">Patient</p>
                        <p class="mt-1 font-medium text-white">
                            {{ $insuranceClaim->testRequest->patient->full_name ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Tracking Code</p>
                        <p class="mt-1 font-medium text-slate-200">
                            {{ $insuranceClaim->testRequest->tracking_code ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-lg border border-slate-700 bg-slate-900/60 p-4">
                        <p class="text-xs font-medium text-slate-400">Claim Amount</p>
                        <p class="mt-2 text-lg font-bold text-white">
                            ₦{{ number_format((float) $insuranceClaim->covered_amount, 2) }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-green-800 bg-green-950/20 p-4">
                        <p class="text-xs font-medium text-slate-400">Already Settled</p>
                        <p class="mt-2 text-lg font-bold text-green-400">
                            ₦{{ number_format((float) $insuranceClaim->totalSettled(), 2) }}
                        </p>
                    </div>

                    <div class="rounded-lg border border-red-800 bg-red-950/20 p-4">
                        <p class="text-xs font-medium text-slate-400">Outstanding Balance</p>
                        <p class="mt-2 text-lg font-bold text-red-400">
                            ₦{{ number_format((float) $insuranceClaim->outstandingAmount(), 2) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Settlement Form --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
                <div class="border-b border-slate-700 px-6 py-5">
                    <h3 class="text-lg font-semibold text-white">
                        Settlement Information
                    </h3>
                    <p class="mt-1 text-sm text-slate-400">
                        Enter the amount received and the transaction details.
                    </p>
                </div>

                <div class="p-6">

                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-700 bg-red-950/50 p-4">
                            <p class="mb-2 font-semibold text-red-300">
                                Please correct the following errors:
                            </p>
                            <ul class="list-inside list-disc space-y-1 text-sm text-red-200">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($insuranceClaim->outstandingAmount() > 0)
                        <div class="mb-6 rounded-lg border border-indigo-800 bg-indigo-950/30 p-4">
                            <p class="text-sm text-slate-200">
                                The amount entered cannot exceed the outstanding balance of
                                <strong>
                                    ₦{{ number_format((float) $insuranceClaim->outstandingAmount(), 2) }}
                                </strong>.
                            </p>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('insurance-claims.payments.store', $insuranceClaim) }}">
                        @csrf

                        {{-- Amount --}}
                        <div class="mb-5">
                            <label for="amount"
                                   class="mb-2 block text-sm font-medium text-slate-300">
                                Amount Received (₦)
                                <span class="text-red-400">*</span>
                            </label>

                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                value="{{ old('amount') }}"
                                required
                                min="0.01"
                                max="{{ number_format((float) $insuranceClaim->outstandingAmount(), 2, '.', '') }}"
                                step="0.01"
                                placeholder="Enter amount received"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                            @error('amount')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date Received --}}
                        <div class="mb-5">
                            <label for="paid_at"
                                   class="mb-2 block text-sm font-medium text-slate-300">
                                Date Received
                                <span class="text-red-400">*</span>
                            </label>

                            <input
                                type="datetime-local"
                                name="paid_at"
                                id="paid_at"
                                value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                            @error('paid_at')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Transaction Reference --}}
                        <div class="mb-5">
                            <label for="payment_reference"
                                   class="mb-2 block text-sm font-medium text-slate-300">
                                Bank Transaction Reference
                            </label>

                            <input
                                type="text"
                                name="payment_reference"
                                id="payment_reference"
                                value="{{ old('payment_reference') }}"
                                maxlength="255"
                                placeholder="Optional transaction reference"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                            @error('payment_reference')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remarks --}}
                        <div class="mb-6">
                            <label for="remarks"
                                   class="mb-2 block text-sm font-medium text-slate-300">
                                Remarks
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                maxlength="2000"
                                placeholder="Optional settlement notes..."
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >{{ old('remarks') }}</textarea>

                            @error('remarks')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col-reverse gap-3 border-t border-slate-700 pt-5 sm:flex-row sm:items-center sm:justify-end">
                            <a href="{{ route('insurance-claims.show', $insuranceClaim) }}"
                               class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-700 px-5 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-slate-600">
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-800"
                            >
                                Record Settlement
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
