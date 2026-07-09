<x-layouts.admin title="Hasil Generate Dokumen" :company="$company">
    <div class="max-w-5xl space-y-5">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('companies.workspace.generate.index', ['company' => $company, 'application_id' => $application->id]) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-950">
                &larr; Kembali ke Generate
            </a>
            <a href="{{ route('companies.workspace.archives.index', $company) }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Lihat Arsip
            </a>
        </div>

        <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5">
                <h3 class="text-lg font-semibold text-slate-950">
                    @if ($action === 'preview')
                        Preview Dokumen
                    @else
                        Dokumen Berhasil Digenerate
                    @endif
                </h3>
                <p class="mt-1 text-sm text-slate-500">Pengajuan: {{ $application->application_number }}</p>
            </div>

            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Dokumen</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Catatan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($documentStatuses as $key => $status)
                            @php
                                $archive = $generatedArchives[$key] ?? null;
                            @endphp
                            <tr>
                                <td class="px-5 py-4 font-bold text-slate-950">{{ $status['label'] }}</td>
                                <td class="px-5 py-4">
                                    @if ($status['is_valid'])
                                        @if ($action === 'preview')
                                            <span class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-xs font-bold text-sky-700">Siap Preview</span>
                                        @else
                                            <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Generated</span>
                                        @endif
                                    @else
                                        <span class="inline-flex rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">Dilewati</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    @if ($status['is_valid'])
                                        @if ($archive)
                                            Tersimpan ke arsip sebagai {{ $archive->original_filename }}.
                                        @else
                                            Siap dibuka untuk pratinjau.
                                        @endif
                                    @else
                                        <div class="space-y-1 text-amber-800">
                                            @foreach ($status['warnings'] as $warning)
                                                <p>{{ $warning }}</p>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        @if ($status['is_valid'] && $action === 'preview')
                                            <a href="{{ route('companies.workspace.generate.documents.preview', [$company, $application, $key]) }}" target="_blank" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
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
                                <td colspan="4" class="px-5 py-12 text-center font-medium text-slate-500">
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
