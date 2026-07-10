<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Tab 1: Profil Aplikasi
            ['key' => 'app_name', 'value' => 'SBU Management System', 'type' => 'string', 'description' => 'Nama aplikasi yang ditampilkan di header dan judul halaman'],
            ['key' => 'app_company_name', 'value' => 'Lembaga Sertifikasi Badan Usaha', 'type' => 'string', 'description' => 'Nama perusahaan/lembaga pemilik aplikasi'],
            ['key' => 'app_logo', 'value' => '', 'type' => 'file', 'description' => 'Logo aplikasi yang ditampilkan di header'],
            ['key' => 'app_favicon', 'value' => '', 'type' => 'file', 'description' => 'Favicon aplikasi yang ditampilkan di tab browser'],
            ['key' => 'app_address', 'value' => '', 'type' => 'string', 'description' => 'Alamat kantor perusahaan/lembaga'],
            ['key' => 'app_phone', 'value' => '', 'type' => 'string', 'description' => 'Nomor telepon yang dapat dihubungi'],
            ['key' => 'app_email', 'value' => '', 'type' => 'string', 'description' => 'Alamat email resmi perusahaan/lembaga'],
            ['key' => 'app_website', 'value' => '', 'type' => 'string', 'description' => 'Website resmi perusahaan/lembaga'],
            ['key' => 'app_footer', 'value' => 'Sistem Manajemen Pengajuan SBU', 'type' => 'string', 'description' => 'Teks footer yang ditampilkan di bagian bawah halaman'],
            ['key' => 'app_copyright', 'value' => 'Hak Cipta ' . date('Y') . ' LSBU. Seluruh hak cipta dilindungi undang-undang.', 'type' => 'string', 'description' => 'Teks copyright yang ditampilkan di footer'],

            // Tab 2: Pengaturan Dokumen
            ['key' => 'doc_city_default', 'value' => 'Jayapura', 'type' => 'string', 'description' => 'Kota default untuk tempat penandatanganan dokumen'],
            ['key' => 'doc_number_format', 'value' => 'SBU/{tahun}/{bulan}/{nomor}', 'type' => 'string', 'description' => 'Format nomor dokumen yang digunakan'],
            ['key' => 'app_prefix_number', 'value' => 'PNJ', 'type' => 'string', 'description' => 'Prefix nomor pengajuan aplikasi'],
            ['key' => 'doc_default_year', 'value' => (string) date('Y'), 'type' => 'string', 'description' => 'Tahun default untuk dokumen dan pengajuan'],
            ['key' => 'doc_date_format', 'value' => 'd F Y', 'type' => 'string', 'description' => 'Format tanggal Indonesia untuk dokumen'],
            ['key' => 'doc_paper_size', 'value' => 'A4', 'type' => 'string', 'description' => 'Ukuran kertas default untuk generate PDF'],
            ['key' => 'doc_margin_pdf', 'value' => '10mm', 'type' => 'string', 'description' => 'Margin default untuk dokumen PDF'],
            ['key' => 'doc_orientation', 'value' => 'portrait', 'type' => 'string', 'description' => 'Orientasi halaman default (portrait/landscape)'],

            // Tab 3: Pengaturan Penyimpanan
            ['key' => 'storage_upload_folder', 'value' => 'uploads', 'type' => 'string', 'description' => 'Folder default untuk menyimpan file upload'],
            ['key' => 'storage_archive_folder', 'value' => 'archives', 'type' => 'string', 'description' => 'Folder default untuk menyimpan arsip PDF'],
            ['key' => 'storage_max_upload_size', 'value' => '10', 'type' => 'string', 'description' => 'Ukuran maksimal file upload dalam MB'],
            ['key' => 'storage_allowed_types', 'value' => 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'type' => 'string', 'description' => 'Jenis file yang diizinkan untuk diupload'],

            // Tab 4: Pengaturan Backup
            ['key' => 'backup_db_folder', 'value' => storage_path('backups/database'), 'type' => 'string', 'description' => 'Folder penyimpanan backup database'],
            ['key' => 'backup_doc_folder', 'value' => storage_path('backups/documents'), 'type' => 'string', 'description' => 'Folder penyimpanan backup dokumen'],
        ];

        foreach ($settings as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                [
                    'value' => $item['value'],
                    'type' => $item['type'],
                    'description' => $item['description'],
                ],
            );
        }
    }
}
