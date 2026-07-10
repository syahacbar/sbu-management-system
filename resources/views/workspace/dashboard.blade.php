<x-layouts.admin :title="'Ringkasan Workspace'" :company="$company">
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-sm font-medium text-emerald-800 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md border border-rose-200 dark:border-red-800 bg-rose-50 dark:bg-red-900/30 px-4 py-3 text-sm font-medium text-rose-800 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Status Checklist</p>
                <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">{{ $completedChecklistCount }}/{{ $totalChecklistCount }}</p>
                <p class="mt-2 text-xs {{ $allComplete ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-red-400' }}">
                    {{ $allComplete ? 'Semua persyaratan terpenuhi' : 'Masih ada persyaratan belum lengkap' }}
                </p>
            </article>

            <article class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pengajuan Aktif</p>
                <p class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">{{ $activeApplication?->application_number ?: '-' }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $activeApplication ? strtoupper(str_replace('_', ' ', $activeApplication->status)) : 'Belum ada pengajuan aktif' }}</p>
            </article>

            <article class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Dokumen Upload</p>
                <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">{{ number_format($uploadedDocumentsCount, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Dokumen pendukung pengajuan aktif</p>
            </article>

            <article class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Dokumen Generated</p>
                <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">{{ number_format($generatedDocumentsCount, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">PDF tersimpan di arsip</p>
            </article>
        </section>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm lg:col-span-2">
                <div class="border-b border-slate-100 dark:border-slate-700 pb-4">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Ringkasan Profil Perusahaan</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Identitas utama badan usaha dan penanggung jawab.</p>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Nama Badan Usaha</p>
                        <p class="mt-1 font-bold text-slate-900 dark:text-slate-100">{{ $company->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">NIB</p>
                        <p class="mt-1 font-mono font-bold text-slate-900 dark:text-slate-100">{{ $company->nib ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">NPWP</p>
                        <p class="mt-1 font-mono font-bold text-slate-900 dark:text-slate-100">{{ $company->npwp ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Kualifikasi</p>
                        <p class="mt-1 font-bold text-slate-900 dark:text-slate-100">{{ $company->qualification ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Direktur Utama</p>
                        <p class="mt-1 font-bold text-slate-900 dark:text-slate-100">{{ $mainDirector?->name ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">PJBU Utama</p>
                        <p class="mt-1 font-bold text-slate-900 dark:text-slate-100">{{ $mainPjbu?->name ?: '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Alamat</p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-700 dark:text-slate-300">{{ $company->address ?: 'Alamat belum diisi lengkap.' }}</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Quick Action</h3>
                    <div class="mt-4 grid gap-2">
                        <a href="{{ route('companies.workspace.profile.edit', $company) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600">
                            Edit Profil
                        </a>
                        <a href="{{ route('companies.workspace.applications.create', $company) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600">
                            Buat Pengajuan
                        </a>
                        <a href="{{ route('companies.workspace.balance.create', $company) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600">
                            Input Neraca
                        </a>
                        <a href="{{ route('companies.workspace.generate.index', $company) }}" class="rounded-md bg-emerald-700 dark:bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700">
                            Generate Dokumen
                        </a>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Pengajuan Aktif</h3>
                    @if ($activeApplication)
                        <div class="mt-4 space-y-3 text-sm">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Nomor</p>
                                <p class="mt-1 font-mono font-bold text-slate-900 dark:text-slate-100">{{ $activeApplication->application_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Skema</p>
                                <p class="mt-1 font-bold text-slate-900 dark:text-slate-100">{{ $activeApplication->scheme?->scheme_code ?: '-' }}</p>
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $activeApplication->scheme?->scheme_name ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Status</p>
                                <p class="mt-1 font-bold text-slate-900 dark:text-slate-100">{{ strtoupper(str_replace('_', ' ', $activeApplication->status)) }}</p>
                            </div>
                            <a href="{{ route('companies.workspace.applications.show', [$company, $activeApplication]) }}" class="inline-flex w-full items-center justify-center rounded-md bg-slate-900 dark:bg-slate-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800 dark:hover:bg-slate-500">
                                Detail Pengajuan
                            </a>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Belum ada pengajuan aktif untuk perusahaan ini.</p>
                    @endif
                </section>
            </aside>
        </div>

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 dark:border-slate-700 pb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Status Checklist</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelengkapan data yang dipakai untuk kesiapan pengajuan dan generate dokumen.</p>
                </div>

                @if ($activeApplication)
                    <form method="POST" action="{{ route('companies.workspace.applications.update_status', [$company, $activeApplication]) }}">
                        @csrf
                        <button type="submit" class="rounded-md bg-slate-900 dark:bg-slate-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 dark:hover:bg-slate-500">
                            Update Status
                        </button>
                    </form>
                @endif
            </div>

            <div class="mt-2 divide-y divide-slate-100 dark:divide-slate-700">
                @foreach ($checklist as $item)
                    <div class="flex items-start gap-3 py-4">
                        <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full {{ $item['is_complete'] ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-red-900/30 text-rose-700 dark:text-red-400' }}">
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
                            <p class="font-bold text-slate-900 dark:text-slate-100">{{ $item['label'] }}</p>
                            <p class="mt-0.5 text-sm {{ $item['is_complete'] ? 'text-slate-500 dark:text-slate-400' : 'text-rose-700 dark:text-red-400' }}">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.admin>
