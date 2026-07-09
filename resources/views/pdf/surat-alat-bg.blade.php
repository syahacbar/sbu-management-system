<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Peralatan BG - {{ $company->name }}</title>
    <style>
        @page {
            size: a4;
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #0f172a;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 9px;
            color: #475569;
            margin: 2px 0 0 0;
        }
        .doc-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        .intro {
            margin-bottom: 12px;
            text-align: justify;
        }
        .table-identity {
            width: 100%;
            margin-bottom: 15px;
            margin-left: 15px;
        }
        .table-identity td {
            padding: 3px 0;
            vertical-align: top;
        }
        .table-identity td.label {
            width: 150px;
            font-weight: bold;
            color: #475569;
        }
        .table-identity td.separator {
            width: 15px;
            text-align: center;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        .table-data th, .table-data td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 10px;
            text-align: left;
        }
        .table-data th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
        }
        .signature-block {
            float: right;
            width: 220px;
            margin-top: 20px;
            text-align: center;
        }
        .signature-date {
            margin-bottom: 10px;
        }
        .signature-space {
            height: 70px;
            position: relative;
        }
        .stamp-img {
            position: absolute;
            top: -10px;
            left: 10px;
            max-height: 55px;
            opacity: 0.8;
            z-index: 1;
        }
        .tte-img {
            position: absolute;
            top: 10px;
            left: 40px;
            max-height: 45px;
            z-index: 2;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .clear {
            clear: both;
        }
        .no-data {
            text-align: center;
            color: #64748b;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-title">Surat Pernyataan Pemenuhan Peralatan BG</div>
        <div class="header-subtitle">Sistem Informasi Pengajuan Sertifikat Badan Usaha (SBU)</div>
    </div>

    <div class="doc-title">
        SURAT PERNYATAAN KELAYAKAN DAN PEMENUHAN PERALATAN (BG)
    </div>

    <div class="intro">
        Yang bertanda tangan di bawah ini mewakili perusahaan berikut:
    </div>

    <table class="table-identity">
        <tr>
            <td class="label">Nama PJBU Utama</td>
            <td class="separator">:</td>
            <td><strong>{{ $pjbu->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nama Badan Usaha</td>
            <td class="separator">:</td>
            <td>{{ $company->name }}</td>
        </tr>
        <tr>
            <td class="label">NPWP Perusahaan</td>
            <td class="separator">:</td>
            <td>{{ $company->npwp ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIB Perusahaan</td>
            <td class="separator">:</td>
            <td>{{ $company->nib ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Kantor</td>
            <td class="separator">:</td>
            <td>{{ $company->address ?: '-' }}</td>
        </tr>
    </table>

    <div class="intro" style="text-align: justify;">
        Dengan ini menyatakan dengan sebenarnya bahwa seluruh peralatan konstruksi kategori **Peralatan Gedung (BG)** yang tercantum di bawah ini adalah benar dikuasai, dimiliki, atau disewa secara sah oleh Badan Usaha kami, siap operasional, serta dipergunakan secara khusus untuk memenuhi persyaratan kualifikasi pengajuan Sertifikat Badan Usaha (SBU) nomor registrasi <strong>{{ $application->application_number }}</strong>.
    </div>

    <!-- Equipments BG Table -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Peralatan</th>
                <th style="width: 25%">Spesifikasi Alat</th>
                <th style="width: 10%; text-align: center;">Jumlah</th>
                <th style="width: 10%">Satuan</th>
                <th style="width: 15%">Kepemilikan</th>
                <th style="width: 10%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipments as $index => $equip)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $equip->name }}</strong></td>
                    <td>{{ $equip->specification ?: '-' }}</td>
                    <td style="text-align: center;">{{ $equip->quantity }}</td>
                    <td>{{ $equip->unit }}</td>
                    <td>
                        @if($equip->ownership_status === 'milik_sendiri')
                            Milik Sendiri
                        @elseif($equip->ownership_status === 'sewa')
                            Sewa
                        @else
                            Pinjam
                        @endif
                    </td>
                    <td>{{ $equip->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="no-data">Belum ada peralatan konstruksi kategori Gedung (BG) terdaftar pada pengajuan aktif ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="intro" style="text-align: justify;">
        Demikian surat pernyataan ini kami buat dengan sadar, tanpa paksaan, dan kami bersedia dituntut secara hukum perdata maupun pidana apabila di kemudian hari terbukti pernyataan ini palsu atau tidak sesuai dengan kondisi riil di lapangan.
    </div>

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
