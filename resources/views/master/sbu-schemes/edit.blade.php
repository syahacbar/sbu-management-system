<x-layouts.admin title="Edit Skema SBU">
    <section class="max-w-4xl rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-slate-200 p-5 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Edit Skema SBU</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Perubahan skema berlaku sebagai referensi global.</p>
        </div>

        @include('master.sbu-schemes.partials.form', [
            'action' => route('master.schemes.update', $scheme),
            'method' => 'PUT',
            'cancelRoute' => route('master.schemes.show', $scheme),
        ])
    </section>
</x-layouts.admin>
