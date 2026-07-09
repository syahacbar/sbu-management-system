<form method="POST" action="{{ $action }}" class="space-y-5 p-5">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="master_kbli_id" class="block text-sm font-medium text-slate-700">KBLI</label>
        <select id="master_kbli_id" name="master_kbli_id" required class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            <option value="">Pilih KBLI</option>
            @foreach ($kblis as $kbli)
                <option value="{{ $kbli->id }}" @selected((string) old('master_kbli_id', $scheme?->master_kbli_id) === (string) $kbli->id)>
                    {{ $kbli->code }} - {{ $kbli->name }}
                </option>
            @endforeach
        </select>
        @error('master_kbli_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <label for="master_sbu_classification_id" class="block text-sm font-medium text-slate-700">Klasifikasi</label>
            <select id="master_sbu_classification_id" name="master_sbu_classification_id" required class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                <option value="">Pilih klasifikasi</option>
                @foreach ($classifications as $classification)
                    <option value="{{ $classification->id }}" @selected((string) old('master_sbu_classification_id', $scheme?->master_sbu_classification_id) === (string) $classification->id)>
                        {{ $classification->code }} - {{ $classification->name }}
                    </option>
                @endforeach
            </select>
            @error('master_sbu_classification_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="master_sbu_subclassification_id" class="block text-sm font-medium text-slate-700">Subklasifikasi</label>
            <select id="master_sbu_subclassification_id" name="master_sbu_subclassification_id" required class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                <option value="">Pilih subklasifikasi</option>
                @foreach ($subclassifications as $subclassification)
                    <option value="{{ $subclassification->id }}" @selected((string) old('master_sbu_subclassification_id', $scheme?->master_sbu_subclassification_id) === (string) $subclassification->id)>
                        {{ $subclassification->classification?->code }} / {{ $subclassification->code }} - {{ $subclassification->name }}
                    </option>
                @endforeach
            </select>
            @error('master_sbu_subclassification_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-3">
        <div>
            <label for="scheme_code" class="block text-sm font-medium text-slate-700">Kode Skema</label>
            <input id="scheme_code" name="scheme_code" type="text" value="{{ old('scheme_code', $scheme?->scheme_code) }}" required class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            @error('scheme_code') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="qualification" class="block text-sm font-medium text-slate-700">Kualifikasi</label>
            <select id="qualification" name="qualification" required class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                <option value="">Pilih kualifikasi</option>
                @foreach ($qualifications as $qualification)
                    <option value="{{ $qualification }}" @selected(old('qualification', $scheme?->qualification) === $qualification)>{{ $qualification }}</option>
                @endforeach
            </select>
            @error('qualification') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="sort_order" class="block text-sm font-medium text-slate-700">Urutan</label>
            <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $scheme?->sort_order ?? 0) }}" required class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            @error('sort_order') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="scheme_name" class="block text-sm font-medium text-slate-700">Nama Skema</label>
        <input id="scheme_name" name="scheme_name" type="text" value="{{ old('scheme_name', $scheme?->scheme_name) }}" required class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
        @error('scheme_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-slate-700">Keterangan</label>
        <textarea id="description" name="description" rows="4" class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">{{ old('description', $scheme?->description) }}</textarea>
        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <fieldset>
        <legend class="block text-sm font-medium text-slate-700">Status</legend>
        <div class="mt-2 flex gap-4">
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="radio" name="is_active" value="1" @checked((string) old('is_active', $scheme?->is_active ?? true) === '1') class="border-slate-300 text-emerald-700 focus:ring-emerald-600">
                Aktif
            </label>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="radio" name="is_active" value="0" @checked((string) old('is_active', $scheme?->is_active ?? true) === '0') class="border-slate-300 text-emerald-700 focus:ring-emerald-600">
                Nonaktif
            </label>
        </div>
        @error('is_active') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </fieldset>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
        <a href="{{ $cancelRoute }}" class="inline-flex items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            Batal
        </a>
        <button type="submit" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
            Simpan
        </button>
    </div>
</form>
