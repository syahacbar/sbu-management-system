<x-layouts.admin title="{{ $item ? 'Edit Template Dokumen' : 'Tambah Template Dokumen' }}">
    <div class="space-y-5">
        <a href="{{ route('master.document-templates.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-950 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Template
        </a>

        @if ($errors->any())
            <div class="rounded-md border border-red-200 bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Terdapat kesalahan penginputan data:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="lg:col-span-2 rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5">
                    <h3 class="text-lg font-semibold text-slate-950">{{ $item ? 'Edit Template Dokumen' : 'Tambah Template Dokumen' }}</h3>
                    <p class="mt-1 text-sm text-slate-500">Konfigurasi file aset visual kop, tanda tangan, cap, dan struktur HTML untuk pencetakan dokumen.</p>
                </div>

                <form
                    method="POST"
                    action="{{ $item ? route('master.document-templates.update', $item) : route('master.document-templates.store') }}"
                    enctype="multipart/form-data"
                    class="p-5 space-y-6"
                >
                    @csrf
                    @if ($item)
                        @method('PUT')
                    @endif

                    <div class="grid gap-5 sm:grid-cols-2">
                        <!-- Kode Template -->
                        <div>
                            <label for="code" class="block text-sm font-semibold text-slate-700">Kode Template <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                name="code"
                                id="code"
                                required
                                value="{{ old('code', $item?->code) }}"
                                placeholder="Contoh: SBU-CERT"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >
                        </div>

                        <!-- Nama Template -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Nama Template <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                required
                                value="{{ old('name', $item?->name) }}"
                                placeholder="Contoh: Sertifikat Resmi SBU"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-3">
                        <!-- Urutan -->
                        <div>
                            <label for="sort_order" class="block text-sm font-semibold text-slate-700">Urutan Tampilan <span class="text-red-500">*</span></label>
                            <input
                                type="number"
                                name="sort_order"
                                id="sort_order"
                                required
                                min="0"
                                value="{{ old('sort_order', $item?->sort_order ?? 0) }}"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >
                        </div>

                        <!-- Status Aktif -->
                        <div>
                            <label for="is_active" class="block text-sm font-semibold text-slate-700">Status Aktif <span class="text-red-500">*</span></label>
                            <select
                                name="is_active"
                                id="is_active"
                                required
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >
                                <option value="1" @selected((string) old('is_active', $item?->is_active ?? '1') === '1')>Aktif</option>
                                <option value="0" @selected((string) old('is_active', $item?->is_active ?? '1') === '0')>Nonaktif</option>
                            </select>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <div class="sm:col-span-1">
                            <label for="description" class="block text-sm font-semibold text-slate-700">Deskripsi Singkat</label>
                            <input
                                type="text"
                                name="description"
                                id="description"
                                value="{{ old('description', $item?->description) }}"
                                placeholder="Keterangan singkat"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >
                        </div>
                    </div>

                    <!-- Kop Surat / Header Text -->
                    <div>
                        <label for="header_text" class="block text-sm font-semibold text-slate-700">Teks Kop Surat (Header)</label>
                        <textarea
                            name="header_text"
                            id="header_text"
                            rows="2"
                            placeholder="Contoh: DEPARTEMEN PEKERJAAN UMUM DAN PERUMAHAN RAKYAT"
                            class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >{{ old('header_text', $item?->header_text) }}</textarea>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3 border-t border-slate-100 pt-5">
                        <!-- Upload Logo -->
                        <div class="space-y-2">
                            <label for="logo" class="block text-sm font-semibold text-slate-700">Logo Kop (.png/.jpg)</label>
                            <input
                                type="file"
                                name="logo"
                                id="logo"
                                accept=".png,.jpg,.jpeg"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                            >
                            @if ($item?->logo_path)
                                <div class="mt-2 rounded-md border border-slate-200 p-2 bg-slate-50">
                                    <p class="text-[10px] text-slate-400 truncate mb-1">Terunggah:</p>
                                    <img src="{{ asset('storage/' . $item->logo_path) }}" alt="Logo" class="max-h-12 max-w-full rounded object-contain">
                                </div>
                            @endif
                        </div>

                        <!-- Upload Tanda Tangan -->
                        <div class="space-y-2">
                            <label for="signature" class="block text-sm font-semibold text-slate-700">Tanda Tangan (.png/.jpg)</label>
                            <input
                                type="file"
                                name="signature"
                                id="signature"
                                accept=".png,.jpg,.jpeg"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            >
                            @if ($item?->signature_path)
                                <div class="mt-2 rounded-md border border-slate-200 p-2 bg-slate-50">
                                    <p class="text-[10px] text-slate-400 truncate mb-1">Terunggah:</p>
                                    <img src="{{ asset('storage/' . $item->signature_path) }}" alt="Ttd" class="max-h-12 max-w-full rounded object-contain">
                                </div>
                            @endif
                        </div>

                        <!-- Upload Stempel -->
                        <div class="space-y-2">
                            <label for="stamp" class="block text-sm font-semibold text-slate-700">Cap / Stempel (.png/.jpg)</label>
                            <input
                                type="file"
                                name="stamp"
                                id="stamp"
                                accept=".png,.jpg,.jpeg"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100"
                            >
                            @if ($item?->stamp_path)
                                <div class="mt-2 rounded-md border border-slate-200 p-2 bg-slate-50">
                                    <p class="text-[10px] text-slate-400 truncate mb-1">Terunggah:</p>
                                    <img src="{{ asset('storage/' . $item->stamp_path) }}" alt="Stempel" class="max-h-12 max-w-full rounded object-contain">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Template Body HTML -->
                    <div class="border-t border-slate-100 pt-5">
                        <label for="template_body" class="block text-sm font-semibold text-slate-700">Struktur Template HTML (Isi Dokumen)</label>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Tulis kode HTML untuk menyusun tata letak isi dokumen. Anda dapat menyisipkan token placeholder (misal: <code class="bg-slate-100 px-1 rounded font-bold text-slate-800">{company_name}</code>) yang akan otomatis diganti data dinamis saat dicetak.</p>
                        <textarea
                            name="template_body"
                            id="template_body"
                            rows="12"
                            placeholder="&lt;div style=&quot;text-align: center;&quot;&gt;&#10;  &lt;h1&gt;SERTIFIKAT BADAN USAHA&lt;/h1&gt;&#10;  &lt;p&gt;Diberikan kepada: {company_name}&lt;/p&gt;&#10;&lt;/div&gt;"
                            class="mt-3 w-full rounded-md border border-slate-300 px-3 py-2 font-mono text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >{{ old('template_body', $item?->template_body) }}</textarea>
                    </div>

                    <!-- Footer Text -->
                    <div>
                        <label for="footer_text" class="block text-sm font-semibold text-slate-700">Teks Penutup / Kaki Surat (Footer)</label>
                        <textarea
                            name="footer_text"
                            id="footer_text"
                            rows="2"
                            placeholder="Contoh: Sertifikat ini sah dan diterbitkan secara elektronik."
                            class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >{{ old('footer_text', $item?->footer_text) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                        <a href="{{ route('master.document-templates.index') }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                            Batal
                        </a>
                        <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                            Simpan Template
                        </button>
                    </div>
                </form>
            </section>

            <!-- Placeholder Token Sidebar Guide -->
            <aside class="space-y-6">
                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                    <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-3">Panduan Token Placeholder</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">Klik salah satu nama token di bawah ini untuk langsung menyalin token tersebut ke clipboard Anda, lalu tempelkan (paste) ke dalam kolom editor HTML.</p>
                    
                    <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1">
                        @php
                            $tokens = [
                                'Perusahaan' => [
                                    '{company_name}' => 'Nama Perusahaan',
                                    '{company_address}' => 'Alamat Perusahaan',
                                    '{company_npwp}' => 'NPWP Perusahaan',
                                    '{director_name}' => 'Nama Direktur Utama',
                                ],
                                'Domain SBU' => [
                                    '{kbli_code}' => 'Kode KBLI SBU',
                                    '{kbli_name}' => 'Nama KBLI SBU',
                                    '{classification_code}' => 'Kode Klasifikasi SBU',
                                    '{subclassification_code}' => 'Kode Subklasifikasi SBU',
                                    '{scheme_code}' => 'Kode Skema SBU',
                                    '{scheme_name}' => 'Nama Skema SBU',
                                    '{qualification}' => 'Tingkat Kualifikasi SBU',
                                ],
                                'Pengajuan' => [
                                    '{application_code}' => 'Nomor Pengajuan SBU',
                                    '{application_date}' => 'Tanggal Pengajuan',
                                ],
                                'Lainnya' => [
                                    '{current_date}' => 'Tanggal Cetak Hari Ini',
                                ]
                            ];
                        @endphp

                        @foreach ($tokens as $category => $list)
                            <div>
                                <h5 class="text-xs font-bold text-emerald-800 mb-1.5">{{ $category }}</h5>
                                <div class="space-y-1">
                                    @foreach ($list as $token => $label)
                                        <button
                                            type="button"
                                            onclick="copyToClipboard('{{ $token }}', this)"
                                            class="flex w-full items-center justify-between rounded border border-slate-100 bg-slate-50 px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 group"
                                        >
                                            <span class="font-mono text-emerald-700 group-hover:underline">{{ $token }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $label }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    </div>

    <!-- Copy to Clipboard Script -->
    <script>
        function copyToClipboard(text, element) {
            navigator.clipboard.writeText(text).then(function() {
                const originalText = element.innerHTML;
                element.classList.add('bg-emerald-50', 'border-emerald-200');
                element.innerHTML = '<span class="text-emerald-700 font-bold">Tersalin!</span><span class="text-[10px] text-emerald-600">Copied</span>';
                
                setTimeout(function() {
                    element.classList.remove('bg-emerald-50', 'border-emerald-200');
                    element.innerHTML = originalText;
                }, 1500);
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</x-layouts.admin>
