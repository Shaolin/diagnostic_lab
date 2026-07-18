<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h2 class="text-xl font-semibold text-white">
                    Edit Patient
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Update patient information and medical details.
                </p>

            </div>

            <a
                href="{{ route('patients.show', $patient) }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
            >
                ← Back to Profile
            </a>

        </div>

    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <form
                action="{{ route('patients.update', $patient) }}"
                method="POST"
                class="space-y-8"
            >

                @csrf
                @method('PUT')

                @include('patients.form')

            </form>

        </div>

    </div>

</x-app-layout>