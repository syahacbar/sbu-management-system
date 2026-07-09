<x-layouts.admin :title="($person ? 'Edit ' : 'Tambah ') . ($type === 'direktur' ? 'Direktur' : 'PJBU')" :company="$company">
    <section class="max-w-2xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">
                {{ ($person ? 'Edit ' : 'Tambah ') . ($type === 'direktur' ? 'Direktur' : 'PJBU') }}
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Isi data diri {{ $type === 'direktur' ? 'Direktur' : 'PJBU' }} untuk keperluan administrasi pengajuan SBU.
            </p>
        </div>

        <form 
            method="POST" 
            action="{{ $person ? route('companies.workspace.' . ($type === 'direktur' ? 'directors' : 'pjbus') . '.update', [$company, $person]) : route('companies.workspace.' . ($type === 'direktur' ? 'directors' : 'pjbus') . '.store', $company) }}" 
            class="space-y-5 p-5"
        >
            @csrf
            @if ($person)
                @method('PUT')
            @endif

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $person?->name) }}" required placeholder="Nama lengkap beserta gelar" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('name') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="nik" class="block text-sm font-semibold text-slate-700">NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span></label>
                    <input id="nik" name="nik" type="text" maxlength="16" value="{{ old('nik', $person?->nik) }}" required placeholder="NIK 16 digit" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 font-mono">
                    @error('nik') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="birthplace" class="block text-sm font-semibold text-slate-700">Tempat Lahir</label>
                    <input id="birthplace" name="birthplace" type="text" value="{{ old('birthplace', $person?->birthplace) }}" placeholder="Tempat Lahir" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    @error('birthplace') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="npwp" class="block text-sm font-semibold text-slate-700">NPWP Pribadi</label>
                    <input id="npwp" name="npwp" type="text" value="{{ old('npwp', $person?->npwp) }}" placeholder="Format: 00.000.000.0-000.000" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 font-mono">
                    @error('npwp') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $person?->email) }}" placeholder="alamat@email.com" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    @error('email') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="position" class="block text-sm font-semibold text-slate-700">Jabatan <span class="text-red-500">*</span></label>
                <input id="position" name="position" type="text" value="{{ old('position', $person?->position) }}" required placeholder="Contoh: Direktur Utama, Komisaris, PJBU" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('position') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="relative flex items-start pt-2">
                <div class="flex h-5 items-center">
                    <input id="is_main" name="is_main" type="checkbox" value="1" @checked(old('is_main', $person?->is_main)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                </div>
                <div class="ml-3 text-sm">
                    <label for="is_main" class="font-semibold text-slate-700 cursor-pointer">
                        Ditetapkan sebagai {{ $type === 'direktur' ? 'Direktur Utama / Utama' : 'PJBU Utama' }}
                    </label>
                    <p class="text-xs text-slate-400">
                        Mencentang ini akan otomatis menonaktifkan status Utama pada personel {{ $type === 'direktur' ? 'Direktur' : 'PJBU' }} lain di perusahaan ini.
                    </p>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('companies.workspace.directors_pjbus', $company) }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Batal
                </a>
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                    Simpan Data
                </button>
            </div>
        </form>
    </section>
</x-layouts.admin>
