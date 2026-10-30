
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Register Insurance Claim
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Record insurance coverage for a patient's test request.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Test Request Details --}}
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-white">
                        Test Request Details
                    </h3>
                    <p class="mt-1 text-sm text-slate-400">
                        Confirm the patient's information before registering the claim.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <p class="text-sm text-slate-400">Patient</p>
                        <p class="mt-1 font-medium text-white">
                            {{ $testRequest->patient->full_name ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Tracking Code</p>
                        <p class="mt-1 font-medium text-white">
                            {{ $testRequest->tracking_code }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Total Test Cost</p>
                        <p class="mt-1 font-semibold text-white">
                            ₦{{ number_format((float) $testRequest->total_amount, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Patient Payments Received</p>
                        <p class="mt-1 font-semibold text-white">
                            ₦{{ number_format((float) $testRequest->totalPaid(), 2) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Claim Form --}}
            <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
                <div class="border-b border-slate-700 px-6 py-5">
                    <h3 class="text-lg font-semibold text-white">
                        Insurance Information
                    </h3>
                    <p class="mt-1 text-sm text-slate-400">
                        Enter the insurer details and the amount covered by insurance.
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

                    <form method="POST" action="{{ route('insurance-claims.store', $testRequest) }}">
                        @csrf

                        {{-- Insurance Company --}}
                        <div class="mb-5">
                            <label for="insurance_company"
                                   class="mb-2 block text-sm font-medium text-slate-300">
                                Insurance Company / HMO
                                <span class="text-red-400">*</span>
                            </label>

                            <input
                                type="text"
                                name="insurance_company"
                                id="insurance_company"
                                value="{{ old('insurance_company') }}"
                                required
                                maxlength="255"
                                placeholder="e.g. NHIA, AXA Mansard, Reliance HMO"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                            @error('insurance_company')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Membership Number --}}
                        <div class="mb-5">
                            <label for="membership_number"
                                   class="mb-2 block text-sm font-medium text-slate-300">
                                Insurance Membership Number
                            </label>

                            <input
                                type="text"
                                name="membership_number"
                                id="membership_number"
                                value="{{ old('membership_number') }}"
                                maxlength="255"
                                placeholder="Enter the patient's insurance ID"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                            @error('membership_number')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Amounts --}}
                        <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="covered_amount"
                                       class="mb-2 block text-sm font-medium text-slate-300">
                                    Amount Payable by Insurance (₦)
                                    <span class="text-red-400">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="covered_amount"
                                    id="covered_amount"
                                    value="{{ old('covered_amount') }}"
                                    required
                                    min="0.01"
                                    max="{{ $testRequest->total_amount }}"
                                    step="0.01"
                                    placeholder="0.00"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >

                                @error('covered_amount')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="patient_contribution"
                                       class="mb-2 block text-sm font-medium text-slate-300">
                                    Patient Contribution (₦)
                                    <span class="text-red-400">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="patient_contribution"
                                    id="patient_contribution"
                                    value="{{ old('patient_contribution', 0) }}"
                                    required
                                    min="0"
                                    max="{{ $testRequest->total_amount }}"
                                    step="0.01"
                                    placeholder="0.00"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >

                                @error('patient_contribution')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
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
                                placeholder="Optional notes about the insurance claim..."
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >{{ old('remarks') }}</textarea>

                            @error('remarks')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col-reverse gap-3 border-t border-slate-700 pt-5 sm:flex-row sm:items-center sm:justify-end">
                            <a
                                href="{{ route('insurance-claims.index') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-700 px-5 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-slate-600"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-800"
                            >
                                Save Insurance Claim
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
