<x-guest-layout>

    <div class="w-full max-w-4xl">

        <div class="mb-8 text-center">

            <h1 class="text-3xl font-bold text-white">
                Create Your Laboratory Account
            </h1>

            <p class="mt-2 text-slate-400">
                Register your diagnostic laboratory and create the administrator account.
            </p>

        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-8">

            @csrf

            {{-- ========================================================= --}}
            {{-- Laboratory Information --}}
            {{-- ========================================================= --}}

            <div class="rounded-xl border border-slate-700 bg-slate-800 p-8 shadow-lg">

                <h2 class="mb-6 text-xl font-semibold text-white">
                    🏥 Laboratory Information
                </h2>

                <div class="grid gap-6 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Laboratory Name
                        </label>

                        <input
                            type="text"
                            name="laboratory_name"
                            value="{{ old('laboratory_name') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                        @error('laboratory_name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                        >

                        @error('phone')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Laboratory Email
                        </label>

                        <input
                            type="email"
                            name="laboratory_email"
                            value="{{ old('laboratory_email') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                        >

                        @error('laboratory_email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Country
                        </label>

                        <input
                            type="text"
                            name="country"
                            value="{{ old('country', 'Nigeria') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                        @error('country')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Address
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                        >{{ old('address') }}</textarea>

                        @error('address')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror

                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Currency Code
                        </label>

                        <input
                            type="text"
                            name="currency_code"
                            value="{{ old('currency_code', 'NGN') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                        @error('currency_code')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Currency Symbol
                        </label>

                        <input
                            type="text"
                            name="currency_symbol"
                            value="{{ old('currency_symbol', '₦') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                        @error('currency_symbol')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Timezone
                        </label>

                        <input
                            type="text"
                            name="timezone"
                            value="{{ old('timezone', 'Africa/Lagos') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                        @error('timezone')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- Administrator --}}
            {{-- ========================================================= --}}

            <div class="rounded-xl border border-slate-700 bg-slate-800 p-8 shadow-lg">

                <h2 class="mb-6 text-xl font-semibold text-white">
                    👤 Administrator Information
                </h2>

                <div class="grid gap-6 md:grid-cols-2">

                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror

                    </div>

                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror

                    </div>

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror

                    </div>

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-300">
                            Confirm Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-blue-500 focus:outline-none"
                            required
                        >

                    </div>

                </div>

            </div>

            <div class="flex items-center justify-between">

                <a
                    href="{{ route('login') }}"
                    class="text-sm text-slate-400 hover:text-white"
                >
                    Already have an account?
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-8 py-3 font-semibold text-white transition hover:bg-blue-700"
                >
                    Register Laboratory
                </button>

            </div>

        </form>

    </div>

</x-guest-layout>