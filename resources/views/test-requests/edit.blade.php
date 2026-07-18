<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h2 class="text-xl font-semibold text-white">
                    Edit Test Request
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                   Update the patient information and requested tests.
                </p>

            </div>

            <a
                href="{{ route('test-requests.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
            >
                ← Back to Test Requests
            </a>

        </div>

    </x-slot>


    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- Success Message --}}
            @if(session('success'))

                <div class="mb-6 rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-green-300">

                    {{ session('success') }}

                </div>

            @endif


            {{-- Error Message --}}
            @if(session('error'))

                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300">

                    {{ session('error') }}

                </div>

            @endif


            {{-- Validation Errors --}}
            @if ($errors->any())

                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 p-4">

                    <div class="mb-3 font-semibold text-red-300">
                        Please correct the following errors:
                    </div>

                    <ul class="list-disc space-y-1 pl-5 text-sm text-red-200">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



    <form action="{{ route('test-requests.update', $testRequest) }}" method="POST" id="testRequestForm">

    @csrf
    @method('PUT')


                {{-- Patient Information --}}
                <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                    <div class="border-b border-slate-700 px-6 py-4">

                        <h3 class="text-lg font-semibold text-white">

                            Patient Information

                        </h3>

                        <p class="mt-1 text-sm text-slate-400">

                            Select the patient requesting laboratory tests.

                        </p>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 gap-6">

                            {{-- Patient --}}
                            <div>

                                <label
                                    for="patient_id"
                                    class="mb-2 block text-sm font-medium text-slate-300"
                                >
                                    Patient
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    name="patient_id"
                                    id="patient_id"
                                    required
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
                                >

                                    <option value="">
                                        -- Select Patient --
                                    </option>

                                    @foreach($patients as $patient)

                                        <option
                                            value="{{ $patient->id }}"
                                            @selected(old('patient_id', $testRequest->patient_id) == $patient->id)
                                        >

                                            {{ $patient->patient_number }}
                                            —
                                            {{ $patient->full_name }}
                                            —
                                            {{ $patient->phone }}

                                        </option>

                                    @endforeach

                                </select>

                                <p class="mt-2 text-xs text-slate-500">
                                    Only patients registered under your laboratory are shown.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Laboratory Tests Card Starts Here --}}
                {{-- Laboratory Tests --}}
<div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="flex items-center justify-between border-b border-slate-700 px-6 py-4">

        <div>

            <h3 class="text-lg font-semibold text-white">
                Laboratory Tests
            </h3>

            <p class="mt-1 text-sm text-slate-400">
                Select one or more laboratory tests for this request.
            </p>

        </div>

        <button
            type="button"
            id="add-test-row"
            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
        >
            + Add Test
        </button>

    </div>


    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-700">

            <thead class="bg-slate-900">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Test Type
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Price (₦)
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Action
                    </th>

                </tr>

            </thead>


           <tbody id="test-items-container">

@foreach($testRequest->items as $index => $item)

<tr class="test-row">

    <td class="px-6 py-4">

        <select
            name="items[{{ $index }}][test_type_id]"
            class="test-type w-full rounded-lg border border-slate-600 bg-slate-900 text-white"
            required
        >

            <option value="">-- Select Test --</option>

            @foreach($testTypes as $testType)

                <option
                    value="{{ $testType->id }}"
                    data-price="{{ $testType->default_price }}"
                    @selected($item->test_type_id == $testType->id)
                >

                    {{ $testType->name }}
                    (₦{{ number_format($testType->default_price,2) }})

                </option>

            @endforeach

        </select>

    </td>

    <td class="px-6 py-4">

        <input
            type="number"
            name="items[{{ $index }}][price]"
            class="test-price w-full rounded-lg border border-slate-600 bg-slate-900 text-right text-white"
            value="{{ $item->price }}"
            min="0"
            step="0.01"
            required
        >

    </td>

    <td class="px-6 py-4 text-center">

        <button
            type="button"
            class="remove-test-row rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700"
        >
            Remove
        </button>

    </td>

</tr>

@endforeach

</tbody>

        </table>

    </div>

