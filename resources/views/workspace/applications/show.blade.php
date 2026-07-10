<x-layouts.admin title="Detail Pengajuan SBU" :company="$company">
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <a href="{{ route('companies.workspace.applications.index', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-950 dark:hover:text-white transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Pengajuan
            </a>

            <div class="flex gap-2">
                @if (!$application->is_active)
                    <form method="POST" action="{{ route('companies.workspace.applications.activate', [$company, $application]) }}">
                        @csrf
                        <button type="submit" class="rounded-md bg-slate-900 dark:bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 dark:hover:bg-slate-500">
                            Jadikan Pengajuan Aktif
                        </button>
                    </form>
                @endif

                <a href="{{ route('companies.workspace.applications.edit', [$company, $application]) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600">
                    Edit Pengajuan
                </a>
                <form method="POST" action="{{ route('companies.workspace.applications.destroy', [$company, $application]) }}" onsubmit="return confirm('Hapus data pengajuan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md border border-red-200 dark:border-red-800 bg-white dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-red-700 dark:text-red-400 shadow-sm transition hover:bg-red-50 dark:hover:bg-red-900/30">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Details -->
            <section class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                    <div class="border-b border-slate-200 dark:border-slate-700 p-5 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Nomor Pengajuan: {{ $application->application_number }}</span>
                            <h3 class="mt-1 text-xl font-bold text-slate-950 dark:text-white">
                                SBU {{ $application->scheme?->scheme_name ?: 'Sertifikasi Badan Usaha' }}
                            </h3>
                        </div>
                        @php
                            $badgeClasses = match($application->status) {
                                'draft' => 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-600',
                                'berkas_belum_lengkap' => 'bg-rose-50 dark:bg-red-900/30 text-rose-700 dark:text-red-400 border-rose-200 dark:border-red-800',
                                'berkas_lengkap' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                'proses' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800',
                                'revisi' => 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                'terbit' => 'bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 border-teal-200 dark:border-teal-800',
                                'selesai' => 'bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 border-sky-200 dark:border-sky-800',
                                default => 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'
                            };
                            $statusLabel = match($application->status) {
                                'draft' => 'Draft',
                                'berkas_belum_lengkap' => 'Berkas Belum Lengkap',
                                'berkas_lengkap' => 'Berkas Lengkap',
                                'proses' => 'Proses',
                                'revisi' => 'Revisi',
                                'terbit' => 'Terbit',
                                'selesai' => 'Selesai',
                                default => ucfirst($application->status)
                            };
                        @endphp
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="p-5 space-y-6">
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tipe Pengajuan</span>
                                <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200 uppercase">{{ $application->application_type }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tahun Pengajuan</span>
                                <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">{{ $application->application_year }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tanggal Pengajuan</span>
                                <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">{{ $application->submission_date?->format('d F Y') ?: '-' }}</p>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kualifikasi</span>
                                <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">{{ $application->qualification ?: '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nama LSBU</span>
                                <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">{{ $application->lsbu_name ?: '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Asosiasi</span>
                                <p class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">{{ $application->association_name ?: '-' }}</p>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 border-t border-slate-50 dark:border-slate-700 pt-4">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status Keaktifan</span>
                                <p class="mt-1 text-sm font-bold {{ $application->is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500' }}">
                                    {{ $application->is_active ? 'Pengajuan Aktif (Default Cetak)' : 'Pengajuan Tidak Aktif' }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-700 pt-5">
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Catatan Pengajuan</span>
                            <p class="mt-2 text-sm text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-700/50 rounded-md p-3 whitespace-pre-line border border-slate-100 dark:border-slate-700">
                                {{ $application->notes ?: 'Tidak ada catatan tambahan.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Checklist Kelengkapan Dokumen SBU -->
                <div class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                    <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Checklist Kelengkapan Dokumen SBU</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Unggah dan verifikasi berkas administrasi asli untuk pengajuan SBU ini.</p>
                    </div>

                    <div class="p-5 space-y-4">
                        @php
                            $requirements = [
                                'Akta Pendirian' => ['required' => true, 'desc' => 'Akta pendirian awal perusahaan resmi yang sah.'],
                                'Akta Perubahan' => ['required' => false, 'desc' => 'Akta perubahan direksi atau modal terbaru (jika ada).'],
                                'NIB (Nomor Induk Berusaha)' => ['required' => true, 'desc' => 'NIB aktif dari sistem OSS RBA.'],
                                'NPWP Perusahaan' => ['required' => true, 'desc' => 'Kartu NPWP atas nama badan usaha.'],
                                'Neraca Keuangan' => ['required' => true, 'desc' => 'Laporan posisi keuangan / neraca tahun terakhir.'],
                                'Surat Pernyataan PJBU / PJT' => ['required' => true, 'desc' => 'Surat penunjukan dan keabsahan penanggung jawab.'],
                            ];
                            $uploadedDocs = $application->documents->keyBy('requirement_name');
                        @endphp

                        @foreach ($requirements as $name => $meta)
                            @php
                                $doc = $uploadedDocs->get($name);
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-lg border border-slate-100 dark:border-slate-700 p-4 hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition">
                                <div class="space-y-1 max-w-md">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $name }}</h4>
                                        @if ($meta['required'])
                                            <span class="inline-block rounded bg-rose-50 dark:bg-red-900/30 px-1.5 py-0.5 text-[10px] font-bold text-rose-700 dark:text-red-400 uppercase">Wajib</span>
                                        @else
                                            <span class="inline-block rounded bg-slate-50 dark:bg-slate-700 px-1.5 py-0.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Opsional</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ $meta['desc'] }}</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    @if ($doc)
                                        <div class="flex flex-col items-end gap-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                                <span class="text-xs font-semibold text-emerald-800 dark:text-emerald-400">Sudah Dilampirkan</span>
                                            </div>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 max-w-[180px] truncate" title="{{ $doc->file_name }}">{{ $doc->file_name }}</p>
                                            <div class="flex gap-2 mt-1">
                                                <a href="{{ route('companies.workspace.applications.documents.download', [$company, $application, $doc]) }}" class="text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition">
                                                    Unduh
                                                </a>
                                                <span class="text-slate-300 dark:text-slate-600 text-xs">|</span>
                                                <form method="POST" action="{{ route('companies.workspace.applications.documents.destroy', [$company, $application, $doc]) }}" onsubmit="return confirm('Hapus dokumen {{ $name }} ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-end gap-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-flex h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Belum Dilampirkan</span>
                                            </div>
                                            <form method="POST" action="{{ route('companies.workspace.applications.documents.upload', [$company, $application]) }}" enctype="multipart/form-data" class="flex items-center gap-1 mt-1">
                                                @csrf
                                                <input type="hidden" name="requirement_name" value="{{ $name }}">
                                                <label class="cursor-pointer rounded-md bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600 focus-within:ring-2 focus-within:ring-emerald-500">
                                                    <span>Unggah</span>
                                                    <input type="file" name="file" accept=".pdf,.png,.jpg,.jpeg" required class="sr-only" onchange="this.form.submit()">
                                                </label>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Domain SBU Sidebar Relation Details -->
            <aside class="space-y-6">
                <!-- SMAP Actions -->
                <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm space-y-4">
                    <h4 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-700 pb-3">Dokumen SMAP</h4>

                    @php
                        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
                    @endphp

                    @if (!$pjbu)
                        <div class="rounded-md border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 p-3.5 text-xs text-amber-800 dark:text-amber-400 space-y-2">
                            <p class="font-bold">Peringatan</p>
                            <p class="leading-relaxed">Belum ada data Penanggung Jawab Badan Usaha (PJBU) terdaftar. Anda wajib menambahkan data PJBU terlebih dahulu sebelum dapat membuat dokumen SMAP.</p>
                            <a href="{{ route('companies.workspace.directors_pjbus', $company) }}" class="inline-block font-bold text-amber-900 dark:text-amber-300 underline hover:text-amber-950 dark:hover:text-amber-200">
                                Kelola PJBU & Direktur &rarr;
                            </a>
                        </div>
                    @else
                        <div class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 rounded-md p-3">
                            <p class="font-bold text-slate-700 dark:text-slate-300">PJBU Utama Penandatangan:</p>
                            <p class="mt-1 font-semibold text-emerald-800 dark:text-emerald-400">{{ $pjbu->name }} ({{ $pjbu->position ?: 'PJBU' }})</p>
                        </div>

                        <div class="grid gap-2">
                            <a href="{{ route('companies.workspace.applications.smap.preview', [$company, $application]) }}" target="_blank" class="inline-flex w-full items-center justify-center rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600">
                                Preview SMAP
                            </a>
                            <a href="{{ route('companies.workspace.applications.smap.download', [$company, $application]) }}" class="inline-flex w-full items-center justify-center rounded-md bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700">
                                Download SMAP (PDF)
                            </a>
                        </div>
                    @endif
                </section>

                <!-- SPTJM & PDF Actions -->
                <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm space-y-4">
                    <h4 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-700 pb-3">Dokumen SPTJM Resmi</h4>
                    
                    @php
                        $pjbu = $company->pjbus()->where('is_main', true)->first() ?: $company->pjbus()->first();
                    @endphp

                    @if (!$pjbu)
                        <div class="rounded-md border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 p-3.5 text-xs text-amber-800 dark:text-amber-400 space-y-2">
                            <p class="font-bold">Warning / Peringatan</p>
                            <p class="leading-relaxed">Belum ada data Penanggung Jawab Badan Usaha (PJBU) terdaftar. Anda wajib menambahkan data PJBU terlebih dahulu sebelum dapat membuat dokumen SPTJM.</p>
                            <a href="{{ route('companies.workspace.directors_pjbus', $company) }}" class="inline-block font-bold text-amber-900 dark:text-amber-300 underline hover:text-amber-950 dark:hover:text-amber-200">
                                Kelola PJBU & Direktur &rarr;
                            </a>
                        </div>
                    @else
                        <div class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 rounded-md p-3">
                            <p class="font-bold text-slate-700 dark:text-slate-300">PJBU Utama Penandatangan:</p>
                            <p class="mt-1 font-semibold text-emerald-800 dark:text-emerald-400">{{ $pjbu->name }} ({{ $pjbu->position ?: 'PJBU' }})</p>
                        </div>

                        <div class="grid gap-2">
                            <a href="{{ route('companies.workspace.applications.sptjm.preview', [$company, $application]) }}" target="_blank" class="inline-flex w-full items-center justify-center rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600">
                                Preview SPTJM
                            </a>
                            <a href="{{ route('companies.workspace.applications.sptjm.download', [$company, $application]) }}" class="inline-flex w-full items-center justify-center rounded-md bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700">
                                Download SPTJM (PDF)
                            </a>
                        </div>
                    @endif
                </section>

                <!-- Lampiran Tenaga Ahli Actions -->
                <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm space-y-4">
                    <h4 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-700 pb-3">Lampiran Tenaga Ahli</h4>

                    @php
                        $pjtbuCount = $application->experts()->where('expert_type', 'pjtbu')->count();
                        $pjskbuCount = $application->experts()->where('expert_type', 'pjskbu')->count();
                        $expertsComplete = $pjtbuCount > 0 && $pjskbuCount > 0;
                    @endphp

                    @if (!$expertsComplete)
                        <div class="rounded-md border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 p-3.5 text-xs text-amber-800 dark:text-amber-400 space-y-2">
                            <p class="font-bold">Warning / Peringatan</p>
                            <p class="leading-relaxed">Data PJTBU atau PJSKBU belum lengkap (PJTBU: {{ $pjtbuCount }}, PJSKBU: {{ $pjskbuCount }}). Anda direkomendasikan untuk melengkapi data Tenaga Ahli terlebih dahulu sebelum generate lampiran resmi.</p>
                            <a href="{{ route('companies.workspace.experts.index', $company) }}" class="inline-block font-bold text-amber-900 dark:text-amber-300 underline hover:text-amber-950 dark:hover:text-amber-200">
                                Kelola Tenaga Ahli &rarr;
                            </a>
                        </div>
                    @else
                        <div class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 rounded-md p-3">
                            <p class="font-bold text-slate-700 dark:text-slate-300">Status Persyaratan TA:</p>
                            <p class="mt-1 font-semibold text-emerald-800 dark:text-emerald-400">Lengkap (PJTBU & PJSKBU tersedia)</p>
                        </div>
                    @endif

                    <div class="grid gap-2">
                        <a href="{{ route('companies.workspace.applications.experts_annex.preview', [$company, $application]) }}" target="_blank" class="inline-flex w-full items-center justify-center rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600">
                            Preview Lampiran TA
                        </a>
                        <a href="{{ route('companies.workspace.applications.experts_annex.download', [$company, $application]) }}" class="inline-flex w-full items-center justify-center rounded-md bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700">
                            Download Lampiran (PDF)
                        </a>
                    </div>
                </section>

                <!-- Laporan Neraca Actions -->
                <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm space-y-4">
                    <h4 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-700 pb-3">Laporan Neraca Resmi</h4>

                    @php
                        $hasBalance = $company->balanceEntries()->where('sbu_application_id', $application->id)->exists();
                    @endphp

                    @if (!$hasBalance)
                        <div class="rounded-md border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 p-3.5 text-xs text-amber-800 dark:text-amber-400 space-y-2">
                            <p class="font-bold">Warning / Peringatan</p>
                            <p class="leading-relaxed">Laporan neraca keuangan belum diinput untuk pengajuan SBU ini. Anda wajib menginput neraca terlebih dahulu sebelum dapat mencetak dokumen neraca resmi.</p>
                            <a href="{{ route('companies.workspace.balance.index', $company) }}" class="inline-block font-bold text-amber-900 dark:text-amber-300 underline hover:text-amber-950 dark:hover:text-amber-200">
                                Input Neraca Keuangan &rarr;
                            </a>
                        </div>
                    @else
                        <div class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 rounded-md p-3">
                            <p class="font-bold text-slate-700 dark:text-slate-300">Status Laporan Neraca:</p>
                            <p class="mt-1 font-semibold text-emerald-800 dark:text-emerald-400">Tersedia (Siap Cetak Landscape)</p>
                        </div>
                    @endif

                    <div class="grid gap-2">
                        <a href="{{ route('companies.workspace.applications.balance.preview', [$company, $application]) }}" target="_blank" class="inline-flex w-full items-center justify-center rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600 @if(!$hasBalance) opacity-50 pointer-events-none @endif">
                            Preview Neraca
                        </a>
                        <a href="{{ route('companies.workspace.applications.balance.download', [$company, $application]) }}" class="inline-flex w-full items-center justify-center rounded-md bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700 @if(!$hasBalance) opacity-50 pointer-events-none @endif">
                            Download Neraca (PDF)
                        </a>
                    </div>
                </section>

                <!-- Surat Alat BG Actions -->
                <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm space-y-4">
                    <h4 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-700 pb-3">Surat Pernyataan Alat BG</h4>

                    @php
                        $bgEquipmentCount = $company->equipment()->where('sbu_application_id', $application->id)->whereRaw('LOWER(category) = ?', ['bg'])->count();
                        $hasBgEquip = $bgEquipmentCount > 0;
                    @endphp

                    @if (!$hasBgEquip)
                        <div class="rounded-md border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 p-3.5 text-xs text-amber-800 dark:text-amber-400 space-y-2">
                            <p class="font-bold">Warning / Peringatan</p>
                            <p class="leading-relaxed">Belum ada peralatan konstruksi kategori BG (Bangunan Gedung) terdaftar untuk pengajuan ini. Anda wajib menginput peralatan kategori BG terlebih dahulu.</p>
                            <a href="{{ route('companies.workspace.equipment.index', $company) }}" class="inline-block font-bold text-amber-900 dark:text-amber-300 underline hover:text-amber-950 dark:hover:text-amber-200">
                                Input Peralatan BG &rarr;
                            </a>
                        </div>
                    @else
                        <div class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700 rounded-md p-3">
                            <p class="font-bold text-slate-700 dark:text-slate-300">Status Peralatan BG:</p>
                            <p class="mt-1 font-semibold text-emerald-800 dark:text-emerald-400">Tersedia ({{ $bgEquipmentCount }} unit alat terdaftar)</p>
                        </div>
                    @endif

                    <div class="grid gap-2">
                        <a href="{{ route('companies.workspace.applications.equip_bg.preview', [$company, $application]) }}" target="_blank" class="inline-flex w-full items-center justify-center rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600 @if(!$hasBgEquip) opacity-50 pointer-events-none @endif">
                            Preview Surat Alat BG
                        </a>
                        <a href="{{ route('companies.workspace.applications.equip_bg.download', [$company, $application]) }}" class="inline-flex w-full items-center justify-center rounded-md bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700 @if(!$hasBgEquip) opacity-50 pointer-events-none @endif">
                            Download Surat Alat (PDF)
                        </a>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm space-y-5">
                    <h4 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-700 pb-3">Informasi Sektor & Skema SBU</h4>

                    <!-- KBLI -->
                    <div class="space-y-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-sky-700 dark:text-sky-400 bg-sky-50 dark:bg-sky-900/30 px-2 py-0.5 rounded">KBLI</span>
                        @if ($application->kbli)
                            <div class="pt-1">
                                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $application->kbli->code }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-0.5">{{ $application->kbli->name }}</p>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 dark:text-slate-500 italic">Belum dihubungkan ke KBLI</p>
                        @endif
                    </div>

                    <!-- Klasifikasi -->
                    <div class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-700">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded">Klasifikasi SBU</span>
                        @if ($application->classification)
                            <div class="pt-1">
                                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $application->classification->code }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-0.5">{{ $application->classification->name }}</p>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 dark:text-slate-500 italic">Belum dihubungkan ke Klasifikasi</p>
                        @endif
                    </div>

                    <!-- Subklasifikasi -->
                    <div class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-700">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-0.5 rounded">Subklasifikasi SBU</span>
                        @if ($application->subclassification)
                            <div class="pt-1">
                                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $application->subclassification->code }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-0.5">{{ $application->subclassification->name }}</p>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 dark:text-slate-500 italic">Belum dihubungkan ke Subklasifikasi</p>
                        @endif
                    </div>

                    <!-- Skema -->
                    <div class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-700">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded">Skema SBU</span>
                        @if ($application->scheme)
                            <div class="pt-1">
                                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $application->scheme->scheme_code }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-0.5">{{ $application->scheme->scheme_name }}</p>
                                <div class="mt-2 flex items-center gap-1">
                                    <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500">Kualifikasi:</span>
                                    <span class="text-xs font-bold text-emerald-800 dark:text-emerald-400">{{ $application->scheme->qualification }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 dark:text-slate-500 italic">Belum dihubungkan ke Skema SBU</p>
                        @endif
                    </div>
                </section>
            </aside>
        </div>
    </div>
</x-layouts.admin>
