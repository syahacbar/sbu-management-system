<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Neraca Keuangan - {{ $company->name }}</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #0f172a;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
        .header-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 8px;
            color: #475569;
            margin: 1px 0 0 0;
        }
        .doc-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .meta-table td {
            padding: 2px 4px;
        }
        .meta-table td.label {
            font-weight: bold;
            width: 120px;
        }
        .meta-table td.separator {
            width: 10px;
            text-align: center;
        }
        .master-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .master-grid > tr > td {
            vertical-align: top;
            border: none;
        }
        .section-table {
            width: 100%;
            border-collapse: collapse;
        }
        .section-table th, .section-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            font-size: 9px;
            text-align: left;
        }
        .section-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
        }
        .group-header {
            background-color: #f8fafc;
            font-weight: bold;
            font-size: 9px;
            color: #475569;
        }
        .total-row {
            font-weight: bold;
            background-color: #f1f5f9;
        }
        .grand-total-row {
            font-weight: bold;
            background-color: #e2e8f0;
            font-size: 9px;
        }
        .amount-col {
            text-align: right !important;
            font-family: Courier, monospace;
            width: 80px;
        }
        .signature-block {
            float: right;
            width: 200px;
            margin-top: 15px;
            text-align: center;
        }
        .signature-date {
            margin-bottom: 5px;
        }
        .signature-space {
            height: 55px;
            position: relative;
        }
        .stamp-img {
            position: absolute;
            top: -10px;
            left: 10px;
            max-height: 45px;
            opacity: 0.8;
            z-index: 1;
        }
        .tte-img {
            position: absolute;
            top: 5px;
            left: 30px;
            max-height: 35px;
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
        <div class="header-title">Neraca Keuangan Dinamis</div>
        <div class="header-subtitle">Sistem Informasi Pengajuan Sertifikat Badan Usaha (SBU)</div>
    </div>

    <div class="doc-title">
        NERACA KEUANGAN PERUSAHAAN TAHUN {{ $statement->year_two }} DAN {{ $statement->year_one }}
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Nama Perusahaan</td>
            <td class="separator">:</td>
            <td><strong>{{ $company->name }}</strong></td>
            <td class="label" style="text-align: right;">Tanggal Laporan</td>
            <td class="separator" style="text-align: right;">:</td>
            <td style="width: 100px;">{{ $formattedDate }}</td>
        </tr>
        <tr>
            <td class="label">NPWP Perusahaan</td>
            <td class="separator">:</td>
            <td>{{ $company->npwp ?: '-' }}</td>
            <td class="label" style="text-align: right;">No. Pengajuan SBU</td>
            <td class="separator" style="text-align: right;">:</td>
            <td>{{ $application->application_number }}</td>
        </tr>
    </table>

    <table class="master-grid">
        <tr>
            <!-- LEFT COLUMN: AKTIVA -->
            <td style="width: 50%; padding-right: 8px;">
                <table class="section-table">
                    <thead>
                        <tr>
                            <th>Uraian Aktiva</th>
                            <th class="amount-col">Th. {{ $statement->year_two }}</th>
                            <th class="amount-col">Th. {{ $statement->year_one }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aktiva Lancar Group -->
                        <tr class="group-header">
                            <td colspan="3">A. AKTIVA LANCAR</td>
                        </tr>
                        @forelse($aktivaLancar as $val)
                            <tr>
                                <td>{{ $val->masterItem->name }}</td>
                                <td class="amount-col">{{ number_format($val->year_two_amount, 0, ',', '.') }}</td>
                                <td class="amount-col">{{ number_format($val->year_one_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="font-style: italic; color: #64748b; text-align: center;">Tidak ada data aktiva lancar</td>
                            </tr>
                        @endforelse
                        <tr class="total-row">
                            <td>TOTAL AKTIVA LANCAR</td>
                            <td class="amount-col">{{ number_format($statement->total_aktiva_lancar_year_two, 0, ',', '.') }}</td>
                            <td class="amount-col">{{ number_format($statement->total_aktiva_lancar_year_one, 0, ',', '.') }}</td>
                        </tr>

                        <!-- Aktiva Tetap Group -->
                        <tr class="group-header">
                            <td colspan="3">B. AKTIVA TETAP</td>
                        </tr>
                        @forelse($aktivaTetap as $val)
                            <tr>
                                <td>{{ $val->masterItem->name }}</td>
                                <td class="amount-col">{{ number_format($val->year_two_amount, 0, ',', '.') }}</td>
                                <td class="amount-col">{{ number_format($val->year_one_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="font-style: italic; color: #64748b; text-align: center;">Tidak ada data aktiva tetap</td>
                            </tr>
                        @endforelse
                        <tr class="total-row">
                            <td>TOTAL AKTIVA TETAP</td>
                            <td class="amount-col">{{ number_format($statement->total_aktiva_tetap_year_two, 0, ',', '.') }}</td>
                            <td class="amount-col">{{ number_format($statement->total_aktiva_tetap_year_one, 0, ',', '.') }}</td>
                        </tr>

                        <!-- Grand Total Aktiva -->
                        <tr class="grand-total-row">
                            <td>JUMLAH AKTIVA (A + B)</td>
                            <td class="amount-col">{{ number_format($statement->total_aktiva_year_two, 0, ',', '.') }}</td>
                            <td class="amount-col">{{ number_format($statement->total_aktiva_year_one, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <!-- RIGHT COLUMN: PASIVA -->
            <td style="width: 50%; padding-left: 8px;">
                <table class="section-table">
                    <thead>
                        <tr>
                            <th>Uraian Pasiva & Ekuitas</th>
                            <th class="amount-col">Th. {{ $statement->year_two }}</th>
                            <th class="amount-col">Th. {{ $statement->year_one }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Kewajiban Group -->
                        <tr class="group-header">
                            <td colspan="3">C. KEWAJIBAN / UTANG</td>
                        </tr>
                        @forelse($kewajiban as $val)
                            <tr>
                                <td>{{ $val->masterItem->name }}</td>
                                <td class="amount-col">{{ number_format($val->year_two_amount, 0, ',', '.') }}</td>
                                <td class="amount-col">{{ number_format($val->year_one_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="font-style: italic; color: #64748b; text-align: center;">Tidak ada data kewajiban</td>
                            </tr>
                        @endforelse
                        <tr class="total-row">
                            <td>TOTAL KEWAJIBAN</td>
                            <td class="amount-col">{{ number_format($statement->total_kewajiban_year_two, 0, ',', '.') }}</td>
                            <td class="amount-col">{{ number_format($statement->total_kewajiban_year_one, 0, ',', '.') }}</td>
                        </tr>

                        <!-- Ekuitas Group -->
                        <tr class="group-header">
                            <td colspan="3">D. EKUITAS / MODAL</td>
                        </tr>
                        @forelse($ekuitas as $val)
                            <tr>
                                <td>{{ $val->masterItem->name }}</td>
                                <td class="amount-col">{{ number_format($val->year_two_amount, 0, ',', '.') }}</td>
                                <td class="amount-col">{{ number_format($val->year_one_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="font-style: italic; color: #64748b; text-align: center;">Tidak ada data ekuitas</td>
                            </tr>
                        @endforelse
                        <tr class="total-row">
                            <td>TOTAL EKUITAS</td>
                            <td class="amount-col">{{ number_format($statement->total_ekuitas_year_two, 0, ',', '.') }}</td>
                            <td class="amount-col">{{ number_format($statement->total_ekuitas_year_one, 0, ',', '.') }}</td>
                        </tr>

                        <!-- Grand Total Pasiva & Net Worth -->
                        <tr class="grand-total-row">
                            <td>JUMLAH PASIVA & EKUITAS (C + D)</td>
                            <td class="amount-col">{{ number_format($statement->total_kewajiban_year_two + $statement->total_ekuitas_year_two, 0, ',', '.') }}</td>
                            <td class="amount-col">{{ number_format($statement->total_kewajiban_year_one + $statement->total_ekuitas_year_one, 0, ',', '.') }}</td>
                        </tr>
                        
                        <tr class="grand-total-row" style="background-color: #cbd5e1; color: #000;">
                            <td>KEKAYAAN BERSIH (AKTIVA - KEWAJIBAN)</td>
                            <td class="amount-col">{{ number_format($statement->kekayaan_bersih_year_two, 0, ',', '.') }}</td>
                            <td class="amount-col">{{ number_format($statement->kekayaan_bersih_year_one, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
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
