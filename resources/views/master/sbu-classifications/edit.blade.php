<x-layouts.admin title="Edit Klasifikasi SBU">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">Edit Klasifikasi SBU</h3>
            <p class="mt-1 text-sm text-slate-500">Perubahan klasifikasi berlaku secara global.</p>
        </div>

        @include('master.sbu-classifications.partials.form', [
            'action' => route('master.classifications.update', $classification),
            'method' => 'PUT',
            'cancelRoute' => route('master.classifications.show', $classification),
        ])
    </section>
</x-layouts.admin>
