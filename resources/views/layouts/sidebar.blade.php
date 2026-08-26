<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-700">

    <!-- Logo -->
    <div class="flex h-16 items-center border-b border-slate-700 px-6">

        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-xl font-bold text-white">
                🧪
            </div>

            <div>
                <h1 class="text-lg font-bold text-white">
                    Diagnostic Lab
                </h1>

               <p class="text-xs text-slate-400">

    @if(auth()->user()->isSuperAdmin())
        Super Admin
    @elseif(auth()->user()->isAdmin())
        Laboratory Administrator
    @else
        Staff
    @endif

</p>
            </div>

        </a>

    </div>

    
    <!-- Navigation -->
<nav class="mt-6 space-y-2 px-4">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
       {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

        <span>🏠</span>
        Dashboard

    </a>

    {{-- Laboratories (Admins Only) --}}
   @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())

<a href="{{ route('laboratories.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
   {{ request()->routeIs('laboratories.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

    <span>🏥</span>
    Laboratories

</a>

@endif

    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())

<a href="{{ route('users.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
   {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

    <span>👤</span>
    Users

</a>

@endif

{{-- Patients --}}
<a href="{{ route('patients.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
   {{ request()->routeIs('patients.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

    <span>🩺</span>
    Patients

</a>

{{-- Test Types --}}
<a href="{{ route('test-types.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
   {{ request()->routeIs('test-types.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

    <span>🧪</span>
    Test Types

</a>

{{-- Test Requests --}}
<a href="{{ route('test-requests.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
   {{ request()->routeIs('test-requests.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

    <span>🧾</span>
    Test Requests

</a>

{{-- Results --}}
<a href="{{ route('results.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
   {{ request()->routeIs('results.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

    <span>📄</span>
    Results

</a>

{{-- Payments --}}
<a href="{{ route('payments.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
   {{ request()->routeIs('payments.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

    <span>💳</span>
    Payments

</a>

{{-- Reports --}}
<a href="{{ route('reports.index') }}"
   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
   {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

    <span>📊</span>
    Reports

</a>

    <!-- Future Modules -->



   



    <div class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-500">
        <span>⚙️</span>
        Settings
    </div>

</nav>

</aside>