<x-layouts.admin title="{{ $item ? 'Edit Dokumen Pendukung' : 'Unggah Dokumen Pendukung' }}" :company="$company">
    <div class="space-y-5">
        <a href="{{ route('companies.workspace.documents.index', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-950 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Dokumen
        </a>

        <section class="max-w-3xl rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5">
                <h3 class="text-lg font-semibold text-slate-950">{{ $item ? 'Edit Dokumen' : 'Unggah Dokumen Baru' }}</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Unggah berkas resmi dalam format PDF, JPG, atau PNG dengan ukuran maksimal 5MB.
                </p>
            </div>

            <form
                method="POST"
                action="{{ $item ? route('companies.workspace.documents.update', [$company, $item]) : route('companies.workspace.documents.store', $company) }}"
                enctype="multipart/form-data"
                class="p-5 space-y-6"
            >
                @csrf
                @if ($item)
                    @method('PUT')
                @endif

                <div class="grid gap-5 md:grid-cols-2">
                    <!-- Jenis Dokumen -->
                    <div>
                        <label for="document_type" class="block text-sm font-semibold text-slate-700">Jenis Dokumen <span class="text-red-500">*</span></label>
                        <select
                            name="document_type"
                            id="document_type"
                            required
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('document_type') border-red-500 @enderror"
                        >
                            <option value="">Pilih Jenis Dokumen</option>
                            @foreach ($documentTypes as $type)
                                <option value="{{ $type }}" @selected(old('document_type', $item?->document_type) === $type)>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('document_type') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status Validitas Dokumen -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700">Status Validasi Dokumen <span class="text-red-500">*</span></label>
                        <select
                            name="status"
                            id="status"
                            required
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('status') border-red-500 @enderror"
                        >
                            @foreach ($statuses as $val => $lbl)
                                <option value="{{ $val }}" @selected(old('status', $item?->status ?: 'ada') === $val)>
                                    {{ $lbl }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <!-- Tanggal Terbit Dokumen -->
                    <div>
                        <label for="document_date" class="block text-sm font-semibold text-slate-700">Tanggal Terbit Dokumen</label>
                        <input
                            type="date"
                            name="document_date"
                            id="document_date"
                            value="{{ old('document_date', $item?->document_date?->format('Y-m-d')) }}"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('document_date') border-red-500 @enderror"
                        >
                        @error('document_date') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Kedaluwarsa Dokumen -->
                    <div>
                        <label for="expired_at" class="block text-sm font-semibold text-slate-700">Tanggal Kedaluwarsa</label>
                        <input
                            type="date"
                            name="expired_at"
                            id="expired_at"
                            value="{{ old('expired_at', $item?->expired_at?->format('Y-m-d')) }}"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('expired_at') border-red-500 @enderror"
                        >
                        @error('expired_at') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">Kosongkan jika dokumen berlaku selamanya / seumur hidup.</p>
                    </div>
                </div>

                <!-- Unggah File -->
                <div>
                    <label for="file" class="block text-sm font-semibold text-slate-700">
                        Berkas File (PDF, JPG, PNG) <span class="text-red-500">{{ $item ? '' : '*' }}</span>
                    </label>
                    
                    @if ($item && $item->file_path)
                        <div class="mt-2 flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50 p-3 text-sm">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 truncate">{{ $item->original_filename }}</p>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">Sudah terunggah di server</p>
                            </div>
                            <a href="{{ Storage::url($item->file_path) }}" target="_blank" class="rounded bg-white border border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                Lihat Berkas
                            </a>
                        </div>
                    @endif

                    <input
                        type="file"
                        name="file"
                        id="file"
                        accept=".pdf,.jpg,.jpeg,.png"
                        {{ $item ? '' : 'required' }}
                        class="mt-3 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition"
                    >
                    @error('file') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    <p class="mt-1.5 text-xs text-slate-400">Ukuran berkas maksimal 5MB.</p>
                </div>

                <!-- Catatan Tambahan -->
                <div>
                    <label for="notes" class="block text-sm font-semibold text-slate-700">Catatan / Keterangan Dokumen</label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="3"
                        placeholder="Tambahkan catatan khusus, nomor SK, instansi penerbit, atau alasan revisi..."
                        class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('notes') border-red-500 @enderror"
                    >{{ old('notes', $item?->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                    <a href="{{ route('companies.workspace.documents.index', $company) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 font-medium">
                        Batal
                    </a>
                    <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 font-medium">
                        Simpan Dokumen
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
