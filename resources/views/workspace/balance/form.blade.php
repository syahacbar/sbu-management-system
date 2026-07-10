<x-layouts.admin title="{{ $item ? 'Edit Neraca Keuangan' : 'Tambah Neraca Keuangan' }}" :company="$company">
    <div class="space-y-5">
        <a href="{{ route('companies.workspace.balance.index', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-950 dark:hover:text-white transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Neraca
        </a>

        <section class="max-w-5xl rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
            <div class="border-b border-slate-200 dark:border-slate-700 p-5">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">{{ $item ? 'Edit Neraca Keuangan' : 'Tambah Neraca Keuangan' }}</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Masukkan nominal neraca keuangan untuk pembuktian kapasitas modal dan aset bersih.
                </p>
            </div>

            <form
                method="POST"
                action="{{ $item ? route('companies.workspace.balance.update', [$company, $item]) : route('companies.workspace.balance.store', $company) }}"
                class="p-5 space-y-6"
            >
                @csrf
                @if ($item)
                    @method('PUT')
                @endif

                <!-- Meta Fields -->
                <div class="grid gap-5 md:grid-cols-3 border-b border-slate-100 dark:border-slate-700 pb-5">
                    <div>
                        <label for="year_two" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tahun Pelaporan (Tahun Terbaru) <span class="text-red-500">*</span></label>
                        <input
                            type="number"
                            name="year_two"
                            id="year_two"
                            required
                            min="2000"
                            max="2100"
                            value="{{ old('year_two', $item?->year_two ?: date('Y') - 1) }}"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('year_two') border-red-500 @enderror"
                        >
                        @error('year_two') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="year_one" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tahun Sebelumnya <span class="text-red-500">*</span></label>
                        <input
                            type="number"
                            name="year_one"
                            id="year_one"
                            required
                            min="2000"
                            max="2100"
                            value="{{ old('year_one', $item?->year_one ?: date('Y') - 2) }}"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('year_one') border-red-500 @enderror"
                        >
                        @error('year_one') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="statement_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Tanda Tangan Neraca <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            name="statement_date"
                            id="statement_date"
                            required
                            value="{{ old('statement_date', $item?->statement_date?->format('Y-m-d') ?: date('Y-m-d')) }}"
                            class="mt-2 w-full min-h-10 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-3 py-2 text-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 @error('statement_date') border-red-500 @enderror"
                        >
                        @error('statement_date') <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Dynamic Dynamic Items Table -->
                <div class="space-y-6">
                    @php
                        $sections = [
                            'aktiva' => 'AKTIVA (ASET / HARTA)',
                            'pasiva' => 'PASIVA (KEWAJIBAN & EKUITAS)',
                        ];
                    @endphp

                    @foreach ($sections as $sectionKey => $sectionTitle)
                        <div class="space-y-3">
                            <h4 class="text-sm font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider border-l-4 border-emerald-700 dark:border-emerald-500 pl-3">
                                {{ $sectionTitle }}
                            </h4>

                            <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 font-semibold uppercase text-xs">
                                        <tr>
                                            <th class="px-5 py-3 text-left">Nama Akun / Uraian</th>
                                            <th class="px-5 py-3 text-left w-1/3">Jumlah Tahun Sebelumnya (<span class="lbl-y1">{{ old('year_one', $item?->year_one ?: date('Y') - 2) }}</span>)</th>
                                            <th class="px-5 py-3 text-left w-1/3">Jumlah Tahun Pelaporan (<span class="lbl-y2">{{ old('year_two', $item?->year_two ?: date('Y') - 1) }}</span>)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @php
                                            $itemsInSection = $masterItems->where('section', $sectionKey);
                                            $groups = $itemsInSection->pluck('group_name')->unique();
                                        @endphp

                                        @foreach ($groups as $group)
                                            <tr class="bg-slate-100/50 dark:bg-slate-700/50 font-bold text-slate-600 dark:text-slate-400 text-xs tracking-wider">
                                                <td colspan="3" class="px-5 py-2 uppercase">
                                                    Kelompok: {{ str_replace('_', ' ', $group) }}
                                                </td>
                                            </tr>

                                            @foreach ($itemsInSection->where('group_name', $group) as $masterItem)
                                                @php
                                                    $valRecord = $values->get($masterItem->id);
                                                    $amountOne = $valRecord ? $valRecord->year_one_amount : 0;
                                                    $amountTwo = $valRecord ? $valRecord->year_two_amount : 0;
                                                @endphp

                                                <tr class="{{ $masterItem->is_calculated ? 'bg-emerald-50/40 dark:bg-emerald-900/20 font-bold text-emerald-900 dark:text-emerald-300' : '' }}">
                                                    <td class="px-5 py-3">
                                                        <p class="{{ $masterItem->is_calculated ? 'font-bold' : 'text-slate-800 dark:text-slate-200 font-medium' }}">
                                                            {{ $masterItem->name }}
                                                        </p>
                                                        @if ($masterItem->description)
                                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 leading-relaxed">{{ $masterItem->description }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3">
                                                        @if ($masterItem->is_calculated)
                                                            <!-- Calculated Field (Output only) -->
                                                            <span class="calc-label font-mono font-bold text-emerald-800 dark:text-emerald-400 text-sm" 
                                                                  id="calc-y1-{{ $masterItem->code }}" 
                                                                  data-code="{{ $masterItem->code }}">
                                                                Rp {{ number_format($amountOne, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <!-- Input Field -->
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="text-slate-400 dark:text-slate-500 font-semibold text-xs">Rp</span>
                                                                <input
                                                                    type="text"
                                                                    name="items[{{ $masterItem->id }}][year_one_amount]"
                                                                    value="{{ old('items.'.$masterItem->id.'.year_one_amount', $amountOne) }}"
                                                                    data-group="{{ $masterItem->group_name }}"
                                                                    data-code="{{ $masterItem->code }}"
                                                                    class="input-y1 min-h-9 w-full rounded border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-2 py-1 text-sm outline-none transition focus:border-emerald-600 focus:ring-1 focus:ring-emerald-100 font-mono text-right"
                                                                >
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3">
                                                        @if ($masterItem->is_calculated)
                                                            <!-- Calculated Field (Output only) -->
                                                            <span class="calc-label font-mono font-bold text-emerald-800 dark:text-emerald-400 text-sm" 
                                                                  id="calc-y2-{{ $masterItem->code }}" 
                                                                  data-code="{{ $masterItem->code }}">
                                                                Rp {{ number_format($amountTwo, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <!-- Input Field -->
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="text-slate-400 dark:text-slate-500 font-semibold text-xs">Rp</span>
                                                                <input
                                                                    type="text"
                                                                    name="items[{{ $masterItem->id }}][year_two_amount]"
                                                                    value="{{ old('items.'.$masterItem->id.'.year_two_amount', $amountTwo) }}"
                                                                    data-group="{{ $masterItem->group_name }}"
                                                                    data-code="{{ $masterItem->code }}"
                                                                    class="input-y2 min-h-9 w-full rounded border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 px-2 py-1 text-sm outline-none transition focus:border-emerald-600 focus:ring-1 focus:ring-emerald-100 font-mono text-right"
                                                                >
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Row (Kekayaan Bersih / Modal) -->
                <div class="rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-900/20 p-5 space-y-3">
                    <h4 class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">Hasil Ringkasan Perhitungan Kekayaan Bersih</h4>
                    <div class="grid gap-5 md:grid-cols-2 font-bold text-slate-800 dark:text-slate-200">
                        <div class="flex items-center justify-between bg-white dark:bg-slate-800 border border-emerald-100 dark:border-emerald-800 rounded-md p-4 shadow-sm">
                            <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">Kekayaan Bersih (<span class="lbl-y1">Tahun 1</span>)</span>
                            <span class="font-mono text-lg text-emerald-800 dark:text-emerald-400" id="summary-net-y1">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between bg-white dark:bg-slate-800 border border-emerald-100 dark:border-emerald-800 rounded-md p-4 shadow-sm">
                            <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">Kekayaan Bersih (<span class="lbl-y2">Tahun 2</span>)</span>
                            <span class="font-mono text-lg text-emerald-800 dark:text-emerald-400" id="summary-net-y2">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-700 pt-5">
                    <a href="{{ route('companies.workspace.balance.index', $company) }}" class="rounded-md border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-100 dark:hover:bg-slate-600 font-medium">
                        Batal
                    </a>
                    <button type="submit" class="rounded-md bg-emerald-700 dark:bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 dark:hover:bg-emerald-700 font-medium">
                        Simpan Neraca
                    </button>
                </div>
            </form>
        </section>
    </div>

    <!-- Client-side Calculation & Formatting Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const yearOneInput = document.getElementById('year_one');
            const yearTwoInput = document.getElementById('year_two');
            const lblsY1 = document.querySelectorAll('.lbl-y1');
            const lblsY2 = document.querySelectorAll('.lbl-y2');

            // Dynamic Year labels
            function updateYearLabels() {
                lblsY1.forEach(el => el.textContent = yearOneInput.value || 'Tahun 1');
                lblsY2.forEach(el => el.textContent = yearTwoInput.value || 'Tahun 2');
            }
            yearOneInput.addEventListener('input', updateYearLabels);
            yearTwoInput.addEventListener('input', updateYearLabels);

            // Calculation Logic
            function formatRupiah(value) {
                return 'Rp ' + Math.round(value).toLocaleString('id-ID');
            }

            function parseAmount(val) {
                if (!val) return 0;
                let clean = val.replace(/[^0-9,-]/g, '');
                clean = clean.replace(',', '.');
                return parseFloat(clean) || 0;
            }

            function calculateTotals() {
                // Year 1 sums
                let lancarY1 = 0;
                let tetapY1 = 0;
                let kewajibanY1 = 0;
                let ekuitasY1 = 0;

                // Year 2 sums
                let lancarY2 = 0;
                let tetapY2 = 0;
                let kewajibanY2 = 0;
                let ekuitasY2 = 0;

                document.querySelectorAll('.input-y1').forEach(input => {
                    const group = input.getAttribute('data-group');
                    const val = parseAmount(input.value);
                    if (group === 'lancar') lancarY1 += val;
                    if (group === 'tetap') tetapY1 += val;
                    if (group === 'kewajiban') kewajibanY1 += val;
                    if (group === 'ekuitas') ekuitasY1 += val;
                });

                document.querySelectorAll('.input-y2').forEach(input => {
                    const group = input.getAttribute('data-group');
                    const val = parseAmount(input.value);
                    if (group === 'lancar') lancarY2 += val;
                    if (group === 'tetap') tetapY2 += val;
                    if (group === 'kewajiban') kewajibanY2 += val;
                    if (group === 'ekuitas') ekuitasY2 += val;
                });

                // Update Calculated items
                const totalAktivaLancarY1 = lancarY1;
                const totalAktivaLancarY2 = lancarY2;
                const totalAktivaTetapY1 = tetapY1;
                const totalAktivaTetapY2 = tetapY2;

                const totalAktivaY1 = totalAktivaLancarY1 + totalAktivaTetapY1;
                const totalAktivaY2 = totalAktivaLancarY2 + totalAktivaTetapY2;

                const totalKewajibanY1 = kewajibanY1;
                const totalKewajibanY2 = kewajibanY2;

                const totalEkuitasY1 = ekuitasY1;
                const totalEkuitasY2 = ekuitasY2;

                const netY1 = totalAktivaY1 - totalKewajibanY1;
                const netY2 = totalAktivaY2 - totalKewajibanY2;

                // Populate Labels
                updateLabel('total_aktiva_lancar', totalAktivaLancarY1, totalAktivaLancarY2);
                updateLabel('total_aktiva_tetap', totalAktivaTetapY1, totalAktivaTetapY2);
                updateLabel('total_aktiva', totalAktivaY1, totalAktivaY2);
                updateLabel('total_kewajiban', totalKewajibanY1, totalKewajibanY2);
                updateLabel('total_ekuitas', totalEkuitasY1, totalEkuitasY2);
                
                // Summaries
                document.getElementById('summary-net-y1').textContent = formatRupiah(netY1);
                document.getElementById('summary-net-y2').textContent = formatRupiah(netY2);
            }

            function updateLabel(code, val1, val2) {
                const el1 = document.getElementById('calc-y1-' + code);
                const el2 = document.getElementById('calc-y2-' + code);
                if (el1) el1.textContent = formatRupiah(val1);
                if (el2) el2.textContent = formatRupiah(val2);
            }

            // Input formatters
            function setupInputFormatting(input) {
                input.addEventListener('focus', function() {
                    // strip Rp and dots for easier typing
                    let clean = this.value.replace(/[^0-9,-]/g, '');
                    this.value = clean;
                });

                input.addEventListener('blur', function() {
                    const val = parseAmount(this.value);
                    this.value = val.toLocaleString('id-ID');
                    calculateTotals();
                });

                // Init load formatting
                const val = parseAmount(input.value);
                input.value = val.toLocaleString('id-ID');
            }

            document.querySelectorAll('.input-y1, .input-y2').forEach(setupInputFormatting);
            
            // Trigger first calculation
            calculateTotals();
            updateYearLabels();
        });
    </script>
</x-layouts.admin>
