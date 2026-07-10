<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panduan Aplikasi - {{ $appName }}</title>
    @vite(['resources/css/app.css'])
    <style>
        html { scroll-behavior: smooth; }
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            body { font-size: 11pt; color: #000; }
            h2 { font-size: 16pt; margin-top: 24pt; }
            h3 { font-size: 13pt; }
            .print-mt { margin-top: 0 !important; }
        }
        .flow-arrow { color: #94a3b8; }
        .entity-box { transition: box-shadow 0.2s; }
        .entity-box:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">{{ $companyName }}</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-950">Panduan Penggunaan Aplikasi</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $appName }}</p>
            </div>
            <button onclick="window.print()" class="no-print rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                <span class="hidden sm:inline">Cetak / Simpan PDF</span>
                <span class="sm:hidden">Cetak</span>
            </button>
        </div>

        {{-- Layout: TOC + Content --}}
        <div class="lg:flex lg:gap-8">

            {{-- Table of Contents --}}
            <nav class="no-print lg:w-56 lg:shrink-0">
                <div class="lg:sticky lg:top-6 lg:max-h-[calc(100vh-3rem)] lg:overflow-y-auto">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">Daftar Isi</p>
                    <ul class="space-y-1 text-sm">
                        <li><a href="#umum" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">1. Gambaran Umum</a></li>
                        <li><a href="#alur" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">2. Alur Sistem</a></li>
                        <li><a href="#menu" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">3. Struktur Menu</a></li>
                        <li><a href="#relasi" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">4. Relasi Data</a></li>
                        <li><a href="#master" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">5. Master Data</a></li>
                        <li><a href="#workspace" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">6. Workspace Perusahaan</a></li>
                        <li><a href="#dokumen" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">7. Generate Dokumen</a></li>
                        <li><a href="#pengaturan" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">8. Pengaturan</a></li>
                        <li><a href="#import" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">9. Import Data</a></li>
                        <li><a href="#arsip" class="block rounded-md px-3 py-1.5 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">10. Arsip Global</a></li>
                    </ul>
                </div>
            </nav>

            {{-- Main Content --}}
            <div class="mt-8 min-w-0 flex-1 lg:mt-0">

                {{-- 1. Gambaran Umum --}}
                <section id="umum" class="mb-10 scroll-mt-6">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">1. Gambaran Umum Aplikasi</h2>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">
                            <strong>{{ $appName }}</strong> adalah aplikasi berbasis web yang digunakan untuk mengelola proses
                            pengajuan Sertifikasi Badan Usaha (SBU). Aplikasi ini dirancang untuk membantu admin dalam
                            mencatat, memproses, dan mendokumentasikan seluruh tahapan pengajuan SBU mulai dari
                            pendataan perusahaan hingga penerbitan dokumen sertifikat.
                        </p>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Fitur utama meliputi pengelolaan data master (KBLI, klasifikasi, skema, kualifikasi, LSBU,
                            asosiasi, dll), workspace perusahaan yang lengkap, pembuatan dokumen otomatis (SPTJM, SMAP,
                            Lampiran Tenaga Ahli, Neraca, Surat Alat), serta sistem arsip global.
                        </p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-center">
                                <p class="text-2xl font-bold text-emerald-700">Single User</p>
                                <p class="mt-1 text-xs text-emerald-600">Admin tunggal, tanpa registrasi multi-user</p>
                            </div>
                            <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-center">
                                <p class="text-2xl font-bold text-emerald-700">PDF Otomatis</p>
                                <p class="mt-1 text-xs text-emerald-600">Generate dokumen SBU dalam format PDF</p>
                            </div>
                            <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-center">
                                <p class="text-2xl font-bold text-emerald-700">Arsip Terpusat</p>
                                <p class="mt-1 text-xs text-emerald-600">Semua dokumen tersimpan di satu tempat</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 2. Alur Sistem --}}
                <section id="alur" class="mb-10 scroll-mt-6 page-break">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">2. Alur Sistem</h2>
                        <p class="mt-3 text-sm text-slate-600">Berikut adalah alur kerja keseluruhan sistem dari awal hingga akhir:</p>

                        <div class="mt-6 space-y-3">
                            {{-- Step 1 --}}
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-bold text-white">1</div>
                                <div class="min-w-0 flex-1 rounded-md border border-slate-200 bg-slate-50 p-4">
                                    <h4 class="font-bold text-slate-950">Setting Awal</h4>
                                    <p class="mt-1 text-sm text-slate-600">Konfigurasi profil aplikasi, format dokumen, penyimpanan, dan backup di menu <strong>Pengaturan</strong>.</p>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <svg class="flow-arrow h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Step 2 --}}
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-bold text-white">2</div>
                                <div class="min-w-0 flex-1 rounded-md border border-slate-200 bg-slate-50 p-4">
                                    <h4 class="font-bold text-slate-950">Input Master Data</h4>
                                    <p class="mt-1 text-sm text-slate-600">Pastikan data referensi sudah lengkap: KBLI, Klasifikasi, Subklasifikasi, Skema, Kualifikasi, LSBU, Asosiasi, Bidang Keilmuan, Peralatan, Item Neraca, Template Dokumen.</p>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <svg class="flow-arrow h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Step 3 --}}
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-bold text-white">3</div>
                                <div class="min-w-0 flex-1 rounded-md border border-slate-200 bg-slate-50 p-4">
                                    <h4 class="font-bold text-slate-950">Tambah Perusahaan</h4>
                                    <p class="mt-1 text-sm text-slate-600">Buat data perusahaan baru. Setiap perusahaan memiliki workspace sendiri yang terisolasi.</p>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <svg class="flow-arrow h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Step 4 --}}
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-bold text-white">4</div>
                                <div class="min-w-0 flex-1 rounded-md border border-slate-200 bg-slate-50 p-4">
                                    <h4 class="font-bold text-slate-950">Lengkapi Workspace</h4>
                                    <p class="mt-1 text-sm text-slate-600">Isi profil perusahaan, data direktur/PJBU, tenaga ahli, peralatan, dan laporan neraca keuangan di workspace perusahaan.</p>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <svg class="flow-arrow h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Step 5 --}}
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-bold text-white">5</div>
                                <div class="min-w-0 flex-1 rounded-md border border-slate-200 bg-slate-50 p-4">
                                    <h4 class="font-bold text-slate-950">Buat Pengajuan SBU</h4>
                                    <p class="mt-1 text-sm text-slate-600">Buat pengajuan SBU dengan memilih KBLI, Klasifikasi, Subklasifikasi, Skema, Kualifikasi, LSBU, dan Asosiasi. Unggah dokumen persyaratan.</p>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <svg class="flow-arrow h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Step 6 --}}
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-bold text-white">6</div>
                                <div class="min-w-0 flex-1 rounded-md border border-slate-200 bg-slate-50 p-4">
                                    <h4 class="font-bold text-slate-950">Generate Dokumen</h4>
                                    <p class="mt-1 text-sm text-slate-600">Generate dokumen SBU (SPTJM, SMAP, Lampiran Tenaga Ahli, Neraca, Surat Alat BG/BS) dalam format PDF. Preview sebelum menyimpan arsip.</p>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                <svg class="flow-arrow h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Step 7 --}}
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-bold text-white">7</div>
                                <div class="min-w-0 flex-1 rounded-md border border-slate-200 bg-slate-50 p-4">
                                    <h4 class="font-bold text-slate-950">Arsip & Selesai</h4>
                                    <p class="mt-1 text-sm text-slate-600">Dokumen yang sudah di-generate dapat diarsipkan. Arsip global menyimpan seluruh dokumen dari semua perusahaan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 3. Struktur Menu --}}
                <section id="menu" class="mb-10 scroll-mt-6 page-break">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">3. Struktur Menu</h2>
                        <p class="mt-3 text-sm text-slate-600">Sidebar navigasi utama dikelompokkan sebagai berikut:</p>

                        <div class="mt-5 space-y-4">
                            <div class="rounded-md border border-slate-200">
                                <div class="border-b border-slate-200 bg-slate-50 px-4 py-2.5">
                                    <h4 class="font-bold text-slate-950">Dashboard</h4>
                                </div>
                                <div class="px-4 py-2 text-sm text-slate-600">
                                    Halaman utama berisi ringkasan statistik (total perusahaan, pengajuan, dll) dan daftar perusahaan terbaru.
                                </div>
                            </div>

                            <div class="rounded-md border border-slate-200">
                                <div class="border-b border-slate-200 bg-slate-50 px-4 py-2.5">
                                    <h4 class="font-bold text-slate-950">Master SBU <span class="font-normal text-slate-400">(dropdown)</span></h4>
                                </div>
                                <div class="px-4 py-2 text-sm text-slate-600">
                                    <ul class="list-inside list-disc space-y-1">
                                        <li><strong>KBLI</strong> — Referensi Klasifikasi Baku Lapangan Usaha Indonesia</li>
                                        <li><strong>Klasifikasi</strong> — Klasifikasi bidang usaha SBU</li>
                                        <li><strong>Subklasifikasi</strong> — Sub-klasifikasi dari klasifikasi SBU</li>
                                        <li><strong>Skema</strong> — Skema sertifikasi SBU</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="rounded-md border border-slate-200">
                                <div class="border-b border-slate-200 bg-slate-50 px-4 py-2.5">
                                    <h4 class="font-bold text-slate-950">Referensi <span class="font-normal text-slate-400">(dropdown)</span></h4>
                                </div>
                                <div class="px-4 py-2 text-sm text-slate-600">
                                    <ul class="list-inside list-disc space-y-1">
                                        <li><strong>Kualifikasi</strong> — Tingkatan kualifikasi badan usaha</li>
                                        <li><strong>LSBU</strong> — Lembaga Sertifikasi Badan Usaha</li>
                                        <li><strong>Asosiasi</strong> — Asosiasi perusahaan terkait</li>
                                        <li><strong>Bidang Keilmuan</strong> — Bidang keilmuan tenaga ahli</li>
                                        <li><strong>Peralatan BG</strong> — Jenis peralatan BG (Barang dan/atau Jasa)</li>
                                        <li><strong>Peralatan BS</strong> — Jenis peralatan BS (Barang dan/atau Jasa Spesifik)</li>
                                        <li><strong>Item Neraca</strong> — Item-item dalam laporan neraca keuangan</li>
                                        <li><strong>Template Dokumen</strong> — Template untuk dokumen yang di-generate</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="rounded-md border border-slate-200">
                                <div class="border-b border-slate-200 bg-slate-50 px-4 py-2.5">
                                    <h4 class="font-bold text-slate-950">Perusahaan</h4>
                                </div>
                                <div class="px-4 py-2 text-sm text-slate-600">
                                    Daftar perusahaan / badan usaha yang terdaftar di sistem. Setiap perusahaan memiliki <strong>workspace</strong> sendiri.
                                </div>
                            </div>

                            <div class="rounded-md border border-slate-200">
                                <div class="border-b border-slate-200 bg-slate-50 px-4 py-2.5">
                                    <h4 class="font-bold text-slate-950">Arsip Global</h4>
                                </div>
                                <div class="px-4 py-2 text-sm text-slate-600">
                                    Seluruh dokumen yang telah diarsipkan dari semua perusahaan. Bisa difilter berdasarkan perusahaan dan kata kunci.
                                </div>
                            </div>

                            <div class="rounded-md border border-slate-200">
                                <div class="border-b border-slate-200 bg-slate-50 px-4 py-2.5">
                                    <h4 class="font-bold text-slate-950">Pengaturan</h4>
                                </div>
                                <div class="px-4 py-2 text-sm text-slate-600">
                                    6 tab pengaturan: Profil Aplikasi, Pengaturan Dokumen, Pengaturan Penyimpanan, Pengaturan Backup, Profil Admin, Informasi Sistem.
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 rounded-md border border-sky-200 bg-sky-50 p-4">
                            <h4 class="font-bold text-sky-800">Workspace Perusahaan</h4>
                            <p class="mt-1 text-sm text-sky-700">
                                Saat masuk ke detail perusahaan, muncul navigasi workspace horizontal: Ringkasan, Profil, Direktur/PJBU, Pengajuan, Tenaga Ahli, Peralatan, Neraca, Dokumen, Generate, Arsip.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- 4. Relasi Data --}}
                <section id="relasi" class="mb-10 scroll-mt-6 page-break">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">4. Relasi Data</h2>
                        <p class="mt-3 text-sm text-slate-600">Berikut adalah hubungan antar entitas dalam sistem:</p>

                        <div class="mt-6 space-y-5">
                            {{-- Level 1 --}}
                            <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-center">
                                <h4 class="font-bold text-emerald-800">Setting</h4>
                                <p class="text-xs text-emerald-600">Konfigurasi global (1 baris setting)</p>
                            </div>

                            <div class="flex justify-center">
                                <svg class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Level 2 --}}
                            <div class="rounded-md border border-indigo-200 bg-indigo-50 p-4">
                                <h4 class="text-center font-bold text-indigo-800">Master Data</h4>
                                <p class="mt-1 text-center text-xs text-indigo-600">KBLI, Klasifikasi, Subklasifikasi, Skema, Kualifikasi, LSBU, Asosiasi, Bidang Keilmuan, Peralatan BG/BS, Item Neraca, Template Dokumen</p>
                            </div>

                            <div class="flex justify-center">
                                <svg class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Level 3 --}}
                            <div class="rounded-md border border-slate-300 bg-white p-4 text-center shadow-sm">
                                <h4 class="font-bold text-slate-950">Company (Perusahaan)</h4>
                                <p class="text-xs text-slate-500">Root data untuk workspace</p>
                            </div>

                            <div class="flex justify-center">
                                <svg class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Level 4 - Grid --}}
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div class="rounded-md border border-slate-200 bg-slate-50 p-3 text-center">
                                    <h5 class="font-bold text-slate-800">Profil Perusahaan</h5>
                                    <p class="text-xs text-slate-500">Alamat, legalitas, kontak</p>
                                </div>
                                <div class="rounded-md border border-slate-200 bg-slate-50 p-3 text-center">
                                    <h5 class="font-bold text-slate-800">Direktur / PJBU</h5>
                                    <p class="text-xs text-slate-500">Personil perusahaan</p>
                                </div>
                                <div class="rounded-md border border-slate-200 bg-slate-50 p-3 text-center">
                                    <h5 class="font-bold text-slate-800">Peralatan</h5>
                                    <p class="text-xs text-slate-500">Inventaris alat</p>
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-md border border-amber-200 bg-amber-50 p-3 text-center">
                                    <h5 class="font-bold text-amber-800">Pengajuan SBU</h5>
                                    <p class="text-xs text-amber-600">Pilihan KBLI, Klasifikasi, Skema, dll + upload dokumen</p>
                                </div>
                                <div class="rounded-md border border-amber-200 bg-amber-50 p-3 text-center">
                                    <h5 class="font-bold text-amber-800">Laporan Neraca</h5>
                                    <p class="text-xs text-amber-600">Data keuangan 2 tahun</p>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <svg class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            </div>

                            {{-- Level 5 --}}
                            <div class="rounded-md border border-teal-200 bg-teal-50 p-4 text-center">
                                <h4 class="font-bold text-teal-800">Dokumen Tercetak (Arsip)</h4>
                                <p class="text-xs text-teal-600">SPTJM, SMAP, Lampiran Tenaga Ahli, Neraca, Surat Alat BG, Surat Alat BS</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-md border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-semibold text-slate-700">Ringkasan Relasi:</p>
                            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-slate-600">
                                <li><strong>Setting</strong> bersifat global — mempengaruhi semua modul</li>
                                <li><strong>Master Data</strong> digunakan sebagai referensi oleh semua perusahaan</li>
                                <li><strong>Company</strong> memiliki satu workspace yang berisi profil, personil, peralatan</li>
                                <li><strong>Pengajuan SBU</strong> (Application) milik satu Company, menggunakan data Master</li>
                                <li><strong>Neraca</strong> terikat dengan pengajuan yang aktif</li>
                                <li><strong>Dokumen</strong> di-generate dari data Pengajuan + data pendukung (Tenaga Ahli, Peralatan, Neraca)</li>
                                <li><strong>Arsip</strong> adalah hasil generate yang sudah disimpan</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {{-- 5. Master Data --}}
                <section id="master" class="mb-10 scroll-mt-6 page-break">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">5. Master Data</h2>
                        <p class="mt-3 text-sm text-slate-600">
                            Modul Master Data berisi data referensi yang digunakan secara global oleh seluruh workspace perusahaan.
                            Setiap entri master bisa diatur status aktif/nonaktif.
                        </p>

                        <div class="mt-5 overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <tr><th class="px-4 py-2">Menu</th><th class="px-4 py-2">Fungsi</th><th class="px-4 py-2">Import Excel</th></tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">KBLI</td><td class="px-4 py-2 text-slate-600">Klasifikasi Baku Lapangan Usaha Indonesia</td><td class="px-4 py-2 text-center text-emerald-600">&#10003;</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Klasifikasi</td><td class="px-4 py-2 text-slate-600">Klasifikasi bidang usaha SBU</td><td class="px-4 py-2 text-center text-emerald-600">&#10003;</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Subklasifikasi</td><td class="px-4 py-2 text-slate-600">Sub-klasifikasi dari klasifikasi SBU</td><td class="px-4 py-2 text-center text-emerald-600">&#10003;</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Skema</td><td class="px-4 py-2 text-slate-600">Skema sertifikasi SBU</td><td class="px-4 py-2 text-center text-emerald-600">&#10003;</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Kualifikasi</td><td class="px-4 py-2 text-slate-600">Tingkatan kualifikasi perusahaan</td><td class="px-4 py-2 text-center text-slate-400">—</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">LSBU</td><td class="px-4 py-2 text-slate-600">Lembaga Sertifikasi Badan Usaha</td><td class="px-4 py-2 text-center text-slate-400">—</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Asosiasi</td><td class="px-4 py-2 text-slate-600">Asosiasi perusahaan</td><td class="px-4 py-2 text-center text-slate-400">—</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Bidang Keilmuan</td><td class="px-4 py-2 text-slate-600">Bidang keilmuan tenaga ahli</td><td class="px-4 py-2 text-center text-emerald-600">&#10003;</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Peralatan BG</td><td class="px-4 py-2 text-slate-600">Jenis peralatan BG</td><td class="px-4 py-2 text-center text-emerald-600">&#10003;</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Peralatan BS</td><td class="px-4 py-2 text-slate-600">Jenis peralatan BS</td><td class="px-4 py-2 text-center text-emerald-600">&#10003;</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Item Neraca</td><td class="px-4 py-2 text-slate-600">Item laporan neraca keuangan</td><td class="px-4 py-2 text-center text-emerald-600">&#10003;</td></tr>
                                    <tr><td class="px-4 py-2 font-semibold text-slate-900">Template Dokumen</td><td class="px-4 py-2 text-slate-600">Template tampilan dokumen PDF</td><td class="px-4 py-2 text-center text-slate-400">—</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 rounded-md border border-sky-200 bg-sky-50 p-3">
                            <p class="text-sm text-sky-800"><strong>Tips:</strong> Gunakan fitur <strong>Impor Excel</strong> untuk memasukkan data master dalam jumlah besar sekaligus. Download template terlebih dahulu, isi data, lalu upload.</p>
                        </div>
                    </div>
                </section>

                {{-- 6. Workspace Perusahaan --}}
                <section id="workspace" class="mb-10 scroll-mt-6 page-break">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">6. Workspace Perusahaan</h2>
                        <p class="mt-3 text-sm text-slate-600">
                            Setiap perusahaan memiliki workspace sendiri yang diakses melalui tombol <strong>"Buka Workspace"</strong> di daftar perusahaan.
                            Navigasi workspace berupa menu horizontal di bawah header.
                        </p>

                        <div class="mt-5 space-y-4">
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Ringkasan</h4>
                                <p class="mt-1 text-sm text-slate-600">Dashboard workspace. Menampilkan informasi ringkas perusahaan dan statistik pengajuan.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Profil</h4>
                                <p class="mt-1 text-sm text-slate-600">Edit data profil perusahaan: nama, alamat, legalitas (NIB, NPWP), kontak, dan catatan.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Direktur / PJBU</h4>
                                <p class="mt-1 text-sm text-slate-600">Kelola data direktur dan Penanggung Jawab Badan Usaha (PJBU). Masing-masing memiliki form input berbeda.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Pengajuan</h4>
                                <p class="mt-1 text-sm text-slate-600">
                                    Modul inti. Buat pengajuan SBU dengan memilih data master (KBLI, klasifikasi, skema, kualifikasi, LSBU, asosiasi).
                                    Setiap pengajuan memiliki status (Draft &rarr; Berkas Belum Lengkap &rarr; Berkas Lengkap &rarr; Proses &rarr; Revisi &rarr; Terbit &rarr; Selesai).
                                    Upload dokumen persyaratan di halaman detail pengajuan.
                                </p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Tenaga Ahli</h4>
                                <p class="mt-1 text-sm text-slate-600">Data tenaga ahli yang terkait dengan pengajuan SBU aktif.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Peralatan</h4>
                                <p class="mt-1 text-sm text-slate-600">Inventaris peralatan perusahaan yang digunakan dalam pengajuan.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Neraca</h4>
                                <p class="mt-1 text-sm text-slate-600">Laporan neraca keuangan 2 tahun terakhir. Terkait dengan pengajuan yang aktif. Item neraca menggunakan data dari Master Item Neraca.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Dokumen</h4>
                                <p class="mt-1 text-sm text-slate-600">Upload dokumen pendukung persyaratan pengajuan SBU (tidak termasuk generate dokumen otomatis).</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Generate</h4>
                                <p class="mt-1 text-sm text-slate-600">Halaman untuk generate dokumen PDF. Lihat penjelasan detail di bagian <a href="#dokumen" class="font-semibold text-emerald-700 underline">Generate Dokumen</a>.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-emerald-700">Arsip</h4>
                                <p class="mt-1 text-sm text-slate-600">Arsip dokumen yang sudah di-generate untuk perusahaan ini.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 7. Generate Dokumen --}}
                <section id="dokumen" class="mb-10 scroll-mt-6 page-break">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">7. Generate Dokumen</h2>
                        <p class="mt-3 text-sm text-slate-600">
                            Fitur ini menghasilkan dokumen SBU dalam format PDF secara otomatis berdasarkan data yang sudah diinput.
                            Dokumen yang dapat di-generate:
                        </p>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">SPTJM</h4>
                                <p class="mt-1 text-sm text-slate-600">Surat Pernyataan Tanggung Jawab Mutlak. Memuat data perusahaan, direktur, dan pernyataan komitmen.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">SMAP</h4>
                                <p class="mt-1 text-sm text-slate-600">Surat Mengajukan Asosiasi Profesi. Memuat data pengajuan dan asosiasi terkait.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">Lampiran Tenaga Ahli</h4>
                                <p class="mt-1 text-sm text-slate-600">Daftar tenaga ahli yang terlibat, termasuk bidang keilmuan dan pengalaman.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">Neraca Keuangan</h4>
                                <p class="mt-1 text-sm text-slate-600">Laporan neraca keuangan 2 tahun dalam format dokumen resmi.</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">Surat Alat BG</h4>
                                <p class="mt-1 text-sm text-slate-600">Surat pernyataan peralatan BG (Barang dan/atau Jasa).</p>
                            </div>
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">Surat Alat BS</h4>
                                <p class="mt-1 text-sm text-slate-600">Surat pernyataan peralatan BS (Barang dan/atau Jasa Spesifik).</p>
                            </div>
                        </div>

                        <div class="mt-5 space-y-3 rounded-md border border-slate-200 bg-slate-50 p-4">
                            <h4 class="font-bold text-slate-950">Alur Generate Dokumen:</h4>
                            <ol class="list-inside list-decimal space-y-2 text-sm text-slate-600">
                                <li>Pastikan data pendukung sudah lengkap (tenaga ahli, peralatan, neraca, direktur/PJBU).</li>
                                <li>Buka menu <strong>Generate</strong> di workspace perusahaan.</li>
                                <li>Pilih pengajuan aktif dan centang dokumen yang ingin di-generate.</li>
                                <li>Klik tombol <strong>Generate Dokumen</strong> untuk melihat preview.</li>
                                <li>Jika sudah sesuai, klik <strong>Simpan Arsip</strong> untuk menyimpan ke arsip.</li>
                                <li>Dokumen yang sudah diarsipkan bisa diunduh kapan saja dari menu Arsip.</li>
                            </ol>
                        </div>

                        <div class="mt-4 rounded-md border border-amber-200 bg-amber-50 p-3">
                            <p class="text-sm text-amber-800"><strong>Catatan:</strong> Preview dokumen menggunakan data real-time. Pastikan semua data sudah benar sebelum menyimpan arsip.</p>
                        </div>
                    </div>
                </section>

                {{-- 8. Pengaturan --}}
                <section id="pengaturan" class="mb-10 scroll-mt-6 page-break">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">8. Pengaturan</h2>
                        <p class="mt-3 text-sm text-slate-600">
                            Menu Pengaturan terdiri dari 6 tab yang mengkonfigurasi perilaku global aplikasi.
                        </p>

                        <div class="mt-5 space-y-4">
                            <div class="rounded-md border-l-4 border-emerald-500 bg-emerald-50 p-4">
                                <h4 class="font-bold text-emerald-800">Profil Aplikasi</h4>
                                <p class="mt-1 text-sm text-emerald-700">Nama aplikasi, nama perusahaan, logo, favicon, alamat, telepon, email, website, footer, copyright.</p>
                            </div>
                            <div class="rounded-md border-l-4 border-blue-500 bg-blue-50 p-4">
                                <h4 class="font-bold text-blue-800">Pengaturan Dokumen</h4>
                                <p class="mt-1 text-sm text-blue-700">Kota TTD default, format nomor dokumen, prefix nomor pengajuan, tahun default, format tanggal Indonesia, ukuran kertas, margin PDF, orientation.</p>
                            </div>
                            <div class="rounded-md border-l-4 border-purple-500 bg-purple-50 p-4">
                                <h4 class="font-bold text-purple-800">Pengaturan Penyimpanan</h4>
                                <p class="mt-1 text-sm text-purple-700">Folder upload, folder arsip PDF, maksimal ukuran upload, jenis file yang diizinkan.</p>
                            </div>
                            <div class="rounded-md border-l-4 border-orange-500 bg-orange-50 p-4">
                                <h4 class="font-bold text-orange-800">Pengaturan Backup</h4>
                                <p class="mt-1 text-sm text-orange-700">Folder backup database, folder backup dokumen. Dilengkapi tombol untuk backup manual database dan storage.</p>
                            </div>
                            <div class="rounded-md border-l-4 border-slate-500 bg-slate-50 p-4">
                                <h4 class="font-bold text-slate-800">Profil Admin</h4>
                                <p class="mt-1 text-sm text-slate-600">Ubah nama, email, dan password admin.</p>
                            </div>
                            <div class="rounded-md border-l-4 border-teal-500 bg-teal-50 p-4">
                                <h4 class="font-bold text-teal-800">Informasi Sistem</h4>
                                <p class="mt-1 text-sm text-teal-700">Informasi teknis: versi Laravel, PHP, database, environment, storage terpakai, total perusahaan/pengajuan/dokumen/arsip.</p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-md border border-sky-200 bg-sky-50 p-3">
                            <p class="text-sm text-sky-800"><strong>Tips:</strong> Semua pengaturan disimpan di database (tabel <code class="rounded bg-sky-100 px-1 font-mono text-xs">settings</code>), bukan di file .env. Gunakan fungsi <code class="rounded bg-sky-100 px-1 font-mono text-xs">Setting::get('key')</code> untuk mengakses nilai pengaturan di seluruh aplikasi.</p>
                        </div>
                    </div>
                </section>

                {{-- 9. Import Data --}}
                <section id="import" class="mb-10 scroll-mt-6">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">9. Import Data Excel</h2>
                        <p class="mt-3 text-sm text-slate-600">
                            Fitur import Excel tersedia untuk data master tertentu. Format file yang didukung: <strong>.xlsx</strong> dan <strong>.csv</strong>.
                        </p>

                        <div class="mt-5 space-y-4">
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">Data yang mendukung Import Excel:</h4>
                                <div class="mt-2 grid gap-2 sm:grid-cols-2">
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        KBLI, Klasifikasi, Subklasifikasi, Skema
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        Bidang Keilmuan, Peralatan BG, Peralatan BS
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-slate-600">
                                        <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        Item Neraca (Balance Items)
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">Cara Import:</h4>
                                <ol class="mt-2 list-inside list-decimal space-y-1 text-sm text-slate-600">
                                    <li>Klik tombol <strong>"Impor Excel"</strong> di halaman index data master.</li>
                                    <li>Klik <strong>"Unduh Template"</strong> untuk mendapatkan format CSV/Excel yang benar.</li>
                                    <li>Isi template dengan data yang diinginkan. Perhatikan kolom wajib (Kode dan Nama).</li>
                                    <li>Upload file yang sudah diisi. File akan diproses dan divalidasi.</li>
                                    <li>Jika ada kesalahan validasi, sistem akan menampilkan daftar error per baris.</li>
                                </ol>
                            </div>

                            <div class="rounded-md border border-amber-200 bg-amber-50 p-3">
                                <p class="text-sm text-amber-800"><strong>Catatan:</strong> Jika kode sudah ada di database, data akan diperbarui (update). Jika belum ada, data baru akan dibuat (insert).</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 10. Arsip Global --}}
                <section id="arsip" class="mb-10 scroll-mt-6">
                    <div class="rounded-lg border border-slate-200 bg-white p-6">
                        <h2 class="text-xl font-bold text-slate-950">10. Arsip Global</h2>
                        <p class="mt-3 text-sm text-slate-600">
                            Menu <strong>Arsip Global</strong> menampilkan seluruh dokumen yang telah di-generate dan diarsipkan dari semua perusahaan. 
                            Berbeda dengan menu Arsip di dalam workspace yang hanya menampilkan arsip untuk satu perusahaan tertentu.
                        </p>

                        <div class="mt-5 space-y-4">
                            <div class="rounded-md border border-slate-200 p-4">
                                <h4 class="font-bold text-slate-950">Fitur Arsip Global:</h4>
                                <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-slate-600">
                                    <li>Filter berdasarkan <strong>Perusahaan</strong> (dropdown select)</li>
                                    <li>Pencarian berdasarkan <strong>kata kunci</strong> (nama dokumen, nama berkas, nomor pengajuan)</li>
                                    <li>Tombol <strong>Reset</strong> untuk menghapus filter</li>
                                    <li>Aksi: <strong>View</strong> (lihat dokumen), <strong>Download</strong> (unduh file), <strong>Print</strong> (cetak langsung)</li>
                                </ul>
                            </div>

                            <div class="rounded-md border border-sky-200 bg-sky-50 p-3">
                                <p class="text-sm text-sky-800"><strong>Tips:</strong> Gunakan filter perusahaan untuk mempersempit pencarian jika sudah memiliki banyak data arsip.</p>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 border-t border-slate-200 py-6 text-center text-sm text-slate-400">
            <p>{{ $appName }} &mdash; {{ $companyName }}</p>
        </div>
    </div>
</body>
</html>
