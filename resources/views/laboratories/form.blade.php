
<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Laboratory Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-300">
                    Laboratory Name <span class="text-red-500">*</span>
                </label>

                <input
                   type="text"
                   id="name"
                   name="name"
                   value="{{ old('name', $laboratory->name ?? '') }}"
                   class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Subdomain --}}
            <div>
                <label for="subdomain" class="block text-sm font-medium text-slate-300">
                    Subdomain <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="subdomain"
                    name="subdomain"
                    value="{{ old('subdomain', $laboratory->subdomain ?? '') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('subdomain')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Logo --}}
            {{-- <div>
                <label for="logo" class="block text-sm font-medium text-slate-300">
                    Logo
                </label>

                <input
                    type="file"
                    id="logo"
                    name="logo"
                    
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-slate-300 file:mr-4 file:rounded-md file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white hover:file:bg-blue-700">

                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @isset($laboratory)
                    @if($laboratory->logo_url)
                        <div class="mt-3">
                            <img
                                src="{{ $laboratory->logo_url }}"
                                alt="{{ $laboratory->name }}"
                                class="h-20 w-20 rounded object-cover">
                        </div>
                    @endif
                @endisset
            </div> --}}

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-300">
                    Phone
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $laboratory->phone ?? '') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $laboratory->email ?? '') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Country --}}
            <div>
                <label for="country" class="block text-sm font-medium text-slate-300">
                    Country
                </label>

                <input
                    type="text"
                    id="country"
                    name="country"
                    
                    value="{{ old('country', $laboratory->country ?? 'Nigeria') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('country')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Currency Code --}}
            <div>
                <label for="currency_code" class="block text-sm font-medium text-slate-300">
                    Currency Code <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="currency_code"
                    name="currency_code"
                    value="{{ old('currency_code', $laboratory->currency_code ?? 'NGN') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('currency_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Currency Symbol --}}
            <div>
                <label for="currency_symbol" class="block text-sm font-medium text-slate-300">
                    Currency Symbol <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="currency_symbol"
                    name="currency_symbol"
                    value="{{ old('currency_symbol', $laboratory->currency_symbol ?? '₦') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('currency_symbol')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Timezone --}}
            <div>
                <label for="timezone" class="block text-sm font-medium text-slate-300">
                    Timezone
                </label>

                <input
                    type="text"
                    id="timezone"
                    name="timezone"
                    
                    value="{{ old('timezone', $laboratory->timezone ?? 'Africa/Lagos') }}"
                    class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                @error('timezone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div> 

        {{-- Address --}}
        <div class="mt-6">
            <label for="address" class="block text-sm font-medium text-slate-300">
                Address
            </label>

            <textarea
                id="address"
                name="address"
                rows="3"
                class="mt-1 block w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $laboratory->address ?? '') }}</textarea>

            @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Active --}}
        <div class="mt-6">
            <label class="inline-flex items-center">

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    
                    class="rounded border-slate-600 bg-slate-900 text-blue-600 focus:ring-blue-500"
                    {{ old('is_active', $laboratory->is_active ?? true) ? 'checked' : '' }}>

                <span class="ml-2 text-sm text-slate-300">
                    Active
                </span>

            </label>
        </div>

    </div>

    
    <div class="flex justify-end gap-3 border-t border-slate-700 bg-slate-900 px-6 py-4">

        <a href="{{ route('laboratories.index') }}"
           class="rounded-md bg-slate-600 px-4 py-2 text-white hover:bg-slate-700">
           
            Cancel
        </a>

        <button
            type="submit"
            class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
           
            Save Laboratory
        </button>

    </div>

</div>