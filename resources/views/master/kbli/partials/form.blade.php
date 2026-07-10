<form method="POST" action="{{ $action }}" class="space-y-5 p-5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="code" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kode KBLI</label>
            <input
                id="code"
                name="code"
                type="text"
                value="{{ old('code', $kbli?->code) }}"
                required
                class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
            >
            @error('code')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sort_order" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Urutan</label>
            <input
                id="sort_order"
                name="sort_order"
                type="number"
                min="0"
                value="{{ old('sort_order', $kbli?->sort_order ?? 0) }}"
                required
                class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
            >
            @error('sort_order')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama KBLI</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $kbli?->name) }}"
            required
            class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
        >
        @error('name')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Keterangan</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
        >{{ old('description', $kbli?->description) }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <fieldset>
        <legend class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</legend>
        <div class="mt-2 flex gap-4">
            <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                <input
                    type="radio"
                    name="is_active"
                    value="1"
                    @checked((string) old('is_active', $kbli?->is_active ?? true) === '1')
                    class="border-slate-300 text-emerald-700 focus:ring-emerald-600 dark:border-slate-600"
                >
                Aktif
            </label>

            <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                <input
                    type="radio"
                    name="is_active"
                    value="0"
                    @checked((string) old('is_active', $kbli?->is_active ?? true) === '0')
                    class="border-slate-300 text-emerald-700 focus:ring-emerald-600 dark:border-slate-600"
                >
                Nonaktif
            </label>
        </div>
        @error('is_active')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </fieldset>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end dark:border-slate-700">
        <a
            href="{{ $cancelRoute }}"
            class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700"
        >
            Batal
        </a>
        <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-700">
            Simpan
        </button>
    </div>
</form>
