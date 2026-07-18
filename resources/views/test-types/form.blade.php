@csrf

<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    {{-- Card Header --}}
    <div class="border-b border-slate-700 px-6 py-5">

        <h3 class="text-lg font-semibold text-white">
            Test Type Information
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Enter the details of the diagnostic test.
        </p>

    </div>

    {{-- Card Body --}}
    <div class="space-y-6 p-6">

        {{-- Test Code (Edit Only) --}}
        @isset($testType)

            <div>

                <label class="mb-2 block text-sm font-medium text-slate-300">
                    Test Code
                </label>

                <div
                    class="rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-sm font-semibold tracking-wide text-indigo-400"
                >
                    {{ $testType->code }}
                </div>

                <p class="mt-2 text-xs text-slate-500">
                    Test codes are automatically generated and cannot be changed.
                </p>

            </div>

        @endisset


        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Test Name --}}
            <div>

                <label
                    for="name"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Test Name <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $testType->name ?? '') }}"
                    required
                    autofocus
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Category --}}
            <div>

                <label
                    for="category"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Category
                </label>

                <input
                    type="text"
                    id="category"
                    name="category"
                    list="categories"
                    value="{{ old('category', $testType->category ?? '') }}"
                    placeholder="e.g. Haematology"
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                >

                <datalist id="categories">
                    @foreach ($categories as $category)
                        <option value="{{ $category }}">
                    @endforeach
                </datalist>

                @error('category')
                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Default Price --}}
            <div>

                <label
                    for="default_price"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Default Price (₦)
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="number"
                    name="default_price"
                    id="default_price"
                    value="{{ old('default_price', $testType->default_price ?? '') }}"
                    step="0.01"
                    min="0"
                    required
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                >

                @error('default_price')
                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Estimated Turnaround --}}
            <div>

                <label
                    for="estimated_turnaround_hours"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Estimated Turnaround (Hours)
                </label>

                <input
                    type="number"
                    id="estimated_turnaround_hours"
                    name="estimated_turnaround_hours"
                    value="{{ old('estimated_turnaround_hours', $testType->estimated_turnaround_hours ?? '') }}"
                    min="1"
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                >

                @error('estimated_turnaround_hours')
                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>
                @enderror

            </div>

        </div>


        {{-- Description --}}
        <div>

            <label
                for="description"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="5"
                placeholder="Optional description..."
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
            >{{ old('description', $testType->description ?? '') }}</textarea>

            @error('description')
                <p class="mt-2 text-sm text-red-400">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- Active --}}
        <div>

            <label class="flex items-center gap-3">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', $testType->is_active ?? true))
                    class="rounded border-slate-600 bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                >

                <span class="text-sm text-slate-300">
                    Active Test Type
                </span>

            </label>

            <p class="mt-2 text-xs text-slate-500">
                Inactive Test Types cannot be selected when creating new Test Requests.
            </p>

        </div>

    </div>


    {{-- Card Footer --}}
    <div
        class="flex flex-col gap-3 border-t border-slate-700 bg-slate-800 px-6 py-5 sm:flex-row sm:justify-end"
    >

        <a
            href="{{ route('test-types.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
        >
            {{ isset($testType) ? 'Update Test Type' : 'Save Test Type' }}
        </button>

    </div>

</div>