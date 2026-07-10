<x-layouts.admin title="Hasil Generate Dokumen" :company="$company">
    <div class="max-w-5xl space-y-5">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('companies.workspace.generate.index', ['company' => $company, 'application_id' => $application->id]) }}" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-950 dark:hover:text-white">
                &larr; Kembali ke Generate
            </a>
            <a href="{{ route('companies.workspace.archives.index', $company) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600">
                Lihat Arsip
            </a>
        </div>

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
            <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">
                    @if ($action === 'preview')
                        Preview Dokumen
                    @else
                        Dokumen Berhasil Digenerate
                    @endif
                </h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pengajuan: {{ $application->application_number }}</p>
            </div>

            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Dokumen</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Catatan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @forelse ($documentStatuses as $key => $status)
                            @php
                                $archive = $generatedArchives[$key] ?? null;
                            @endphp
                            <tr>
                                <td class="px-5 py-4 font-bold text-slate-950 dark:text-white">{{ $status['label'] }}</td>
                                <td class="px-5 py-4">
                                    @if ($status['is_valid'])
                                        @if ($action === 'preview')
                                            <span class="inline-flex rounded-full border border-sky-200 dark:border-sky-800 bg-sky-50 dark:bg-sky-900/30 px-2.5 py-1 text-xs font-bold text-sky-700 dark:text-sky-400">Siap Preview</span>
                                        @else
                                            <span class="inline-flex rounded-full border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-2.5 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-400">Generated</span>
                                        @endif
                                    @else
                                        <span class="inline-flex rounded-full border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 px-2.5 py-1 text-xs font-bold text-amber-700 dark:text-amber-400">Dilewati</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600 dark:text-slate-400">
                                    @if ($status['is_valid'])
                                        @if ($archive)
                                            Tersimpan ke arsip sebagai {{ $archive->original_filename }}.
                                        @else
                                            Siap dibuka untuk pratinjau.
                                        @endif
                                    @else
                                        <div class="space-y-1 text-amber-800 dark:text-amber-400">
                                            @foreach ($status['warnings'] as $warning)
                                                <p>{{ $warning }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        @if ($status['is_valid'] && $action === 'preview')
                                            <a href="{{ route('companies.workspace.generate.documents.preview', [$company, $application, $key]) }}" target="_blank" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-50 dark:hover:bg-slate-600">
                                                Preview
                                            </a>
                                        @endif

                                        @if ($archive)
                                            <a href="{{ route('archives.download', $archive) }}" class="rounded-md bg-rose-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-rose-700">
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center font-medium text-slate-500 dark:text-slate-400">
                                    Belum ada dokumen yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.admin>
