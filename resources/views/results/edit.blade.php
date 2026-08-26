<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    Replace Result
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    {{ $result->testRequestItem->test_name }}
                    — {{ $result->testRequestItem->testRequest->tracking_code }}
                </p>
            </div>

            <a
                href="{{ route(
                    'test-requests.show',
                    $result->testRequestItem->testRequest
                ) }}"
                class="text-sm text-slate-400 hover:text-white transition"
            >
                ← Back to Test Request
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Current Result --}}
            <div class="bg-slate-900 border border-slate-800 shadow-sm sm:rounded-xl mb-6">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-white mb-5">
                        Current Result
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Patient --}}
                        <div>
                            <p class="text-sm text-slate-500">
                                Patient
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $result->testRequestItem->testRequest->patient->full_name }}
                            </p>
                        </div>

                        {{-- Test --}}
                        <div>
                            <p class="text-sm text-slate-500">
                                Test
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $result->testRequestItem->test_name }}
                            </p>
                        </div>

                        {{-- Uploaded --}}
                        <div>
                            <p class="text-sm text-slate-500">
                                Uploaded
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $result->uploaded_at?->format('d M Y, h:i A') ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Verification --}}
                        <div>
                            <p class="text-sm text-slate-500">
                                Verification
                            </p>

                            @if ($result->verified_at)

                                <p class="mt-1 font-medium text-green-400">
                                    ✓ Verified
                                </p>

                            @else

                                <p class="mt-1 font-medium text-yellow-400">
                                    Not Verified
                                </p>

                            @endif
                        </div>

                    </div>

                    {{-- Remarks --}}
                    @if ($result->remarks)

                        <div class="mt-6 border-t border-slate-800 pt-5">

                            <p class="text-sm text-slate-500">
                                Current Remarks
                            </p>

                            <p class="mt-1 text-sm text-slate-300">
                                {{ $result->remarks }}
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Replacement Form --}}
            <div class="bg-slate-900 border border-slate-800 shadow-sm sm:rounded-xl">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-white">
                        Upload Replacement
                    </h3>

                    <p class="text-sm text-slate-400 mt-1">
                        Upload a new PDF to replace the existing laboratory result.
                    </p>


                    {{-- Warning --}}
                    <div class="mt-5 rounded-lg bg-amber-950/40 border border-amber-800 p-4">

                        <div class="flex items-start gap-3">

                            <div class="flex-shrink-0 text-amber-400">
                                ⚠
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-white">
                                    Re-verification required
                                </p>

                                <p class="text-sm text-slate-400 mt-1">
                                    Replacing this result will invalidate the
                                    current verification. The new result must
                                    be verified again before it can be sent
                                    to the patient.
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Validation Errors --}}
                    @if ($errors->any())

                        <div class="mt-6 rounded-lg bg-red-950/40 border border-red-800 p-4">

                            <p class="text-sm font-semibold text-red-400 mb-2">
                                Please correct the following errors:
                            </p>

                            <ul class="text-sm text-red-300 space-y-1">

                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('results.update', $result) }}"
                        enctype="multipart/form-data"
                        class="mt-6"
                    >

                        @csrf
                        @method('PUT')


                        {{-- PDF --}}
                        <div>

                            <label
                                for="pdf"
                                class="block text-sm font-medium text-slate-300"
                            >
                                New Result PDF
                            </label>

                            <input
                                id="pdf"
                                name="pdf"
                                type="file"
                                accept="application/pdf"
                                required
                                class="mt-2 block w-full text-sm text-slate-300
                                       border border-slate-700 rounded-lg
                                       cursor-pointer bg-slate-800
                                       focus:outline-none
                                       focus:border-indigo-500
                                       file:mr-4
                                       file:py-2
                                       file:px-4
                                       file:rounded-l-lg
                                       file:border-0
                                       file:text-sm
                                       file:font-semibold
                                       file:bg-slate-700
                                       file:text-slate-200
                                       hover:file:bg-slate-600"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                PDF files only. Maximum size: 10 MB.
                            </p>

                        </div>


                        {{-- Remarks --}}
                        <div class="mt-6">

                            <label
                                for="remarks"
                                class="block text-sm font-medium text-slate-300"
                            >
                                Remarks
                            </label>

                            <textarea
                                id="remarks"
                                name="remarks"
                                rows="4"
                                class="mt-2 block w-full rounded-lg
                                       bg-slate-800
                                       border-slate-700
                                       text-slate-200
                                       placeholder-slate-500
                                       shadow-sm
                                       focus:border-indigo-500
                                       focus:ring-indigo-500"
                                placeholder="Optional remarks about this replacement..."
                            >{{ old('remarks', $result->remarks) }}</textarea>

                        </div>


                        {{-- Actions --}}
                        <div class="mt-6 flex items-center justify-end gap-3">

                            <a
                                href="{{ route(
                                    'test-requests.show',
                                    $result->testRequestItem->testRequest
                                ) }}"
                                class="px-4 py-2 text-sm font-medium
                                       text-slate-300
                                       bg-slate-800
                                       border border-slate-700
                                       rounded-lg
                                       hover:bg-slate-700
                                       hover:text-white
                                       transition"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium
                                       text-white
                                       bg-amber-600
                                       rounded-lg
                                       hover:bg-amber-700
                                       transition"
                            >
                                Replace Result
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>