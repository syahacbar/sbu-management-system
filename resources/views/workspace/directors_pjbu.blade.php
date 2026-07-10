<x-layouts.admin title="Direktur / PJBU" :company="$company">
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-sm font-medium text-emerald-800 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-2">
            <!-- Directors Panel -->
            <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex flex-col gap-4 border-b border-slate-200 dark:border-slate-700 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-950 dark:text-white">Daftar Direktur</h3>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Anggota direksi yang terdaftar pada perusahaan.</p>
                        </div>
                        <a href="{{ route('companies.workspace.directors.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 dark:bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700">
                            Tambah Direktur
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-700/50 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                <tr>
                                    <th class="px-4 py-3">Nama / NIK</th>
                                    <th class="px-4 py-3">Jabatan</th>
                                    <th class="px-4 py-3">NPWP</th>
                                    <th class="px-4 py-3">Utama</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                @forelse ($directors as $item)
                                    <tr>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-slate-950 dark:text-white">{{ $item->name }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400 dark:text-slate-500 font-mono">NIK: {{ $item->nik }}</p>
                                            @if ($item->birthplace)
                                                <p class="text-[10px] text-slate-400 dark:text-slate-500">Lahir: {{ $item->birthplace }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $item->position }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400 dark:text-slate-500">{{ $item->email ?: '-' }}</p>
                                        </td>
                                        <td class="px-4 py-3.5 font-mono text-slate-600 dark:text-slate-400">{{ $item->npwp ?: '-' }}</td>
                                        <td class="px-4 py-3.5">
                                            @if ($item->is_main)
                                                <span class="rounded bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 px-2 py-0.5 text-[9px] font-bold text-emerald-700 dark:text-emerald-400 uppercase">
                                                    Utama
                                                </span>
                                            @else
                                                <span class="text-slate-400 dark:text-slate-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <div class="flex justify-end gap-1.5">
                                                <a href="{{ route('companies.workspace.directors.edit', [$company, $item]) }}" class="rounded border border-slate-300 dark:border-slate-600 px-2 py-1 text-[10px] font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-100 dark:hover:bg-slate-700">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('companies.workspace.directors.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus direktur ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded border border-red-200 dark:border-red-800 px-2 py-1 text-[10px] font-semibold text-red-700 dark:text-red-400 transition hover:bg-red-50 dark:hover:bg-red-900/30">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">Belum ada data Direktur.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- PJBU Panel -->
            <section class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex flex-col gap-4 border-b border-slate-200 dark:border-slate-700 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-950 dark:text-white">Daftar Penanggung Jawab Badan Usaha (PJBU)</h3>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">PJBU yang terdaftar untuk keperluan sertifikasi.</p>
                        </div>
                        <a href="{{ route('companies.workspace.pjbus.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 dark:bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700">
                            Tambah PJBU
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-700/50 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                <tr>
                                    <th class="px-4 py-3">Nama / NIK</th>
                                    <th class="px-4 py-3">Jabatan</th>
                                    <th class="px-4 py-3">NPWP</th>
                                    <th class="px-4 py-3">Utama</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                @forelse ($pjbus as $item)
                                    <tr>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-slate-950 dark:text-white">{{ $item->name }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400 dark:text-slate-500 font-mono">NIK: {{ $item->nik }}</p>
                                            @if ($item->birthplace)
                                                <p class="text-[10px] text-slate-400 dark:text-slate-500">Lahir: {{ $item->birthplace }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $item->position }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400 dark:text-slate-500">{{ $item->email ?: '-' }}</p>
                                        </td>
                                        <td class="px-4 py-3.5 font-mono text-slate-600 dark:text-slate-400">{{ $item->npwp ?: '-' }}</td>
                                        <td class="px-4 py-3.5">
                                            @if ($item->is_main)
                                                <span class="rounded bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 px-2 py-0.5 text-[9px] font-bold text-emerald-700 dark:text-emerald-400 uppercase">
                                                    Utama
                                                </span>
                                            @else
                                                <span class="text-slate-400 dark:text-slate-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <div class="flex justify-end gap-1.5">
                                                <a href="{{ route('companies.workspace.pjbus.edit', [$company, $item]) }}" class="rounded border border-slate-300 dark:border-slate-600 px-2 py-1 text-[10px] font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-100 dark:hover:bg-slate-700">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('companies.workspace.pjbus.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus PJBU ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded border border-red-200 dark:border-red-800 px-2 py-1 text-[10px] font-semibold text-red-700 dark:text-red-400 transition hover:bg-red-50 dark:hover:bg-red-900/30">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">Belum ada data PJBU.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-layouts.admin>
