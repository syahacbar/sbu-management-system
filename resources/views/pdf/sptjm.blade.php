<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPTJM - {{ $company->name }}</title>
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
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 10px;
            color: #475569;
            margin: 4px 0 0 0;
        }
        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        .intro {
            margin-bottom: 15px;
            text-align: justify;
        }
        .table-identity {
            width: 100%;
            margin-bottom: 20px;
            margin-left: 20px;
        }
        .table-identity td {
            padding: 4px 0;
            vertical-align: top;
        }
        .table-identity td.label {
            width: 150px;
        }
        .table-identity td.separator {
            width: 15px;
            text-align: center;
        }
        .statements {
            margin-bottom: 25px;
            padding-left: 20px;
            text-align: justify;
        }
        .statements li {
            margin-bottom: 8px;
        }
        .signature-block {
            float: right;
            width: 250px;
            margin-top: 20px;
            text-align: center;
        }
        .signature-date {
            margin-bottom: 15px;
        }
        .signature-space {
            height: 80px;
            position: relative;
        }
        .stamp-img {
            position: absolute;
            top: -10px;
            left: 20px;
            max-height: 60px;
            opacity: 0.8;
            z-index: 1;
        }
        .tte-img {
            position: absolute;
            top: 10px;
            left: 50px;
            max-height: 50px;
            z-index: 2;
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

    <div class="header">
        <div class="header-title">Surat Pernyataan Tanggung Jawab Mutlak</div>
        <div class="header-subtitle">Sistem Informasi Pengajuan Sertifikat Badan Usaha (SBU)</div>
    </div>

    <div class="doc-title">
        SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK (SPTJM)
    </div>

    <div class="intro">
        Yang bertanda tangan di bawah ini:
    </div>

    <table class="table-identity">
        <tr>
            <td class="label">Nama PJBU</td>
            <td class="separator">:</td>
            <td><strong>{{ $pjbu->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="separator">:</td>
            <td>{{ $pjbu->nik }}</td>
        </tr>
        <tr>
            <td class="label">Nama Badan Usaha</td>
            <td class="separator">:</td>
            <td>{{ $company->name }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Badan Usaha</td>
            <td class="separator">:</td>
            <td>{{ $company->address ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Telepon / Email</td>
            <td class="separator">:</td>
            <td>{{ $company->phone ?: '-' }} / {{ $company->email ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="separator">:</td>
            <td>{{ ucfirst($pjbu->position ?: 'Penanggung Jawab Badan Usaha') }}</td>
        </tr>
    </table>

    <div class="intro">
        Dengan ini menyatakan dengan sebenarnya dan bertanggung jawab penuh secara mutlak atas hal-hal sebagai berikut:
    </div>

    <ol class="statements">
        <li>Bahwa seluruh data, informasi, dokumen, dan berkas administrasi yang diunggah serta dilampirkan dalam pengajuan Sertifikat Badan Usaha (SBU) nomor registrasi <strong>{{ $application->application_number }}</strong> ini adalah benar, sah, valid, dan sesuai dengan kondisi fisik riil Badan Usaha kami.</li>
        <li>Bahwa Tenaga Ahli (termasuk PJTBU dan PJSKBU) yang didaftarkan adalah tenaga profesional yang benar bekerja pada Badan Usaha kami, memiliki sertifikasi kompetensi (SKK) yang sah, dan tidak terdaftar secara ganda pada Badan Usaha jasa konstruksi lainnya.</li>
        <li>Bahwa seluruh Peralatan Konstruksi yang kami daftarkan adalah benar dikuasai secara sah oleh Badan Usaha kami dengan status kepemilikan yang sah sesuai bukti kepemilikan/kontrak sewa yang dilampirkan.</li>
        <li>Bahwa Pos Neraca Keuangan dan kekayaan bersih Badan Usaha yang dilaporkan telah disusun sesuai dengan standar akuntansi yang berlaku dan mencerminkan kapasitas keuangan yang sebenarnya tanpa ada manipulasi data.</li>
        <li>Bahwa kami berkomitmen untuk selalu menjaga integritas, mematuhi seluruh regulasi perundang-undangan jasa konstruksi yang berlaku di Republik Indonesia, serta bersedia dilakukan verifikasi lapangan sewaktu-waktu oleh tim LPK/LSBU.</li>
        <li>Apabila di kemudian hari ditemukan bukti bahwa pernyataan ini tidak benar, data yang dilampirkan palsu, atau terdapat manipulasi informasi, maka kami bersedia menerima sanksi administratif berupa pembatalan SBU, dimasukkan ke dalam daftar hitam (blacklist), serta dituntut secara hukum perdata maupun pidana sesuai dengan peraturan perundang-undangan yang berlaku.</li>
    </ol>

    <div class="signature-block">
        <div class="signature-date">
            {{ $company->signing_place ?: 'Jakarta' }}, {{ $formattedDate }}
        </div>
        <div class="signature-company">
            {{ $company->name }}
        </div>
        <div class="signature-space">
            @if(isset($stampBase64) && $stampBase64)
                <img src="{{ $stampBase64 }}" class="stamp-img" alt="Stempel">
            @endif
            @if(isset($signatureBase64) && $signatureBase64)
                <img src="{{ $signatureBase64 }}" class="tte-img" alt="TTE">
            @endif
        </div>
        <div class="signature-name">
            {{ $pjbu->name }}
        </div>
        <div class="signature-position">
            {{ ucfirst($pjbu->position ?: 'Penanggung Jawab Badan Usaha') }}
        </div>
    </div>

    <div class="clear"></div>

</body>
</html>
