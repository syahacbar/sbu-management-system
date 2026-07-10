<form method="POST" action="{{ $action }}" class="space-y-6 p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-4">
        <h4 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-slate-100 pb-2 dark:border-slate-700 dark:text-emerald-400">Informasi Utama</h4>
        
        <div class="grid gap-5 md:grid-cols-3">
            <div class="md:col-span-1">
                <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Perusahaan <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $company?->name) }}" required placeholder="Contoh: PT Konstruksi Maju" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('name') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="business_type" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Jenis Usaha</label>
                <input id="business_type" name="business_type" type="text" value="{{ old('business_type', $company?->business_type) }}" placeholder="Contoh: PT, CV, Koperasi" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('business_type') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="qualification" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kualifikasi</label>
                <input id="qualification" name="qualification" type="text" value="{{ old('qualification', $company?->qualification) }}" placeholder="Contoh: Kecil, Menengah, Besar" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('qualification') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
        <h4 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-slate-100 pb-2 dark:border-slate-700 dark:text-emerald-400">Legalitas & Kontak</h4>
        
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="nib" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">NIB (Nomor Induk Berusaha)</label>
                <input id="nib" name="nib" type="text" value="{{ old('nib', $company?->nib) }}" placeholder="Nomor NIB 13 digit" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('nib') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="npwp" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">NPWP</label>
                <input id="npwp" name="npwp" type="text" value="{{ old('npwp', $company?->npwp) }}" placeholder="Format: 00.000.000.0-000.000" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('npwp') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Email Perusahaan</label>
                <input id="email" name="email" type="email" value="{{ old('email', $company?->email) }}" placeholder="alamat@email.com" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('email') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Telepon / Kontak</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $company?->phone) }}" placeholder="Nomor telepon aktif" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('phone') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
        <h4 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-slate-100 pb-2 dark:border-slate-700 dark:text-emerald-400">Alamat Lengkap Perusahaan</h4>

        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label for="province" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Provinsi</label>
                <input id="province" name="province" type="text" value="{{ old('province', $company?->province) }}" placeholder="Provinsi" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('province') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="city" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kabupaten / Kota</label>
                <input id="city" name="city" type="text" value="{{ old('city', $company?->city) }}" placeholder="Kota / Kabupaten" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('city') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="district" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kecamatan</label>
                <input id="district" name="district" type="text" value="{{ old('district', $company?->district) }}" placeholder="Kecamatan" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('district') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label for="village" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kelurahan / Desa</label>
                <input id="village" name="village" type="text" value="{{ old('village', $company?->village) }}" placeholder="Kelurahan / Desa" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('village') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="rt_rw" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">RT / RW</label>
                <input id="rt_rw" name="rt_rw" type="text" value="{{ old('rt_rw', $company?->rt_rw) }}" placeholder="Contoh: 001/002" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('rt_rw') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="street" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Jalan / Blok / No.</label>
                <input id="street" name="street" type="text" value="{{ old('street', $company?->street) }}" placeholder="Nama jalan, perumahan, nomor rumah" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('street') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
        <h4 class="text-sm font-bold text-emerald-800 uppercase tracking-wider border-b border-slate-100 pb-2 dark:border-slate-700 dark:text-emerald-400">Administrasi & Catatan</h4>

        <div class="grid gap-5 md:grid-cols-3">
            <div class="md:col-span-1">
                <label for="signing_place" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tempat Tanda Tangan Dokumen</label>
                <input id="signing_place" name="signing_place" type="text" value="{{ old('signing_place', $company?->signing_place) }}" placeholder="Contoh: Jakarta, Jayapura" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                @error('signing_place') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Catatan Lainnya</label>
                <textarea id="notes" name="notes" rows="2" placeholder="Catatan internal atau deskripsi tambahan..." class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">{{ old('notes', $company?->notes) }}</textarea>
                @error('notes') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end dark:border-slate-700">
        <a href="{{ $cancelRoute }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
            Batal
        </a>
        <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-700">
            Simpan Perusahaan
        </button>
    </div>
</form>
