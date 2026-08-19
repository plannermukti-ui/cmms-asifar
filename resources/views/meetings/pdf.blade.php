<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notulen Rapat - {{ $meeting->meeting_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .kop-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-sub {
            font-size: 10px;
            color: #555;
        }
        .doc-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 15px 0;
            padding: 4px;
            background-color: #f4f6f8;
            border: 1px solid #ddd;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .meta-table td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 10.5px;
        }
        .meta-label {
            font-weight: bold;
            width: 18%;
            color: #444;
        }
        .meta-sep {
            width: 2%;
        }
        .meta-val {
            width: 30%;
        }
        .box-section {
            border: 1px solid #ccc;
            padding: 6px 8px;
            margin-bottom: 12px;
            background: #fafafa;
            border-radius: 3px;
        }
        .box-title {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 2px;
        }
        .action-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .action-table th, .action-table td {
            border: 1px solid #555;
            padding: 6px;
            font-size: 10px;
        }
        .action-table th {
            background-color: #e9ecef;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .status-badge {
            font-size: 9px;
            font-weight: bold;
            padding: 2px 4px;
            border-radius: 2px;
            text-transform: uppercase;
        }
        .sig-table {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .sig-table td {
            text-align: center;
            vertical-align: bottom;
            width: 50%;
            font-size: 11px;
        }
        .sig-space {
            height: 60px;
        }
        .no-print { display: none; }
        @media screen {
            body { background: #525659; padding: 20px; display: flex; justify-content: center; }
            .page-container { background: white; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.5); max-width: 800px; width: 100%; margin: 0 auto; }
            .no-print { display: block; text-align: center; margin-bottom: 20px; }
            .btn-print { background: #206bc4; color: white; border: none; padding: 10px 20px; font-size: 14px; border-radius: 4px; cursor: pointer; font-weight: bold; font-family: sans-serif; }
            .btn-print:hover { background: #1d5b99; }
        }
        @media print {
            body { background: white; padding: 0; }
            .page-container { padding: 0; box-shadow: none; max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="no-print">
            <button class="btn-print" onclick="window.print()">🖨️ Cetak Dokumen (Print)</button>
        </div>

        {{-- KOP SURAT --}}
        @php
            $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
            $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS AISFAR';
            $appAddress = \App\Models\AppSetting::where('key', 'app_address')->first()?->value ?? '';
            $siteName = $meeting->site->name ?? 'Head Office / All Sites';
        @endphp
        <table class="kop-table">
            <tr>
                <td style="width: 70%; padding-right: 15px;">
                    <table style="width: 100%; border: none;">
                        <tr>
                            @if($appLogo)
                            <td style="width: 50px; vertical-align: top; padding-right: 12px; border: none;">
                                <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="Logo" style="max-height: 45px;">
                            </td>
                            @endif
                            <td style="vertical-align: top; border: none;">
                                <div class="kop-title" style="margin-top: 2px;">{{ $appName }}</div>
                                @if($appAddress)
                                    <div class="kop-sub" style="margin-bottom: 2px;">{{ $appAddress }}</div>
                                @endif
                                <div class="kop-sub" style="font-weight: 500;">Site: {{ $siteName }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 30%; text-align: right; vertical-align: middle;">
                    <div style="font-size: 12px; font-weight: bold; font-family: monospace;">{{ $meeting->meeting_number }}</div>
                    <div style="font-size: 9px; color: #666;">Tgl: {{ $meeting->meeting_date->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>

    {{-- JUDUL DOKUMEN --}}
    <div class="doc-title">
        NOTULEN RAPAT KOORDINASI (MEETING MINUTES)
    </div>

    {{-- METADATA RAPAT --}}
    <table class="meta-table">
        <tr>
            <td class="meta-label">Topik Rapat</td>
            <td class="meta-sep">:</td>
            <td class="meta-val fw-bold" colspan="4">{{ $meeting->title }}</td>
        </tr>
        <tr>
            <td class="meta-label">Jenis Rapat</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $meeting->meeting_type }}</td>
            <td class="meta-label">Tanggal Pelaksanaan</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $meeting->meeting_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Pimpinan Rapat</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $meeting->leader_name ?: '-' }}</td>
            <td class="meta-label">Waktu Pelaksanaan</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">
                {{ $meeting->start_time ? substr($meeting->start_time, 0, 5) : '--:--' }} 
                {{ $meeting->end_time ? 's/d ' . substr($meeting->end_time, 0, 5) : '' }}
            </td>
        </tr>
        <tr>
            <td class="meta-label">Notulis</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $meeting->notetaker_name ?: '-' }}</td>
            <td class="meta-label">Lokasi / Ruangan</td>
            <td class="meta-sep">:</td>
            <td class="meta-val">{{ $meeting->location ?: '-' }}</td>
        </tr>
    </table>

    {{-- PESERTA HADIR --}}
    @if($meeting->attendees)
    <div class="box-section">
        <div class="box-title">Daftar Hadir / Peserta Rapat:</div>
        <div style="white-space: pre-line; font-size: 10px;">{{ $meeting->attendees }}</div>
    </div>
    @endif

    {{-- AGENDA / PEMBAHASAN UMUM --}}
    @if($meeting->agenda)
    <div class="box-section">
        <div class="box-title">Agenda & Pembahasan Umum:</div>
        <div style="white-space: pre-line; font-size: 10px;">{{ $meeting->agenda }}</div>
    </div>
    @endif

    {{-- MATRIKS ACTION ITEMS --}}
    <div style="font-weight: bold; font-size: 11px; text-transform: uppercase; margin-top: 15px;">
        Matriks Butir Tindak Lanjut (Action Items)
    </div>

    <table class="action-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Isu / Permasalahan</th>
                <th style="width: 30%;">Rencana Tindakan / Kesepakatan</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 13%;">PIC</th>
                <th style="width: 10%;">Target Selesai</th>
                <th style="width: 5%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($meeting->actionItems as $item)
            <tr>
                <td class="text-center fw-bold">{{ $item->item_number }}</td>
                <td>
                    <b>{{ $item->issue }}</b>
                    @if($item->parentActionItem)
                        <div style="font-size: 8.5px; color: #b8860b;">(Lanjutan dari {{ $item->parentActionItem->meeting->meeting_number ?? 'Sesi Sebelumnya' }})</div>
                    @endif
                </td>
                <td>
                    <div style="white-space: pre-line;">{{ $item->discussion ?: '-' }}</div>
                    @if($item->latest_update)
                        <div style="font-size: 8.5px; color: #0275d8; margin-top: 3px;">
                            <i>Update: {{ $item->latest_update }}</i>
                        </div>
                    @endif
                </td>
                <td class="text-center">{{ $item->category }}</td>
                <td class="text-center">{{ $item->effective_pic_name }}</td>
                <td class="text-center">
                    {{ $item->due_date ? $item->due_date->format('d/m/Y') : '-' }}
                </td>
                <td class="text-center fw-bold">
                    {{ $item->status }} ({{ $item->progress_percent }}%)
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="color: #666;">Tidak ada butir tindak lanjut dalam rapat ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <table class="sig-table">
        <tr>
            <td>
                <div>Notulis Rapat,</div>
                <div class="sig-space"></div>
                <div class="fw-bold" style="text-decoration: underline;">( {{ $meeting->notetaker_name ?: '...................................' }} )</div>
                <div style="font-size: 9px; color: #555;">Notulis</div>
            </td>
            <td>
                <div>Pimpinan Rapat,</div>
                <div class="sig-space"></div>
                <div class="fw-bold" style="text-decoration: underline;">( {{ $meeting->leader_name ?: '...................................' }} )</div>
                <div style="font-size: 9px; color: #555;">Pimpinan Rapat / Moderator</div>
            </td>
        </tr>
    </table>
    </div>
</body>
</html>
