<x-layouts.admin title="Template Dokumen">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between dark:border-slate-700">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Master Template Dokumen</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Referensi template layout kop, tanda tangan, stempel, dan isi HTML cetak surat/sertifikat.</p>
                </div>

                <a href="{{ route('master.document-templates.create') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                    Tambah Template
                </a>
            </div>

            <div class="border-b border-slate-200 p-5 dark:border-slate-700">
                <form method="GET" action="{{ route('master.document-templates.index') }}" class="flex gap-3">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari kode atau nama template"
                        class="min-h-10 flex-1 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                    >

                    <button type="submit" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-700/50 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Nama Template</th>
                            <th class="px-5 py-3">Aset Gambar</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                        @forelse ($templates as $item)
                            <tr>
                                <td class="px-5 py-4 font-semibold text-slate-950 dark:text-white">{{ $item->code ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $item->name }}</p>
                                    <p class="mt-1 max-w-md text-xs text-slate-500 truncate dark:text-slate-400">{{ $item->description ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @if ($item->logo_path)
                                            <span class="inline-block rounded bg-sky-50 border border-sky-200 px-2 py-0.5 text-[10px] font-bold text-sky-700 dark:bg-sky-900/30 dark:border-sky-800 dark:text-sky-400">Logo</span>
                                        @endif
                                        @if ($item->signature_path)
                                            <span class="inline-block rounded bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-bold text-purple-700 dark:bg-purple-900/30 dark:border-purple-800 dark:text-purple-400">TTE/Ttd</span>
                                        @endif
                                        @if ($item->stamp_path)
                                            <span class="inline-block rounded bg-amber-50 border border-amber-200 px-2 py-0.5 text-[10px] font-bold text-amber-700 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-400">Cap/Stempel</span>
                                        @endif
                                        @if (!$item->logo_path && !$item->signature_path && !$item->stamp_path)
                                            <span class="text-xs text-slate-400 italic dark:text-slate-500">Tidak ada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('master.document-templates.edit', $item) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                                            Edit / Modifikasi
                                        </a>
                                        <form method="POST" action="{{ route('master.document-templates.destroy', $item) }}" onsubmit="return confirm('Hapus template ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/30">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">Belum ada template dokumen yang didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($templates->hasPages())
                <div class="border-t border-slate-200 p-5 dark:border-slate-700">
                    {{ $templates->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
