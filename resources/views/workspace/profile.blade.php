<x-layouts.admin title="Profil Perusahaan" :company="$company">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">Profil Perusahaan</h3>
            <p class="mt-1 text-sm text-slate-500">Data profil ini hanya berlaku untuk workspace {{ $company->name }}.</p>
        </div>

        @if (session('status'))
            <div class="mx-5 mt-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @include('workspace.partials.company-form', [
            'action' => route('companies.workspace.profile.update', $company),
            'method' => 'PUT',
            'cancelRoute' => route('companies.workspace.dashboard', $company),
        ])
    </section>
</x-layouts.admin>
