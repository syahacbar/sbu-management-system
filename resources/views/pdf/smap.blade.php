<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SMAP - {{ $company->name }}</title>
    <style>
        @page {
            size: a4;
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #0f172a;
        }
        .document-header {
            border-bottom: 2px solid #0f172a;
            margin-bottom: 22px;
            padding-bottom: 10px;
            text-align: center;
        }
        .document-header-title {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .document-header-subtitle {
            color: #475569;
            font-size: 10px;
            margin: 3px 0 0;
        }
        .document-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 18px;
            text-align: center;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .section {
            margin-bottom: 16px;
        }
        .section-title {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 8px;
            padding: 6px 8px;
            text-transform: uppercase;
        }
        .identity-table {
            border-collapse: collapse;
            width: 100%;
        }
        .identity-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .identity-table .label {
            color: #475569;
            font-weight: bold;
            width: 185px;
        }
        .identity-table .separator {
            text-align: center;
            width: 16px;
        }
        .paragraph {
            margin: 0 0 10px;
            text-align: justify;
        }
        .signature-block {
            float: right;
            margin-top: 22px;
            text-align: center;
            width: 255px;
        }
        .signature-space {
            height: 72px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <header class="document-header">
        <h1 class="document-header-title">Dokumen SMAP</h1>
        <p class="document-header-subtitle">Sistem Manajemen Anti Penyuapan untuk Pengajuan Sertifikat Badan Usaha</p>
    </header>

    <h2 class="document-title">Pernyataan Komitmen Penerapan SMAP</h2>

    <section class="section">
        <h3 class="section-title">Identitas Badan Usaha</h3>
        <table class="identity-table">
            <tr>
                <td class="label">Nama Badan Usaha</td>
                <td class="separator">:</td>
                <td><strong>{{ $company->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIB</td>
                <td class="separator">:</td>
                <td>{{ $company->nib ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">NPWP</td>
                <td class="separator">:</td>
                <td>{{ $company->npwp ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td>{{ $company->address ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Telepon / Email</td>
                <td class="separator">:</td>
                <td>{{ $company->phone ?: '-' }} / {{ $company->email ?: '-' }}</td>
            </tr>
        </table>
    </section>

    <section class="section">
        <h3 class="section-title">Pernyataan Komitmen</h3>
        <p class="paragraph">
            Yang bertanda tangan di bawah ini, selaku Penanggung Jawab Badan Usaha (PJBU) dari
            <strong>{{ $company->name }}</strong>, menyatakan berkomitmen untuk menerapkan Sistem Manajemen Anti
            Penyuapan (SMAP) secara konsisten dalam seluruh proses kegiatan badan usaha, termasuk dalam proses
            pengajuan Sertifikat Badan Usaha (SBU).
        </p>
        <p class="paragraph">
            Badan usaha berkomitmen untuk mencegah, menolak, dan tidak menoleransi segala bentuk penyuapan,
            gratifikasi yang tidak sesuai ketentuan, konflik kepentingan, atau tindakan lain yang dapat
            mencederai integritas proses sertifikasi dan kegiatan usaha jasa konstruksi.
        </p>
    </section>

    <section class="section">
        <h3 class="section-title">Informasi Pengajuan SBU</h3>
        <table class="identity-table">
            <tr>
                <td class="label">Nomor Pengajuan</td>
                <td class="separator">:</td>
                <td>{{ $sbu_application->application_number ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tipe Pengajuan</td>
                <td class="separator">:</td>
                <td>{{ $sbu_application->application_type ? ucfirst($sbu_application->application_type) : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Pengajuan</td>
                <td class="separator">:</td>
                <td>{{ $sbu_application->application_year ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Klasifikasi / Subklasifikasi</td>
                <td class="separator">:</td>
                <td>
                    {{ $sbu_application->classification?->code ?: '-' }}
                    /
                    {{ $sbu_application->subclassification?->code ?: '-' }}
                </td>
            </tr>
            <tr>
                <td class="label">Skema SBU</td>
                <td class="separator">:</td>
                <td>{{ $sbu_application->scheme?->scheme_code ?: '-' }} - {{ $sbu_application->scheme?->scheme_name ?: '-' }}</td>
            </tr>
        </table>
    </section>

    <section class="section">
        <h3 class="section-title">Penandatangan</h3>
        <table class="identity-table">
            <tr>
                <td class="label">Nama PJBU</td>
                <td class="separator">:</td>
                <td><strong>{{ $pjbu->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="separator">:</td>
                <td>{{ $pjbu->nik ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td>{{ $pjbu->position ?: 'Penanggung Jawab Badan Usaha' }}</td>
            </tr>
        </table>
    </section>

    <div class="signature-block">
        <div>{{ $company->signing_place ?: '-' }}, {{ $formattedDate }}</div>
        <div>{{ $company->name }}</div>
        <div class="signature-space"></div>
        <div class="signature-name">{{ $pjbu->name }}</div>
        <div>{{ $pjbu->position ?: 'Penanggung Jawab Badan Usaha' }}</div>
    </div>

    <div class="clear"></div>
</body>
</html>
