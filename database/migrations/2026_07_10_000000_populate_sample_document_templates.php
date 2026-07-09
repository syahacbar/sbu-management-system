<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $templates = [
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
                'is_active' => true,
                'sort_order' => 10,
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
                'is_active' => true,
                'sort_order' => 20,
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
                'is_active' => true,
                'sort_order' => 30,
            ]
        ];

        foreach ($templates as $tpl) {
            DB::table('master_document_templates')->updateOrInsert(
                ['code' => $tpl['code']],
                [
                    'name' => $tpl['name'],
                    'description' => $tpl['description'],
                    'header_text' => $tpl['header_text'],
                    'footer_text' => $tpl['footer_text'],
                    'template_body' => $tpl['template_body'],
                    'is_active' => $tpl['is_active'],
                    'sort_order' => $tpl['sort_order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('master_document_templates')->whereIn('code', ['SBU_CERT', 'SPTJM', 'NERACA'])->delete();
    }
};
