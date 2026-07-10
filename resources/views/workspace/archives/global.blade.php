<x-layouts.admin title="Arsip Global Hasil Cetak" :company="$company">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-sm font-medium text-emerald-800 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Arsip Global Hasil Cetak</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Daftar lengkap berkas sertifikat dan SPTJM yang telah dicetak dari seluruh perusahaan.</p>
            </div>

            <!-- Filters -->
            <div class="border-b border-slate-200 dark:border-slate-700 p-5 bg-slate-50/50 dark:bg-slate-700/50">
                <form method="GET" action="{{ route('archives.global') }}" class="grid gap-3 sm:grid-cols-3">
                    <div>
                        <label for="company_id" class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Filter Perusahaan</label>
                        <select
                            name="company_id"
                            id="company_id"
                            class="min-h-10 w-full rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                        >
                            <option value="">-- Semua Perusahaan --</option>
                            @foreach ($companies as $c)
                                <option value="{{ $c->id }}" @selected($companyId === $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="search" class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Cari Kata Kunci</label>
                        <div class="flex gap-2">
                            <input
                                type="search"
                                name="search"
                                id="search"
                                value="{{ $search }}"
                                placeholder="Cari nama dokumen, nama berkas, nomor pengajuan..."
                                class="min-h-10 flex-1 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                            >
                            
                            <button type="submit" class="rounded-md bg-slate-900 dark:bg-slate-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 dark:hover:bg-slate-500">
                                Cari
                            </button>

                            @if ($search || $companyId)
                                <a href="{{ route('archives.global') }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600 transition flex items-center justify-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">Jenis Dokumen</th>
                            <th class="px-5 py-3">Nomor Pengajuan</th>
                            <th class="px-5 py-3">Nama Berkas</th>
                            <th class="px-5 py-3">Tanggal Cetak</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @forelse ($archives as $item)
                            <tr>
                                <td class="px-5 py-4 font-bold text-slate-900 dark:text-slate-100">
                                    @if ($item->company)
                                        <a href="{{ route('companies.workspace.dashboard', $item->company) }}" class="hover:underline">
                                            {{ $item->company->name }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $item->document_type }}
                                </td>
                                <td class="px-5 py-4 font-mono text-xs text-slate-600 dark:text-slate-400">
                                    @if ($item->application && $item->company)
                                        <a href="{{ route('companies.workspace.applications.show', [$item->company, $item->application]) }}" class="hover:underline font-bold">
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500 dark:text-slate-400 font-medium">
                                    Tidak menemukan arsip cetak dokumen yang cocok.
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
