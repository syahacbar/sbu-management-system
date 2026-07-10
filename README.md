# SBU Management System

Sistem informasi manajemen sertifikasi **SBU (Sertifikat Badan Usaha)** bidang jasa konstruksi. Digunakan untuk mengelola data perusahaan, pengajuan sertifikasi, kelengkapan dokumen, tenaga ahli, peralatan, laporan keuangan, hingga generate dokumen PDF (sertifikat, SPTJM, neraca, dll).

---

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| **Framework** | Laravel 13.x |
| **PHP** | ^8.3 |
| **Database** | MySQL (via Laragon) |
| **CSS** | Tailwind CSS 4 |
| **Build Tool** | Vite 8 + `laravel-vite-plugin` |
| **PDF** | Barryvdh DomPDF |
| **Session/Cache/Queue** | Database driver (default) |

---

## Cara Install

### Prasyarat

- PHP ^8.3
- Composer
- Node.js & npm
- MySQL (via Laragon atau standalone)

### Langkah

```bash
# 1. Clone repositori
git clone <repo-url> sbu-management-system
cd sbu-management-system

# 2. Install dependensi PHP
composer install

# 3. Install dependensi frontend
npm install

# 4. Build asset frontend
npm run build
```

---

## Setup .env

```bash
# Salin file environment
cp .env.example .env
```

Sesuaikan isi `.env` terutama bagian database:

```env
APP_NAME=SBU Management System
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sbu_management_system
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan database `sbu_management_system` sudah dibuat di MySQL.

```bash
# Generate APP_KEY
php artisan key:generate
```

---

## Migrate dan Seed

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder (data dummy + demo)
php artisan db:seed
```

Seeder akan membuat:
- 1 user admin
- 4 data KBLI
- 3 klasifikasi SBU + 6 subklasifikasi
- 3 skema SBU
- Referensi master (kualifikasi, LSBU, asosiasi, bidang keilmuan, peralatan, item neraca, template dokumen)
- 2 perusahaan dummy + workspace lengkap (direktur, PJBU, pengajuan, tenaga ahli, peralatan, neraca, dokumen, arsip)

---

## Menjalankan Server

```bash
php artisan serve
```

Akses di **http://localhost:8000**

Untuk development dengan Vite (hot reload CSS):

```bash
npm run dev
```

---

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@sbu.local` | `password` |

> Semua user memiliki akses penuh (belum ada role/permission system).

---

## Alur Penggunaan

### 1. Login

Buka `/login`, masukkan email dan password admin. Setelah login akan diarahkan ke dashboard.

### 2. Buat Perusahaan

- Menu **Perusahaan** -> **Tambah Perusahaan**
- Isi data: NIB, NPWP, nama, alamat lengkap, jenis badan usaha, kualifikasi
- Setelah tersimpan, masuk ke workspace perusahaan

### 3. Buat Pengajuan

Di workspace perusahaan, menu **Pengajuan** -> **Buat Pengajuan**:
- Pilih KBLI, klasifikasi, subklasifikasi, dan skema
- Isi LSBU dan asosiasi
- Status awal: `draft`

### 4. Lengkapi Data

 sebelum pengajuan dapat diproses, lengkapi:

| Data | Menu | Keterangan |
|------|------|-----------|
| **Direktur & PJBU** | Direktur/PJBU | Minimal 1 direktur (is_main) dan 1 PJBU (is_main) |
| **Tenaga Ahli** | Tenaga Ahli | Tambah PJTBU/PJSKBU/tenaga ahli dengan data SKK |
| **Peralatan** | Peralatan | Pilih dari master equipment, isi jumlah & kepemilikan |
| **Neraca Keuangan** | Neraca | Isi nilai aset, kewajiban, dan ekuitas |
| **Dokumen** | Dokumen | Upload NIB, NPWP, dan dokumen pendukung lainnya |

### 5. Generate PDF

Setelah data lengkap, buka menu **Generate**:
- Pilih template dokumen yang tersedia (Sertifikat SBU, SPTJM, Neraca)
- Klik **Preview** untuk melihat hasil
- Klik **Download** untuk mengunduh PDF
- Klik **Arsipkan** untuk menyimpan ke arsip

PDF yang dapat di-generate per pengajuan:
- **SMAP** (Surat Permohonan)
- **SPTJM** (Surat Pernyataan Tanggung Jawab Mutlak)
- **Lampiran Tenaga Ahli**
- **Neraca Keuangan**
- **Surat Alat BG**
- **Surat Alat BS**

### 6. Manajemen Status Pengajuan

Status pengajuan dapat diubah melalui tombol **Update Status**:

`draft` -> `berkas_belum_lengkap` -> `berkas_lengkap` -> `proses` -> `revisi` -> `terbit` -> `selesai`

### 7. Arsip

Dokumen yang sudah di-generate dan diarsipkan dapat dilihat di menu **Arsip** (per perusahaan) atau **Arsip Global** (semua perusahaan).

---

## Catatan Development

### Struktur Direktori Penting

```
app/
├── Http/Controllers/        # 21 controller
│   ├── Auth/                # AuthenticatedSessionController
│   ├── Master/              # MasterResourceController, MasterDocumentTemplateController
│   └── Workspace/           # ApplicationController, GenerateController, dll
├── Models/                  # 25 model
│   ├── Master/              # Master reference models
│   └── Workspace/           # Application, CompanyPerson, dll
├── Services/
│   └── PdfDocumentService   # Layanan generate PDF
├── Helpers/
│   └── SimpleXlsxReader     # Parser XLSX (custom)
database/
├── migrations/              # 23 migration
└── seeders/
    └── DatabaseSeeder.php   # Seeder utama
resources/
└── views/
    ├── auth/                # Login
    ├── companies/           # CRUD perusahaan
    ├── master/              # Master data (KBLI, klasifikasi, dll)
    ├── workspace/           # Workspace perusahaan
    ├── pdf/                 # Template PDF
    └── components/layouts/  # Layout admin
routes/
└── web.php                  # Semua route (225 baris)
```

### Catatan

- Semua route menggunakan session-based auth (web), tidak ada API route
- PDF template menggunakan token replacement: `{company_name}`, `{application_code}`, `{director_name}`, dll
- Template dokumen disimpan di tabel `master_document_templates` dan bisa diedit via menu Master -> Template Dokumen
- Tanda tangan & stempel dibaca dari file gambar (path dikonfigurasi di konfigurasi masing-masing)
- Fungsi import Excel untuk master data menggunakan `SimpleXlsxReader` (custom, bukan library pihak ketiga)
- Session, cache, dan queue menggunakan database driver (dapat diubah ke file/redis untuk production)
- Beberapa model legacy (`Director`, `Pjbu`, `Pjtbu`, `Pjskbu`, `Equipment`, `Document`, dll) masih ada tapi tidak digunakan (tabelnya sudah di-drop migration)
