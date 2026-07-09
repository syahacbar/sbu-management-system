<x-layouts.admin :title="$item ? 'Edit '.$resource['title'] : 'Tambah '.$resource['title']">
    <section class="max-w-3xl rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-200 p-5">
            <h3 class="text-lg font-semibold text-slate-950">
                {{ $item ? 'Edit '.$resource['title'] : 'Tambah '.$resource['title'] }}
            </h3>
            <p class="mt-1 text-sm text-slate-500">Isi data referensi global. Data ini tidak bergantung pada perusahaan.</p>
        </div>

        <form
            method="POST"
            action="{{ $item ? route($resource['route'].'.update', $item) : route($resource['route'].'.store') }}"
            class="space-y-5 p-5"
        >
            @csrf
            @if ($item)
                @method('PUT')
            @endif

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700">Kode</label>
                    <input
                        id="code"
                        name="code"
                        type="text"
                        value="{{ old('code', $item?->code) }}"
                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-slate-700">Urutan</label>
                    <input
                        id="sort_order"
                        name="sort_order"
                        type="number"
                        min="0"
                        value="{{ old('sort_order', $item?->sort_order ?? 0) }}"
                        required
                        class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                    >
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $item?->name) }}"
                    required
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700">Keterangan</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"
                >{{ old('description', $item?->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <fieldset>
                <legend class="block text-sm font-medium text-slate-700">Status</legend>
                <div class="mt-2 flex gap-4">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input
                            type="radio"
                            name="is_active"
                            value="1"
                            @checked((string) old('is_active', $item?->is_active ?? true) === '1')
                            class="border-slate-300 text-emerald-700 focus:ring-emerald-600"
                        >
                        Aktif
                    </label>

                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input
                            type="radio"
                            name="is_active"
                            value="0"
                            @checked((string) old('is_active', $item?->is_active ?? true) === '0')
                            class="border-slate-300 text-emerald-700 focus:ring-emerald-600"
                        >
                        Nonaktif
                    </label>
                </div>
                @error('is_active')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </fieldset>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                <a
                    href="{{ route($resource['route'].'.index') }}"
                    class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Batal
                </a>
                <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                    Simpan
                </button>
            </div>
        </form>
    </section>
</x-layouts.admin>
