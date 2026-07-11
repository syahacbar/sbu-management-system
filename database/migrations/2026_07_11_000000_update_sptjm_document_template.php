<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('master_document_templates')->updateOrInsert(
            ['code' => 'SPTJM'],
            [
                'name' => 'Template SPTJM',
                'description' => 'Surat Pernyataan Tanggung Jawab Mutlak sesuai format referensi LSBU.',
                'header_text' => null,
                'footer_text' => null,
                'template_body' => $this->templateBody(),
                'is_active' => true,
                'sort_order' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }

    public function down(): void
    {
        DB::table('master_document_templates')
            ->where('code', 'SPTJM')
            ->update([
                'description' => 'Surat Pernyataan Tanggung Jawab Mutlak dari pimpinan/direktur badan usaha.',
                'header_text' => 'SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK',
                'footer_text' => 'Dokumen ini sah secara hukum dan menjadi bagian yang tidak terpisahkan dari berkas pengajuan SBU.',
                'updated_at' => now(),
            ]);
    }

    private function templateBody(): string
    {
        return <<<'HTML'
<div style="font-family: Arial, sans-serif; font-size: 12px; line-height: 1.45; color: #111827;">
    <h2 style="text-align: center; font-size: 15px; font-weight: bold; text-transform: uppercase; text-decoration: underline; margin: 0 0 24px 0; letter-spacing: 0;">Surat Pernyataan Tanggung Jawab Mutlak</h2>

    <p style="margin: 0 0 10px 0;">Yang bertandatangan di bawah ini :</p>

    <table style="width: 100%; border-collapse: collapse; margin: 0 0 18px 0; font-size: 12px;">
        <tr>
            <td style="width: 155px; padding: 2px 0; vertical-align: top; white-space: nowrap;">Nama</td>
            <td style="width: 16px; padding: 2px 0; text-align: center; vertical-align: top;">:</td>
            <td style="padding: 2px 0; vertical-align: top;">{pjbu_name}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top; white-space: nowrap;">Nama Badan Usaha</td>
            <td style="padding: 2px 0; text-align: center; vertical-align: top;">:</td>
            <td style="padding: 2px 0; vertical-align: top;">{company_name}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top; white-space: nowrap;">Alamat Badan Usaha</td>
            <td style="padding: 2px 0; text-align: center; vertical-align: top;">:</td>
            <td style="padding: 2px 0; vertical-align: top;">{company_address}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top; white-space: nowrap;">Telepon</td>
            <td style="padding: 2px 0; text-align: center; vertical-align: top;">:</td>
            <td style="padding: 2px 0; vertical-align: top;">{company_phone}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top; white-space: nowrap;">Email</td>
            <td style="padding: 2px 0; text-align: center; vertical-align: top;">:</td>
            <td style="padding: 2px 0; vertical-align: top;">{company_email}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top; white-space: nowrap;">Jabatan</td>
            <td style="padding: 2px 0; text-align: center; vertical-align: top;">:</td>
            <td style="padding: 2px 0; vertical-align: top;">{pjbu_position}</td>
        </tr>
    </table>

    <p style="margin: 0 0 9px 0;">Menyatakan dengan sesungguhnya bahwa :</p>

    <ol style="margin: 0 0 18px 18px; padding: 0; text-align: justify;">
        <li style="margin-bottom: 7px; padding-left: 5px;">Bahwa benar Penanggung Jawab Badan Usaha (PJBU), Penanggung Jawab Teknik Badan Usaha (PJTBU) dan Penanggung Jawab Sub Klasifikasi Badan Usaha (PJSKBU) yang diajukan bukan/tidak menjabat sebagai Aparatur Sipil Negara (ASN) dan bekerja penuh waktu pada Badan Usaha kami (daftar terlampir).</li>
        <li style="margin-bottom: 7px; padding-left: 5px;">Segala data dalam dokumen yang kami berikan adalah terbaru dan benar serta final.</li>
        <li style="margin-bottom: 7px; padding-left: 5px;">Kami akan mematuhi segala ketentuan kode etik asosiasi, ketentuan LSBU dan LPJK serta peraturan perundangan yang berlaku, dan bersedia dikenakan sanksi bilamana kami melanggarnya.</li>
        <li style="margin-bottom: 7px; padding-left: 5px;">Apabila dikemudian hari, ditemui bahwa dokumen-dokumen yang telah kami berikan tidak benar, maka kami bersedia dikenakan sanksi dan dimasukkan pada Daftar Sanksi Badan Usaha dan atau dikeluarkan dari Daftar Registrasi Badan Usaha.</li>
        <li style="margin-bottom: 7px; padding-left: 5px;">Bilamana badan usaha kami dikenakan sanksi atas hal-hal tersebut, maka kami akan menerima ketentuan yang ditetapkan termasuk diumumkan melalui situs LSBU dan LPJK.</li>
        <li style="margin-bottom: 7px; padding-left: 5px;">Bilamana dikemudian hari terdapat permasalahan terkait perpajakan dan hukum, sepenuhnya menjadi tanggung jawab kami.</li>
    </ol>

    <p style="margin: 8px 0 0 0;">Demikian pernyataan ini dibuat dengan sesungguhnya.</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 30px; font-size: 12px;">
        <tr>
            <td style="width: 54%;"></td>
            <td style="width: 46%; text-align: center; vertical-align: top;">
                <p style="margin: 0 0 4px 0;">{signing_place}, {current_date}</p>
                <p style="margin: 0 0 4px 0;">{company_name}</p>
                <div style="position: relative; height: 84px; margin: 0 auto;">
                    <div style="position: absolute; top: -4px; left: 38px; opacity: 0.82; z-index: 1;">{stamp_img}</div>
                    <div style="position: absolute; top: 15px; left: 82px; z-index: 2;">{signature_img}</div>
                </div>
                <p style="font-weight: bold; text-decoration: underline; margin: 0 0 2px 0;">{pjbu_name}</p>
                <p style="margin: 0;">{pjbu_position}</p>
            </td>
        </tr>
    </table>
</div>
HTML;
    }
};
