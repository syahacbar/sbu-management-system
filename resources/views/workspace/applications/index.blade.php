<x-layouts.admin title="Pengajuan SBU" :company="$company">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between dark:border-slate-700">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Daftar Pengajuan SBU</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kumpulan pengajuan Sertifikasi Badan Usaha (SBU) untuk {{ $company->name }}.</p>
                </div>

                <a href="{{ route('companies.workspace.applications.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                    Tambah Pengajuan
                </a>
            </div>

            <div class="border-b border-slate-200 p-5 dark:border-slate-700">
                <form method="GET" action="{{ route('companies.workspace.applications.index', $company) }}" class="grid gap-3 lg:grid-cols-[1fr_180px_auto]">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nomor pengajuan, LSBU, atau asosiasi"
                        class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                    >

                    <select name="status" class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        <option value="">Semua Status</option>
                        <option value="draft" @selected($status === 'draft')>Draft</option>
                        <option value="berkas_belum_lengkap" @selected($status === 'berkas_belum_lengkap')>Berkas Belum Lengkap</option>
                        <option value="berkas_lengkap" @selected($status === 'berkas_lengkap')>Berkas Lengkap</option>
                        <option value="proses" @selected($status === 'proses')>Proses</option>
                        <option value="revisi" @selected($status === 'revisi')>Revisi</option>
                        <option value="terbit" @selected($status === 'terbit')>Terbit</option>
                        <option value="selesai" @selected($status === 'selesai')>Selesai</option>
                    </select>

                    <button type="submit" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-700/50 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Nomor / Tipe</th>
                            <th class="px-5 py-3">Domain SBU</th>
                            <th class="px-5 py-3">Kualifikasi</th>
                            <th class="px-5 py-3">LSBU & Asosiasi</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Keaktifan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                        @forelse ($items as $item)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-900 dark:text-slate-100 font-mono">{{ $item->application_number }}</p>
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-bold text-slate-700 uppercase dark:bg-slate-700 dark:text-slate-300">
                                            {{ $item->application_type }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Tahun {{ $item->application_year }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 space-y-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-block rounded bg-sky-50 px-1.5 py-0.5 text-[10px] font-bold text-sky-700 uppercase dark:bg-sky-900/30 dark:text-sky-400">KBLI</span>
                                        <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $item->kbli?->code ?: '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-block rounded bg-indigo-50 px-1.5 py-0.5 text-[10px] font-bold text-indigo-700 uppercase dark:bg-indigo-900/30 dark:text-indigo-400">Klasifikasi</span>
                                        <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">
                                            {{ $item->classification?->code ?: '-' }} &rarr; {{ $item->subclassification?->code ?: '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-block rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700 uppercase dark:bg-emerald-900/30 dark:text-emerald-400">Skema</span>
                                        <span class="text-xs text-slate-700 dark:text-slate-300 truncate max-w-[200px]" title="{{ $item->scheme?->scheme_name }}">
                                            {{ $item->scheme?->scheme_code ?: '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-700 dark:text-slate-300 font-medium">
                                    {{ $item->qualification ?: '-' }}
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600 dark:text-slate-400">
                                    <p><span class="font-semibold text-slate-400 dark:text-slate-500">LSBU:</span> {{ $item->lsbu_name ?: '-' }}</p>
                                    <p class="mt-1"><span class="font-semibold text-slate-400 dark:text-slate-500">Asosiasi:</span> {{ $item->association_name ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $badgeClasses = match($item->status) {
                                            'draft' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600',
                                            'berkas_belum_lengkap' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800',
                                            'berkas_lengkap' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                                            'proses' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800',
                                            'revisi' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
                                            'terbit' => 'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-900/30 dark:text-teal-400 dark:border-teal-800',
                                            'selesai' => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-900/30 dark:text-sky-400 dark:border-sky-800',
                                            default => 'bg-slate-100 text-slate-700'
                                        };
                                        $statusLabel = match($item->status) {
                                            'draft' => 'Draft',
                                            'berkas_belum_lengkap' => 'Berkas Belum Lengkap',
                                            'berkas_lengkap' => 'Berkas Lengkap',
                                            'proses' => 'Proses',
                                            'revisi' => 'Revisi',
                                            'terbit' => 'Terbit',
                                            'selesai' => 'Selesai',
                                            default => ucfirst($item->status)
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $badgeClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($item->is_active)
                                        <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Aktif
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('companies.workspace.applications.activate', [$company, $item]) }}">
                                            @csrf
                                            <button type="submit" class="rounded bg-slate-100 border border-slate-300 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                                                Jadikan Pengajuan Aktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('companies.workspace.applications.show', [$company, $item]) }}" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                                            Detail
                                        </a>
                                        <a href="{{ route('companies.workspace.applications.edit', [$company, $item]) }}" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('companies.workspace.applications.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus data pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-50 dark:border-red-800 dark:bg-slate-800 dark:text-red-400 dark:hover:bg-red-900/30">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-500 font-medium dark:text-slate-400">
                                    <div class="max-w-md mx-auto py-5 space-y-4">
                                        <p class="text-slate-500 dark:text-slate-400 text-sm">Belum ada pengajuan SBU untuk {{ $company->name }}.</p>
                                        <a href="{{ route('companies.workspace.applications.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                            Buat Pengajuan Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($items->hasPages())
                <div class="border-t border-slate-200 p-5 dark:border-slate-700">
                    {{ $items->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
