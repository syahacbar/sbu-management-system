<x-layouts.admin title="Dashboard">
    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($stats as $stat)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ number_format((int) $stat['value'], 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-slate-500">{{ $stat['note'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Perusahaan Terbaru</h3>
                    <p class="mt-1 text-sm text-slate-500">Daftar badan usaha terakhir yang masuk ke sistem.</p>
                </div>
                <a href="{{ route('companies.index') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">NIB</th>
                            <th class="px-5 py-3">Kontak</th>
                            <th class="px-5 py-3 text-center">Pengajuan</th>
                            <th class="px-5 py-3 text-center">Generated</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($latestCompanies as $company)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-950">{{ $company->name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $company->business_type ?: '-' }} / {{ $company->qualification ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4 font-mono text-xs text-slate-700">{{ $company->nib ?: '-' }}</td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <p>{{ $company->email ?: '-' }}</p>
                                    <p class="mt-0.5">{{ $company->phone ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-slate-900">{{ $company->applications_count }}</td>
                                <td class="px-5 py-4 text-center font-bold text-slate-900">{{ $company->archives_count }}</td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('companies.workspace.dashboard', $company) }}" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-slate-800">
                                        Workspace
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center font-medium text-slate-500">
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
