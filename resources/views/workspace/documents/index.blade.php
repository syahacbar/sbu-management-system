<x-layouts.admin title="Dokumen Pendukung" :company="$company">
    <div class="space-y-5">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        @if (!$activeApplication)
            <div class="rounded-md border border-amber-200 bg-amber-50 p-5">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-amber-800">Pengajuan SBU Aktif Tidak Ditemukan</h3>
                        <div class="mt-2 text-sm text-amber-700">
                            <p>Perusahaan ini belum memiliki pengajuan SBU yang aktif. Untuk mengelola berkas dokumen pendukung, harap tentukan atau buat pengajuan aktif terlebih dahulu di menu Pengajuan.</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('companies.workspace.applications.index', $company) }}" class="rounded bg-amber-800 px-3 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-amber-900 transition">
                                Buka Menu Pengajuan &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Kelayakan & Filter -->
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                    <p class="text-sm text-slate-600">
                        Terhubung ke Pengajuan Aktif: 
                        <a href="{{ route('companies.workspace.applications.show', [$company, $activeApplication]) }}" class="font-bold text-slate-900 underline font-mono">
                            {{ $activeApplication->application_number }}
                        </a> 
                        ({{ ucfirst($activeApplication->application_type) }})
                    </p>
                </div>
            </div>

            <section class="rounded-lg border border-slate-200 bg-white">
                <div class="flex flex-col gap-4 border-b border-slate-200 p-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950">Daftar Dokumen Pendukung</h3>
                        <p class="mt-1 text-sm text-slate-500">Daftar dokumen persyaratan administrasi perusahaan yang sah dan terverifikasi.</p>
                    </div>

                    <a href="{{ route('companies.workspace.documents.create', $company) }}" class="inline-flex items-center justify-center rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                        Unggah Dokumen Baru
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Jenis Dokumen</th>
                                <th class="px-5 py-3">Nama Berkas</th>
                                <th class="px-5 py-3">Tanggal Dokumen</th>
                                <th class="px-5 py-3">Masa Berlaku</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Catatan</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($documents as $item)
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-900">
                                        {{ $item->document_type }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($item->file_path)
                                            <p class="text-xs text-slate-700 font-mono truncate max-w-[200px]" title="{{ $item->original_filename }}">
                                                {{ $item->original_filename }}
                                            </p>
                                        @else
                                            <span class="text-xs text-slate-400 font-medium italic">Tidak ada berkas</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-slate-600 text-xs">
                                        {{ $item->document_date?->format('d/m/Y') ?: '-' }}
                                    </td>
                                    <td class="px-5 py-4 text-slate-600 text-xs">
                                        @if ($item->expired_at)
                                            <span class="{{ $item->expired_at->isPast() ? 'text-red-600 font-bold' : '' }}">
                                                {{ $item->expired_at->format('d/m/Y') }}
                                                @if ($item->expired_at->isPast())
                                                    <span class="block text-[10px] text-red-500 font-bold uppercase mt-0.5">Kedaluwarsa</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-slate-400">Seumur Hidup / -</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $statusBadge = match($item->status) {
                                                'ada' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'revisi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                default => 'bg-rose-50 text-rose-700 border-rose-200'
                                            };
                                            $statusLabel = match($item->status) {
                                                'ada' => 'Ada / Valid',
                                                'revisi' => 'Perlu Revisi',
                                                default => 'Belum Ada'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded border px-2 py-0.5 text-xs font-bold uppercase {{ $statusBadge }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $item->notes }}">
                                        {{ $item->notes ?: '-' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-1.5">
                                            @if ($item->file_path)
                                                <a href="{{ Storage::url($item->file_path) }}" target="_blank" class="rounded-md border border-slate-300 bg-white px-2 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                                    Lihat
                                                </a>
                                                <a href="{{ route('companies.workspace.documents.download', [$company, $item]) }}" class="rounded-md border border-slate-300 bg-white px-2 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                                    Unduh
                                                </a>
                                            @endif
                                            <a href="{{ route('companies.workspace.documents.edit', [$company, $item]) }}" class="rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('companies.workspace.documents.destroy', [$company, $item]) }}" onsubmit="return confirm('Hapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-red-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-50">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-slate-500 font-medium">
                                        Belum ada dokumen pendukung terunggah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($documents && $documents->hasPages())
                    <div class="border-t border-slate-200 p-5">
                        {{ $documents->links() }}
                    </div>
                @endif
            </section>
        @endif
    </div>
</x-layouts.admin>
