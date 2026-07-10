<x-layouts.admin title="Arsip Dokumen" :company="$company">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-sm font-medium text-emerald-800 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Arsip Hasil Cetak Dokumen</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kumpulan riwayat dokumen sertifikat dan SPTJM yang telah digenerate untuk perusahaan ini.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Jenis Dokumen</th>
                            <th class="px-5 py-3">Nomor Pengajuan</th>
                            <th class="px-5 py-3">Nama Berkas</th>
                            <th class="px-5 py-3">Tanggal Generate</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @forelse ($archives as $item)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-950 dark:text-white">{{ $item->document_type }}</p>
                                </td>
                                <td class="px-5 py-4 font-mono text-xs text-slate-700 dark:text-slate-300 font-semibold">
                                    @if ($item->application)
                                        <a href="{{ route('companies.workspace.applications.show', [$company, $item->application]) }}" class="hover:underline">
                                            {{ $item->application->application_number }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500 dark:text-slate-400 font-mono truncate max-w-[200px]" title="{{ $item->original_filename }}">
                                    {{ $item->original_filename }}
                                </td>
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400 text-xs">
                                    {{ $item->generated_at?->format('d/m/Y H:i') ?: '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('archives.view', $item) }}" target="_blank" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-2.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-50 dark:hover:bg-slate-600">
                                            View
                                        </a>
                                        <a href="{{ route('archives.download', $item) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-2.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-50 dark:hover:bg-slate-600">
                                            Download
                                        </a>
                                        <a href="{{ route('archives.print', $item) }}" target="_blank" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-2.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-50 dark:hover:bg-slate-600">
                                            Print
                                        </a>
                                        <form method="POST" action="{{ route('companies.workspace.archives.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus berkas arsip ini dari database?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 dark:border-red-800 bg-white dark:bg-slate-700 px-2.5 py-1.5 text-xs font-semibold text-red-700 dark:text-red-400 transition hover:bg-red-50 dark:hover:bg-red-900/30">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-slate-500 dark:text-slate-400 font-medium">
                                    Belum ada arsip dokumen terbuat untuk perusahaan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($archives && $archives->hasPages())
                <div class="border-t border-slate-200 dark:border-slate-700 p-5">
                    {{ $archives->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
