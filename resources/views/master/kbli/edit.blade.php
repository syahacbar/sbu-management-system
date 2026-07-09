<x-layouts.admin title="Edit KBLI">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">Edit KBLI</h3>
            <p class="mt-1 text-sm text-slate-500">Perubahan referensi KBLI berlaku secara global.</p>
        </div>

        @include('master.kbli.partials.form', [
            'action' => route('master.kbli.update', $kbli),
            'method' => 'PUT',
            'cancelRoute' => route('master.kbli.show', $kbli),
        ])
    </section>
</x-layouts.admin>
