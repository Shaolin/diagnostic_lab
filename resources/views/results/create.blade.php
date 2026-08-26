<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
               

  <h2 class="text-xl font-semibold text-white">
                    Upload Result
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    {{ $testRequestItem->test_name }}
                    — {{ $testRequestItem->testRequest->tracking_code }}
                </p>
            </div>

            <a
                href="{{ route('test-requests.show', $testRequestItem->testRequest) }}"
                class="text-sm text-slate-400 hover:text-white transition"
            >
                ← Back to Test Request
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Patient Information --}}
            <div class="bg-slate-900 border border-slate-800 shadow-sm sm:rounded-xl mb-6">
                <div class="p-6">

                    <h3 class="text-lg font-semibold text-white mb-5">
                        Patient Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <p class="text-sm text-slate-500">
                                Patient
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $testRequestItem->testRequest->patient->full_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">
                                Tracking Code
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $testRequestItem->testRequest->tracking_code }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">
                                Test
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $testRequestItem->test_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">
                                Test Status
                            </p>

                            <p class="mt-1 font-medium text-green-400">
                                {{ $testRequestItem->status }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>


            {{-- Upload Form --}}
            <div class="bg-slate-900 border border-slate-800 shadow-sm sm:rounded-xl">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-white">
                        Laboratory Result
                    </h3>

                    <p class="text-sm text-slate-400 mt-1 mb-6">
                        Upload the completed laboratory result as a PDF.
                    </p>


                    {{-- Validation Errors --}}
                    @if ($errors->any())

                        <div class="mb-6 rounded-lg bg-red-950/40 border border-red-800 p-4">

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
                        action="{{ route('results.store', $testRequestItem) }}"
                        enctype="multipart/form-data"
                    >

                        @csrf


                        {{-- PDF --}}
                        <div>

                            <label
                                for="pdf"
                                class="block text-sm font-medium text-slate-300"
                            >
                                Result PDF
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
                                placeholder="Optional remarks about this result..."
                            >{{ old('remarks') }}</textarea>

                        </div>


                        {{-- Actions --}}
                        <div class="mt-6 flex items-center justify-end gap-3">

                            <a
                                href="{{ route('test-requests.show', $testRequestItem->testRequest) }}"
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
                                       bg-indigo-600
                                       rounded-lg
                                       hover:bg-indigo-700
                                       transition"
                            >
                                Upload Result
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>