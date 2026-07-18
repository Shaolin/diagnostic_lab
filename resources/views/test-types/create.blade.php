<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                Create Test Type
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Add a new diagnostic test to your laboratory catalogue.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-green-300 shadow-sm">
                    <div class="flex items-center">
                        <svg class="mr-2 h-5 w-5 text-green-600"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"/>
                        </svg>

                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form
                action="{{ route('test-types.store') }}"
                method="POST"
            >
                @include('test-types.form')
            </form>

        </div>
    </div>
</x-app-layout>