<x-layouts.admin title="KBLI">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Master KBLI</h3>
                    <p class="mt-1 text-sm text-slate-500">Referensi global KBLI untuk seluruh workspace perusahaan.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('master.kbli.import-form') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <svg class="mr-1.5 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                        </svg>
                        Impor Excel
                    </a>
                    <a href="{{ route('master.kbli.create') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                        Tambah KBLI
                    </a>
                </div>
            </div>

            <div class="border-b border-slate-200 p-5">
                <form method="GET" action="{{ route('master.kbli.index') }}" class="grid gap-3 lg:grid-cols-[1fr_180px_auto]">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari kode atau nama KBLI"
                        class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >

                    <select name="status" class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                    </select>

                    <button type="submit" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Urutan</th>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Keterangan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($kblis as $kbli)
                            <tr>
                                <td class="px-5 py-4 text-slate-600">{{ $kbli->sort_order }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-950">{{ $kbli->code }}</td>
                                <td class="px-5 py-4 text-slate-800">{{ $kbli->name }}</td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $kbli->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $kbli->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="max-w-md px-5 py-4 text-slate-600">{{ $kbli->description ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('master.kbli.show', $kbli) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                            Detail
                                        </a>
                                        <a href="{{ route('master.kbli.edit', $kbli) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('master.kbli.destroy', $kbli) }}" onsubmit="return confirm('Hapus data KBLI ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 transition hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada data KBLI.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kblis->hasPages())
                <div class="border-t border-slate-200 p-5">
                    {{ $kblis->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
