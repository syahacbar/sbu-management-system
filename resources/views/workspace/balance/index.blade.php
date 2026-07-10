<x-layouts.admin title="Neraca Keuangan" :company="$company">
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
                            <p>Perusahaan ini belum memiliki pengajuan SBU yang aktif. Untuk mengelola data neraca keuangan, harap tentukan atau buat pengajuan aktif terlebih dahulu di menu Pengajuan.</p>
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
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Laporan Neraca Keuangan</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Data posisi keuangan perusahaan yang dinilai berdasarkan tahun pelaporan.</p>
                    </div>

                    <a href="{{ route('companies.workspace.balance.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 dark:bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700">
                        Tambah Laporan Neraca
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-3">Tanggal Laporan</th>
                                <th class="px-5 py-3">Tahun Pertama & Kedua</th>
                                <th class="px-5 py-3">Total Aktiva</th>
                                <th class="px-5 py-3">Total Kewajiban</th>
                                <th class="px-5 py-3">Kekayaan Bersih</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            @forelse ($statements as $item)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-bold text-slate-950 dark:text-white">{{ $item->statement_date?->format('d/m/Y') ?: '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-600 dark:text-slate-400">
                                        <p><span class="font-bold text-slate-400 dark:text-slate-500">Tahun 1:</span> {{ $item->year_one }}</p>
                                        <p class="mt-1"><span class="font-bold text-slate-400 dark:text-slate-500">Tahun 2:</span> {{ $item->year_two }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-700 dark:text-slate-300 font-mono">
                                        <p class="text-slate-500 dark:text-slate-400">Rp {{ number_format($item->total_aktiva_year_one, 0, ',', '.') }}</p>
                                        <p class="mt-1 font-bold text-slate-950 dark:text-white">Rp {{ number_format($item->total_aktiva_year_two, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-700 dark:text-slate-300 font-mono">
                                        <p class="text-slate-500 dark:text-slate-400">Rp {{ number_format($item->total_kewajiban_year_one, 0, ',', '.') }}</p>
                                        <p class="mt-1 font-bold text-slate-950 dark:text-white">Rp {{ number_format($item->total_kewajiban_year_two, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-700 dark:text-slate-300 font-mono">
                                        <p class="text-slate-500 dark:text-slate-400">Rp {{ number_format($item->kekayaan_bersih_year_one, 0, ',', '.') }}</p>
                                        <p class="mt-1 font-bold text-emerald-700 dark:text-emerald-400">Rp {{ number_format($item->kekayaan_bersih_year_two, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-1.5">
                                            <a href="{{ route('companies.workspace.balance.edit', [$company, $item]) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-2.5 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-50 dark:hover:bg-slate-600">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('companies.workspace.balance.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus neraca keuangan ini?')">
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
                                        Belum ada data neraca keuangan untuk pengajuan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($statements && $statements->hasPages())
                    <div class="border-t border-slate-200 dark:border-slate-700 p-5">
                        {{ $statements->links() }}
                    </div>
                @endif
            </section>
        @endif
    </div>
</x-layouts.admin>
