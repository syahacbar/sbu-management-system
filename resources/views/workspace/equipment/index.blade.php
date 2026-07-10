<x-layouts.admin title="Peralatan Perusahaan" :company="$company">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-sm font-medium text-emerald-800 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md border border-rose-200 dark:border-red-800 bg-rose-50 dark:bg-red-900/30 px-4 py-3 text-sm font-medium text-rose-800 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        @if (!$activeApplication)
            <div class="rounded-md border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/30 p-5">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-amber-800 dark:text-amber-400">Pengajuan SBU Aktif Tidak Ditemukan</h3>
                        <div class="mt-2 text-sm text-amber-700 dark:text-amber-400">
                            <p>Perusahaan ini belum memiliki pengajuan SBU yang aktif. Untuk mengelola data peralatan konstruksi, harap tentukan atau buat pengajuan aktif terlebih dahulu di menu Pengajuan.</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('companies.workspace.applications.index', $company) }}" class="rounded bg-amber-800 dark:bg-amber-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-amber-900 dark:hover:bg-amber-500 transition">
                                Buka Menu Pengajuan &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Kelayakan & Filter -->
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Terhubung ke Pengajuan Aktif: 
                        <a href="{{ route('companies.workspace.applications.show', [$company, $activeApplication]) }}" class="font-bold text-slate-900 dark:text-slate-100 underline font-mono">
                            {{ $activeApplication->application_number }}
                        </a> 
                        ({{ ucfirst($activeApplication->application_type) }})
                    </p>
                </div>
            </div>

            <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                <div class="flex flex-col gap-4 border-b border-slate-200 dark:border-slate-700 p-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Daftar Peralatan</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Daftar inventaris peralatan utama untuk syarat klasifikasi pengajuan.</p>
                    </div>

                    <a href="{{ route('companies.workspace.equipment.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 dark:bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700">
                        Tambah Peralatan
                    </a>
                </div>

                <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                    <form method="GET" action="{{ route('companies.workspace.equipment.index', $company) }}" class="flex flex-wrap items-center gap-3">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kategori Peralatan SBU:</span>
                        
                        <a href="{{ route('companies.workspace.equipment.index', [$company, 'category' => '']) }}" 
                           class="rounded-md border px-3 py-1.5 text-xs font-semibold transition {{ $category === '' ? 'bg-slate-900 dark:bg-slate-600 border-slate-900 dark:border-slate-600 text-white' : 'bg-white dark:bg-slate-700 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600' }}">
                            Semua Peralatan
                        </a>

                        <a href="{{ route('companies.workspace.equipment.index', [$company, 'category' => 'bg']) }}" 
                           class="rounded-md border px-3 py-1.5 text-xs font-semibold transition {{ $category === 'bg' ? 'bg-slate-900 dark:bg-slate-600 border-slate-900 dark:border-slate-600 text-white' : 'bg-white dark:bg-slate-700 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600' }}">
                            BG (Bangunan Gedung)
                        </a>

                        <a href="{{ route('companies.workspace.equipment.index', [$company, 'category' => 'bs']) }}" 
                           class="rounded-md border px-3 py-1.5 text-xs font-semibold transition {{ $category === 'bs' ? 'bg-slate-900 dark:bg-slate-600 border-slate-900 dark:border-slate-600 text-white' : 'bg-white dark:bg-slate-700 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600' }}">
                            BS (Bangunan Sipil)
                        </a>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-3">Nama Alat</th>
                                <th class="px-5 py-3">Kategori SBU</th>
                                <th class="px-5 py-3">Spesifikasi</th>
                                <th class="px-5 py-3">Jumlah & Satuan</th>
                                <th class="px-5 py-3">Status Kepemilikan</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            @forelse ($equipments as $item)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-bold text-slate-950 dark:text-white">{{ $item->name }}</p>
                                        @if($item->masterEquipment)
                                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400 font-mono">Kode Master: {{ $item->masterEquipment->code }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $catBadge = $item->category === 'bg'
                                                ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400'
                                                : 'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-400';
                                            $catLabel = $item->category === 'bg'
                                                ? 'BG - Bangunan Gedung'
                                                : 'BS - Bangunan Sipil';
                                        @endphp
                                        <span class="inline-flex items-center rounded border px-2 py-0.5 text-xs font-bold uppercase {{ $catBadge }}">
                                            {{ $catLabel }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-700 dark:text-slate-300 text-xs">
                                        {{ $item->specification ?: '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-slate-900 dark:text-slate-100 font-medium">
                                        {{ $item->quantity }} {{ $item->unit }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $ownBadge = match($item->ownership_status) {
                                                'milik_sendiri' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                                'sewa' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800',
                                                default => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-600'
                                            };
                                            $ownLabel = match($item->ownership_status) {
                                                'milik_sendiri' => 'Milik Sendiri',
                                                'sewa' => 'Sewa',
                                                default => 'Pinjam'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $ownBadge }}">
                                            {{ $ownLabel }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-1.5">
                                            <a href="{{ route('companies.workspace.equipment.edit', [$company, $item]) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-2.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-50 dark:hover:bg-slate-600">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('companies.workspace.equipment.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus data peralatan ini?')">
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
                                    <td colspan="6" class="px-5 py-12 text-center text-slate-500 dark:text-slate-400 font-medium">
                                        Belum ada data peralatan terdaftar untuk pengajuan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($equipments && $equipments->hasPages())
                    <div class="border-t border-slate-200 dark:border-slate-700 p-5">
                        {{ $equipments->links() }}
                    </div>
                @endif
            </section>
        @endif
    </div>
</x-layouts.admin>
