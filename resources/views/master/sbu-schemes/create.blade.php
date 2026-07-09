<x-layouts.admin title="Tambah Skema SBU">
    <section class="max-w-4xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">Tambah Skema SBU</h3>
            <p class="mt-1 text-sm text-slate-500">Pilih KBLI, klasifikasi, subklasifikasi, dan kualifikasi.</p>
        </div>

        @include('master.sbu-schemes.partials.form', [
            'action' => route('master.schemes.store'),
            'method' => 'POST',
            'cancelRoute' => route('master.schemes.index'),
        ])
    </section>
</x-layouts.admin>
