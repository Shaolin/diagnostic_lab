<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-slate-300 leading-tight">
                Laboratory Modules
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Manage optional modules for {{ $laboratory->name }}.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('super-admin.dashboard') }}"
                   class="text-sm text-slate-500 hover:text-slate-700">
                    ← Back to Super Admin
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

                <form method="POST"
                      action="{{ route('super-admin.modules.update', $laboratory) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">

                        @foreach($modules as $key => $label)
                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">

                                <div>
                                    <h3 class="font-semibold text-slate-800">
                                        {{ $label }}
                                    </h3>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Enable the {{ strtolower($label) }} module for this laboratory.
                                    </p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="modules[]"
                                    value="{{ $key }}"
                                    class="h-5 w-5 rounded border-slate-300 text-slate-700 focus:ring-slate-500"
                                    {{ in_array($key, $enabledModules) ? 'checked' : '' }}
                                >

                            </label>
                        @endforeach

                    </div>

                    <div class="mt-6 flex justify-end">
                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition">
                            Save Module Settings
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>