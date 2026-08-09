<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template JSA - {{ $workOrder->no_wo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            width: 150px;
        }
        .jsa-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .jsa-table th, .jsa-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .jsa-table th {
            background-color: #f0f0f0;
            text-align: center;
        }
        .jsa-table td {
            height: 40px; /* Empty rows height */
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
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print print-btn">Cetak Template (Print)</button>
    <button onclick="window.close()" class="no-print print-btn" style="background-color: #6c757d;">Tutup</button>

    <div class="company-header" style="display: flex; align-items: center; gap: 15px;">
        @php
            $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
            $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS Aisfar';
            $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
            $siteCode = $workOrder->unit->siteRelation->code ?? (is_string($workOrder->unit->site) ? $workOrder->unit->site : ($workOrder->site->code ?? auth()->user()->site?->code ?? ''));
            if ($siteCode) {
                $appName .= ' - ' . $siteCode;
            }
        @endphp
        @if($appLogo)
            <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="height: 50px;">
        @endif
        <div>
            <h1 style="margin: 0; font-size: 20px;">{{ $appName }}</h1>
            <p style="margin: 3px 0 0 0; font-size: 12px; color: #555;">{{ $appAddress }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="header" style="margin-top: 15px;">
        <h2>Job Safety Analysis (JSA)</h2>
        <p>Formulir Identifikasi Bahaya dan Pengendalian Risiko</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nomor Work Order</td>
            <td>: {{ $workOrder->no_wo }}</td>
            <td class="label">Tanggal Dibuat</td>
            <td>: ____________________</td>
        </tr>
        <tr>
            <td class="label">Identitas Unit</td>
            <td>: {{ $workOrder->unit->nomor_unit ?? '-' }} ({{ $workOrder->unit->model->name ?? '-' }})</td>
            <td class="label">Dibuat Oleh</td>
            <td>: ____________________</td>
        </tr>
        <tr>
            <td class="label">Deskripsi Pekerjaan</td>
            <td colspan="3">: {{ $workOrder->description }}</td>
        </tr>
    </table>

    <table class="jsa-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Uraian Langkah Kerja (Job Steps)</th>
                <th style="width: 30%;">Potensi Bahaya (Potential Hazards)</th>
                <th style="width: 30%;">Tindakan Pengendalian (Control Measures)</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 1; $i <= 10; $i++)
            <tr>
                <td style="text-align: center;">{{ $i }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Dibuat Oleh,</p>
            <div class="signature-space"></div>
            <p>( _______________________ )</p>
            <p>Leader / Pelaksana</p>
        </div>
        <div class="signature-box">
            <p>Diperiksa Oleh,</p>
            <div class="signature-space"></div>
            <p>( _______________________ )</p>
            <p>Supervisor</p>
        </div>
        <div class="signature-box">
            <p>Disetujui Oleh,</p>
            <div class="signature-space"></div>
            <p>( _______________________ )</p>
            <p>HSE / Safety Officer</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
