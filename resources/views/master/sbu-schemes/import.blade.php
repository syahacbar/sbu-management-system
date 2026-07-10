<x-layouts.admin title="Import Massal Skema SBU">
    <div class="space-y-5">
        <a href="{{ route('master.schemes.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-950 transition dark:text-slate-400 dark:hover:text-white">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Skema
        </a>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="lg:col-span-2 rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="border-b border-slate-200 p-5 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Form Import Massal</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Unggah file format Excel (.xlsx) atau CSV yang berisi data Skema SBU beserta relasi kodenya.</p>
                </div>

                <form method="POST" action="{{ route('master.schemes.import') }}" enctype="multipart/form-data" class="p-5 space-y-5">
                    @csrf

                    <div>
                        <label for="file" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Pilih Berkas Berisi Data Skema</label>
                        <div class="mt-2 flex justify-center rounded-lg border border-dashed border-slate-300 px-6 py-10 transition hover:border-emerald-500 dark:border-slate-600">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div class="mt-4 flex text-sm text-slate-600 dark:text-slate-400">
                                    <label for="file" class="relative cursor-pointer rounded-md bg-white font-semibold text-emerald-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-500 focus-within:ring-offset-2 hover:text-emerald-800 dark:bg-slate-800 dark:text-emerald-400 dark:hover:text-emerald-300">
                                        <span>Unggah file Anda</span>
                                        <input id="file" name="file" type="file" accept=".xlsx,.csv" required class="sr-only">
                                    </label>
                                    <p class="pl-1">atau seret dan lepas di sini</p>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 dark:text-slate-400">XLSX atau CSV (Maksimal 5MB)</p>
                            </div>
                        </div>
                        <span id="file-name" class="mt-2 block text-sm font-semibold text-emerald-700 dark:text-emerald-400"></span>
                        @error('file') <p class="mt-2 text-sm text-red-600 font-medium dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    @if (session('import_errors'))
                        <div class="rounded-md border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/30">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-red-800 dark:text-red-400">Terdapat kesalahan validasi pada data file:</h3>
                                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                        <ul role="list" class="list-disc space-y-1 pl-5 max-h-60 overflow-y-auto">
                                            @foreach (session('import_errors') as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5 dark:border-slate-700">
                        <a href="{{ route('master.schemes.index') }}" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                            Batal
                        </a>
                        <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                            Mulai Impor
                        </button>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <h4 class="font-bold text-slate-900 dark:text-white">Format Template</h4>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Unduh berkas template di bawah ini untuk melihat contoh format pengisian Skema SBU yang didukung oleh sistem.</p>
                    
                    <a href="{{ route('master.schemes.download-template') }}" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                        <svg class="h-4 w-4 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Unduh Template (.csv / Excel)
                    </a>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm space-y-4 dark:border-slate-700 dark:bg-slate-800">
                    <h4 class="font-bold text-slate-900 dark:text-white">Petunjuk Pengisian</h4>
                    <ul class="text-xs text-slate-600 space-y-3 list-decimal pl-4 dark:text-slate-400">
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 1: Kode KBLI (Wajib)</strong>
                            <br>Harus berupa Kode KBLI terdaftar di database. Contoh: <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">41011</code>.
                        </li>
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 2: Kode Klasifikasi (Wajib)</strong>
                            <br>Harus berupa Kode Klasifikasi SBU terdaftar. Contoh: <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">BG</code>.
                        </li>
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 3: Kode Subklasifikasi (Wajib)</strong>
                            <br>Harus berupa Kode Subklasifikasi terdaftar di bawah klasifikasi yang dipilih. Contoh: <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">BG001</code>.
                        </li>
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 4: Kode Skema (Wajib)</strong>
                            <br>Harus unik di database. Contoh: <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">SK-BG001-K</code>. Jika kode sudah ada, data lama akan diperbarui.
                        </li>
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 5: Nama Skema (Wajib)</strong>
                            <br>Nama lengkap skema. Contoh: <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">Skema Konstruksi Gedung Hunian Kecil</code>.
                        </li>
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 6: Kualifikasi (Wajib)</strong>
                            <br>Harus bernilai: <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">Kecil</code>, <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">Menengah</code>, atau <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">Besar</code>.
                        </li>
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 7: Status (Opsional)</strong>
                            <br>Gunakan kata <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">Aktif</code> atau <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">Nonaktif</code>. Default adalah Aktif jika dikosongkan.
                        </li>
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 8: Urutan (Opsional)</strong>
                            <br>Harus berupa angka integer positif. Contoh: <code class="bg-slate-100 px-1 rounded dark:bg-slate-700">10</code>. Default adalah 0 jika dikosongkan.
                        </li>
                        <li>
                            <strong class="text-slate-900 dark:text-slate-200">Kolom 9: Keterangan (Opsional)</strong>
                            <br>Penjelasan singkat mengenai Skema SBU.
                        </li>
                    </ul>
                </section>
            </aside>
        </div>
    </div>

    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('file-name').textContent = fileName ? 'Terpilih: ' + fileName : '';
        });
    </script>
</x-layouts.admin>
