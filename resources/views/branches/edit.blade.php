<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Edit Branch
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl p-6">

                <form method="POST"
                      action="{{ route('branches.update', $branch) }}">

                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        {{-- Branch Name --}}
                        <div>
                            <label for="name"
                                   class="block text-sm font-medium text-slate-300 mb-2">
                                Branch Name
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $branch->name) }}"
                                   required
                                   class="w-full rounded-lg border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500">

                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Branch Code --}}
                        <div>
                            <label for="code"
                                   class="block text-sm font-medium text-slate-300 mb-2">
                                Branch Code
                            </label>

                            <input type="text"
                                   name="code"
                                   id="code"
                                   value="{{ old('code', $branch->code) }}"
                                   class="w-full rounded-lg border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500">

                            @error('code')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone"
                                   class="block text-sm font-medium text-slate-300 mb-2">
                                Phone
                            </label>

                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone', $branch->phone) }}"
                                   class="w-full rounded-lg border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500">

                            @error('phone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div>
                            <label for="address"
                                   class="block text-sm font-medium text-slate-300 mb-2">
                                Address
                            </label>

                            <textarea name="address"
                                      id="address"
                                      rows="4"
                                      class="w-full rounded-lg border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $branch->address) }}</textarea>

                            @error('address')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Active --}}
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">

                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   id="is_active"
                                   {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                                   class="rounded border-slate-600 bg-slate-900 text-indigo-600 focus:ring-indigo-500">

                            <label for="is_active"
                                   class="ml-2 text-sm text-slate-300">
                                Active
                            </label>
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-3 mt-8">

                        <a href="{{ route('branches.index') }}"
                           class="rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600 transition">
                            Cancel
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                            Update Branch
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>