</div>
{{-- Summary & Remarks --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- Remarks --}}
    <div class="lg:col-span-2">

        <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

            <div class="border-b border-slate-700 px-6 py-4">

                <h3 class="text-lg font-semibold text-white">
                    Additional Remarks
                </h3>

                <p class="mt-1 text-sm text-slate-400">
                    Add any notes or special instructions for the laboratory staff.
                </p>

            </div>

            <div class="p-6">

                <label
                    for="remarks"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    id="remarks"
                    rows="6"
                    placeholder="Enter optional remarks..."
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('remarks', $testRequest->remarks) }}</textarea>

            </div>

        </div>

    </div>


    {{-- Summary Card --}}
    <div>

        <div class="sticky top-6 rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

            <div class="border-b border-slate-700 px-6 py-4">

                <h3 class="text-lg font-semibold text-white">
                    Request Summary
                </h3>

            </div>

            <div class="space-y-6 p-6">

                {{-- Number of Tests --}}
                <div class="flex items-center justify-between">

                    <span class="text-sm text-slate-400">
                        Selected Tests
                    </span>

                    <span
                        id="test-count"
                        class="rounded-full bg-indigo-900/30 px-3 py-1 text-sm font-semibold text-indigo-300 ring-1 ring-indigo-700"
                    >
                        1
                    </span>

                </div>


                {{-- Total Amount --}}
                <div class="rounded-lg border border-emerald-700 bg-emerald-900/20 p-4">

                    <p class="text-sm text-slate-400">
                        Total Amount
                    </p>

                    <h2
                        id="total-amount"
                        class="mt-2 text-3xl font-bold text-emerald-400"
                    >
                        ₦0.00
                    </h2>

                </div>


                <hr class="border-slate-700">


                {{-- Action Buttons --}}
                <div class="space-y-3">

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Update Test Request
                    </button>

                    <a
                        href="{{ route('test-requests.index') }}"
                        class="block w-full rounded-lg border border-slate-600 bg-slate-900 px-5 py-3 text-center text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
                    >
                        Cancel
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</form>

</div>

</div>

{{-- JavaScript Starts Here --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', () => {

    let rowIndex = document.querySelectorAll('.test-row').length;

    const container = document.getElementById('test-items-container');

    const addButton = document.getElementById('add-test-row');

    const totalAmount = document.getElementById('total-amount');

    const testCount = document.getElementById('test-count');


    /*
    |--------------------------------------------------------------------------
    | Add Row
    |--------------------------------------------------------------------------
    */

    addButton.addEventListener('click', () => {

        const row = document.createElement('tr');

        row.classList.add('test-row');

        row.innerHTML = `
            <td class="px-6 py-4">

                <select
                    name="items[${rowIndex}][test_type_id]"
                    class="test-type w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >

                    <option value="">-- Select Test --</option>

                    @foreach($testTypes as $testType)

                        <option
                            value="{{ $testType->id }}"
                            data-price="{{ $testType->default_price }}"
                        >

                            {{ $testType->name }}
                            (₦{{ number_format($testType->default_price,2) }})

                        </option>

                    @endforeach

                </select>

            </td>

            <td class="px-6 py-4">

                <input
                    type="number"
                    name="items[${rowIndex}][price]"
                    class="test-price w-full rounded-lg border border-slate-600 bg-slate-900 text-right text-white"
                    min="0"
                    step="0.01"
                    value=""
                    required
                >

            </td>

            <td class="px-6 py-4 text-center">

                <button
                    type="button"
                    class="remove-test-row rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-700"
                >
                    Remove
                </button>

            </td>
        `;

        container.appendChild(row);

        rowIndex++;

        attachEvents(row);

        updateSummary();

    });


    /*
    |--------------------------------------------------------------------------
    | Attach Events
    |--------------------------------------------------------------------------
    */

    function attachEvents(scope = document) {

        scope.querySelectorAll('.test-type').forEach(select => {

            select.removeEventListener('change', handleTestChange);

            select.addEventListener('change', handleTestChange);

        });

        scope.querySelectorAll('.test-price').forEach(input => {

            input.removeEventListener('input', updateSummary);

            input.addEventListener('input', updateSummary);

        });

        scope.querySelectorAll('.remove-test-row').forEach(button => {

            button.removeEventListener('click', removeRow);

            button.addEventListener('click', removeRow);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Auto-fill Price
    |--------------------------------------------------------------------------
    */

    function handleTestChange(e) {

        const selected = e.target.options[e.target.selectedIndex];

        const price = selected.dataset.price ?? '';

        const row = e.target.closest('tr');

        row.querySelector('.test-price').value = price;

        updateSummary();

    }


    /*
    |--------------------------------------------------------------------------
    | Remove Row
    |--------------------------------------------------------------------------
    */

    function removeRow(e) {

        if (document.querySelectorAll('.test-row').length === 1) {

            alert('At least one test is required.');

            return;

        }

        e.target.closest('tr').remove();

        renumberRows();

        updateSummary();

    }


    /*
    |--------------------------------------------------------------------------
    | Renumber Input Names
    |--------------------------------------------------------------------------
    */

    function renumberRows() {

        document.querySelectorAll('.test-row').forEach((row, index) => {

            row.querySelector('.test-type')
                .setAttribute('name', `items[${index}][test_type_id]`);

            row.querySelector('.test-price')
                .setAttribute('name', `items[${index}][price]`);

        });

        rowIndex = document.querySelectorAll('.test-row').length;

    }


    /*
    |--------------------------------------------------------------------------
    | Update Summary
    |--------------------------------------------------------------------------
    */

    function updateSummary() {

        let total = 0;

        document.querySelectorAll('.test-price').forEach(input => {

            total += parseFloat(input.value) || 0;

        });

        totalAmount.textContent =
            '₦' + total.toLocaleString(undefined, {
                minimumFractionDigits:2,
                maximumFractionDigits:2
            });

        testCount.textContent =
            document.querySelectorAll('.test-row').length;

    }


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    attachEvents();

    updateSummary();

});

</script>

@endpush
</x-app-layout>