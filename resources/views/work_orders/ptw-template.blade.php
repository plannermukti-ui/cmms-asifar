<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template PTW - {{ $workOrder->no_wo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #000;
            color-scheme: light;
        }
        .company-header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            width: 150px;
        }
        .ptw-checklist {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .ptw-checklist th, .ptw-checklist td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .ptw-checklist th {
            background-color: #f0f0f0;
            text-align: center;
        }
        .ptw-checklist td {
            height: 30px;
        }
        .signature-section {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            float: left;
            width: 33%;
            text-align: center;
        }
        .signature-space {
            height: 80px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        .print-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .box-check {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print print-btn">Cetak Template (Print)</button>
    <button onclick="window.close()" class="no-print print-btn" style="background-color: #6c757d;">Tutup</button>

    <div class="company-header">
        @php
            $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
            $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS Aisfar';
            $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
            $siteCode = $workOrder->unit->siteRelation->code ?? (is_string($workOrder->unit->site) ? $workOrder->unit->site : ($workOrder->site->code ?? auth()->user()->site?->code ?? ''));
            if ($siteCode) {
                $appName .= ' - ' . $siteCode;
            }
        @endphp
        <div style="flex: 1; display: flex; align-items: center; gap: 15px;">
            @if($appLogo)
                <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="height: 50px;">
            @endif
            <div>
                <h1 style="margin: 0; font-size: 20px;">{{ $appName }}</h1>
                <p style="margin: 3px 0 0 0; font-size: 12px; color: #555;">{{ $appAddress }}</p>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="header">
        <h2>Permit to Work (PTW)</h2>
        <p>{{ $permitType }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nomor Work Order</td>
            <td>: {{ $workOrder->no_wo }}</td>
            <td class="label">Tgl Pengajuan</td>
            <td>: ____________________</td>
        </tr>
        <tr>
            <td class="label">Identitas Unit</td>
            <td>: {{ $workOrder->unit->nomor_unit ?? '-' }} ({{ $workOrder->unit->model->name ?? '-' }})</td>
            <td class="label">Berlaku Dari</td>
            <td>: ____________________</td>
        </tr>
        <tr>
            <td class="label">Deskripsi Pekerjaan</td>
            <td>: {{ $workOrder->tasks->pluck('problem')->filter()->implode(' | ') ?: '-' }}</td>
            <td class="label">Berlaku Sampai</td>
            <td>: ____________________</td>
        </tr>
    </table>

    <table class="ptw-checklist">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Persyaratan / Dokumen Keselamatan (Checklist)</th>
                <th style="width: 15%;">Ya / Tidak</th>
                <th style="width: 35%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Apakah JSA sudah dibuat dan disosialisasikan?</td>
                <td><span class="box-check"></span> Ya &nbsp; <span class="box-check"></span> Tidak</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Apakah pekerja sudah mendapatkan izin khusus / induksi?</td>
                <td><span class="box-check"></span> Ya &nbsp; <span class="box-check"></span> Tidak</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Apakah LOTO (Lockout/Tagout) sudah dipasang?</td>
                <td><span class="box-check"></span> Ya &nbsp; <span class="box-check"></span> Tidak</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Apakah APD (Alat Pelindung Diri) lengkap dan sesuai standar?</td>
                <td><span class="box-check"></span> Ya &nbsp; <span class="box-check"></span> Tidak</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Apakah peralatan kerja sudah diinspeksi dan aman digunakan?</td>
                <td><span class="box-check"></span> Ya &nbsp; <span class="box-check"></span> Tidak</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td>(Khusus Hot Work) Apakah area bebas dari material mudah terbakar?</td>
                <td><span class="box-check"></span> Ya &nbsp; <span class="box-check"></span> Tidak</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td>(Khusus Confined Space) Apakah gas test sudah dilakukan?</td>
                <td><span class="box-check"></span> Ya &nbsp; <span class="box-check"></span> Tidak</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 10px; margin-bottom: 20px;">
        <p style="font-weight: bold;">Catatan Tambahan:</p>
        <div style="border: 1px solid #000; height: 60px; padding: 5px;"></div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Pemohon (Applicant),</p>
            <div class="signature-space"></div>
            <p>( _______________________ )</p>
            <p>Leader / Pelaksana</p>
        </div>
        <div class="signature-box">
            <p>Pemeriksa,</p>
            <div class="signature-space"></div>
            <p>( _______________________ )</p>
            <p>Supervisor</p>
        </div>
        <div class="signature-box">
            <p>Disetujui Oleh (Approver),</p>
            <div class="signature-space"></div>
            <p>( _______________________ )</p>
            <p>HSE / Superintendent</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
