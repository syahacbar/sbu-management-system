<x-layouts.admin title="{{ $item ? 'Edit Tenaga Ahli' : 'Tambah Tenaga Ahli' }}" :company="$company">
    <div class="space-y-5">
        <a href="{{ route('companies.workspace.experts.index', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-950 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Tenaga Ahli
        </a>

        <section class="max-w-4xl rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5">
                <h3 class="text-lg font-semibold text-slate-950">{{ $item ? 'Edit Tenaga Ahli' : 'Tambah Tenaga Ahli' }}</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Lengkapi berkas kualifikasi keahlian, nomor sertifikasi SKK, serta tanggal masa aktif ahli.
                </p>
            </div>

            <form
                method="POST"
                action="{{ $item ? route('companies.workspace.experts.update', [$company, $item]) : route('companies.workspace.experts.store', $company) }}"
                class="p-5 space-y-6"
            >
                @csrf
                @if ($item)
                    @method('PUT')
                @endif

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider border-b border-slate-100 pb-2">Identitas Personal</h4>

                    <div class="grid gap-5 md:grid-cols-3">
                        <!-- Tipe Tenaga Ahli -->
                        <div>
                            <label for="expert_type" class="block text-sm font-semibold text-slate-700">Tipe Ahli <span class="text-red-500">*</span></label>
                            <select
                                name="expert_type"
                                id="expert_type"
                                required
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('expert_type') border-red-500 @enderror"
                            >
                                <option value="">Pilih Tipe</option>
                                @foreach ($types as $val => $label)
                                    <option value="{{ $val }}" @selected(old('expert_type', $item?->expert_type) === $val)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('expert_type') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                required
                                value="{{ old('name', $item?->name) }}"
                                placeholder="Nama lengkap gelar"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('name') border-red-500 @enderror"
                            >
                            @error('name') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-sm font-semibold text-slate-700">NIK (16 Digit) <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                name="nik"
                                id="nik"
                                required
                                maxlength="16"
                                value="{{ old('nik', $item?->nik) }}"
                                placeholder="Masukkan NIK 16 digit"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('nik') border-red-500 @enderror"
                            >
                            @error('nik') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <!-- NPWP -->
                        <div>
                            <label for="npwp" class="block text-sm font-semibold text-slate-700">NPWP Ahli</label>
                            <input
                                type="text"
                                name="npwp"
                                id="npwp"
                                value="{{ old('npwp', $item?->npwp) }}"
                                placeholder="00.000.000.0-000.000"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('npwp') border-red-500 @enderror"
                            >
                            @error('npwp') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider border-b border-slate-100 pb-2">Sertifikat Kompetensi Kerja (SKK / SIB)</h4>

                    <div class="grid gap-5 md:grid-cols-2">
                        <!-- Nomor Registrasi SKK -->
                        <div>
                            <label for="skk_registration_number" class="block text-sm font-semibold text-slate-700">Nomor Registrasi SKK</label>
                            <input
                                type="text"
                                name="skk_registration_number"
                                id="skk_registration_number"
                                value="{{ old('skk_registration_number', $item?->skk_registration_number) }}"
                                placeholder="Contoh: Reg. 12345/SKK/LPJK"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('skk_registration_number') border-red-500 @enderror"
                            >
                            @error('skk_registration_number') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Jenjang SKK -->
                        <div>
                            <label for="skk_level" class="block text-sm font-semibold text-slate-700">Jenjang SKK</label>
                            <input
                                type="text"
                                name="skk_level"
                                id="skk_level"
                                value="{{ old('skk_level', $item?->skk_level) }}"
                                placeholder="Contoh: Jenjang 7, Jenjang 8, Muda"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('skk_level') border-red-500 @enderror"
                            >
                            @error('skk_level') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        <!-- Klasifikasi SKK -->
                        <div>
                            <label for="skk_classification" class="block text-sm font-semibold text-slate-700">Klasifikasi SKK</label>
                            <input
                                type="text"
                                name="skk_classification"
                                id="skk_classification"
                                value="{{ old('skk_classification', $item?->skk_classification) }}"
                                placeholder="Contoh: Sipil, Arsitektur"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('skk_classification') border-red-500 @enderror"
                            >
                            @error('skk_classification') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Subklasifikasi SKK -->
                        <div>
                            <label for="skk_subclassification" class="block text-sm font-semibold text-slate-700">Subklasifikasi SKK</label>
                            <input
                                type="text"
                                name="skk_subclassification"
                                id="skk_subclassification"
                                value="{{ old('skk_subclassification', $item?->skk_subclassification) }}"
                                placeholder="Contoh: Gedung, Jalan Raya"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('skk_subclassification') border-red-500 @enderror"
                            >
                            @error('skk_subclassification') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kualifikasi SKK -->
                        <div>
                            <label for="skk_qualification" class="block text-sm font-semibold text-slate-700">Kualifikasi SKK</label>
                            <input
                                type="text"
                                name="skk_qualification"
                                id="skk_qualification"
                                value="{{ old('skk_qualification', $item?->skk_qualification) }}"
                                placeholder="Contoh: Ahli Muda, Ahli Madya"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('skk_qualification') border-red-500 @enderror"
                            >
                            @error('skk_qualification') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <!-- Tanggal Terbit SKK -->
                        <div>
                            <label for="skk_issued_at" class="block text-sm font-semibold text-slate-700">Tanggal Terbit SKK</label>
                            <input
                                type="date"
                                name="skk_issued_at"
                                id="skk_issued_at"
                                value="{{ old('skk_issued_at', $item?->skk_issued_at?->format('Y-m-d')) }}"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('skk_issued_at') border-red-500 @enderror"
                            >
                            @error('skk_issued_at') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tanggal Expired SKK -->
                        <div>
                            <label for="skk_expired_at" class="block text-sm font-semibold text-slate-700">Tanggal Expired SKK</label>
                            <input
                                type="date"
                                name="skk_expired_at"
                                id="skk_expired_at"
                                value="{{ old('skk_expired_at', $item?->skk_expired_at?->format('Y-m-d')) }}"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('skk_expired_at') border-red-500 @enderror"
                            >
                            @error('skk_expired_at') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Catatan / Keterangan -->
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-slate-700">Catatan Lainnya</label>
                        <textarea
                            name="notes"
                            id="notes"
                            rows="2"
                            placeholder="Catatan tambahan mengenai status ahli atau sertifikasi..."
                            class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('notes') border-red-500 @enderror"
                        >{{ old('notes', $item?->notes) }}</textarea>
                        @error('notes') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                    <a href="{{ route('companies.workspace.experts.index', $company) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 font-medium">
                        Batal
                    </a>
                    <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 font-medium">
                        Simpan Tenaga Ahli
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
