<x-layouts.admin title="Perusahaan">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between dark:border-slate-700">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Daftar Perusahaan</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Setiap perusahaan memiliki workspace administrasi SBU sendiri.</p>
                </div>

                <a href="{{ route('companies.create') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                    Tambah Perusahaan
                </a>
            </div>

            <div class="border-b border-slate-200 p-5 dark:border-slate-700">
                <form method="GET" action="{{ route('companies.index') }}" class="flex flex-col gap-3 sm:flex-row">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama, NIB, atau NPWP"
                        class="min-h-10 flex-1 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                    >
                    <button type="submit" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-700/50 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">NIB</th>
                            <th class="px-5 py-3">NPWP</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-800">
                        @forelse ($companies as $company)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-950 dark:text-white">{{ $company->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $company->email ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $company->nib ?: '-' }}</td>
                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">{{ $company->npwp ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('companies.workspace.dashboard', $company) }}" class="rounded-md bg-slate-900 px-3 py-1.5 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-slate-600 dark:hover:bg-slate-500">
                                            Buka Workspace
                                        </a>
                                        <a href="{{ route('companies.edit', $company) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('companies.destroy', $company) }}" onsubmit="return confirm('Hapus perusahaan dan seluruh data workspace?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 transition hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/30">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">Belum ada perusahaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($companies->hasPages())
                <div class="border-t border-slate-200 p-5 dark:border-slate-700">
                    {{ $companies->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
