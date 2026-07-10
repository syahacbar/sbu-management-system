<x-layouts.admin title="Pengaturan">
    @php
        $tab = request('tab', 'app-profile');
        $tabs = [
            'app-profile' => 'Profil Aplikasi',
            'documents' => 'Pengaturan Dokumen',
            'storage' => 'Pengaturan Penyimpanan',
            'backup' => 'Pengaturan Backup',
            'profile' => 'Profil Admin',
            'system' => 'Informasi Sistem',
        ];
    @endphp

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
            <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Pengaturan Sistem</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola konfigurasi aplikasi secara global.</p>
            </div>

            <div class="border-b border-slate-200 px-5 dark:border-slate-700">
                <nav class="-mb-px flex gap-6 overflow-x-auto">
                    @foreach ($tabs as $key => $label)
                        <a
                            href="{{ route('settings.index', ['tab' => $key]) }}"
                            class="shrink-0 border-b-2 px-1 py-4 text-sm font-medium transition {{ $tab === $key ? 'border-emerald-700 text-emerald-700' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:border-slate-600 dark:hover:text-slate-300' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="p-5">
                @switch($tab)
                    @case('app-profile')
                        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="tab" value="app-profile">

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="app_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Aplikasi</label>
                                    <input
                                        id="app_name"
                                        name="app_name"
                                        type="text"
                                        value="{{ old('app_name', $settings['app_name'] ?? '') }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                        required
                                    >
                                    @error('app_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_company_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Perusahaan</label>
                                    <input
                                        id="app_company_name"
                                        name="app_company_name"
                                        type="text"
                                        value="{{ old('app_company_name', $settings['app_company_name'] ?? '') }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                        required
                                    >
                                    @error('app_company_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_logo" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Logo Aplikasi</label>
                                    <input
                                        id="app_logo"
                                        name="app_logo"
                                        type="file"
                                        accept="image/png,image/jpeg,image/svg+xml"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition file:mr-3 file:rounded file:border-0 file:bg-emerald-50 file:px-3 file:py-1 file:text-sm file:font-semibold file:text-emerald-700 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400"
                                    >
                                    @if (!empty($settings['app_logo']))
                                        <div class="mt-2 flex items-center gap-2">
                                            <img src="{{ asset('storage/' . $settings['app_logo']) }}" class="h-10 w-auto rounded border border-slate-200 dark:border-slate-700">
                                            <span class="text-xs text-slate-500 dark:text-slate-400">Logo saat ini</span>
                                        </div>
                                    @endif
                                    @error('app_logo')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_favicon" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Favicon</label>
                                    <input
                                        id="app_favicon"
                                        name="app_favicon"
                                        type="file"
                                        accept="image/png,image/x-icon,image/svg+xml"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition file:mr-3 file:rounded file:border-0 file:bg-emerald-50 file:px-3 file:py-1 file:text-sm file:font-semibold file:text-emerald-700 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400"
                                    >
                                    @if (!empty($settings['app_favicon']))
                                        <div class="mt-2 flex items-center gap-2">
                                            <img src="{{ asset('storage/' . $settings['app_favicon']) }}" class="h-8 w-auto rounded border border-slate-200 dark:border-slate-700">
                                            <span class="text-xs text-slate-500 dark:text-slate-400">Favicon saat ini</span>
                                        </div>
                                    @endif
                                    @error('app_favicon')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="app_address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Alamat</label>
                                    <textarea
                                        id="app_address"
                                        name="app_address"
                                        rows="2"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >{{ old('app_address', $settings['app_address'] ?? '') }}</textarea>
                                    @error('app_address')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Telepon</label>
                                    <input
                                        id="app_phone"
                                        name="app_phone"
                                        type="text"
                                        value="{{ old('app_phone', $settings['app_phone'] ?? '') }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('app_phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                                    <input
                                        id="app_email"
                                        name="app_email"
                                        type="email"
                                        value="{{ old('app_email', $settings['app_email'] ?? '') }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('app_email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="app_website" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Website</label>
                                    <input
                                        id="app_website"
                                        name="app_website"
                                        type="url"
                                        value="{{ old('app_website', $settings['app_website'] ?? '') }}"
                                        placeholder="https://contoh.com"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('app_website')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_footer" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Footer</label>
                                    <input
                                        id="app_footer"
                                        name="app_footer"
                                        type="text"
                                        value="{{ old('app_footer', $settings['app_footer'] ?? '') }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('app_footer')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_copyright" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Copyright</label>
                                    <input
                                        id="app_copyright"
                                        name="app_copyright"
                                        type="text"
                                        value="{{ old('app_copyright', $settings['app_copyright'] ?? '') }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('app_copyright')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end border-t border-slate-200 pt-5 dark:border-slate-700">
                                <button type="submit" class="rounded-md bg-emerald-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    @break

                    @case('documents')
                        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="tab" value="documents">

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="doc_city_default" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kota Tempat TTD Default</label>
                                    <input
                                        id="doc_city_default"
                                        name="doc_city_default"
                                        type="text"
                                        value="{{ old('doc_city_default', $settings['doc_city_default'] ?? '') }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('doc_city_default')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="doc_number_format" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Format Nomor Dokumen</label>
                                    <input
                                        id="doc_number_format"
                                        name="doc_number_format"
                                        type="text"
                                        value="{{ old('doc_number_format', $settings['doc_number_format'] ?? '') }}"
                                        placeholder="SBU/{tahun}/{bulan}/{nomor}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('doc_number_format')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_prefix_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Prefix Nomor Pengajuan</label>
                                    <input
                                        id="app_prefix_number"
                                        name="app_prefix_number"
                                        type="text"
                                        value="{{ old('app_prefix_number', $settings['app_prefix_number'] ?? '') }}"
                                        placeholder="PNJ"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('app_prefix_number')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="doc_default_year" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tahun Default</label>
                                    <input
                                        id="doc_default_year"
                                        name="doc_default_year"
                                        type="number"
                                        value="{{ old('doc_default_year', $settings['doc_default_year'] ?? date('Y')) }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('doc_default_year')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="doc_date_format" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Format Tanggal Indonesia</label>
                                    <input
                                        id="doc_date_format"
                                        name="doc_date_format"
                                        type="text"
                                        value="{{ old('doc_date_format', $settings['doc_date_format'] ?? '') }}"
                                        placeholder="d F Y"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('doc_date_format')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="doc_paper_size" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Ukuran Kertas Default</label>
                                    <select
                                        id="doc_paper_size"
                                        name="doc_paper_size"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                        @foreach (['A4', 'A3', 'Letter', 'Legal'] as $size)
                                            <option value="{{ $size }}" {{ (old('doc_paper_size', $settings['doc_paper_size'] ?? '')) === $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    @error('doc_paper_size')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="doc_margin_pdf" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Margin PDF</label>
                                    <input
                                        id="doc_margin_pdf"
                                        name="doc_margin_pdf"
                                        type="text"
                                        value="{{ old('doc_margin_pdf', $settings['doc_margin_pdf'] ?? '') }}"
                                        placeholder="10mm"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('doc_margin_pdf')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="doc_orientation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Orientation Default</label>
                                    <select
                                        id="doc_orientation"
                                        name="doc_orientation"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                        @foreach (['portrait' => 'Portrait', 'landscape' => 'Landscape'] as $val => $label)
                                            <option value="{{ $val }}" {{ (old('doc_orientation', $settings['doc_orientation'] ?? '')) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('doc_orientation')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end border-t border-slate-200 pt-5 dark:border-slate-700">
                                <button type="submit" class="rounded-md bg-emerald-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    @break

                    @case('storage')
                        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="tab" value="storage">

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="storage_upload_folder" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Folder Upload</label>
                                    <input
                                        id="storage_upload_folder"
                                        name="storage_upload_folder"
                                        type="text"
                                        value="{{ old('storage_upload_folder', $settings['storage_upload_folder'] ?? '') }}"
                                        placeholder="uploads"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('storage_upload_folder')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="storage_archive_folder" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Folder Arsip PDF</label>
                                    <input
                                        id="storage_archive_folder"
                                        name="storage_archive_folder"
                                        type="text"
                                        value="{{ old('storage_archive_folder', $settings['storage_archive_folder'] ?? '') }}"
                                        placeholder="archives"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('storage_archive_folder')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="storage_max_upload_size" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Maksimal Upload File (MB)</label>
                                    <input
                                        id="storage_max_upload_size"
                                        name="storage_max_upload_size"
                                        type="number"
                                        value="{{ old('storage_max_upload_size', $settings['storage_max_upload_size'] ?? '10') }}"
                                        min="1"
                                        max="1024"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('storage_max_upload_size')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="storage_allowed_types" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Jenis File Yang Diizinkan</label>
                                    <input
                                        id="storage_allowed_types"
                                        name="storage_allowed_types"
                                        type="text"
                                        value="{{ old('storage_allowed_types', $settings['storage_allowed_types'] ?? '') }}"
                                        placeholder="pdf,doc,docx,xls,xlsx,jpg,jpeg,png"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    @error('storage_allowed_types')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Pisahkan dengan koma, tanpa spasi. Contoh: pdf,doc,jpg</p>
                                </div>
                            </div>

                            <div class="flex justify-end border-t border-slate-200 pt-5 dark:border-slate-700">
                                <button type="submit" class="rounded-md bg-emerald-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    @break

                    @case('backup')
                        <div class="space-y-6">
                            <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                                @csrf
                                <input type="hidden" name="tab" value="backup">

                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="backup_db_folder" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Folder Backup Database</label>
                                        <input
                                            id="backup_db_folder"
                                            name="backup_db_folder"
                                            type="text"
                                            value="{{ old('backup_db_folder', $settings['backup_db_folder'] ?? '') }}"
                                            class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                        >
                                        @error('backup_db_folder')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="backup_doc_folder" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Folder Backup Dokumen</label>
                                        <input
                                            id="backup_doc_folder"
                                            name="backup_doc_folder"
                                            type="text"
                                            value="{{ old('backup_doc_folder', $settings['backup_doc_folder'] ?? '') }}"
                                            class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                        >
                                        @error('backup_doc_folder')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end border-t border-slate-200 pt-5 dark:border-slate-700">
                                    <button type="submit" class="rounded-md bg-emerald-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                        Simpan Pengaturan
                                    </button>
                                </div>
                            </form>

                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-700/50">
                                <h4 class="text-sm font-semibold text-slate-950 dark:text-white">Aksi Backup</h4>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Jalankan backup secara manual.</p>
                                <div class="mt-4 flex flex-wrap gap-3">
                                    <form method="POST" action="{{ route('settings.backup.database') }}">
                                        @csrf
                                        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:ring-offset-2 dark:bg-slate-600 dark:hover:bg-slate-500">
                                            Backup Database
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('settings.backup.storage') }}">
                                        @csrf
                                        <button type="submit" class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                                            Backup Storage
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @break

                    @case('profile')
                        <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-6">
                            @csrf

                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama</label>
                                    <input
                                        id="name"
                                        name="name"
                                        type="text"
                                        value="{{ old('name', $user->name) }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                        required
                                    >
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        value="{{ old('email', $user->email) }}"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                        required
                                    >
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password Baru</label>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Kosongkan jika tidak ingin mengubah password.</p>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi Password</label>
                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                                    >
                                </div>
                            </div>

                            <div class="flex justify-end border-t border-slate-200 pt-5 dark:border-slate-700">
                                <button type="submit" class="rounded-md bg-emerald-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                    Perbarui Profil
                                </button>
                            </div>
                        </form>
                    @break

                    @case('system')
                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ([
                                ['label' => 'Laravel Version', 'value' => $info['laravel_version']],
                                ['label' => 'PHP Version', 'value' => $info['php_version']],
                                ['label' => 'Database', 'value' => $info['database']],
                                ['label' => 'Environment', 'value' => $info['environment']],
                                ['label' => 'Storage Used', 'value' => $info['storage_used']],
                                ['label' => 'Total Perusahaan', 'value' => number_format($info['total_companies'], 0, ',', '.')],
                                ['label' => 'Total Pengajuan', 'value' => number_format($info['total_applications'], 0, ',', '.')],
                                ['label' => 'Total Dokumen', 'value' => number_format($info['total_documents'], 0, ',', '.')],
                                ['label' => 'Total Arsip PDF', 'value' => number_format($info['total_archives'], 0, ',', '.')],
                            ] as $item)
                                <article class="rounded-lg border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    @break
                @endswitch
            </div>
        </section>
    </div>
</x-layouts.admin>
