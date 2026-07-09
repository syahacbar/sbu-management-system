<x-layouts.admin title="Dashboard">
    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">{{ $stat['note'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center">
            <h3 class="text-base font-semibold text-slate-950">Dashboard tahap awal</h3>
            <p class="mt-2 text-sm text-slate-500">Fondasi layout sudah disiapkan untuk Master Referensi Global, Workspace Perusahaan, dan Pengaturan.</p>
        </section>
    </div>
</x-layouts.admin>
