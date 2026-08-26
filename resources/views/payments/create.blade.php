<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-semibold text-white">
                Record Payment
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Record a payment for this laboratory test request.
            </p>

        </div>

    </x-slot>


    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-5xl px-4 text-white sm:px-6 lg:px-8">


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-4 text-red-300">

                    <p class="font-semibold">
                        Please fix the following errors:
                    </p>

                    <ul class="mt-2 list-disc pl-5 text-sm">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Request Information --}}
            <div class="mb-6 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Test Request
                            </p>

                            <h3 class="mt-1 font-mono text-lg font-semibold text-indigo-400">
                                {{ $testRequest->tracking_code }}
                            </h3>

                        </div>


                        <div class="text-left md:text-right">

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Patient
                            </p>

                            <p class="mt-1 font-semibold text-white">
                                {{ $testRequest->patient->full_name }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ $testRequest->patient->patient_number }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Tests --}}
                <div class="border-b border-slate-700 px-6 py-5">

                    <h4 class="mb-4 text-sm font-semibold text-slate-300">
                        Tests Requested
                    </h4>

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead>

                                <tr class="border-b border-slate-700">

                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Test
                                    </th>

                                    <th class="pb-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Amount
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-slate-700">

                                @foreach($testRequest->items as $item)

                                    <tr>

                                        <td class="py-3 text-sm text-slate-300">
                                            {{ $item->test_name }}
                                        </td>

                                        <td class="py-3 text-right text-sm font-medium text-slate-300">
                                            ₦{{ number_format((float) $item->price, 2) }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- Payment Summary --}}
                <div class="grid grid-cols-1 divide-y divide-slate-700 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

                    {{-- Total --}}
                    <div class="px-6 py-5">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total Amount
                        </p>

                        <p class="mt-2 text-2xl font-bold text-white">
                            ₦{{ number_format((float) $testRequest->total_amount, 2) }}
                        </p>

                    </div>


                    {{-- Paid --}}
                    <div class="px-6 py-5">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Amount Paid
                        </p>

                        <p class="mt-2 text-2xl font-bold text-emerald-400">
                            ₦{{ number_format($testRequest->totalPaid(), 2) }}
                        </p>

                    </div>


                    {{-- Balance --}}
                    <div class="px-6 py-5">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Outstanding Balance
                        </p>

                        <p class="mt-2 text-2xl font-bold text-yellow-400">
                            ₦{{ number_format($testRequest->balance(), 2) }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Payment Form --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Payment Details
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Enter the details of the payment received.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('payments.store', $testRequest) }}"
                    class="p-6"
                >

                    @csrf


                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">


                        {{-- Amount --}}
                        <div>

                            <label
                                for="amount"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Amount Paid
                                <span class="text-red-400">*</span>
                            </label>

                            <div class="relative">

                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm text-slate-500">
                                    ₦
                                </span>

                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    value="{{ old('amount') }}"
                                    min="0.01"
                                    max="{{ $testRequest->balance() }}"
                                    step="0.01"
                                    required
                                    class="block w-full rounded-lg border border-slate-600 bg-slate-900 py-3 pl-9 pr-4 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="0.00"
                                >

                            </div>

                            <p class="mt-2 text-xs text-slate-500">
                                Maximum payment:
                                ₦{{ number_format($testRequest->balance(), 2) }}
                            </p>

                            @error('amount')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Payment Method --}}
                        <div>

                            <label
                                for="payment_method"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Payment Method
                                <span class="text-red-400">*</span>
                            </label>

                            <select
                                name="payment_method"
                                id="payment_method"
                                required
                                class="block w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option value="">
                                    Select payment method
                                </option>

                                <option
                                    value="Cash"
                                    @selected(old('payment_method') === 'Cash')
                                >
                                    Cash
                                </option>

                                <option
                                    value="Transfer"
                                    @selected(old('payment_method') === 'Transfer')
                                >
                                    Transfer
                                </option>

                                <option
                                    value="POS"
                                    @selected(old('payment_method') === 'POS')
                                >
                                    POS
                                </option>

                                <option
                                    value="Other"
                                    @selected(old('payment_method') === 'Other')
                                >
                                    Other
                                </option>

                            </select>

                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Payment Reference --}}
                        <div>

                            <label
                                for="payment_reference"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Payment Reference
                            </label>

                            <input
                                type="text"
                                name="payment_reference"
                                id="payment_reference"
                                value="{{ old('payment_reference') }}"
                                maxlength="255"
                                class="block w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g. TRX-123456"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                Recommended for transfers and POS payments.
                            </p>

                            @error('payment_reference')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Paid At --}}
                        <div>

                            <label
                                for="paid_at"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Payment Date & Time
                                <span class="text-red-400">*</span>
                            </label>

                            <input
                                type="datetime-local"
                                name="paid_at"
                                id="paid_at"
                                value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}"
                                required
                                class="block w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('paid_at')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Remarks --}}
                        <div class="md:col-span-2">

                            <label
                                for="remarks"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Remarks
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                maxlength="1000"
                                class="block w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Optional payment notes..."
                            >{{ old('remarks') }}</textarea>

                            @error('remarks')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- Form Actions --}}
                    <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-700 pt-6 sm:flex-row sm:justify-end">

                        <a
                            href="{{ route('payments.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-600 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-700 hover:text-white"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-800"
                        >

                            <svg
                                class="mr-2 h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>

                            Record Payment

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>