<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-white">
                Laboratory Details
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('laboratories.edit', $laboratory) }}"
                   class="rounded-md bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">
                    Edit
                </a>

                <a href="{{ route('laboratories.index') }}"
                   class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                {{-- Header --}}
                <div class="border-b border-slate-700 p-6 flex items-center gap-6">

                    @if($laboratory->logo_url)
                        <img
                            src="{{ $laboratory->logo_url }}"
                            alt="{{ $laboratory->name }}"
                            class="h-24 w-24 rounded-full object-cover">
                    @else
                        <div class="flex h-24 w-24 items-center justify-center rounded-full bg-slate-700 text-3xl font-bold text-white">
                            {{ strtoupper(substr($laboratory->name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h3 class="text-2xl font-bold text-white">
                            {{ $laboratory->name }}
                        </h3>

                        <p class="text-slate-400 mt-1">
                            {{ $laboratory->subdomain }}
                        </p>

                        <div class="mt-3">

                            @if($laboratory->is_active)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                                    Inactive
                                </span>
                            @endif

                        </div>
                    </div>

                </div>

                {{-- Details --}}
                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <h4 class="text-sm font-semibold text-slate-400 uppercase">
                                Phone
                            </h4>
                            

                            <p class="mt-1 text-white">
                                {{ $laboratory->phone ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-slate-400 uppercase">
                                Email
                            </h4>

                            <p class="mt-1 text-white">
                                {{ $laboratory->email ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-slate-400 uppercase">
                                Country
                            </h4>

                            <p class="mt-1 text-white">
                                {{ $laboratory->country ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-slate-400 uppercase">
                                Timezone
                            </h4>

                            <p class="mt-1 text-white">
                                {{ $laboratory->timezone ?: '-' }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-slate-400 uppercase">
                                Currency Code
                            </h4>

                            <p class="mt-1 text-white">
                                {{ $laboratory->currency_code }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-slate-400 uppercase">
                                Currency Symbol
                            </h4>

                            <p class="mt-1 text-white">
                                {{ $laboratory->currency_symbol }}
                            </p>
                        </div>

                    </div>

                    {{-- Address --}}
                    <div class="mt-8">

                        <h4 class="text-sm font-semibold text-slate-400 uppercase">
                            Address
                        </h4>

                        <p class="mt-2 text-white whitespace-pre-line">
                            {{ $laboratory->address ?: '-' }}
                        </p>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="border-t border-slate-700 bg-slate-900 px-6 py-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
    <span class="font-semibold text-slate-300">Created:</span>

    <span class="text-white">
        {{ $laboratory->created_at->format('d M Y h:i A') }}
    </span>
</div>

<div>
    <span class="font-semibold text-slate-300">Last Updated:</span>

    <span class="text-white">
        {{ $laboratory->updated_at->format('d M Y h:i A') }}
    </span>
</div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>