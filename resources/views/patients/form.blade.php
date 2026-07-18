
                {{-- Patient Information --}}
              <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Patient Information
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Enter the patient's basic personal information.
        </p>

    </div>

    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

        {{-- First Name --}}
        <div>

            <label
                for="first_name"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                First Name <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                id="first_name"
                name="first_name"
                value="{{ old('first_name', $patient->first_name ?? '') }}"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                required
            >

            @error('first_name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror

        </div>

        {{-- Last Name --}}
        <div>

            <label
                for="last_name"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                Last Name <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                id="last_name"
                name="last_name"
                value="{{ old('last_name', $patient->last_name ?? '') }}"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
                required
            >

            @error('last_name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror

        </div>

        {{-- Other Names --}}
        <div>

            <label
                for="other_names"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                Other Names
            </label>

            <input
                type="text"
                id="other_names"
                name="other_names"
                value="{{ old('other_names', $patient->other_names ?? '') }}"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500"
            >

            @error('other_names')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror

        </div>

        {{-- Gender --}}
        <div>

            <label
                for="gender"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                Gender <span class="text-red-500">*</span>
            </label>

            <select
                id="gender"
                name="gender"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
                required
            >

                <option value="">Select Gender</option>

                <option
                    value="Male"
                   
                    @selected(old('gender', $patient->gender ?? '') == 'Male')
                >
                    Male
                </option>

                <option
                    value="Female"
                    @selected(old('gender', $patient->gender ?? '') == 'Female')
                >
                    Female
                </option>

            </select>

            @error('gender')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror

        </div>

        {{-- Date of Birth --}}
        <div>

            <label
                for="date_of_birth"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                Date of Birth
            </label>

            <input
                type="date"
                id="date_of_birth"
                name="date_of_birth"
                value="{{ old('date_of_birth', optional($patient->date_of_birth ?? null)->format('Y-m-d')) }}"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
            >

            @error('date_of_birth')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror

        </div>

    </div>

</div>
{{-- Contact Information --}}
<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Contact Information
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Contact details for the patient.
        </p>

    </div>

    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

        {{-- Phone --}}
        <div>

            <label
                for="phone"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                Phone Number <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone', $patient->phone ?? '') }}"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
                required
            >

            @error('phone')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror

        </div>

        {{-- Email --}}
        <div>

            <label
                for="email"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                Email Address
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $patient->email ?? '') }}"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
            >

            @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror

        </div>

        {{-- Address --}}
        <div class="md:col-span-2">

            <label
                for="address"
                class="mb-2 block text-sm font-medium text-slate-300"
            >
                Address
            </label>

            <textarea
                id="address"
                name="address"
                rows="3"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
            >{{ old('address', $patient->address ?? '') }}</textarea>

            @error('address')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror

        </div>

    </div>

</div>
{{-- Medical Information --}}
<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Medical Information
        </h3>

    </div>

    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

        {{-- Blood Group --}}
        <div>

            <label class="mb-2 block text-sm font-medium text-slate-300">
                Blood Group
            </label>

            <select
                name="blood_group"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white"
            >
                <option value="">Select Blood Group</option>

                @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $group)

                    <option
                        value="{{ $group }}"
                        @selected(old('blood_group', $patient->blood_group ?? '') == $group)
                    >
                        {{ $group }}
                    </option>

                @endforeach

            </select>

        </div>

        {{-- Genotype --}}
        <div>

            <label class="mb-2 block text-sm font-medium text-slate-300">
                Genotype
            </label>

            <select
                name="genotype"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white"
            >
                <option value="">Select Genotype</option>

                @foreach (['AA','AS','AC','SS','SC'] as $type)

                    <option
                        value="{{ $type }}"
                       @selected(old('genotype', $patient->genotype ?? '') == $type)
                    >
                        {{ $type }}
                    </option>

                @endforeach

            </select>

        </div>

    </div>

</div>
{{-- Emergency Contact --}}
<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Emergency Contact
        </h3>

    </div>

    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

        <div>

            <label class="mb-2 block text-sm font-medium text-slate-300">
                Contact Name
            </label>

            <input
                type="text"
                name="emergency_contact_name"
                value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white"
            >

        </div>

        <div>

            <label class="mb-2 block text-sm font-medium text-slate-300">
                Contact Phone
            </label>

            <input
                type="text"
                name="emergency_contact_phone"
                value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone ?? '') }}"
                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white"
            >

        </div>

    </div>

</div>
{{-- Notes --}}
<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Additional Notes
        </h3>

    </div>

    <div class="p-6">

        <textarea
            name="notes"
            rows="5"
            class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white"
        >{{ old('notes', $patient->notes ?? '') }}</textarea>

    </div>

</div>
{{-- Status & Actions --}}
<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="flex flex-col gap-6 p-6 md:flex-row md:items-center md:justify-between">

        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                name="is_active"
                value="1"
               @checked(old('is_active', $patient->is_active ?? true))
                class="rounded border-slate-600 bg-slate-900 text-indigo-600"
            >

            <span class="text-sm text-slate-300">
                Patient is Active
            </span>

        </label>

        <div class="flex gap-3">

            <a
                href="{{ route('patients.index') }}"
                class="rounded-lg border border-slate-600 bg-slate-900 px-6 py-2.5 text-slate-300 hover:bg-slate-700"
            >
                Cancel
            </a>

           <button
    type="submit"
    class="rounded-lg bg-indigo-600 px-6 py-2.5 font-semibold text-white hover:bg-indigo-700"
>
    {{ isset($patient) ? 'Update Patient' : 'Save Patient' }}
</button>

        </div>

    </div>

</div>

          