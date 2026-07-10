<x-layouts.admin title="Generate Dokumen SBU" :company="$company">
    <div class="max-w-5xl space-y-5">
        @if (session('error'))
            <div class="rounded-md border border-rose-200 dark:border-red-800 bg-rose-50 dark:bg-red-900/30 px-4 py-3 text-sm font-medium text-rose-800 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
            <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Generate Semua Dokumen</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih pengajuan SBU, centang dokumen yang diperlukan, lalu preview atau generate PDF yang valid.</p>
            </div>

            <form method="GET" action="{{ route('companies.workspace.generate.index', $company) }}" class="border-b border-slate-200 dark:border-slate-700 p-5">
                <label for="application_id_selector" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Pengajuan SBU</label>
                <select
                    name="application_id"
                    id="application_id_selector"
                    onchange="this.form.submit()"
                    class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                >
                    @forelse ($applications as $app)
                        @php
                            $statusLabel = match($app->status) {
                                'draft' => 'Draft',
                                'berkas_belum_lengkap' => 'Berkas Belum Lengkap',
                                'berkas_lengkap' => 'Berkas Lengkap',
                                'proses' => 'Proses',
                                'revisi' => 'Revisi',
                                'terbit' => 'Terbit',
                                'selesai' => 'Selesai',
                                default => ucfirst(str_replace('_', ' ', $app->status))
                            };
                            $subCode = $app->subclassification ? " - {$app->subclassification->code}" : '';
                        @endphp
                        <option value="{{ $app->id }}" @selected($selectedApplication && $selectedApplication->id === $app->id)>
                            {{ strtoupper($app->application_type) }} ({{ $app->application_number }}) - Tahun {{ $app->application_year }} [{{ $statusLabel }}]{{ $subCode }}
                        </option>
                    @empty
                        <option value="">Belum ada pengajuan SBU</option>
                    @endforelse
                </select>
            </form>

            <form method="POST" action="{{ route('companies.workspace.generate.documents.process', $company) }}" class="p-5 space-y-5">
                @csrf
                <input type="hidden" name="application_id" value="{{ $selectedApplication?->id }}">

                <div class="overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-4 py-3">Dokumen</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            @foreach ($documentStatuses as $key => $status)
                                <tr>
                                    <td class="px-4 py-4 align-top">
                                        <input
                                            type="checkbox"
                                            name="document_keys[]"
                                            value="{{ $key }}"
                                            @checked($status['is_valid'])
                                            class="h-4 w-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600"
                                        >
                                    </td>
                                    <td class="px-4 py-4 align-top">
                                        <p class="font-bold text-slate-900 dark:text-slate-100">{{ $status['label'] }}</p>
                                    </td>
                                    <td class="px-4 py-4 align-top">
                                        @if ($status['is_valid'])
                                            <span class="inline-flex rounded-full border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-2.5 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-400">Valid</span>
                                        @else
                                            <span class="inline-flex rounded-full border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 px-2.5 py-1 text-xs font-bold text-amber-700 dark:text-amber-400">Warning</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 align-top text-xs text-slate-600 dark:text-slate-400">
                                        @if ($status['is_valid'])
                                            Siap dibuat dan disimpan ke arsip.
                                        @else
                                            <div class="space-y-1 text-amber-800 dark:text-amber-400">
                                                @foreach ($status['warnings'] as $warning)
                                                    <p>{{ $warning }}</p>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col gap-2 border-t border-slate-200 dark:border-slate-700 pt-5 sm:flex-row sm:justify-end">
                    <button
                        type="submit"
                        name="action"
                        value="preview"
                        class="inline-flex items-center justify-center rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm transition hover:bg-slate-50 dark:hover:bg-slate-600"
                    >
                        Preview
                    </button>
                    <button
                        type="submit"
                        name="action"
                        value="download_selected"
                        class="inline-flex items-center justify-center rounded-md bg-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700"
                    >
                        Download Selected
                    </button>
                    <button
                        type="submit"
                        name="action"
                        value="generate_all"
                        class="inline-flex items-center justify-center rounded-md bg-emerald-700 dark:bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700"
                    >
                        Generate Semua
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
