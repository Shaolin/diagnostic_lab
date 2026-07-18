<x-app-layout>

   <x-slot name="header">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <h2 class="text-xl font-semibold text-white">
                Register Patient
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                Register a new patient for your laboratory.
            </p>

        </div>

        <a
            href="{{ route('patients.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
        >
            ← Back to Patients
        </a>

    </div>

</x-slot>
    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <form
                action="{{ route('patients.store') }}"
                method="POST"
                class="space-y-8"
            >

                @csrf

                @include('patients.form')

            </form>

        </div>

    </div>

</x-app-layout>