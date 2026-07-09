<x-layouts.admin title="Arsip Dokumen Resmi" :company="$company">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Arsip Cetak Dokumen SBU</h3>
                    <p class="mt-1 text-sm text-slate-500">Daftar berkas cetakan resmi sertifikat SBU perusahaan yang telah di-generate dan diarsipkan.</p>
                </div>
            </div>

            <div class="border-b border-slate-200 p-5">
                <form method="GET" action="{{ route('companies.workspace.archives.index', $company) }}" class="flex flex-col gap-3 sm:flex-row">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama arsip atau nomor kode"
                        class="min-h-10 flex-1 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                    <button type="submit" class="rounded-md border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Kode / Nomor Arsip</th>
                            <th class="px-5 py-3">Nama Arsip</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal Pengarsipan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($items as $item)
                            <tr>
                                <td class="px-5 py-4 font-semibold text-slate-950">{{ $item->code ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-900">{{ $item->name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">ID Record: #{{ $item->id }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        {{ $item->status ?: 'Arsip Resmi' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->record_date?->format('d F Y H:i') ?: '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <!-- View Document in New Tab -->
                                        <a
                                            href="{{ route('companies.workspace.archives.show', [$company, $item]) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100"
                                        >
                                            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Lihat Dokumen
                                        </a>

                                        <!-- Delete Archive -->
                                        <form method="POST" action="{{ route('companies.workspace.archives.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus dokumen arsip ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">Belum ada cetakan SBU yang diarsipkan untuk {{ $company->name }}.</td>
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
