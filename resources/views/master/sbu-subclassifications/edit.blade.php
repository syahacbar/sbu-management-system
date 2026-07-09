<x-layouts.admin title="Edit Subklasifikasi SBU">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">Edit Subklasifikasi SBU</h3>
            <p class="mt-1 text-sm text-slate-500">Perubahan referensi subklasifikasi berlaku secara global.</p>
        </div>

        @include('master.sbu-subclassifications.partials.form', [
            'action' => route('master.subclassifications.update', $subclassification),
            'method' => 'PUT',
            'cancelRoute' => route('master.subclassifications.show', $subclassification),
        ])
    </section>
</x-layouts.admin>
