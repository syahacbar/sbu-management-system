<x-layouts.admin :title="'Ringkasan Workspace'" :company="$company">
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Status Checklist</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ $completedChecklistCount }}/{{ $totalChecklistCount }}</p>
                <p class="mt-2 text-xs {{ $allComplete ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $allComplete ? 'Semua persyaratan terpenuhi' : 'Masih ada persyaratan belum lengkap' }}
                </p>
            </article>

            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Pengajuan Aktif</p>
                <p class="mt-3 text-2xl font-bold text-slate-950">{{ $activeApplication?->application_number ?: '-' }}</p>
                <p class="mt-2 text-xs text-slate-500">{{ $activeApplication ? strtoupper(str_replace('_', ' ', $activeApplication->status)) : 'Belum ada pengajuan aktif' }}</p>
            </article>

            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Dokumen Upload</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ number_format($uploadedDocumentsCount, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-slate-500">Dokumen pendukung pengajuan aktif</p>
            </article>

            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Dokumen Generated</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ number_format($generatedDocumentsCount, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-slate-500">PDF tersimpan di arsip</p>
            </article>
        </section>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-semibold text-slate-950">Ringkasan Profil Perusahaan</h3>
                    <p class="mt-1 text-sm text-slate-500">Identitas utama badan usaha dan penanggung jawab.</p>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama Badan Usaha</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $company->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">NIB</p>
                        <p class="mt-1 font-mono font-bold text-slate-900">{{ $company->nib ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">NPWP</p>
                        <p class="mt-1 font-mono font-bold text-slate-900">{{ $company->npwp ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Kualifikasi</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $company->qualification ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Direktur Utama</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $mainDirector?->name ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">PJBU Utama</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $mainPjbu?->name ?: '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Alamat</p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-700">{{ $company->address ?: 'Alamat belum diisi lengkap.' }}</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-950">Quick Action</h3>
                    <div class="mt-4 grid gap-2">
                        <a href="{{ route('companies.workspace.profile.edit', $company) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Edit Profil
                        </a>
                        <a href="{{ route('companies.workspace.applications.create', $company) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Buat Pengajuan
                        </a>
                        <a href="{{ route('companies.workspace.balance.create', $company) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Input Neraca
                        </a>
                        <a href="{{ route('companies.workspace.generate.index', $company) }}" class="rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800">
                            Generate Dokumen
                        </a>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-950">Pengajuan Aktif</h3>
                    @if ($activeApplication)
                        <div class="mt-4 space-y-3 text-sm">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nomor</p>
                                <p class="mt-1 font-mono font-bold text-slate-900">{{ $activeApplication->application_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Skema</p>
                                <p class="mt-1 font-bold text-slate-900">{{ $activeApplication->scheme?->scheme_code ?: '-' }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $activeApplication->scheme?->scheme_name ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</p>
                                <p class="mt-1 font-bold text-slate-900">{{ strtoupper(str_replace('_', ' ', $activeApplication->status)) }}</p>
                            </div>
                            <a href="{{ route('companies.workspace.applications.show', [$company, $activeApplication]) }}" class="inline-flex w-full items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800">
                                Detail Pengajuan
                            </a>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-500">Belum ada pengajuan aktif untuk perusahaan ini.</p>
                    @endif
                </section>
            </aside>
        </div>

        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Status Checklist</h3>
                    <p class="mt-1 text-sm text-slate-500">Kelengkapan data yang dipakai untuk kesiapan pengajuan dan generate dokumen.</p>
                </div>

                @if ($activeApplication)
                    <form method="POST" action="{{ route('companies.workspace.applications.update_status', [$company, $activeApplication]) }}">
                        @csrf
                        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800">
                            Update Status
                        </button>
                    </form>
                @endif
            </div>

            <div class="mt-2 divide-y divide-slate-100">
                @foreach ($checklist as $item)
                    <div class="flex items-start gap-3 py-4">
                        <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full {{ $item['is_complete'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            @if ($item['is_complete'])
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $item['label'] }}</p>
                            <p class="mt-0.5 text-sm {{ $item['is_complete'] ? 'text-slate-500' : 'text-rose-700' }}">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.admin>
