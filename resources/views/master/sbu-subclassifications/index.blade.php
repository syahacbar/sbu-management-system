<x-layouts.admin title="Subklasifikasi SBU">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-lg border border-slate-200 bg-white">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Master Subklasifikasi SBU</h3>
                    <p class="mt-1 text-sm text-slate-500">Referensi global subklasifikasi SBU berdasarkan klasifikasi.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('master.subclassifications.import-form') }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Import Massal
                    </a>
                    <a href="{{ route('master.subclassifications.create') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                        Tambah Subklasifikasi
                    </a>
                </div>
            </div>

            <div class="border-b border-slate-200 p-5">
                <form method="GET" action="{{ route('master.subclassifications.index') }}" class="grid gap-3 xl:grid-cols-[1fr_220px_180px_auto]">
                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari kode atau nama subklasifikasi"
                        class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >

                    <select name="classification_id" class="min-h-10 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        <option value="">Semua Klasifikasi</option>
                        @foreach ($classifications as $classification)
                            <option value="{{ $classification->id }}" @selected((string) $classificationId === (string) $classification->id)>
                                {{ $classification->code }} - {{ $classification->name }}
                            </option>
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
                            <th class="px-5 py-3">Kode Klasifikasi</th>
                            <th class="px-5 py-3">Kode Subklasifikasi</th>
                            <th class="px-5 py-3">Nama Subklasifikasi</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($subclassifications as $subclassification)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-950">{{ $subclassification->classification?->code }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $subclassification->classification?->name }}</p>
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-950">{{ $subclassification->code }}</td>
                                <td class="px-5 py-4">
                                    <p class="text-slate-800">{{ $subclassification->name }}</p>
                                    <p class="mt-1 max-w-md text-xs text-slate-500">{{ $subclassification->description ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $subclassification->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $subclassification->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('master.subclassifications.show', $subclassification) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                            Detail
                                        </a>
                                        <a href="{{ route('master.subclassifications.edit', $subclassification) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('master.subclassifications.destroy', $subclassification) }}" onsubmit="return confirm('Hapus data Subklasifikasi SBU ini?')">
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
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">Belum ada data Subklasifikasi SBU.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($subclassifications->hasPages())
                <div class="border-t border-slate-200 p-5">
                    {{ $subclassifications->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
