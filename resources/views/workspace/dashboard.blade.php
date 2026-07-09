<x-layouts.admin :title="'Workspace - '.$company->name" :company="$company">
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $stat['value'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="rounded-lg border border-dashed border-slate-300 bg-white p-8">
            <h3 class="text-base font-semibold text-slate-950">Dashboard Workspace</h3>
            <p class="mt-2 text-sm text-slate-500">Semua ringkasan di halaman ini dihitung dari data milik {{ $company->name }} saja.</p>
        </section>
    </div>
</x-layouts.admin>
