<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Berita Acara Penerimaan BBM - {{ $receiving->receiving_number }}</title>
  <style>
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #1e293b; margin: 0; padding: 15px; }
    .title { font-size: 15px; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 3px; letter-spacing: 0.5px; }
    .subtitle { font-size: 11px; text-align: center; color: #64748b; margin-bottom: 15px; }
    .section-title { font-size: 11px; font-weight: bold; background-color: #f1f5f9; padding: 4px 8px; border-left: 3px solid #2563eb; margin: 12px 0 6px 0; text-transform: uppercase; }
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 5px 8px; text-align: left; vertical-align: middle; }
    table.data-table th { background-color: #f8fafc; font-weight: bold; width: 30%; color: #334155; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .highlight { background-color: #eff6ff; font-weight: bold; color: #1e40af; }
    .sig-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
    .sig-table td { width: 33.33%; text-align: center; vertical-align: top; padding: 5px; }
    .sig-box { border: 1px dashed #94a3b8; height: 70px; margin: 8px 15px; border-radius: 4px; }
  </style>
</head>
<body>

  @include('partials.kop-surat-pdf', [
    'docTitle' => 'BERITA ACARA PENERIMAAN BBM',
    'docNumber' => $receiving->receiving_number,
    'docDate' => $receiving->date_receive ? $receiving->date_receive->format('d/m/Y') : date('d/m/Y'),
    'siteName' => $receiving->storage->site->name ?? $receiving->site->name ?? null,
  ])

  <div class="subtitle">
    Dokumen Resmi Penerimaan & Pengukuran Fisik (Sonding) Bahan Bakar Minyak di Site
  </div>

  <div class="section-title">1. Informasi Pengiriman & Vendor</div>
  <table class="data-table">
    <tr>
      <th>Vendor / Supplier</th>
      <td><strong>{{ $receiving->vendor->name ?? '-' }}</strong></td>
      <th>Waktu Terima Fisik</th>
      <td>{{ $receiving->date_receive ? $receiving->date_receive->format('d F Y, H:i') : '-' }} WITA</td>
    </tr>
    <tr>
      <th>No. Surat Jalan (DO)</th>
      <td style="font-family: monospace;">{{ $receiving->delivery_order_number }}</td>
      <th>No. Purchase Order (PO)</th>
      <td style="font-family: monospace;">{{ $receiving->po_number ?? '-' }}</td>
    </tr>
    <tr>
      <th>No. Polisi Truk Supplier</th>
      <td style="font-family: monospace; font-weight: bold;">{{ $receiving->truck_plat_nomor ?? '-' }}</td>
      <th>Nama Supir / Driver</th>
      <td>{{ $receiving->driver_name ?? '-' }}</td>
    </tr>
  </table>

  <div class="section-title">2. Tangki Tujuan & Pengukuran Fisik (Sonding)</div>
  <table class="data-table">
    <tr>
      <th>Tangki Timbun Penerima</th>
      <td colspan="3"><strong>{{ $receiving->storage->code ?? '' }} - {{ $receiving->storage->name ?? '' }}</strong> (Site: {{ $receiving->storage->site->name ?? '-' }})</td>
    </tr>
    <tr>
      <th>Sonding Awal Cairan</th>
      <td>{{ $receiving->sonding_awal_cm ?? 0 }} cm</td>
      <th>Sonding Akhir Cairan</th>
      <td>{{ $receiving->sonding_akhir_cm ?? 0 }} cm</td>
    </tr>
    <tr>
      <th>Densitas BBM</th>
      <td>{{ $receiving->density ? $receiving->density . ' g/ml' : '-' }}</td>
      <th>Suhu / Temperatur</th>
      <td>{{ $receiving->temperature ? $receiving->temperature . ' °C' : '-' }}</td>
    </tr>
    <tr>
      <th>Totalizer Pompa Awal</th>
      <td style="font-family: monospace;">{{ number_format($receiving->totalizer_before ?? 0, 2) }}</td>
      <th>Totalizer Pompa Akhir</th>
      <td style="font-family: monospace;">{{ number_format($receiving->totalizer_after ?? 0, 2) }}</td>
    </tr>
  </table>

  <div class="section-title">3. Rekonsiliasi Volume Bahan Bakar</div>
  <table class="data-table">
    <tr>
      <th>Volume Tertulis di Surat Jalan (DO)</th>
      <td class="text-right" style="font-size: 12px; width: 25%;">{{ number_format($receiving->do_volume_liters, 0, ',', '.') }} Liter</td>
      <th rowspan="3">Status Dokumen</th>
      <td rowspan="3" class="text-center" style="font-size: 13px; font-weight: bold; color: {{ $receiving->status == 'Approved' ? '#16a34a' : '#ea580c' }};">
        {{ strtoupper($receiving->status) }}
      </td>
    </tr>
    <tr class="highlight">
      <th>Volume Aktual Fisik Diterima</th>
      <td class="text-right" style="font-size: 13px;">{{ number_format($receiving->received_volume_liters, 0, ',', '.') }} Liter</td>
    </tr>
    <tr>
      <th>Selisih Volume (Losses / Gain)</th>
      <td class="text-right" style="font-weight: bold; color: {{ $receiving->losses_volume_liters < 0 ? '#dc2626' : '#16a34a' }};">
        {{ ($receiving->losses_volume_liters > 0 ? '+' : '') . number_format($receiving->losses_volume_liters, 0, ',', '.') }} Liter
      </td>
    </tr>
  </table>

  @if($receiving->notes)
  <div style="font-size: 10px; color: #475569; margin-top: 5px; font-style: italic;">
    <strong>Catatan Pemeriksaan:</strong> {{ $receiving->notes }}
  </div>
  @endif

  <!-- Signatures -->
  <table class="sig-table">
    <tr>
      <td>
        <div style="font-size: 10px; color: #64748b;">Pengantar BBM (Supir Vendor)</div>
        <div class="sig-box"></div>
        <strong>{{ $receiving->driver_name ?? 'Supir Vendor' }}</strong><br>
        <small style="color: #64748b;">Driver Truk Tangki</small>
      </td>
      <td>
        <div style="font-size: 10px; color: #64748b;">Petugas Penerima (Fuelman)</div>
        <div class="sig-box"></div>
        <strong>{{ $receiving->receiver->nama_lengkap ?? $receiving->receiver->name ?? 'Petugas Fuelman' }}</strong><br>
        <small style="color: #64748b;">Fuelman / Warehouse Staff</small>
      </td>
      <td>
        <div style="font-size: 10px; color: #64748b;">Disetujui Oleh (Approver / Supervisor)</div>
        <div class="sig-box"></div>
        <strong>{{ $receiving->approver->nama_lengkap ?? $receiving->approver->name ?? $receiving->intendedApprover->nama_lengkap ?? $receiving->intendedApprover->name ?? 'Supervisor / Planner' }}</strong><br>
        <small style="color: #64748b;">{{ $receiving->intendedApprover->jabatan ?? 'Fuel / Site Supervisor' }}</small>
      </td>
    </tr>
  </table>

</body>
</html>
