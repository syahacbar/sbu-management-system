<x-layouts.admin title="{{ $item ? 'Edit Pengajuan SBU' : 'Tambah Pengajuan SBU' }}" :company="$company">
    <div class="space-y-5">
        <a href="{{ route('companies.workspace.applications.index', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-950 dark:hover:text-white transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Pengajuan
        </a>

        <section class="max-w-4xl rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
            <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">{{ $item ? 'Edit Pengajuan SBU' : 'Tambah Pengajuan SBU' }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ $item ? 'Ubah data detail pengajuan SBU untuk perusahaan ini.' : 'Buat pengajuan SBU baru dengan menentukan data master referensi.' }}
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

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700 pb-2">Informasi Pengajuan</h4>

                    <div class="grid gap-5 md:grid-cols-3">
                        <!-- Tipe Pengajuan -->
                        <div>
                            <label for="application_type" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tipe Pengajuan <span class="text-red-500">*</span></label>
                            <select
                                name="application_type"
                                id="application_type"
                                required
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('application_type') border-red-500 @enderror"
                            >
                                <option value="">Pilih Tipe</option>
                                @foreach ($types as $val => $label)
                                    <option value="{{ $val }}" @selected(old('application_type', $item?->application_type) === $val)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('application_type') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tahun Pengajuan -->
                        <div>
                            <label for="application_year" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tahun Pengajuan <span class="text-red-500">*</span></label>
                            <input
                                type="number"
                                name="application_year"
                                id="application_year"
                                required
                                min="2000"
                                max="2100"
                                value="{{ old('application_year', $item?->application_year ?: date('Y')) }}"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('application_year') border-red-500 @enderror"
                            >
                            @error('application_year') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tanggal Pengajuan -->
                        <div>
                            <label for="submission_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Pengajuan</label>
                            <input
                                type="date"
                                name="submission_date"
                                id="submission_date"
                                value="{{ old('submission_date', $item?->submission_date?->format('Y-m-d')) }}"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('submission_date') border-red-500 @enderror"
                            >
                            @error('submission_date') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700 pb-2">Domain Klasifikasi SBU</h4>

                    <div class="grid gap-5 md:grid-cols-2">
                        <!-- KBLI -->
                        <div>
                            <label for="master_kbli_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">KBLI <span class="text-red-500">*</span></label>
                            <select
                                name="master_kbli_id"
                                id="master_kbli_id"
                                required
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_kbli_id') border-red-500 @enderror"
                            >
                                <option value="">Pilih KBLI</option>
                                @foreach ($kblis as $kbli)
                                    <option value="{{ $kbli->id }}" @selected((string) old('master_kbli_id', $item?->master_kbli_id) === (string) $kbli->id)>
                                        {{ $kbli->code }} - {{ $kbli->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('master_kbli_id') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Klasifikasi SBU -->
                        <div>
                            <label for="master_sbu_classification_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Klasifikasi SBU <span class="text-red-500">*</span></label>
                            <select
                                name="master_sbu_classification_id"
                                id="master_sbu_classification_id"
                                required
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_sbu_classification_id') border-red-500 @enderror"
                            >
                                <option value="">Pilih Klasifikasi SBU</option>
                                @foreach ($classifications as $classification)
                                    <option value="{{ $classification->id }}" @selected((string) old('master_sbu_classification_id', $item?->master_sbu_classification_id) === (string) $classification->id)>
                                        {{ $classification->code }} - {{ $classification->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('master_sbu_classification_id') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <!-- Subklasifikasi SBU -->
                        <div>
                            <label for="master_sbu_subclassification_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Subklasifikasi SBU <span class="text-red-500">*</span></label>
                            <select
                                name="master_sbu_subclassification_id"
                                id="master_sbu_subclassification_id"
                                required
                                data-last-val="{{ old('master_sbu_subclassification_id', $item?->master_sbu_subclassification_id) }}"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_sbu_subclassification_id') border-red-500 @enderror"
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
                            @error('master_sbu_subclassification_id') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Skema SBU -->
                        <div>
                            <label for="master_sbu_scheme_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Skema SBU <span class="text-red-500">*</span></label>
                            <select
                                name="master_sbu_scheme_id"
                                id="master_sbu_scheme_id"
                                required
                                data-last-val="{{ old('master_sbu_scheme_id', $item?->master_sbu_scheme_id) }}"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_sbu_scheme_id') border-red-500 @enderror"
                            >
                                <option value="">Pilih Skema SBU</option>
                                @foreach ($schemes as $scheme)
                                    <option
                                        value="{{ $scheme->id }}"
                                        data-kbli-id="{{ $scheme->master_kbli_id }}"
                                        data-classification-id="{{ $scheme->master_sbu_classification_id }}"
                                        data-subclassification-id="{{ $scheme->master_sbu_subclassification_id }}"
                                        data-qualification="{{ $scheme->qualification }}"
                                        @selected((string) old('master_sbu_scheme_id', $item?->master_sbu_scheme_id) === (string) $scheme->id)
                                    >
                                        {{ $scheme->scheme_code }} - {{ $scheme->scheme_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('master_sbu_scheme_id') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700 pb-2">Kualifikasi & Penyelenggara</h4>

                    <div class="grid gap-5 md:grid-cols-3">
                        <!-- Kualifikasi -->
                        <div>
                            <label for="qualification" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kualifikasi SBU</label>
                            <input
                                type="text"
                                name="qualification"
                                id="qualification"
                                value="{{ old('qualification', $item?->qualification) }}"
                                placeholder="Contoh: Kecil, Menengah"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('qualification') border-red-500 @enderror"
                            >
                            @error('qualification') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- LSBU -->
                        <div>
                            <label for="lsbu_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama LSBU</label>
                            <input
                                type="text"
                                name="lsbu_name"
                                id="lsbu_name"
                                value="{{ old('lsbu_name', $item?->lsbu_name) }}"
                                placeholder="Nama Lembaga Sertifikasi"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('lsbu_name') border-red-500 @enderror"
                            >
                            @error('lsbu_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Asosiasi -->
                        <div>
                            <label for="association_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Asosiasi</label>
                            <input
                                type="text"
                                name="association_name"
                                id="association_name"
                                value="{{ old('association_name', $item?->association_name) }}"
                                placeholder="Nama Asosiasi Perusahaan"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('association_name') border-red-500 @enderror"
                            >
                            @error('association_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Status Pengajuan <span class="text-red-500">*</span></label>
                            <select
                                name="status"
                                id="status"
                                required
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('status') border-red-500 @enderror"
                            >
                                @foreach ($statuses as $val => $label)
                                    <option value="{{ $val }}" @selected(old('status', $item?->status ?: 'draft') === $val)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Catatan Lainnya</label>
                        <textarea
                            name="notes"
                            id="notes"
                            rows="2"
                            placeholder="Catatan tambahan mengenai berkas atau revisi..."
                            class="mt-2 w-full rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('notes') border-red-500 @enderror"
                        >{{ old('notes', $item?->notes) }}</textarea>
                        @error('notes') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-700 pt-5">
                    <a href="{{ route('companies.workspace.applications.index', $company) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-100 dark:hover:bg-slate-600 font-medium">
                        Batal
                    </a>
                    <button type="submit" class="rounded-md bg-emerald-700 dark:bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700 font-medium">
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
            const qualInput = document.getElementById('qualification');

            // Store original options
            const originalSubs = Array.from(subSelect.options);
            const originalSchemes = Array.from(schemeSelect.options);

            function filterSubclassifications() {
                const selectedClassId = classSelect.value;
                
                // Clear and rebuild options
                subSelect.innerHTML = '';
                subSelect.appendChild(originalSubs[0]); // Keep "Pilih Subklasifikasi"

                originalSubs.slice(1).forEach(option => {
                    const classId = option.getAttribute('data-classification-id');
                    if (!selectedClassId || classId === selectedClassId) {
                        subSelect.appendChild(option);
                    }
                });

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
                schemeSelect.appendChild(originalSchemes[0]); // Keep "Pilih Skema"

                originalSchemes.slice(1).forEach(option => {
                    const kbliId = option.getAttribute('data-kbli-id');
                    const classId = option.getAttribute('data-classification-id');
                    const subId = option.getAttribute('data-subclassification-id');

                    const matchesKbli = !selectedKbliId || kbliId === selectedKbliId;
                    const matchesClass = !selectedClassId || classId === selectedClassId;
                    const matchesSub = !selectedSubId || subId === selectedSubId;

                    if (matchesKbli && matchesClass && matchesSub) {
                        schemeSelect.appendChild(option);
                    }
                });

                const optionExists = Array.from(schemeSelect.options).some(opt => opt.value === schemeSelect.dataset.lastVal);
                if (optionExists && schemeSelect.dataset.lastVal) {
                    schemeSelect.value = schemeSelect.dataset.lastVal;
                } else {
                    schemeSelect.value = '';
                }
                
                updateQualification();
            }

            function updateQualification() {
                const selectedOption = schemeSelect.options[schemeSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const qual = selectedOption.getAttribute('data-qualification');
                    if (qual) {
                        qualInput.value = qual;
                    }
                }
            }

            // Listeners
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
                schemeSelect.dataset.lastVal = '';
                filterSchemes();
            });

            schemeSelect.addEventListener('change', updateQualification);

            // Run on initial load
            filterSubclassifications();
        });
    </script>
</x-layouts.admin>
