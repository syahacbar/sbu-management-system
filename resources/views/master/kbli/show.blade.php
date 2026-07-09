<x-layouts.admin title="Detail KBLI">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">{{ $kbli->code }} - {{ $kbli->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">Detail referensi global KBLI.</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('master.kbli.edit', $kbli) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Edit
                    </a>
                    <a href="{{ route('master.kbli.index') }}" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                        Kembali
                    </a>
                </div>
            </div>

            <dl class="grid gap-0 divide-y divide-slate-200 text-sm">
                <div class="grid gap-2 p-5 sm:grid-cols-3">
                    <dt class="font-medium text-slate-500">Kode</dt>
                    <dd class="sm:col-span-2 font-semibold text-slate-950">{{ $kbli->code }}</dd>
                </div>
                <div class="grid gap-2 p-5 sm:grid-cols-3">
                    <dt class="font-medium text-slate-500">Nama</dt>
                    <dd class="sm:col-span-2 text-slate-800">{{ $kbli->name }}</dd>
                </div>
                <div class="grid gap-2 p-5 sm:grid-cols-3">
                    <dt class="font-medium text-slate-500">Status</dt>
                    <dd class="sm:col-span-2">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $kbli->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $kbli->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </dd>
                </div>
                <div class="grid gap-2 p-5 sm:grid-cols-3">
                    <dt class="font-medium text-slate-500">Urutan</dt>
                    <dd class="sm:col-span-2 text-slate-800">{{ $kbli->sort_order }}</dd>
                </div>
                <div class="grid gap-2 p-5 sm:grid-cols-3">
                    <dt class="font-medium text-slate-500">Keterangan</dt>
                    <dd class="sm:col-span-2 text-slate-800">{{ $kbli->description ?: '-' }}</dd>
                </div>
                <div class="grid gap-2 p-5 sm:grid-cols-3">
                    <dt class="font-medium text-slate-500">Dibuat</dt>
                    <dd class="sm:col-span-2 text-slate-800">{{ $kbli->created_at?->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="grid gap-2 p-5 sm:grid-cols-3">
                    <dt class="font-medium text-slate-500">Diperbarui</dt>
                    <dd class="sm:col-span-2 text-slate-800">{{ $kbli->updated_at?->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </section>
    </div>
</x-layouts.admin>
