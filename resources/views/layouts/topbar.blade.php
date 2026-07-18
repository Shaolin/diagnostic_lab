<header class="sticky top-0 z-40 border-b border-slate-700 bg-slate-800">

    <div class="flex h-16 items-center justify-between px-8">

        {{-- Left Side --}}
        <div>
            <h1 class="text-lg font-semibold text-white">
                {{ config('app.name', 'Diagnostic Lab System') }}
            </h1>

            <p class="text-sm text-slate-400">
                Welcome back, {{ Auth::user()->name }}
            </p>
        </div>

        {{-- Right Side --}}
        <div class="flex items-center gap-4">

            {{-- User Dropdown --}}
            <div x-data="{ open: false }" class="relative">

                <button
                    @click="open = !open"
                    class="flex items-center gap-3 rounded-lg border border-slate-700 bg-slate-900 px-4 py-2 text-sm text-white transition hover:bg-slate-700">

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 font-semibold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="hidden text-left md:block">
                        <div class="font-medium">
                            {{ Auth::user()->name }}
                        </div>

                       <div class="text-xs text-slate-400">

    @if(Auth::user()->isSuperAdmin())
        Super Administrator
    @elseif(Auth::user()->isAdmin())
        Laboratory Administrator
    @elseif(Auth::user()->isStaff())
        Staff
    @else
        User
    @endif

</div>
                    </div>

                    <svg class="h-4 w-4 text-slate-400"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M19 9l-7 7-7-7"/>

                    </svg>

                </button>

                {{-- Dropdown Menu --}}
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-48 overflow-hidden rounded-lg border border-slate-700 bg-slate-800 shadow-xl">

                    <a href="{{ route('profile.edit') }}"
                       class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="block w-full px-4 py-3 text-left text-sm text-red-400 hover:bg-slate-700">

                            Logout

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header>