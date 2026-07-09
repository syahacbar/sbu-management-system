<x-layouts.admin title="Generate Dokumen SBU" :company="$company">
    <div class="max-w-3xl space-y-5">
        <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 p-5">
                <h3 class="text-lg font-semibold text-slate-950">Generate Dokumen SBU</h3>
                <p class="mt-1 text-sm text-slate-500">Pilih berkas pengajuan SBU perusahaan dan template dokumen untuk membuat cetakan siap cetak (PDF).</p>
            </div>

            <form
                method="GET"
                action="{{ route('companies.workspace.generate.preview', $company) }}"
                target="_blank"
                class="p-5 space-y-6"
            >
                <!-- Select Application -->
                <div>
                    <label for="application_id" class="block text-sm font-semibold text-slate-700">Pilih Pengajuan SBU <span class="text-red-500">*</span></label>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Pilih pengajuan aktif yang ingin dibuatkan sertifikat/dokumen cetaknya.</p>
                    <select
                        name="application_id"
                        id="application_id"
                        required
                        class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">-- Pilih Berkas Pengajuan SBU --</option>
                        @foreach ($applications as $app)
                            @php
                                $statusLabel = match($app->status) {
                                    'draft' => 'Draft',
                                    'review' => 'Review',
                                    'approved' => 'Disetujui / Approved',
                                    'rejected' => 'Ditolak',
                                    default => ucfirst($app->status)
                                };
                                $subCode = $app->subclassification ? " - {$app->subclassification->code}" : '';
                            @endphp
                            <option value="{{ $app->id }}">
                                {{ $app->name }} ({{ $app->code ?: 'No Code' }}) [{{ $statusLabel }}]{{ $subCode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Template -->
                <div>
                    <label for="template_id" class="block text-sm font-semibold text-slate-700">Pilih Template Cetak <span class="text-red-500">*</span></label>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Pilih layout ornamen, kop, stempel, dan format tanda tangan yang didaftarkan pada Master Template.</p>
                    <select
                        name="template_id"
                        id="template_id"
                        required
                        class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                        <option value="">-- Pilih Template Cetak --</option>
                        @foreach ($templates as $tpl)
                            <option value="{{ $tpl->id }}">{{ $tpl->name }} ({{ $tpl->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 rounded-md bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Generate Pratinjau (Tab Baru)
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
