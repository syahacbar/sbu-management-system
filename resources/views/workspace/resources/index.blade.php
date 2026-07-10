<x-layouts.admin :title="$resource['title']" :company="$company">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-sm font-medium text-emerald-800 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="flex flex-col gap-4 border-b border-slate-200 dark:border-slate-700 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">{{ $resource['title'] }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Data di halaman ini hanya milik {{ $company->name }}.</p>
                </div>

                <a href="{{ route($resource['route'].'.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 dark:bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700">
                    Tambah Data
                </a>
            </div>

            <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                <form method="GET" action="{{ route($resource['route'].'.index', $company) }}" class="flex flex-col gap-3 sm:flex-row">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari kode, nama, status, atau keterangan" class="min-h-10 flex-1 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    <button type="submit" class="rounded-md border border-slate-300 dark:border-slate-600 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-100 dark:hover:bg-slate-700">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Urutan</th>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Nominal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        @forelse ($items as $item)
                            <tr>
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $item->sort_order }}</td>
                                <td class="px-5 py-4 font-medium text-slate-800 dark:text-slate-200">{{ $item->code ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-950 dark:text-white">{{ $item->name }}</p>
                                    <p class="mt-1 max-w-md text-xs text-slate-500 dark:text-slate-400">{{ $item->description ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $item->is_active ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $item->record_date?->format('d/m/Y') ?: '-' }}</td>
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $item->amount !== null ? 'Rp '.number_format((float) $item->amount, 0, ',', '.') : '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route($resource['route'].'.edit', [$company, $item]) }}" class="rounded-md border border-slate-300 dark:border-slate-600 px-3 py-1.5 text-sm font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-100 dark:hover:bg-slate-700">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route($resource['route'].'.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 dark:border-red-800 px-3 py-1.5 text-sm font-semibold text-red-700 dark:text-red-400 transition hover:bg-red-50 dark:hover:bg-red-900/30">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">Belum ada data {{ $resource['title'] }} untuk {{ $company->name }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($items->hasPages())
                <div class="border-t border-slate-200 dark:border-slate-700 p-5">
                    {{ $items->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
