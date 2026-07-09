<x-layouts.admin title="Detail Pengajuan SBU" :company="$company">
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <a href="{{ route('companies.workspace.applications.index', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-950 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Pengajuan
            </a>

            <div class="flex gap-2">
                <a href="{{ route('companies.workspace.applications.edit', [$company, $application]) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Edit Pengajuan
                </a>
                <form method="POST" action="{{ route('companies.workspace.applications.destroy', [$company, $application]) }}" onsubmit="return confirm('Hapus data pengajuan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-md border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-700 shadow-sm transition hover:bg-red-50">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Details -->
            <section class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 p-5 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kode Pengajuan: {{ $application->code ?: '-' }}</span>
                            <h3 class="mt-1 text-xl font-bold text-slate-950">{{ $application->name }}</h3>
                        </div>
                        @php
                            $badgeClasses = match($application->status) {
                                'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                'review' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-100 text-slate-700'
                            };
                            $statusLabel = match($application->status) {
                                'draft' => 'Draft / Konsep',
                                'review' => 'Dalam Review',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                default => ucfirst($application->status)
                            };
                        @endphp
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="p-5 space-y-6">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</span>
                                <p class="mt-1 text-sm font-bold text-slate-800">{{ $application->record_date?->format('d F Y') ?: '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nominal Biaya</span>
                                <p class="mt-1 text-sm font-bold text-slate-800">
                                    {{ $application->amount !== null ? 'Rp '.number_format((float) $application->amount, 0, ',', '.') : '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Urutan Tampilan</span>
                                <p class="mt-1 text-sm font-bold text-slate-800">{{ $application->sort_order }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Rekam</span>
                                <p class="mt-1 text-sm font-bold {{ $application->is_active ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $application->is_active ? 'Aktif' : 'Nonaktif' }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-5">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Keterangan / Catatan</span>
                            <p class="mt-2 text-sm text-slate-700 bg-slate-50 rounded-md p-3 whitespace-pre-line border border-slate-100">
                                {{ $application->description ?: 'Tidak ada keterangan tambahan.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Checklist Kelengkapan Dokumen SBU -->
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 p-5">
                        <h3 class="text-lg font-semibold text-slate-950">Checklist Kelengkapan Dokumen SBU</h3>
                        <p class="mt-1 text-sm text-slate-500">Unggah dan verifikasi berkas administrasi asli untuk pengajuan SBU ini.</p>
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
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-lg border border-slate-100 p-4 hover:bg-slate-50/50 transition">
                                <div class="space-y-1 max-w-md">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-slate-800">{{ $name }}</h4>
                                        @if ($meta['required'])
                                            <span class="inline-block rounded bg-rose-50 px-1.5 py-0.5 text-[10px] font-bold text-rose-700 uppercase">Wajib</span>
                                        @else
                                            <span class="inline-block rounded bg-slate-50 px-1.5 py-0.5 text-[10px] font-bold text-slate-500 uppercase">Opsional</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed">{{ $meta['desc'] }}</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    @if ($doc)
                                        <div class="flex flex-col items-end gap-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                                <span class="text-xs font-semibold text-emerald-800">Sudah Dilampirkan</span>
                                            </div>
                                            <p class="text-[10px] text-slate-400 max-w-[180px] truncate" title="{{ $doc->file_name }}">{{ $doc->file_name }}</p>
                                            <div class="flex gap-2 mt-1">
                                                <a href="{{ route('companies.workspace.applications.documents.download', [$company, $application, $doc]) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 transition">
                                                    Unduh
                                                </a>
                                                <span class="text-slate-300 text-xs">|</span>
                                                <form method="POST" action="{{ route('companies.workspace.applications.documents.destroy', [$company, $application, $doc]) }}" onsubmit="return confirm('Hapus dokumen {{ $name }} ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-700 transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-end gap-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-flex h-2 w-2 rounded-full bg-slate-300"></span>
                                                <span class="text-xs font-semibold text-slate-500">Belum Dilampirkan</span>
                                            </div>
                                            <form method="POST" action="{{ route('companies.workspace.applications.documents.upload', [$company, $application]) }}" enctype="multipart/form-data" class="flex items-center gap-1 mt-1">
                                                @csrf
                                                <input type="hidden" name="requirement_name" value="{{ $name }}">
                                                <label class="cursor-pointer rounded-md bg-white border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-within:ring-2 focus-within:ring-emerald-500">
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
                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm space-y-5">
                    <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-3">Informasi Sektor & Skema SBU</h4>

                    <!-- KBLI -->
                    <div class="space-y-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-sky-700 bg-sky-50 px-2 py-0.5 rounded">KBLI</span>
                        @if ($application->kbli)
                            <div class="pt-1">
                                <p class="text-sm font-bold text-slate-900">{{ $application->kbli->code }}</p>
                                <p class="text-xs text-slate-500 leading-relaxed mt-0.5">{{ $application->kbli->name }}</p>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">Belum dihubungkan ke KBLI</p>
                        @endif
                    </div>

                    <!-- Klasifikasi -->
                    <div class="space-y-1.5 pt-2 border-t border-slate-100">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">Klasifikasi SBU</span>
                        @if ($application->classification)
                            <div class="pt-1">
                                <p class="text-sm font-bold text-slate-900">{{ $application->classification->code }}</p>
                                <p class="text-xs text-slate-500 leading-relaxed mt-0.5">{{ $application->classification->name }}</p>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">Belum dihubungkan ke Klasifikasi</p>
                        @endif
                    </div>

                    <!-- Subklasifikasi -->
                    <div class="space-y-1.5 pt-2 border-t border-slate-100">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700 bg-purple-50 px-2 py-0.5 rounded">Subklasifikasi SBU</span>
                        @if ($application->subclassification)
                            <div class="pt-1">
                                <p class="text-sm font-bold text-slate-900">{{ $application->subclassification->code }}</p>
                                <p class="text-xs text-slate-500 leading-relaxed mt-0.5">{{ $application->subclassification->name }}</p>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">Belum dihubungkan ke Subklasifikasi</p>
                        @endif
                    </div>

                    <!-- Skema -->
                    <div class="space-y-1.5 pt-2 border-t border-slate-100">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Skema SBU</span>
                        @if ($application->scheme)
                            <div class="pt-1">
                                <p class="text-sm font-bold text-slate-900">{{ $application->scheme->scheme_code }}</p>
                                <p class="text-xs text-slate-500 leading-relaxed mt-0.5">{{ $application->scheme->scheme_name }}</p>
                                <div class="mt-2 flex items-center gap-1">
                                    <span class="text-[10px] font-semibold text-slate-400">Kualifikasi:</span>
                                    <span class="text-xs font-bold text-emerald-800">{{ $application->scheme->qualification }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">Belum dihubungkan ke Skema SBU</p>
                        @endif
                    </div>
                </section>
            </aside>
        </div>
    </div>
</x-layouts.admin>
