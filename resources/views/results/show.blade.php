
<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-slate-300 leading-tight">
                    Laboratory Result
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    View patient result and attached medical files
                </p>
            </div>

            <a href="{{ route('results.index') }}"
               class="rounded-lg bg-slate-800 border border-slate-700 px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">
                Back to Results
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Patient and test information --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            {{ $result->testRequestItem->testRequest->patient->full_name }}
                        </h3>

                        <p class="text-sm text-slate-400 mt-1">
                            Patient No:
                            {{ $result->testRequestItem->testRequest->patient->patient_number }}
                        </p>

                        <p class="text-sm text-slate-400 mt-1">
                            Tracking Code:
                            {{ $result->testRequestItem->testRequest->tracking_code }}
                        </p>

                        <p class="text-sm text-slate-400 mt-1">
                            Test:
                            {{ $result->testRequestItem->test_name }}
                        </p>
                    </div>

                    <div>
                        @if($result->verified_at)
                            <span class="inline-flex rounded-full bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">
                                ✓ Verified
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-yellow-900/30 px-3 py-1 text-xs font-semibold text-yellow-400 ring-1 ring-yellow-700">
                                Awaiting Verification
                            </span>
                        @endif
                    </div>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 pt-5 border-t border-slate-800">

                    <div>
                        <p class="text-xs text-slate-500">Uploaded</p>
                        <p class="text-sm text-slate-300 mt-1">
                            {{ $result->uploaded_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">Uploaded By</p>
                        <p class="text-sm text-slate-300 mt-1">
                            {{ $result->uploadedBy?->name ?? 'N/A' }}
                        </p>
                    </div>

                    @if($result->verified_at)
                        <div>
                            <p class="text-xs text-slate-500">Verified</p>
                            <p class="text-sm text-slate-300 mt-1">
                                {{ $result->verified_at->format('d M Y, h:i A') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Verified By</p>
                            <p class="text-sm text-slate-300 mt-1">
                                {{ $result->verifiedBy?->name ?? 'N/A' }}
                            </p>
                        </div>
                    @endif

                </div>

                @if($result->remarks)
                    <div class="mt-5 pt-5 border-t border-slate-800">
                        <p class="text-xs text-slate-500 mb-1">Remarks</p>
                        <p class="text-sm text-slate-300">
                            {{ $result->remarks }}
                        </p>
                    </div>
                @endif
            </div>


            {{-- PDF Result --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">

                <div class="p-5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h3 class="font-semibold text-white">
                        PDF Laboratory Result
                    </h3>

                    @if($result->verified_at)
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('results.download', $result) }}"
                               class="rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600">
                                Download PDF
                            </a>

                            <button onclick="window.print()"
                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                Print Result
                            </button>
                        </div>
                    @else
                        <span class="text-sm text-yellow-400">
                            Printing is available after verification.
                        </span>
                    @endif
                </div>

                @if($result->verified_at)
                    <div class="p-3 bg-slate-800">
                        <iframe
                            src="{{ route('results.view-pdf', $result) }}"
                            class="w-full rounded-lg bg-white"
                            style="height: 850px;"
                            title="Laboratory PDF Result">
                        </iframe>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-slate-400">
                            This result is awaiting verification. The PDF cannot be printed yet.
                        </p>
                    </div>
                @endif

            </div>


            {{-- Medical Files --}}
            @if($result->testRequestItem->medicalResultFiles->isNotEmpty())

                <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">

                    <div class="p-5 border-b border-slate-800">
                        <h3 class="font-semibold text-white">
                            Attached Medical Images and Files
                        </h3>
                        <p class="text-sm text-slate-400 mt-1">
                            X-rays, EEG, ultrasound and other supporting files.
                        </p>
                    </div>

                    <div class="p-5 space-y-6">

                        @foreach($result->testRequestItem->medicalResultFiles as $file)

                            <div class="border border-slate-800 rounded-lg p-4">

                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">

                                    <div>
                                        <p class="font-medium text-slate-200">
                                            {{ $file->original_name }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            Category: {{ $file->file_type }}
                                        </p>

                                        @if($file->description)
                                            <p class="text-sm text-slate-400 mt-2">
                                                {{ $file->description }}
                                            </p>
                                        @endif
                                    </div>

                                    @if($result->verified_at)
                                        <a href="{{ route('patient.medical-file.download', [
                                            'trackingCode' => $result->testRequestItem->testRequest->tracking_code,
                                            'medicalFile' => $file->id
                                        ]) }}"
                                           class="rounded-lg bg-slate-700 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-600">
                                            Download File
                                        </a>
                                    @endif

                                </div>

                                @if($result->verified_at && str_starts_with($file->mime_type, 'image/'))

                                    <img
                                        src="{{ route('patient.medical-file.view', [
                                            'trackingCode' => $result->testRequestItem->testRequest->tracking_code,
                                            'medicalFile' => $file->id
                                        ]) }}"
                                        alt="{{ $file->original_name }}"
                                        class="max-w-full max-h-[650px] rounded-lg border border-slate-700 mx-auto"
                                    >

                                @elseif($result->verified_at && $file->mime_type === 'application/pdf')

                                    <iframe
                                        src="{{ route('patient.track') }}"
                                        class="w-full rounded-lg bg-white"
                                        style="height: 500px;"
                                        title="{{ $file->original_name }}">
                                    </iframe>

                                    <p class="text-xs text-slate-500 mt-2">
                                        Download this PDF to view it.
                                    </p>

                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>
    </div>

    {{-- Print styling --}}
    <style>
        @media print {
            body {
                background: white !important;
            }

            nav,
            header,
            button,
            a,
            aside {
                display: none !important;
            }

            iframe {
                height: 1000px !important;
            }

            .bg-slate-900,
            .bg-slate-800 {
                background: white !important;
            }

            .text-white,
            .text-slate-200,
            .text-slate-300,
            .text-slate-400 {
                color: black !important;
            }

            .border-slate-800,
            .border-slate-700 {
                border-color: #ddd !important;
            }
        }
    </style>

</x-app-layout>
