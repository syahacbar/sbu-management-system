<x-layouts.admin title="Tambah KBLI">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">Tambah KBLI</h3>
            <p class="mt-1 text-sm text-slate-500">Kode KBLI wajib unik dan dapat digunakan oleh semua workspace perusahaan.</p>
        </div>

        @include('master.kbli.partials.form', [
            'action' => route('master.kbli.store'),
            'method' => 'POST',
            'cancelRoute' => route('master.kbli.index'),
        ])
    </section>
</x-layouts.admin>
