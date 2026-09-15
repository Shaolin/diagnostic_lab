<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                {{ __('Create Branch') }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Add a new branch or location for your laboratory.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-400">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                <form method="POST" action="{{ route('branches.store') }}">
                    @csrf

                    <div class="space-y-6">

                        {{-- Branch Name --}}
                        <div>
                            <label for="name"
                                   class="block text-sm font-medium text-slate-300">
                                Branch Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="e.g. Head Office"
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Branch Code --}}
                        <div>
                            <label for="code"
                                   class="block text-sm font-medium text-slate-300">
                                Branch Code
                            </label>

                            <input
                                type="text"
                                name="code"
                                id="code"
                                value="{{ old('code') }}"
                                placeholder="e.g. HO, ONI, LAG"
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('code')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone"
                                   class="block text-sm font-medium text-slate-300">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                id="phone"
                                value="{{ old('phone') }}"
                                placeholder="Branch phone number"
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('phone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div>
                            <label for="address"
                                   class="block text-sm font-medium text-slate-300">
                                Address
                            </label>

                            <textarea
                                name="address"
                                id="address"
                                rows="4"
                                placeholder="Branch address"
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('address') }}</textarea>

                            @error('address')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Active --}}
                        <div>
                            <label class="inline-flex items-center">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    checked
                                    class="rounded border-slate-600 bg-slate-900 text-indigo-600 focus:ring-indigo-500"
                                >

                                <span class="ml-2 text-sm text-slate-300">
                                    Branch is active
                                </span>

                            </label>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-8 flex justify-end gap-3">

                        <a href="{{ route('branches.index') }}"
                           class="rounded-md bg-slate-600 px-5 py-2 text-sm font-medium text-white hover:bg-slate-500">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Create Branch
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>