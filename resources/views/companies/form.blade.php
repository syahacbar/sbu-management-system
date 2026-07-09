<x-layouts.admin :title="$company ? 'Edit Perusahaan' : 'Tambah Perusahaan'">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">{{ $company ? 'Edit Perusahaan' : 'Tambah Perusahaan' }}</h3>
            <p class="mt-1 text-sm text-slate-500">Data ini menjadi root untuk Workspace Perusahaan.</p>
        </div>

        @include('workspace.partials.company-form', [
            'action' => $company ? route('companies.update', $company) : route('companies.store'),
            'method' => $company ? 'PUT' : 'POST',
            'cancelRoute' => route('companies.index'),
        ])
    </section>
</x-layouts.admin>
