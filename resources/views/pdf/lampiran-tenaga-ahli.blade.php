<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lampiran Tenaga Ahli - {{ $company->name }}</title>
    <style>
        @page {
            size: a4;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
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
        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
            background-color: #f1f5f9;
            padding: 4px 8px;
            border-left: 3px solid #0f172a;
        }
        .table-info {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .table-info td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .table-info td.label {
            width: 160px;
            font-weight: bold;
            color: #475569;
        }
        .table-info td.separator {
            width: 10px;
            text-align: center;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            margin-top: 5px;
        }
        .table-data th, .table-data td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
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
            margin-top: 25px;
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
        <div class="header-title">Lampiran Tenaga Ahli (PJTBU & PJSKBU)</div>
        <div class="header-subtitle">Sistem Informasi Pengajuan Sertifikat Badan Usaha (SBU)</div>
    </div>

    <div class="doc-title">
        LAMPIRAN DATA TENAGA AHLI BADAN USAHA
    </div>

    <div class="section-title">Informasi Klasifikasi & Skema SBU</div>
    <table class="table-info">
        <tr>
            <td class="label">KBLI</td>
            <td class="separator">:</td>
            <td>
                @if($application->kbli)
                    <strong>{{ $application->kbli->code }}</strong> - {{ $application->kbli->name }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Klasifikasi SBU</td>
            <td class="separator">:</td>
            <td>
                @if($application->classification)
                    <strong>{{ $application->classification->code }}</strong> - {{ $application->classification->name }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Subklasifikasi SBU</td>
            <td class="separator">:</td>
            <td>
                @if($application->subclassification)
                    <strong>{{ $application->subclassification->code }}</strong> - {{ $application->subclassification->name }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Kualifikasi SBU</td>
            <td class="separator">:</td>
            <td>{{ $application->qualification ?: ($application->scheme?->qualification ?? '-') }}</td>
        </tr>
    </table>

    <div class="section-title">Identitas Penanggung Jawab Badan Usaha (PJBU)</div>
    <table class="table-info">
        <tr>
            <td class="label">Nama PJBU Utama</td>
            <td class="separator">:</td>
            <td><strong>{{ $pjbu->name }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIK / NPWP</td>
            <td class="separator">:</td>
            <td>{{ $pjbu->nik }} / {{ $pjbu->npwp ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="separator">:</td>
            <td>{{ ucfirst($pjbu->position ?: 'PJBU Utama') }}</td>
        </tr>
    </table>

    <!-- PJTBU Table -->
    <div class="section-title">Daftar Penanggung Jawab Teknis Badan Usaha (PJTBU)</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama Tenaga Ahli</th>
                <th style="width: 25%">No. Reg SKK / NIK</th>
                <th style="width: 20%">Klasifikasi / Subklasifikasi SKK</th>
                <th style="width: 15%">Kualifikasi / Jenjang</th>
                <th style="width: 15%">Tgl Terbit SKK</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pjtbuList as $index => $expert)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $expert->name }}</strong></td>
                    <td>
                        {{ $expert->skk_registration_number }}
                        <div style="font-size: 9px; color: #64748b;">NIK: {{ $expert->nik }}</div>
                    </td>
                    <td>
                        {{ $expert->skk_classification }}
                        <div style="font-size: 9px; color: #64748b;">Sub: {{ $expert->skk_subclassification ?: '-' }}</div>
                    </td>
                    <td>
                        {{ $expert->skk_qualification }}
                        <div style="font-size: 9px; color: #64748b;">Jenjang: {{ $expert->skk_level ?: '-' }}</div>
                    </td>
                    <td>{{ $expert->skk_issued_at ? date('d/m/Y', strtotime($expert->skk_issued_at)) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">Belum ada Tenaga Ahli PJTBU terdaftar pada pengajuan aktif ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- PJSKBU Table -->
    <div class="section-title">Daftar Penanggung Jawab Subklasifikasi Badan Usaha (PJSKBU)</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama Tenaga Ahli</th>
                <th style="width: 25%">No. Reg SKK / NIK</th>
                <th style="width: 20%">Klasifikasi / Subklasifikasi SKK</th>
                <th style="width: 15%">Kualifikasi / Jenjang</th>
                <th style="width: 15%">Tgl Terbit SKK</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pjskbuList as $index => $expert)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $expert->name }}</strong></td>
                    <td>
                        {{ $expert->skk_registration_number }}
                        <div style="font-size: 9px; color: #64748b;">NIK: {{ $expert->nik }}</div>
                    </td>
                    <td>
                        {{ $expert->skk_classification }}
                        <div style="font-size: 9px; color: #64748b;">Sub: {{ $expert->skk_subclassification ?: '-' }}</div>
                    </td>
                    <td>
                        {{ $expert->skk_qualification }}
                        <div style="font-size: 9px; color: #64748b;">Jenjang: {{ $expert->skk_level ?: '-' }}</div>
                    </td>
                    <td>{{ $expert->skk_issued_at ? date('d/m/Y', strtotime($expert->skk_issued_at)) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">Belum ada Tenaga Ahli PJSKBU terdaftar pada pengajuan aktif ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

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
