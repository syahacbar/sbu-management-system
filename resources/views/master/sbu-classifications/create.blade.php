<x-layouts.admin title="Tambah Klasifikasi SBU">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-slate-200 p-5 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Tambah Klasifikasi SBU</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kode klasifikasi wajib unik dan berlaku global.</p>
        </div>

        @include('master.sbu-classifications.partials.form', [
            'action' => route('master.classifications.store'),
            'method' => 'POST',
            'cancelRoute' => route('master.classifications.index'),
        ])
    </section>
</x-layouts.admin>
