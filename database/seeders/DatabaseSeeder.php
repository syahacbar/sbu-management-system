<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\MasterKbli;
use App\Models\MasterSbuClassification;
use App\Models\MasterSbuScheme;
use App\Models\MasterSbuSubclassification;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sbu.local'],
            [
                'name' => 'Admin SBU',
                'password' => Hash::make('sayaganteng'),
            ],
        );

        $this->seedMasterKblis();
        $this->seedMasterSbuClassifications();
        $this->seedMasterSbuSchemes();
        $this->seedGenericMasterReferences();
        $this->seedMasterFinancialItems();
        $this->seedCompaniesAndWorkspaces();

        $this->call(SettingSeeder::class);
    }

    private function seedMasterKblis(): void
    {
        foreach ([
            ['code' => '41011', 'name' => 'Konstruksi Gedung Hunian', 'sort_order' => 10],
            ['code' => '41012', 'name' => 'Konstruksi Gedung Perkantoran', 'sort_order' => 20],
            ['code' => '42101', 'name' => 'Konstruksi Jalan Raya', 'sort_order' => 30],
            ['code' => '42201', 'name' => 'Konstruksi Jaringan Irigasi', 'sort_order' => 40, 'is_active' => false],
        ] as $item) {
            MasterKbli::updateOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'description' => 'Data dummy KBLI untuk pengujian aplikasi.',
                    'is_active' => $item['is_active'] ?? true,
                    'sort_order' => $item['sort_order'],
                ],
            );
        }
    }

    private function seedMasterSbuClassifications(): void
    {
        $classifications = [
            ['code' => 'BG', 'name' => 'Bangunan Gedung', 'sort_order' => 10],
            ['code' => 'BS', 'name' => 'Bangunan Sipil', 'sort_order' => 20],
            ['code' => 'SI', 'name' => 'Spesialis', 'sort_order' => 30],
        ];

        foreach ($classifications as $item) {
            $classification = MasterSbuClassification::updateOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'description' => 'Data dummy klasifikasi SBU.',
                    'is_active' => true,
                    'sort_order' => $item['sort_order'],
                ],
            );

            foreach ($this->subclassificationsFor($item['code']) as $subItem) {
                MasterSbuSubclassification::updateOrCreate(
                    ['code' => $subItem['code']],
                    [
                        'master_sbu_classification_id' => $classification->id,
                        'name' => $subItem['name'],
                        'description' => 'Data dummy subklasifikasi SBU.',
                        'is_active' => $subItem['is_active'] ?? true,
                        'sort_order' => $subItem['sort_order'],
                    ],
                );
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function subclassificationsFor(string $classificationCode): array
    {
        return match ($classificationCode) {
            'BG' => [
                ['code' => 'BG001', 'name' => 'Konstruksi Gedung Hunian', 'sort_order' => 10],
                ['code' => 'BG002', 'name' => 'Konstruksi Gedung Perkantoran', 'sort_order' => 20],
            ],
            'BS' => [
                ['code' => 'BS001', 'name' => 'Konstruksi Jalan Raya', 'sort_order' => 10],
                ['code' => 'BS002', 'name' => 'Konstruksi Jembatan', 'sort_order' => 20],
            ],
            default => [
                ['code' => 'SI001', 'name' => 'Pekerjaan Persiapan', 'sort_order' => 10],
                ['code' => 'SI002', 'name' => 'Pekerjaan Pembongkaran', 'sort_order' => 20, 'is_active' => false],
            ],
        };
    }

    private function seedGenericMasterReferences(): void
    {
        $tables = [
            'master_qualifications' => [
                ['code' => 'K', 'name' => 'Kecil'],
                ['code' => 'M', 'name' => 'Menengah'],
                ['code' => 'B', 'name' => 'Besar'],
            ],
            'master_lsbus' => [
                ['code' => 'LSBU-01', 'name' => 'LSBU Nusantara'],
                ['code' => 'LSBU-02', 'name' => 'LSBU Konstruksi Prima'],
            ],
            'master_associations' => [
                ['code' => 'ASKONAS', 'name' => 'Asosiasi Kontraktor Nasional'],
                ['code' => 'GAPENSI', 'name' => 'Gabungan Pelaksana Konstruksi Nasional Indonesia'],
            ],
            'master_science_fields' => [
                ['code' => 'SIPIL', 'name' => 'Teknik Sipil'],
                ['code' => 'ARS', 'name' => 'Arsitektur'],
            ],
            'master_bg_equipment' => [
                ['code' => 'BG-ALAT-01', 'name' => 'Concrete Mixer'],
                ['code' => 'BG-ALAT-02', 'name' => 'Scaffolding'],
            ],
            'master_bs_equipment' => [
                ['code' => 'BS-ALAT-01', 'name' => 'Excavator'],
                ['code' => 'BS-ALAT-02', 'name' => 'Vibro Roller'],
            ],
            'master_equipments' => [
                ['code' => 'EQ-BG01', 'name' => 'Concrete Mixer SBU Gedung', 'category' => 'bg', 'specification' => 'Kapasitas 0.35 m3 / 350 Liter', 'unit' => 'Unit'],
                ['code' => 'EQ-BG02', 'name' => 'Scaffolding Staging SBU Gedung', 'category' => 'bg', 'specification' => 'Main frame 1.9m, Cross brace 2.2m', 'unit' => 'Set'],
                ['code' => 'EQ-BS01', 'name' => 'Excavator SBU Sipil', 'category' => 'bs', 'specification' => 'Bucket 0.8 m3 / Kapasitas Kerja 110 HP', 'unit' => 'Unit'],
                ['code' => 'EQ-BS02', 'name' => 'Vibratory Roller SBU Sipil', 'category' => 'bs', 'specification' => 'Operating Weight 10-12 Ton', 'unit' => 'Unit'],
            ],
            'master_balance_items' => [
                ['code' => 'ASSET', 'name' => 'Total Aset'],
                ['code' => 'MODAL', 'name' => 'Modal Bersih'],
            ],
            'master_document_templates' => [
                [
                    'code' => 'SBU_CERT',
                    'name' => 'Template Sertifikat SBU',
                    'description' => 'Template resmi Sertifikat Badan Usaha Jasa Konstruksi (SBU).',
                    'header_text' => 'LEMBAGA SERTIFIKASI BADAN USAHA (LSBU)',
                    'footer_text' => 'Sertifikat ini diterbitkan secara elektronik dan dapat diverifikasi keabsahannya menggunakan kode QR.',
                    'template_body' => '<div style="text-align: center; margin-bottom: 25px;">
    <h2 style="font-size: 20px; font-weight: 850; margin: 0; color: #1e3a8a; text-transform: uppercase; letter-spacing: 1px;">Sertifikat Badan Usaha Jasa Konstruksi</h2>
    <p style="font-size: 12px; font-weight: 600; color: #475569; margin: 5px 0 0 0;">Nomor Sertifikat: {application_code}</p>
</div>

<div style="margin-bottom: 20px; font-size: 13px;">
    <p style="text-align: justify; text-indent: 30px; margin-bottom: 15px;">
        Berdasarkan Peraturan Pemerintah Republik Indonesia Nomor 5 Tahun 2021 tentang Penyelenggaraan Perizinan Berusaha Berbasis Risiko, Lembaga Sertifikasi Badan Usaha (LSBU) menyatakan bahwa badan usaha berikut:
    </p>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13px;">
        <tr>
            <td style="width: 30%; padding: 5px 0; font-weight: 600; vertical-align: top;">Nama Badan Usaha</td>
            <td style="width: 3%; padding: 5px 0; vertical-align: top;">:</td>
            <td style="width: 67%; padding: 5px 0; font-weight: bold; color: #0f172a; vertical-align: top;">{company_name}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: 600; vertical-align: top;">Alamat Kantor</td>
            <td style="padding: 5px 0; vertical-align: top;">:</td>
            <td style="padding: 5px 0; color: #334155; vertical-align: top;">{company_address}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: 600; vertical-align: top;">NPWP Perusahaan</td>
            <td style="padding: 5px 0; vertical-align: top;">:</td>
            <td style="padding: 5px 0; font-family: monospace; font-size: 13px; color: #334155; vertical-align: top;">{company_npwp}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: 600; vertical-align: top;">Nama Penanggung Jawab</td>
            <td style="padding: 5px 0; vertical-align: top;">:</td>
            <td style="padding: 5px 0; font-weight: 600; color: #334155; vertical-align: top;">{director_name}</td>
        </tr>
    </table>

    <p style="text-align: justify; margin-bottom: 15px;">
        Telah memenuhi standar sertifikasi dan dinyatakan berkualifikasi untuk melaksanakan pekerjaan konstruksi sesuai dengan perincian klasifikasi berikut:
    </p>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; border: 1px solid #cbd5e1;">
        <thead>
            <tr style="background-color: #f8fafc; border-bottom: 2px solid #cbd5e1;">
                <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: left; font-weight: bold;">Klasifikasi</th>
                <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: left; font-weight: bold;">Subklasifikasi</th>
                <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: center; font-weight: bold;">KBLI</th>
                <th style="padding: 8px; border: 1px solid #cbd5e1; text-align: center; font-weight: bold;">Kualifikasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 8px; border: 1px solid #cbd5e1; vertical-align: top;">
                    <strong>{classification_code}</strong><br>
                    <span style="color: #64748b; font-size: 11px;">{classification_name}</span>
                </td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; vertical-align: top;">
                    <strong>{subclassification_code}</strong><br>
                    <span style="color: #64748b; font-size: 11px;">{subclassification_name}</span>
                </td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center; vertical-align: top;">
                    <strong>{kbli_code}</strong><br>
                    <span style="color: #64748b; font-size: 11px;">{kbli_name}</span>
                </td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: center; vertical-align: top; font-weight: bold; color: #15803d;">
                    {qualification}
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-top: 30px; font-size: 13px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-top: 15px;">
                <div style="font-size: 11px; color: #64748b; line-height: 1.4;">
                    <strong>Catatan Penting:</strong><br>
                    1. Sertifikat ini diterbitkan secara elektronik dan sah secara hukum.<br>
                    2. Masa berlaku sertifikat mengikuti ketentuan perundang-undangan.<br>
                    3. Keaslian dokumen dapat diverifikasi secara online.
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 40%; text-align: center; vertical-align: top;">
                <p style="margin: 0 0 5px 0;">Ditetapkan di: Jayapura</p>
                <p style="margin: 0 0 15px 0;">Pada tanggal: {current_date}</p>
                <p style="font-weight: bold; margin: 0 0 10px 0;">Lembaga Sertifikasi Badan Usaha</p>
                <div style="position: relative; height: 90px; margin: 10px 0; display: flex; justify-content: center; align-items: center;">
                    <div style="position: absolute; opacity: 0.85; z-index: 10; margin-left: -30px;">{stamp_img}</div>
                    <div style="position: absolute; z-index: 20;">{signature_img}</div>
                </div>
                <p style="font-weight: bold; text-decoration: underline; margin: 5px 0 2px 0;">Dr. Ir. H. Ahmad Pratama, M.T.</p>
                <p style="font-size: 11px; color: #64748b; margin: 0;">Ketua Pelaksana LSBU</p>
            </td>
        </tr>
    </table>
</div>',
                ],
                [
                    'code' => 'SPTJM',
                    'name' => 'Template SPTJM',
                    'description' => 'Surat Pernyataan Tanggung Jawab Mutlak dari pimpinan/direktur badan usaha.',
                    'header_text' => 'SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK',
                    'footer_text' => 'Dokumen ini sah secara hukum dan menjadi bagian yang tidak terpisahkan dari berkas pengajuan SBU.',
                    'template_body' => '<div style="text-align: center; margin-bottom: 25px;">
    <h2 style="font-size: 18px; font-weight: bold; margin: 0; text-transform: uppercase; text-decoration: underline;">Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)</h2>
    <p style="font-size: 12px; color: #475569; margin: 5px 0 0 0;">Nomor: SPTJM/{application_code}</p>
</div>

<div style="margin-bottom: 20px; font-size: 13px; text-align: justify; line-height: 1.6;">
    <p>Yang bertanda tangan di bawah ini:</p>
    <table style="width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 13px;">
        <tr>
            <td style="width: 30%; padding: 5px 0; font-weight: bold;">Nama Lengkap</td>
            <td style="width: 3%; padding: 5px 0;">:</td>
            <td style="width: 67%; padding: 5px 0;">{director_name}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: bold;">Jabatan</td>
            <td style="padding: 5px 0;">:</td>
            <td style="padding: 5px 0;">Direktur Utama / Penanggung Jawab Badan Usaha</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: bold;">Nama Badan Usaha</td>
            <td style="padding: 5px 0;">:</td>
            <td style="padding: 5px 0; font-weight: bold;">{company_name}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: bold;">NPWP Badan Usaha</td>
            <td style="padding: 5px 0;">:</td>
            <td style="padding: 5px 0; font-family: monospace;">{company_npwp}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: bold;">Alamat Badan Usaha</td>
            <td style="padding: 5px 0;">:</td>
            <td style="padding: 5px 0;">{company_address}</td>
        </tr>
    </table>

    <p style="margin-bottom: 15px;">
        Sehubungan dengan permohonan Sertifikasi Badan Usaha (SBU) Jasa Konstruksi dengan kode registrasi pengajuan <strong>{application_code}</strong>, untuk subklasifikasi <strong>{subclassification_code} ({subclassification_name})</strong>, dengan ini saya menyatakan dengan sebenarnya dan bertanggung jawab penuh secara hukum bahwa:
    </p>

    <ol style="margin-bottom: 20px; padding-left: 20px; line-height: 1.6;">
        <li style="margin-bottom: 8px;">Seluruh dokumen, data, persyaratan administrasi, teknis, keuangan, maupun berkas pendukung lainnya yang diunggah ke dalam sistem adalah benar, sah, valid, dan sesuai dengan keadaan yang sebenarnya.</li>
        <li style="margin-bottom: 8px;">Badan usaha kami bersedia mematuhi segala ketentuan peraturan perundang-undangan di bidang jasa konstruksi yang berlaku di wilayah Republik Indonesia.</li>
        <li style="margin-bottom: 8px;">Apabila di kemudian hari ditemukan bahwa dokumen atau pernyataan yang kami sampaikan tidak benar/palsu, kami bersedia menerima sanksi administratif berupa pembatalan sertifikat, pencantuman dalam daftar hitam (blacklist), serta dituntut sesuai hukum pidana/perdata yang berlaku.</li>
    </ol>

    <p>Demikian pernyataan ini dibuat dengan penuh kesadaran dan tanpa paksaan dari pihak manapun untuk dipergunakan sebagaimana mestinya.</p>
</div>

<div style="margin-top: 40px; font-size: 13px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0 0 5px 0;">Dibuat di: Jayapura</p>
                <p style="margin: 0 0 20px 0;">Tanggal: {current_date}</p>
                <p style="font-weight: bold; margin: 0 0 10px 0;">Yang Menyatakan,</p>
                <p style="font-size: 10px; color: #64748b; margin: 0 0 5px 0;">[Meterai Elektronik Rp10.000]</p>
                <div style="position: relative; height: 95px; margin: 10px 0; display: flex; justify-content: center; align-items: center;">
                    <div style="position: absolute; opacity: 0.8; z-index: 10; margin-left: -20px;">{stamp_img}</div>
                    <div style="position: absolute; z-index: 20;">{signature_img}</div>
                </div>
                <p style="font-weight: bold; text-decoration: underline; margin: 5px 0 2px 0;">{director_name}</p>
                <p style="font-size: 11px; color: #64748b; margin: 0;">Direktur Utama / PJBU</p>
            </td>
        </tr>
    </table>
</div>',
                ],
                [
                    'code' => 'NERACA',
                    'name' => 'Template Neraca',
                    'description' => 'Laporan Posisi Keuangan (Neraca) badan usaha untuk pembuktian kemampuan keuangan.',
                    'header_text' => 'LAPORAN NERACA KEUANGAN BADAN USAHA',
                    'footer_text' => 'Neraca keuangan ini dilampirkan secara sah untuk memenuhi kualifikasi pengajuan SBU.',
                    'template_body' => '<div style="text-align: center; margin-bottom: 25px;">
    <h2 style="font-size: 18px; font-weight: bold; margin: 0; text-transform: uppercase; text-decoration: underline;">Laporan Neraca Keuangan Perusahaan</h2>
    <p style="font-size: 12px; color: #475569; margin: 5px 0 0 0;">Untuk Pengajuan SBU: {application_code}</p>
</div>

<div style="margin-bottom: 20px; font-size: 13px;">
    <p style="margin-bottom: 15px;">Berikut adalah rincian laporan neraca keuangan terakhir dari badan usaha:</p>
    
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13px;">
        <tr>
            <td style="width: 30%; padding: 5px 0; font-weight: bold;">Nama Perusahaan</td>
            <td style="width: 3%; padding: 5px 0;">:</td>
            <td style="width: 67%; padding: 5px 0;">{company_name}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: bold;">NPWP</td>
            <td style="padding: 5px 0;">:</td>
            <td style="padding: 5px 0; font-family: monospace;">{company_npwp}</td>
        </tr>
        <tr>
            <td style="padding: 5px 0; font-weight: bold;">Tanggal Pelaporan</td>
            <td style="padding: 5px 0;">:</td>
            <td style="padding: 5px 0;">{current_date}</td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13px; border: 1px solid #cbd5e1;">
        <thead>
            <tr style="background-color: #f8fafc; border-bottom: 2px solid #cbd5e1;">
                <th style="padding: 10px 8px; border: 1px solid #cbd5e1; text-align: left; font-weight: bold;">Deskripsi Pos Neraca</th>
                <th style="padding: 10px 8px; border: 1px solid #cbd5e1; text-align: right; font-weight: bold;">Nilai (Rupiah)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 8px; border: 1px solid #cbd5e1;"><strong>1. Total Aset (Aktiva)</strong></td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace; font-weight: bold;">Rp 2.500.000.000</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #cbd5e1; padding-left: 20px; color: #475569;">Aset Lancar</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace; color: #475569;">Rp 1.500.000.000</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #cbd5e1; padding-left: 20px; color: #475569;">Aset Tetap</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace; color: #475569;">Rp 1.000.000.000</td>
            </tr>
            <tr style="background-color: #f8fafc;">
                <td style="padding: 8px; border: 1px solid #cbd5e1;"><strong>2. Total Kewajiban (Pasiva)</strong></td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace; font-weight: bold;">Rp 1.300.000.000</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #cbd5e1; padding-left: 20px; color: #475569;">Kewajiban Jangka Pendek</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace; color: #475569;">Rp 800.000.000</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #cbd5e1; padding-left: 20px; color: #475569;">Kewajiban Jangka Panjang</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace; color: #475569;">Rp 500.000.000</td>
            </tr>
            <tr style="background-color: #f0fdf4;">
                <td style="padding: 8px; border: 1px solid #cbd5e1; font-weight: bold; color: #166534;">3. Ekuitas / Modal Bersih</td>
                <td style="padding: 8px; border: 1px solid #cbd5e1; text-align: right; font-family: monospace; font-weight: bold; color: #166534;">Rp 1.200.000.000</td>
            </tr>
        </tbody>
    </table>

    <p style="text-align: justify; margin-top: 15px;">
        Demikian laporan neraca ini dilampirkan sebagai kelengkapan berkas kemampuan keuangan untuk verifikasi permohonan sertifikasi badan usaha.
    </p>
</div>

<div style="margin-top: 30px; font-size: 13px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0 0 5px 0;">Disahkan di: Jayapura</p>
                <p style="margin: 0 0 20px 0;">Tanggal: {current_date}</p>
                <p style="font-weight: bold; margin: 0 0 10px 0;">Direktur Utama / Penanggung Jawab,</p>
                <div style="position: relative; height: 90px; margin: 10px 0; display: flex; justify-content: center; align-items: center;">
                    <div style="position: absolute; opacity: 0.85; z-index: 10; margin-left: -20px;">{stamp_img}</div>
                    <div style="position: absolute; z-index: 20;">{signature_img}</div>
                </div>
                <p style="font-weight: bold; text-decoration: underline; margin: 5px 0 2px 0;">{director_name}</p>
                <p style="font-size: 11px; color: #64748b; margin: 0;">{company_name}</p>
            </td>
        </tr>
    </table>
</div>',
                ],
            ],
        ];

        foreach ($tables as $table => $items) {
            foreach ($items as $index => $item) {
                $updateData = [
                    'name' => $item['name'],
                    'description' => $item['description'] ?? 'Data dummy referensi global.',
                    'is_active' => $item['is_active'] ?? true,
                    'sort_order' => ($index + 1) * 10,
                    'updated_at' => now(),
                ];

                // Merge any other keys defined in $item (excluding 'code' and 'name' which are handled)
                foreach ($item as $key => $value) {
                    if (!in_array($key, ['code', 'name', 'is_active', 'sort_order'])) {
                        $updateData[$key] = $value;
                    }
                }

                DB::table($table)->updateOrInsert(
                    ['code' => $item['code']],
                    $updateData
                );
            }
        }
    }

    private function seedMasterSbuSchemes(): void
    {
        $schemes = [
            ['scheme_code' => 'SK-BG001-K', 'scheme_name' => 'Skema Konstruksi Gedung Hunian Kecil', 'kbli_code' => '41011', 'classification_code' => 'BG', 'subclassification_code' => 'BG001', 'qualification' => 'Kecil', 'sort_order' => 10],
            ['scheme_code' => 'SK-BG002-M', 'scheme_name' => 'Skema Konstruksi Gedung Perkantoran Menengah', 'kbli_code' => '41012', 'classification_code' => 'BG', 'subclassification_code' => 'BG002', 'qualification' => 'Menengah', 'sort_order' => 20],
            ['scheme_code' => 'SK-BS001-B', 'scheme_name' => 'Skema Konstruksi Jalan Raya Besar', 'kbli_code' => '42101', 'classification_code' => 'BS', 'subclassification_code' => 'BS001', 'qualification' => 'Besar', 'sort_order' => 30],
        ];

        foreach ($schemes as $item) {
            $kbli = MasterKbli::where('code', $item['kbli_code'])->first();
            $classification = MasterSbuClassification::where('code', $item['classification_code'])->first();
            $subclassification = MasterSbuSubclassification::where('code', $item['subclassification_code'])->first();

            if (! $kbli || ! $classification || ! $subclassification) {
                continue;
            }

            MasterSbuScheme::updateOrCreate(
                ['scheme_code' => $item['scheme_code']],
                [
                    'master_kbli_id' => $kbli->id,
                    'master_sbu_classification_id' => $classification->id,
                    'master_sbu_subclassification_id' => $subclassification->id,
                    'scheme_name' => $item['scheme_name'],
                    'qualification' => $item['qualification'],
                    'description' => 'Data dummy skema SBU untuk uji coba.',
                    'is_active' => true,
                    'sort_order' => $item['sort_order'],
                ],
            );
        }
    }

    private function seedCompaniesAndWorkspaces(): void
    {
        $companies = [
            [
                'name' => 'PT Maju Konstruksi Papua',
                'nib' => '9120100012345',
                'npwp' => '01.234.567.8-901.000',
                'email' => 'admin@majupapua.test',
                'phone' => '0967-555-0101',
                'business_type' => 'PT',
                'qualification' => 'Menengah',
                'province' => 'Papua',
                'city' => 'Jayapura',
                'district' => 'Abepura',
                'village' => 'Kota Baru',
                'rt_rw' => '01/02',
                'street' => 'Jl. Raya Abepura No. 10',
                'signing_place' => 'Jayapura',
                'notes' => 'Data dummy perusahaan untuk uji coba workspace.',
            ],
            [
                'name' => 'CV Cendrawasih Bangun Persada',
                'nib' => '9120100098765',
                'npwp' => '09.876.543.2-901.000',
                'email' => 'kontak@cendrawasih.test',
                'phone' => '0967-555-0202',
                'business_type' => 'CV',
                'qualification' => 'Kecil',
                'province' => 'Papua',
                'city' => 'Jayapura',
                'district' => 'Heram',
                'village' => 'Waena',
                'rt_rw' => '03/04',
                'street' => 'Jl. Trikora No. 25',
                'signing_place' => 'Jayapura',
                'notes' => 'Data dummy perusahaan untuk uji coba workspace.',
            ],
        ];

        foreach ($companies as $companyData) {
            $company = Company::updateOrCreate(
                ['nib' => $companyData['nib']],
                $companyData
            );

            $this->seedWorkspaceRecords($company);
        }
    }

    private function seedWorkspaceRecords(Company $company): void
    {
        $kbli = MasterKbli::first();
        $class = MasterSbuClassification::first();
        $sub = MasterSbuSubclassification::first();
        $scheme = MasterSbuScheme::first();

        // Seed directors and pjbus into company_persons
        $company->persons()->createMany([
            [
                'type' => 'direktur',
                'name' => 'Yohanes Pratama',
                'nik' => '3171012345678901',
                'birthplace' => 'Jayapura',
                'npwp' => '01.234.567.8-901.000',
                'email' => 'yohanes@majupapua.test',
                'position' => 'Direktur Utama',
                'is_main' => true,
            ],
            [
                'type' => 'pjbu',
                'name' => 'Maria Elisabeth',
                'nik' => '3171012345678902',
                'birthplace' => 'Jayapura',
                'npwp' => '02.345.678.9-901.000',
                'email' => 'maria@majupapua.test',
                'position' => 'Penanggung Jawab Teknik',
                'is_main' => true,
            ],
        ]);

        if ($kbli && $class && $sub && $scheme) {
            $activeApp = $company->applications()->create([
                'application_number' => 'PNJ-2026-0001',
                'application_type' => 'baru',
                'submission_date' => '2026-07-09',
                'application_year' => 2026,
                'master_kbli_id' => $kbli->id,
                'master_sbu_classification_id' => $class->id,
                'master_sbu_subclassification_id' => $sub->id,
                'master_sbu_scheme_id' => $scheme->id,
                'qualification' => $scheme->qualification,
                'lsbu_name' => 'LSBU Gatensi',
                'association_name' => 'GAPENSI',
                'status' => 'draft',
                'notes' => 'Draft pengajuan awal.',
                'is_active' => true,
            ]);

            $company->applications()->create([
                'application_number' => 'PNJ-2026-0002',
                'application_type' => 'perubahan',
                'submission_date' => '2026-07-10',
                'application_year' => 2026,
                'master_kbli_id' => $kbli->id,
                'master_sbu_classification_id' => $class->id,
                'master_sbu_subclassification_id' => $sub->id,
                'master_sbu_scheme_id' => $scheme->id,
                'qualification' => $scheme->qualification,
                'lsbu_name' => 'LSBU Gatensi',
                'association_name' => 'GAPENSI',
                'status' => 'berkas_belum_lengkap',
                'notes' => 'Pengajuan kedua.',
                'is_active' => false,
            ]);

            // Seed experts into active SBU application
            $activeApp->experts()->createMany([
                [
                    'expert_type' => 'pjtbu',
                    'name' => 'Ir. Budi Santoso',
                    'nik' => '3171012345670001',
                    'npwp' => '01.321.456.7-901.000',
                    'skk_registration_number' => 'Reg. 10023/SKK/LPJK',
                    'skk_classification' => 'Sipil',
                    'skk_subclassification' => 'Gedung',
                    'skk_qualification' => 'Ahli Madya',
                    'skk_level' => 'Jenjang 8',
                    'skk_issued_at' => '2025-01-15',
                    'skk_expired_at' => '2030-01-14',
                    'notes' => 'PJTBU Utama perusahaan.',
                ],
                [
                    'expert_type' => 'pjskbu',
                    'name' => 'Lestari Putri, S.T.',
                    'nik' => '3171012345670002',
                    'npwp' => '02.432.567.8-901.000',
                    'skk_registration_number' => 'Reg. 20045/SKK/LPJK',
                    'skk_classification' => 'Arsitektur',
                    'skk_subclassification' => 'Desain Interior',
                    'skk_qualification' => 'Ahli Muda',
                    'skk_level' => 'Jenjang 7',
                    'skk_issued_at' => '2025-02-20',
                    'skk_expired_at' => '2030-02-19',
                    'notes' => 'PJSKBU Subklasifikasi.',
                ],
                [
                    'expert_type' => 'tenaga_ahli',
                    'name' => 'Hendra Wijaya, M.T.',
                    'nik' => '3171012345670003',
                    'npwp' => '03.543.678.9-901.000',
                    'skk_registration_number' => 'Reg. 30056/SKK/LPJK',
                    'skk_classification' => 'Sipil',
                    'skk_subclassification' => 'Manajemen Konstruksi',
                    'skk_qualification' => 'Ahli Pratama',
                    'skk_level' => 'Jenjang 6',
                    'skk_issued_at' => '2025-03-10',
                    'skk_expired_at' => '2030-03-09',
                    'notes' => 'Tenaga ahli pendukung.',
                ]
            ]);

            // Seed equipments into active SBU application
            $eqBg = DB::table('master_equipments')->where('code', 'EQ-BG01')->first();
            $eqBs = DB::table('master_equipments')->where('code', 'EQ-BS01')->first();

            if ($eqBg && $eqBs) {
                $company->equipment()->createMany([
                    [
                        'sbu_application_id' => $activeApp->id,
                        'master_equipment_id' => $eqBg->id,
                        'category' => 'bg',
                        'name' => $eqBg->name,
                        'specification' => $eqBg->specification,
                        'quantity' => 2,
                        'unit' => $eqBg->unit,
                        'ownership_status' => 'milik_sendiri',
                        'notes' => 'Milik PT Maju Konstruksi Papua.',
                    ],
                    [
                        'sbu_application_id' => $activeApp->id,
                        'master_equipment_id' => $eqBs->id,
                        'category' => 'bs',
                        'name' => $eqBs->name,
                        'specification' => $eqBs->specification,
                        'quantity' => 1,
                        'unit' => $eqBs->unit,
                        'ownership_status' => 'sewa',
                        'notes' => 'Sewa Bulanan.',
                    ]
                ]);
            }

            // Seed financial statements & values
            $statement = $company->balanceEntries()->create([
                'sbu_application_id' => $activeApp->id,
                'year_one' => 2024,
                'year_two' => 2025,
                'statement_date' => '2025-12-31',
            ]);

            $masterItems = DB::table('master_financial_items')->where('is_calculated', false)->get();
            foreach ($masterItems as $mItem) {
                $val1 = 0;
                $val2 = 0;
                
                if ($mItem->code === 'kas_bank') { $val1 = 500000000; $val2 = 800000000; }
                if ($mItem->code === 'persediaan') { $val1 = 300000000; $val2 = 400000000; }
                if ($mItem->code === 'peralatan_mesin') { $val1 = 1000000000; $val2 = 1200000000; }
                if ($mItem->code === 'kewajiban_jangka_pendek') { $val1 = 400000000; $val2 = 500000000; }
                if ($mItem->code === 'modal_disetor') { $val1 = 1400000000; $val2 = 1900000000; }

                $statement->values()->create([
                    'master_financial_item_id' => $mItem->id,
                    'year_one_amount' => $val1,
                    'year_two_amount' => $val2,
                ]);
            }

            // Seed documents into active SBU application
            $company->documents()->createMany([
                [
                    'sbu_application_id' => $activeApp->id,
                    'document_type' => 'NIB',
                    'file_path' => 'application-documents/' . $company->id . '/nib_dummy.pdf',
                    'original_filename' => 'NIB_PT_Maju_Konstruksi.pdf',
                    'document_date' => '2025-01-10',
                    'expired_at' => null,
                    'status' => 'ada',
                    'notes' => 'NIB Aktif OSS RBA.',
                ],
                [
                    'sbu_application_id' => $activeApp->id,
                    'document_type' => 'NPWP',
                    'file_path' => 'application-documents/' . $company->id . '/npwp_dummy.pdf',
                    'original_filename' => 'NPWP_PT_Maju_Konstruksi.pdf',
                    'document_date' => '2025-01-11',
                    'expired_at' => null,
                    'status' => 'ada',
                    'notes' => 'NPWP Perusahaan valid.',
                ]
            ]);
            // Seed a dummy generated document into archives
            $company->archives()->create([
                'sbu_application_id' => $activeApp->id,
                'document_template_id' => null,
                'document_type' => 'Sertifikat SBU',
                'file_path' => 'generated-documents/' . $company->id . '/sbu_cert_dummy.html',
                'original_filename' => 'Sertifikat_SBU_' . $activeApp->application_number . '.html',
                'generated_at' => now(),
            ]);
        }
    }

    private function seedMasterFinancialItems(): void
    {
        $items = [
            // Aktiva - Lancar
            ['code' => 'kas_bank', 'name' => 'Kas dan Bank', 'section' => 'aktiva', 'group_name' => 'lancar', 'is_calculated' => false, 'sort_order' => 10, 'description' => 'Saldo kas kasir dan rekening bank perusahaan.'],
            ['code' => 'piutang_usaha', 'name' => 'Piutang Usaha', 'section' => 'aktiva', 'group_name' => 'lancar', 'is_calculated' => false, 'sort_order' => 20, 'description' => 'Tagihan kepada pihak ketiga atas pekerjaan selesai.'],
            ['code' => 'persediaan', 'name' => 'Persediaan', 'section' => 'aktiva', 'group_name' => 'lancar', 'is_calculated' => false, 'sort_order' => 30, 'description' => 'Persediaan bahan baku atau material konstruksi.'],
            ['code' => 'total_aktiva_lancar', 'name' => 'Total Aktiva Lancar', 'section' => 'aktiva', 'group_name' => 'lancar', 'is_calculated' => true, 'sort_order' => 99, 'description' => 'Jumlah seluruh aset lancar.'],

            // Aktiva - Tetap
            ['code' => 'peralatan_mesin', 'name' => 'Peralatan & Mesin', 'section' => 'aktiva', 'group_name' => 'tetap', 'is_calculated' => false, 'sort_order' => 10, 'description' => 'Nilai buku peralatan kerja dan mesin.'],
            ['code' => 'tanah_bangunan', 'name' => 'Tanah & Bangunan', 'section' => 'aktiva', 'group_name' => 'tetap', 'is_calculated' => false, 'sort_order' => 20, 'description' => 'Nilai buku aset tanah dan kantor perusahaan.'],
            ['code' => 'total_aktiva_tetap', 'name' => 'Total Aktiva Tetap', 'section' => 'aktiva', 'group_name' => 'tetap', 'is_calculated' => true, 'sort_order' => 99, 'description' => 'Jumlah seluruh aset tetap.'],

            // Total Aktiva
            ['code' => 'total_aktiva', 'name' => 'TOTAL AKTIVA', 'section' => 'aktiva', 'group_name' => 'total_aktiva', 'is_calculated' => true, 'sort_order' => 100, 'description' => 'Total keseluruhan Aktiva Lancar + Aktiva Tetap.'],

            // Pasiva - Kewajiban
            ['code' => 'kewajiban_jangka_pendek', 'name' => 'Kewajiban Jangka Pendek', 'section' => 'pasiva', 'group_name' => 'kewajiban', 'is_calculated' => false, 'sort_order' => 10, 'description' => 'Utang usaha atau kewajiban jatuh tempo kurang dari 1 tahun.'],
            ['code' => 'kewajiban_jangka_panjang', 'name' => 'Kewajiban Jangka Panjang', 'section' => 'pasiva', 'group_name' => 'kewajiban', 'is_calculated' => false, 'sort_order' => 20, 'description' => 'Utang bank atau kewajiban jangka panjang.'],
            ['code' => 'total_kewajiban', 'name' => 'Total Kewajiban', 'section' => 'pasiva', 'group_name' => 'kewajiban', 'is_calculated' => true, 'sort_order' => 99, 'description' => 'Jumlah seluruh utang/kewajiban.'],

            // Pasiva - Ekuitas
            ['code' => 'modal_disetor', 'name' => 'Modal Disetor', 'section' => 'pasiva', 'group_name' => 'ekuitas', 'is_calculated' => false, 'sort_order' => 10, 'description' => 'Modal awal yang disetorkan pemegang saham.'],
            ['code' => 'laba_ditahan', 'name' => 'Laba Ditahan', 'section' => 'pasiva', 'group_name' => 'ekuitas', 'is_calculated' => false, 'sort_order' => 20, 'description' => 'Akumulasi laba usaha tahun-tahun sebelumnya.'],
            ['code' => 'total_ekuitas', 'name' => 'Total Ekuitas (Kekayaan Bersih)', 'section' => 'pasiva', 'group_name' => 'ekuitas', 'is_calculated' => true, 'sort_order' => 99, 'description' => 'Jumlah modal bersih / ekuitas perusahaan.'],
        ];

        foreach ($items as $item) {
            DB::table('master_financial_items')->updateOrInsert(
                ['code' => $item['code']],
                array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
