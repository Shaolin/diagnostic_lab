<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Edit Laboratory
            </h2>

            <a href="{{ route('laboratories.index') }}"
               class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                Back to Laboratories
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <form
                action="{{ route('laboratories.update', $laboratory) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                @include('laboratories.form')

            </form>

        </div>
    </div>
</x-app-layout>