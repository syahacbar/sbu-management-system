<x-layouts.admin title="Pengajuan SBU" :company="$company">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Daftar Pengajuan SBU</h3>
                    <p class="mt-1 text-sm text-slate-500">Data pengajuan SBU untuk {{ $company->name }}.</p>
                </div>

                <a href="{{ route('companies.workspace.applications.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                    Tambah Pengajuan
                </a>
            </div>

            <div class="border-b border-slate-200 p-5">
                <form method="GET" action="{{ route('companies.workspace.applications.index', $company) }}" class="grid gap-3 lg:grid-cols-[1fr_180px_auto]">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari kode, nama, atau keterangan pengajuan"
                        class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >

                    <select name="status" class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Semua Status</option>
                        <option value="draft" @selected($status === 'draft')>Draft</option>
                        <option value="review" @selected($status === 'review')>Review</option>
                        <option value="approved" @selected($status === 'approved')>Disetujui</option>
                        <option value="rejected" @selected($status === 'rejected')>Ditolak</option>
                    </select>

                    <button type="submit" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Urutan</th>
                            <th class="px-5 py-3">Kode / Nomor</th>
                            <th class="px-5 py-3">Nama Pengajuan</th>
                            <th class="px-5 py-3">Domain SBU</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Nominal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($items as $item)
                            <tr>
                                <td class="px-5 py-4 text-slate-600">{{ $item->sort_order }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-900">{{ $item->code ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-950">{{ $item->name }}</p>
                                    <p class="mt-1 max-w-xs truncate text-xs text-slate-500">{{ $item->description ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4 space-y-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-block rounded bg-sky-50 px-1.5 py-0.5 text-[10px] font-bold text-sky-700 uppercase">KBLI</span>
                                        <span class="text-xs text-slate-700 font-medium">{{ $item->kbli?->code ?: '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-block rounded bg-indigo-50 px-1.5 py-0.5 text-[10px] font-bold text-indigo-700 uppercase">Klasifikasi</span>
                                        <span class="text-xs text-slate-700 font-medium">
                                            {{ $item->classification?->code ?: '-' }} &rarr; {{ $item->subclassification?->code ?: '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-block rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700 uppercase">Skema</span>
                                        <span class="text-xs text-slate-700 truncate max-w-[200px]" title="{{ $item->scheme?->scheme_name }}">
                                            {{ $item->scheme?->scheme_code ?: '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $badgeClasses = match($item->status) {
                                            'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                            'review' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-slate-100 text-slate-700'
                                        };
                                        $statusLabel = match($item->status) {
                                            'draft' => 'Draft',
                                            'review' => 'Review',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            default => ucfirst($item->status)
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $badgeClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->record_date?->format('d/m/Y') ?: '-' }}
                                </td>
                                <td class="px-5 py-4 text-slate-700 font-medium">
                                    {{ $item->amount !== null ? 'Rp '.number_format((float) $item->amount, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('companies.workspace.applications.show', [$company, $item]) }}" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                            Detail
                                        </a>
                                        <a href="{{ route('companies.workspace.applications.edit', [$company, $item]) }}" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('companies.workspace.applications.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus data pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-slate-500 font-medium">
                                    Belum ada data pengajuan SBU untuk {{ $company->name }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($items->hasPages())
                <div class="border-t border-slate-200 p-5">
                    {{ $items->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
