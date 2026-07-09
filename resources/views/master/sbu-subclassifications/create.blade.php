<x-layouts.admin title="Tambah Subklasifikasi SBU">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">Tambah Subklasifikasi SBU</h3>
            <p class="mt-1 text-sm text-slate-500">Pilih klasifikasi induk sebelum menyimpan subklasifikasi.</p>
        </div>

        @include('master.sbu-subclassifications.partials.form', [
            'action' => route('master.subclassifications.store'),
            'method' => 'POST',
            'cancelRoute' => route('master.subclassifications.index'),
        ])
    </section>
</x-layouts.admin>
