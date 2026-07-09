<x-layouts.admin title="Direktur / PJBU" :company="$company">
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-2">
            <!-- Directors Panel -->
            <section class="rounded-lg border border-slate-200 bg-white shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-950">Daftar Direktur</h3>
                            <p class="mt-0.5 text-xs text-slate-500">Anggota direksi yang terdaftar pada perusahaan.</p>
                        </div>
                        <a href="{{ route('companies.workspace.directors.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                            Tambah Direktur
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-xs">
                            <thead class="bg-slate-50 text-left font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Nama / NIK</th>
                                    <th class="px-4 py-3">Jabatan</th>
                                    <th class="px-4 py-3">NPWP</th>
                                    <th class="px-4 py-3">Utama</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($directors as $item)
                                    <tr>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-slate-950">{{ $item->name }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400 font-mono">NIK: {{ $item->nik }}</p>
                                            @if ($item->birthplace)
                                                <p class="text-[10px] text-slate-400">Lahir: {{ $item->birthplace }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-slate-700">{{ $item->position }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400">{{ $item->email ?: '-' }}</p>
                                        </td>
                                        <td class="px-4 py-3.5 font-mono text-slate-600">{{ $item->npwp ?: '-' }}</td>
                                        <td class="px-4 py-3.5">
                                            @if ($item->is_main)
                                                <span class="rounded bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[9px] font-bold text-emerald-700 uppercase">
                                                    Utama
                                                </span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <div class="flex justify-end gap-1.5">
                                                <a href="{{ route('companies.workspace.directors.edit', [$company, $item]) }}" class="rounded border border-slate-300 px-2 py-1 text-[10px] font-semibold text-slate-700 transition hover:bg-slate-100">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('companies.workspace.directors.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus direktur ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded border border-red-200 px-2 py-1 text-[10px] font-semibold text-red-700 transition hover:bg-red-50">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada data Direktur.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- PJBU Panel -->
            <section class="rounded-lg border border-slate-200 bg-white shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-950">Daftar Penanggung Jawab Badan Usaha (PJBU)</h3>
                            <p class="mt-0.5 text-xs text-slate-500">PJBU yang terdaftar untuk keperluan sertifikasi.</p>
                        </div>
                        <a href="{{ route('companies.workspace.pjbus.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                            Tambah PJBU
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-xs">
                            <thead class="bg-slate-50 text-left font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Nama / NIK</th>
                                    <th class="px-4 py-3">Jabatan</th>
                                    <th class="px-4 py-3">NPWP</th>
                                    <th class="px-4 py-3">Utama</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse ($pjbus as $item)
                                    <tr>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-slate-950">{{ $item->name }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400 font-mono">NIK: {{ $item->nik }}</p>
                                            @if ($item->birthplace)
                                                <p class="text-[10px] text-slate-400">Lahir: {{ $item->birthplace }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-slate-700">{{ $item->position }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400">{{ $item->email ?: '-' }}</p>
                                        </td>
                                        <td class="px-4 py-3.5 font-mono text-slate-600">{{ $item->npwp ?: '-' }}</td>
                                        <td class="px-4 py-3.5">
                                            @if ($item->is_main)
                                                <span class="rounded bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[9px] font-bold text-emerald-700 uppercase">
                                                    Utama
                                                </span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <div class="flex justify-end gap-1.5">
                                                <a href="{{ route('companies.workspace.pjbus.edit', [$company, $item]) }}" class="rounded border border-slate-300 px-2 py-1 text-[10px] font-semibold text-slate-700 transition hover:bg-slate-100">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('companies.workspace.pjbus.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus PJBU ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded border border-red-200 px-2 py-1 text-[10px] font-semibold text-red-700 transition hover:bg-red-50">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada data PJBU.</td>
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
