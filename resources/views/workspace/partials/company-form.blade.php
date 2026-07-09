<form method="POST" action="{{ $action }}" class="space-y-5 p-5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nama Perusahaan</label>
        <input id="name" name="name" type="text" value="{{ old('name', $company?->name) }}" required class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="nib" class="block text-sm font-medium text-slate-700">NIB</label>
            <input id="nib" name="nib" type="text" value="{{ old('nib', $company?->nib) }}" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            @error('nib') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="npwp" class="block text-sm font-medium text-slate-700">NPWP</label>
            <input id="npwp" name="npwp" type="text" value="{{ old('npwp', $company?->npwp) }}" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            @error('npwp') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $company?->email) }}" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700">Telepon</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone', $company?->phone) }}" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-slate-700">Alamat</label>
        <textarea id="address" name="address" rows="3" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">{{ old('address', $company?->address) }}</textarea>
        @error('address') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-slate-700">Keterangan</label>
        <textarea id="description" name="description" rows="3" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">{{ old('description', $company?->description) }}</textarea>
        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <fieldset>
        <legend class="block text-sm font-medium text-slate-700">Status</legend>
        <div class="mt-2 flex gap-4">
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="radio" name="is_active" value="1" @checked((string) old('is_active', $company?->is_active ?? true) === '1') class="border-slate-300 text-emerald-700 focus:ring-emerald-600">
                Aktif
            </label>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="radio" name="is_active" value="0" @checked((string) old('is_active', $company?->is_active ?? true) === '0') class="border-slate-300 text-emerald-700 focus:ring-emerald-600">
                Nonaktif
            </label>
        </div>
        @error('is_active') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </fieldset>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
        <a href="{{ $cancelRoute }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            Batal
        </a>
        <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
            Simpan
        </button>
    </div>
</form>
