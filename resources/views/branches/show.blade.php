<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Branch Details
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl p-6">

                <h3 class="text-2xl font-bold text-white mb-6">
                    {{ $branch->name }}
                </h3>

                <div class="space-y-4 text-sm">

                    <div>
                        <span class="text-slate-400">Code:</span>
                        <span class="text-white ml-2">
                            {{ $branch->code ?? '—' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400">Phone:</span>
                        <span class="text-white ml-2">
                            {{ $branch->phone ?? '—' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400">Address:</span>
                        <span class="text-white ml-2">
                            {{ $branch->address ?? '—' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400">Status:</span>

                        @if($branch->is_active)
                            <span class="ml-2 text-green-400">Active</span>
                        @else
                            <span class="ml-2 text-red-400">Inactive</span>
                        @endif
                    </div>

                </div>

                <div class="mt-8">
                    <a href="{{ route('branches.index') }}"
                       class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600 transition">
                        Back to Branches
                    </a>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>