<x-layouts.admin title="{{ $item ? 'Edit Pengajuan SBU' : 'Tambah Pengajuan SBU' }}" :company="$company">
    <div class="space-y-5">
        <a href="{{ route('companies.workspace.applications.index', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-950 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Pengajuan
        </a>

        <section class="max-w-4xl rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5">
                <h3 class="text-lg font-semibold text-slate-950">{{ $item ? 'Edit Pengajuan SBU' : 'Tambah Pengajuan SBU' }}</h3>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $item ? 'Ubah data detail pengajuan SBU untuk perusahaan ini.' : 'Buat pengajuan SBU baru dengan menentukan data master relasi.' }}
                </p>
            </div>

            <form
                method="POST"
                action="{{ $item ? route('companies.workspace.applications.update', [$company, $item]) : route('companies.workspace.applications.store', $company) }}"
                class="p-5 space-y-6"
            >
                @csrf
                @if ($item)
                    @method('PUT')
                @endif

                <div class="grid gap-5 md:grid-cols-2">
                    <!-- Kode / Nomor Pengajuan -->
                    <div>
                        <label for="code" class="block text-sm font-semibold text-slate-700">Kode / Nomor Pengajuan</label>
                        <input
                            type="text"
                            name="code"
                            id="code"
                            value="{{ old('code', $item?->code) }}"
                            placeholder="Contoh: SBU-2026-001 (Opsional)"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('code') border-red-500 @enderror"
                        >
                        @error('code') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Pengajuan -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700">Nama Pengajuan <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            required
                            value="{{ old('name', $item?->name) }}"
                            placeholder="Masukkan nama pengajuan"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('name') border-red-500 @enderror"
                        >
                        @error('name') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <!-- KBLI -->
                    <div>
                        <label for="master_kbli_id" class="block text-sm font-semibold text-slate-700">KBLI <span class="text-red-500">*</span></label>
                        <select
                            name="master_kbli_id"
                            id="master_kbli_id"
                            required
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_kbli_id') border-red-500 @enderror"
                        >
                            <option value="">Pilih KBLI</option>
                            @foreach ($kblis as $kbli)
                                <option value="{{ $kbli->id }}" @selected((string) old('master_kbli_id', $item?->master_kbli_id) === (string) $kbli->id)>
                                    {{ $kbli->code }} - {{ $kbli->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('master_kbli_id') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Klasifikasi SBU -->
                    <div>
                        <label for="master_sbu_classification_id" class="block text-sm font-semibold text-slate-700">Klasifikasi SBU <span class="text-red-500">*</span></label>
                        <select
                            name="master_sbu_classification_id"
                            id="master_sbu_classification_id"
                            required
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_sbu_classification_id') border-red-500 @enderror"
                        >
                            <option value="">Pilih Klasifikasi SBU</option>
                            @foreach ($classifications as $classification)
                                <option value="{{ $classification->id }}" @selected((string) old('master_sbu_classification_id', $item?->master_sbu_classification_id) === (string) $classification->id)>
                                    {{ $classification->code }} - {{ $classification->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('master_sbu_classification_id') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <!-- Subklasifikasi SBU -->
                    <div>
                        <label for="master_sbu_subclassification_id" class="block text-sm font-semibold text-slate-700">Subklasifikasi SBU <span class="text-red-500">*</span></label>
                        <select
                            name="master_sbu_subclassification_id"
                            id="master_sbu_subclassification_id"
                            required
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_sbu_subclassification_id') border-red-500 @enderror"
                        >
                            <option value="">Pilih Subklasifikasi SBU</option>
                            @foreach ($subclassifications as $sub)
                                <option
                                    value="{{ $sub->id }}"
                                    data-classification-id="{{ $sub->master_sbu_classification_id }}"
                                    @selected((string) old('master_sbu_subclassification_id', $item?->master_sbu_subclassification_id) === (string) $sub->id)
                                >
                                    {{ $sub->code }} - {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('master_sbu_subclassification_id') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Skema SBU -->
                    <div>
                        <label for="master_sbu_scheme_id" class="block text-sm font-semibold text-slate-700">Skema SBU <span class="text-red-500">*</span></label>
                        <select
                            name="master_sbu_scheme_id"
                            id="master_sbu_scheme_id"
                            required
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_sbu_scheme_id') border-red-500 @enderror"
                        >
                            <option value="">Pilih Skema SBU</option>
                            @foreach ($schemes as $scheme)
                                <option
                                    value="{{ $scheme->id }}"
                                    data-kbli-id="{{ $scheme->master_kbli_id }}"
                                    data-classification-id="{{ $scheme->master_sbu_classification_id }}"
                                    data-subclassification-id="{{ $scheme->master_sbu_subclassification_id }}"
                                    @selected((string) old('master_sbu_scheme_id', $item?->master_sbu_scheme_id) === (string) $scheme->id)
                                >
                                    {{ $scheme->scheme_code }} - {{ $scheme->scheme_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('master_sbu_scheme_id') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
                        <select
                            name="status"
                            id="status"
                            required
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('status') border-red-500 @enderror"
                        >
                            @foreach ($statuses as $val => $label)
                                <option value="{{ $val }}" @selected(old('status', $item?->status ?: 'draft') === $val)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Pengajuan -->
                    <div>
                        <label for="record_date" class="block text-sm font-semibold text-slate-700">Tanggal Pengajuan</label>
                        <input
                            type="date"
                            name="record_date"
                            id="record_date"
                            value="{{ old('record_date', $item?->record_date?->format('Y-m-d')) }}"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('record_date') border-red-500 @enderror"
                        >
                        @error('record_date') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nominal / Biaya -->
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-slate-700">Nominal / Biaya (Rupiah)</label>
                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            min="0"
                            step="any"
                            value="{{ old('amount', $item?->amount !== null ? (float)$item->amount : '') }}"
                            placeholder="Contoh: 1000000"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('amount') border-red-500 @enderror"
                        >
                        @error('amount') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <!-- Aktif / Nonaktif -->
                    <div>
                        <label for="is_active" class="block text-sm font-semibold text-slate-700">Status Aktif Rekam <span class="text-red-500">*</span></label>
                        <select
                            name="is_active"
                            id="is_active"
                            required
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('is_active') border-red-500 @enderror"
                        >
                            <option value="1" @selected((string) old('is_active', $item?->is_active ?? '1') === '1')>Aktif</option>
                            <option value="0" @selected((string) old('is_active', $item?->is_active ?? '1') === '0')>Nonaktif</option>
                        </select>
                        @error('is_active') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Urutan -->
                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-slate-700">Urutan Tampilan <span class="text-red-500">*</span></label>
                        <input
                            type="number"
                            name="sort_order"
                            id="sort_order"
                            required
                            min="0"
                            value="{{ old('sort_order', $item?->sort_order ?? 0) }}"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('sort_order') border-red-500 @enderror"
                        >
                        @error('sort_order') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700">Keterangan / Catatan</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        placeholder="Tambahkan catatan khusus mengenai pengajuan ini..."
                        class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('description') border-red-500 @enderror"
                    >{{ old('description', $item?->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                    <a href="{{ route('companies.workspace.applications.index', $company) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Batal
                    </a>
                    <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                        Simpan Pengajuan
                    </button>
                </div>
            </form>
        </section>
    </div>

    <!-- Client-side Interactive Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kbliSelect = document.getElementById('master_kbli_id');
            const classSelect = document.getElementById('master_sbu_classification_id');
            const subSelect = document.getElementById('master_sbu_subclassification_id');
            const schemeSelect = document.getElementById('master_sbu_scheme_id');

            // Store original options
            const originalSubs = Array.from(subSelect.options);
            const originalSchemes = Array.from(schemeSelect.options);

            // Fetch initial selected values (from old() or $item)
            const initialSubVal = subSelect.value;
            const initialSchemeVal = schemeSelect.value;

            function filterSubclassifications() {
                const selectedClassId = classSelect.value;
                
                // Clear and rebuild options
                subSelect.innerHTML = '';
                
                // Keep the first default option
                subSelect.appendChild(originalSubs[0]);

                originalSubs.slice(1).forEach(option => {
                    const classId = option.getAttribute('data-classification-id');
                    if (!selectedClassId || classId === selectedClassId) {
                        subSelect.appendChild(option);
                    }
                });

                // Restore previous selection if it's still available, else default to empty
                const optionExists = Array.from(subSelect.options).some(opt => opt.value === subSelect.dataset.lastVal);
                if (optionExists && subSelect.dataset.lastVal) {
                    subSelect.value = subSelect.dataset.lastVal;
                } else {
                    subSelect.value = '';
                }

                filterSchemes();
            }

            function filterSchemes() {
                const selectedKbliId = kbliSelect.value;
                const selectedClassId = classSelect.value;
                const selectedSubId = subSelect.value;

                schemeSelect.innerHTML = '';
                schemeSelect.appendChild(originalSchemes[0]);

                originalSchemes.slice(1).forEach(option => {
                    const kbliId = option.getAttribute('data-kbli-id');
                    const classId = option.getAttribute('data-classification-id');
                    const subId = option.getAttribute('data-subclassification-id');

                    const matchKbli = !selectedKbliId || kbliId === selectedKbliId;
                    const matchClass = !selectedClassId || classId === selectedClassId;
                    const matchSub = !selectedSubId || subId === selectedSubId;

                    if (matchKbli && matchClass && matchSub) {
                        schemeSelect.appendChild(option);
                    }
                });

                const optionExists = Array.from(schemeSelect.options).some(opt => opt.value === schemeSelect.dataset.lastVal);
                if (optionExists && schemeSelect.dataset.lastVal) {
                    schemeSelect.value = schemeSelect.dataset.lastVal;
                } else {
                    schemeSelect.value = '';
                }
            }

            // Set initial last values
            subSelect.dataset.lastVal = initialSubVal;
            schemeSelect.dataset.lastVal = initialSchemeVal;

            // Trigger initial filtering
            filterSubclassifications();

            // Set event listeners
            classSelect.addEventListener('change', function() {
                subSelect.dataset.lastVal = '';
                schemeSelect.dataset.lastVal = '';
                filterSubclassifications();
            });

            kbliSelect.addEventListener('change', function() {
                schemeSelect.dataset.lastVal = '';
                filterSchemes();
            });

            subSelect.addEventListener('change', function() {
                subSelect.dataset.lastVal = subSelect.value;
                schemeSelect.dataset.lastVal = '';
                filterSchemes();
            });

            schemeSelect.addEventListener('change', function() {
                schemeSelect.dataset.lastVal = schemeSelect.value;
            });
        });
    </script>
</x-layouts.admin>
