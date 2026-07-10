<x-layouts.admin title="Detail Skema SBU">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">{{ session('status') }}</div>
        @endif

        <section class="max-w-4xl rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">{{ $scheme->scheme_code }} - {{ $scheme->scheme_name }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Detail referensi global Skema SBU.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('master.schemes.edit', $scheme) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">Edit</a>
                    <a href="{{ route('master.schemes.index') }}" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-slate-600 dark:hover:bg-slate-500">Kembali</a>
                </div>
            </div>

            <dl class="grid gap-0 divide-y divide-slate-200 text-sm dark:divide-slate-700">
                @foreach ([
                    'Kode Skema' => $scheme->scheme_code,
                    'Nama Skema' => $scheme->scheme_name,
                    'KBLI' => $scheme->kbli?->code.' - '.$scheme->kbli?->name,
                    'Klasifikasi' => $scheme->classification?->code.' - '.$scheme->classification?->name,
                    'Subklasifikasi' => $scheme->subclassification?->code.' - '.$scheme->subclassification?->name,
                    'Kualifikasi' => $scheme->qualification,
                    'Urutan' => $scheme->sort_order,
                    'Keterangan' => $scheme->description ?: '-',
                    'Dibuat' => $scheme->created_at?->format('d/m/Y H:i'),
                    'Diperbarui' => $scheme->updated_at?->format('d/m/Y H:i'),
                ] as $label => $value)
                    <div class="grid gap-2 p-5 sm:grid-cols-3">
                        <dt class="font-medium text-slate-500 dark:text-slate-400">{{ $label }}</dt>
                        <dd class="text-slate-800 dark:text-slate-200 sm:col-span-2">{{ $value }}</dd>
                    </div>
                @endforeach
                <div class="grid gap-2 p-5 sm:grid-cols-3">
                    <dt class="font-medium text-slate-500 dark:text-slate-400">Status</dt>
                    <dd class="sm:col-span-2">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $scheme->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                            {{ $scheme->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </dd>
                </div>
            </dl>
        </section>
    </div>
</x-layouts.admin>
