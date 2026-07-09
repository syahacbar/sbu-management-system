<x-layouts.admin title="Skema SBU">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Master Skema SBU</h3>
                    <p class="mt-1 text-sm text-slate-500">Referensi global skema berdasarkan KBLI, klasifikasi, dan subklasifikasi.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('master.schemes.import-form') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Import Massal
                    </a>
                    <a href="{{ route('master.schemes.create') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                        Tambah Skema
                    </a>
                </div>
            </div>

            <div class="border-b border-slate-200 p-5">
                <form method="GET" action="{{ route('master.schemes.index') }}" class="grid gap-3 xl:grid-cols-[1fr_180px_180px_auto]">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari kode/nama skema, KBLI, atau subklasifikasi" class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                    <select name="qualification" class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Semua Kualifikasi</option>
                        @foreach (\App\Models\MasterSbuScheme::QUALIFICATIONS as $option)
                            <option value="{{ $option }}" @selected($qualification === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
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
                            <th class="px-5 py-3">Skema</th>
                            <th class="px-5 py-3">KBLI</th>
                            <th class="px-5 py-3">Klasifikasi</th>
                            <th class="px-5 py-3">Subklasifikasi</th>
                            <th class="px-5 py-3">Kualifikasi</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($schemes as $scheme)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-950">{{ $scheme->scheme_code }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $scheme->scheme_name }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-700">{{ $scheme->kbli?->code }}</td>
                                <td class="px-5 py-4 text-slate-700">{{ $scheme->classification?->code }}</td>
                                <td class="px-5 py-4 text-slate-700">{{ $scheme->subclassification?->code }}</td>
                                <td class="px-5 py-4 text-slate-700">{{ $scheme->qualification }}</td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $scheme->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $scheme->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('master.schemes.show', $scheme) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Detail</a>
                                        <a href="{{ route('master.schemes.edit', $scheme) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Edit</a>
                                        <form method="POST" action="{{ route('master.schemes.destroy', $scheme) }}" onsubmit="return confirm('Hapus data Skema SBU ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 transition hover:bg-red-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-500">Belum ada data Skema SBU.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($schemes->hasPages())
                <div class="border-t border-slate-200 p-5">{{ $schemes->links() }}</div>
            @endif
        </section>
    </div>
</x-layouts.admin>
