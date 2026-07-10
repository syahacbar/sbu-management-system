<x-layouts.admin title="{{ $item ? 'Edit Peralatan' : 'Tambah Peralatan' }}" :company="$company">
    <div class="space-y-5">
        <a href="{{ route('companies.workspace.equipment.index', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-950 dark:hover:text-white transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Peralatan
        </a>

        <section class="max-w-4xl rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
            <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">{{ $item ? 'Edit Peralatan' : 'Tambah Peralatan' }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Masukkan detail alat utama beserta status bukti kepemilikan (invoice/sewa).
                </p>
            </div>

            <form
                method="POST"
                action="{{ $item ? route('companies.workspace.equipment.update', [$company, $item]) : route('companies.workspace.equipment.store', $company) }}"
                class="p-5 space-y-6"
            >
                @csrf
                @if ($item)
                    @method('PUT')
                @endif

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700 pb-2">Pilih Master Referensi</h4>

                    <!-- Pilihan Master Equipment -->
                    <div>
                        <label for="master_equipment_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Referensi Peralatan Master</label>
                        <select
                            name="master_equipment_id"
                            id="master_equipment_id"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('master_equipment_id') border-red-500 @enderror"
                        >
                            <option value="">-- Isi Manual / Pilih Dari Master --</option>
                            @foreach ($masterEquipments as $master)
                                <option
                                    value="{{ $master->id }}"
                                    data-name="{{ $master->name }}"
                                    data-specification="{{ $master->specification }}"
                                    data-category="{{ $master->category }}"
                                    data-unit="{{ $master->unit }}"
                                    @selected((string) old('master_equipment_id', $item?->master_equipment_id) === (string) $master->id)
                                >
                                    [{{ strtoupper($master->category) }}] {{ $master->code }} - {{ $master->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('master_equipment_id') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Memilih referensi master akan otomatis mengisi isian formulir di bawah ini.</p>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700 pb-2">Spesifikasi & Detil Peralatan</h4>

                    <div class="grid gap-5 md:grid-cols-3">
                        <!-- Kategori Alat -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kategori SBU <span class="text-red-500">*</span></label>
                            <select
                                name="category"
                                id="category"
                                required
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('category') border-red-500 @enderror"
                            >
                                <option value="">Pilih Kategori</option>
                                <option value="bg" @selected(old('category', $item?->category) === 'bg')>BG - Bangunan Gedung</option>
                                <option value="bs" @selected(old('category', $item?->category) === 'bs')>BS - Bangunan Sipil</option>
                            </select>
                            @error('category') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nama Peralatan -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Peralatan <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                required
                                value="{{ old('name', $item?->name) }}"
                                placeholder="Masukkan nama alat"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('name') border-red-500 @enderror"
                            >
                            @error('name') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        <!-- Spesifikasi -->
                        <div class="md:col-span-2">
                            <label for="specification" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Spesifikasi Alat</label>
                            <input
                                type="text"
                                name="specification"
                                id="specification"
                                value="{{ old('specification', $item?->specification) }}"
                                placeholder="Kapasitas, merk, HP, tipe, dll."
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('specification') border-red-500 @enderror"
                            >
                            @error('specification') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label for="unit" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Satuan Alat <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                name="unit"
                                id="unit"
                                required
                                value="{{ old('unit', $item?->unit ?: 'Unit') }}"
                                placeholder="Contoh: Unit, Set"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('unit') border-red-500 @enderror"
                            >
                            @error('unit') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <!-- Jumlah -->
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Jumlah Unit <span class="text-red-500">*</span></label>
                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                required
                                min="1"
                                value="{{ old('quantity', $item?->quantity ?: 1) }}"
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('quantity') border-red-500 @enderror"
                            >
                            @error('quantity') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status Kepemilikan -->
                        <div>
                            <label for="ownership_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Status Kepemilikan <span class="text-red-500">*</span></label>
                            <select
                                name="ownership_status"
                                id="ownership_status"
                                required
                                class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('ownership_status') border-red-500 @enderror"
                            >
                                <option value="">Pilih Status</option>
                                @foreach ($ownershipStatuses as $val => $label)
                                    <option value="{{ $val }}" @selected(old('ownership_status', $item?->ownership_status) === $val)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ownership_status') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Keterangan / Catatan -->
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Catatan Tambahan</label>
                        <textarea
                            name="notes"
                            id="notes"
                            rows="2"
                            placeholder="Nomor invoice, merk, nomor mesin, atau detail sewa..."
                            class="mt-2 w-full rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('notes') border-red-500 @enderror"
                        >{{ old('notes', $item?->notes) }}</textarea>
                        @error('notes') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-700 pt-5">
                    <a href="{{ route('companies.workspace.equipment.index', $company) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-100 dark:hover:bg-slate-600 font-medium">
                        Batal
                    </a>
                    <button type="submit" class="rounded-md bg-emerald-700 dark:bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700 font-medium">
                        Simpan Peralatan
                    </button>
                </div>
            </form>
        </section>
    </div>

    <!-- Client-side script to auto-fill fields when master_equipment is chosen -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const masterSelect = document.getElementById('master_equipment_id');
            const nameInput = document.getElementById('name');
            const specInput = document.getElementById('specification');
            const catSelect = document.getElementById('category');
            const unitInput = document.getElementById('unit');

            masterSelect.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                if (option && option.value) {
                    nameInput.value = option.getAttribute('data-name') || '';
                    specInput.value = option.getAttribute('data-specification') || '';
                    catSelect.value = option.getAttribute('data-category') || '';
                    unitInput.value = option.getAttribute('data-unit') || 'Unit';
                }
            });
        });
    </script>
</x-layouts.admin>
