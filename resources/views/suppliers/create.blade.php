<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                Add Supplier
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Add a new supplier to your laboratory.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl border border-slate-700 bg-slate-900 shadow-sm">

                <div class="border-b border-slate-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">
                        Supplier Information
                    </h3>
                </div>

                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                        {{-- Supplier Name --}}
                        <div class="md:col-span-2">
                            <label for="name"
                                   class="block text-sm font-medium text-slate-300">
                                Supplier Name <span class="text-red-400">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                required
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g. ABC Medical Supplies"
                            >

                            @error('name')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Contact Person --}}
                        <div>
                            <label for="contact_person"
                                   class="block text-sm font-medium text-slate-300">
                                Contact Person
                            </label>

                            <input
                                type="text"
                                name="contact_person"
                                id="contact_person"
                                value="{{ old('contact_person') }}"
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contact person's name"
                            >

                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
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
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Phone number"
                            >

                            @error('phone')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email"
                                   class="block text-sm font-medium text-slate-300">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="supplier@example.com"
                            >

                            @error('email')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="md:col-span-2">
                            <label for="address"
                                   class="block text-sm font-medium text-slate-300">
                                Address
                            </label>

                            <textarea
                                name="address"
                                id="address"
                                rows="3"
                                class="mt-2 block w-full rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Supplier's business address"
                            >{{ old('address') }}</textarea>

                            @error('address')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 border-t border-slate-700 px-6 py-4">

                        <a href="{{ route('suppliers.index') }}"
                           class="rounded-lg border border-slate-600 px-4 py-2 text-sm font-medium text-slate-300 hover:bg-slate-800">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                            Save Supplier
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>