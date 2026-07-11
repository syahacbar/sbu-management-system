<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPTJM - {{ $company->name }}</title>
    <style>
        @page {
            size: a4;
            margin: 24mm 20mm 20mm 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.45;
            color: #111827;
        }
        .doc-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0 0 24px 0;
            letter-spacing: 0;
        }
        .intro {
            margin: 0 0 10px 0;
        }
        .identity-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 18px 0;
        }
        .identity-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .identity-table .label {
            width: 155px;
            white-space: nowrap;
        }
        .identity-table .separator {
            width: 16px;
            text-align: center;
        }
        .identity-table .value {
            font-weight: normal;
        }
        .statement-title {
            margin: 0 0 9px 0;
        }
        .statements {
            margin: 0 0 18px 18px;
            padding: 0;
            text-align: justify;
        }
        .statements li {
            margin-bottom: 7px;
            padding-left: 5px;
        }
        .closing {
            margin: 8px 0 0 0;
        }
        .signature-block {
            float: right;
            width: 260px;
            margin-top: 30px;
            text-align: center;
        }
        .signature-date,
        .signature-company {
            margin-bottom: 4px;
        }
        .signature-space {
            height: 84px;
            position: relative;
        }
        .stamp-img {
            position: absolute;
            top: -4px;
            left: 38px;
            max-height: 70px;
            opacity: 0.82;
            z-index: 1;
        }
        .tte-img {
            position: absolute;
            top: 15px;
            left: 82px;
            max-height: 52px;
            z-index: 2;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-position {
            margin-top: 2px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <h1 class="doc-title">Surat Pernyataan Tanggung Jawab Mutlak</h1>

    <p class="intro">Yang bertandatangan di bawah ini :</p>

    <table class="identity-table">
        <tr>
            <td class="label">Nama</td>
            <td class="separator">:</td>
            <td class="value">{{ $pjbu->name }}</td>
        </tr>
        <tr>
            <td class="label">Nama Badan Usaha</td>
            <td class="separator">:</td>
            <td class="value">{{ $company->name }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Badan Usaha</td>
            <td class="separator">:</td>
            <td class="value">{{ $company->address ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Telepon</td>
            <td class="separator">:</td>
            <td class="value">{{ $company->phone ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td class="separator">:</td>
            <td class="value">{{ $company->email ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="separator">:</td>
            <td class="value">{{ $pjbu->position ?: 'Penanggung Jawab Badan Usaha' }}</td>
        </tr>
    </table>

    <p class="statement-title">Menyatakan dengan sesungguhnya bahwa :</p>

    <ol class="statements">
        <li>Bahwa benar Penanggung Jawab Badan Usaha (PJBU), Penanggung Jawab Teknik Badan Usaha (PJTBU) dan Penanggung Jawab Sub Klasifikasi Badan Usaha (PJSKBU) yang diajukan bukan/tidak menjabat sebagai Aparatur Sipil Negara (ASN) dan bekerja penuh waktu pada Badan Usaha kami (daftar terlampir).</li>
        <li>Segala data dalam dokumen yang kami berikan adalah terbaru dan benar serta final.</li>
        <li>Kami akan mematuhi segala ketentuan kode etik asosiasi, ketentuan LSBU dan LPJK serta peraturan perundangan yang berlaku, dan bersedia dikenakan sanksi bilamana kami melanggarnya.</li>
        <li>Apabila dikemudian hari, ditemui bahwa dokumen-dokumen yang telah kami berikan tidak benar, maka kami bersedia dikenakan sanksi dan dimasukkan pada Daftar Sanksi Badan Usaha dan atau dikeluarkan dari Daftar Registrasi Badan Usaha.</li>
        <li>Bilamana badan usaha kami dikenakan sanksi atas hal-hal tersebut, maka kami akan menerima ketentuan yang ditetapkan termasuk diumumkan melalui situs LSBU dan LPJK.</li>
        <li>Bilamana dikemudian hari terdapat permasalahan terkait perpajakan dan hukum, sepenuhnya menjadi tanggung jawab kami.</li>
    </ol>

    <p class="closing">Demikian pernyataan ini dibuat dengan sesungguhnya.</p>

    <div class="signature-block">
        <div class="signature-date">{{ $company->signing_place ?: 'Jayapura' }}, {{ $formattedDate }}</div>
        <div class="signature-company">{{ $company->name }}</div>
        <div class="signature-space">
            @if(isset($stampBase64) && $stampBase64)
                <img src="{{ $stampBase64 }}" class="stamp-img" alt="Stempel">
            @endif
            @if(isset($signatureBase64) && $signatureBase64)
                <img src="{{ $signatureBase64 }}" class="tte-img" alt="TTE">
            @endif
        </div>
        <div class="signature-name">{{ $pjbu->name }}</div>
        <div class="signature-position">{{ $pjbu->position ?: 'Penanggung Jawab Badan Usaha' }}</div>
    </div>

    <div class="clear"></div>
</body>
</html>
