<x-layouts.admin title="Dashboard">
    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($stats as $stat)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">{{ number_format((int) $stat['value'], 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $stat['note'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Perusahaan Terbaru</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Daftar badan usaha terakhir yang masuk ke sistem.</p>
                </div>
                <a href="{{ route('companies.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-700/50 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">NIB</th>
                            <th class="px-5 py-3">Kontak</th>
                            <th class="px-5 py-3 text-center">Pengajuan</th>
                            <th class="px-5 py-3 text-center">Generated</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                        @forelse ($latestCompanies as $company)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-950 dark:text-white">{{ $company->name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $company->business_type ?: '-' }} / {{ $company->qualification ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4 font-mono text-xs text-slate-700 dark:text-slate-300">{{ $company->nib ?: '-' }}</td>
                                <td class="px-5 py-4 text-xs text-slate-600 dark:text-slate-400">
                                    <p>{{ $company->email ?: '-' }}</p>
                                    <p class="mt-0.5">{{ $company->phone ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-slate-900 dark:text-slate-100">{{ $company->applications_count }}</td>
                                <td class="px-5 py-4 text-center font-bold text-slate-900 dark:text-slate-100">{{ $company->archives_count }}</td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('companies.workspace.dashboard', $company) }}" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-slate-800 dark:bg-slate-600 dark:hover:bg-slate-500">
                                        Workspace
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center font-medium text-slate-500 dark:text-slate-400">
                                    Belum ada perusahaan terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.admin>
