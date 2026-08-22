<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Berita Acara Flowmeter - {{ $adjustment->adjustment_number }}</title>
  <style>
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #1e293b; margin: 0; padding: 15px; }
    .title { font-size: 15px; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 3px; }
    .subtitle { font-size: 11px; text-align: center; color: #64748b; margin-bottom: 15px; }
    .section-title { font-size: 11px; font-weight: bold; background-color: #f1f5f9; padding: 4px 8px; border-left: 3px solid #7c3aed; margin: 12px 0 6px 0; text-transform: uppercase; }
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; vertical-align: middle; }
    table.data-table th { background-color: #f8fafc; font-weight: bold; width: 30%; color: #334155; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .sig-table { width: 100%; margin-top: 35px; border-collapse: collapse; }
    .sig-table td { width: 33.33%; text-align: center; vertical-align: top; padding: 5px; }
    .sig-box { border: 1px dashed #94a3b8; height: 70px; margin: 8px 15px; border-radius: 4px; }
  </style>
</head>
<body>

  @include('partials.kop-surat-pdf', [
    'docTitle' => 'BERITA ACARA FLOWMETER',
    'docNumber' => $adjustment->adjustment_number,
    'docDate' => $adjustment->incident_date ? $adjustment->incident_date->format('d/m/Y') : date('d/m/Y'),
    'siteName' => $adjustment->site->name ?? null,
  ])

  <div class="subtitle">
    Berita Acara Resmi Pergantian, Kerusakan, Kalibrasi / Tera Ulang Totalizer Flowmeter BBM
  </div>

  <div class="section-title">1. Data Unit / Perangkat Dispenser</div>
  <table class="data-table">
    <tr>
      <th>Tipe Perangkat</th>
      <td><strong>{{ $adjustment->device_type == 'fuel_storage' ? 'Fuel Storage / Tangki Timbun' : 'Mobile Fuel Truck' }}</strong></td>
      <th>Tanggal Kejadian</th>
      <td>{{ $adjustment->incident_date ? $adjustment->incident_date->format('d F Y') : '-' }}</td>
    </tr>
    <tr>
      <th>Nama Unit / Tangki</th>
      <td colspan="3">
        <strong>
          @if($adjustment->device_type == 'fuel_storage')
            {{ $device->code ?? '' }} - {{ $device->name ?? '' }}
          @else
            Fuel Truck: {{ $device->masterUnit->nomor_unit ?? '-' }} ({{ $device->masterUnit->type->name ?? '' }})
          @endif
        </strong>
      </td>
    </tr>
    <tr>
      <th>Jenis Kejadian</th>
      <td colspan="3"><strong style="color: #7c3aed;">{{ strtoupper($adjustment->incident_type) }}</strong></td>
    </tr>
  </table>

  <div class="section-title">2. Data Perubahan Angka Totalizer Flowmeter</div>
  <table class="data-table">
    <tr style="background-color: #f8fafc;">
      <th style="width: 50%; text-align: center;">Flowmeter Lama (Sebelum Tindakan)</th>
      <th style="width: 50%; text-align: center;">Flowmeter Baru (Setelah Tindakan)</th>
    </tr>
    <tr>
      <td>
        No. Seri: <strong style="font-family: monospace;">{{ $adjustment->old_flowmeter_serial ?? '-' }}</strong><br>
        Totalizer Akhir (Final): <span style="font-family: monospace; font-size: 13px; font-weight: bold; color: #dc2626;">{{ number_format($adjustment->old_totalizer_final, 2) }}</span>
      </td>
      <td>
        No. Seri: <strong style="font-family: monospace;">{{ $adjustment->new_flowmeter_serial ?? '-' }}</strong><br>
        Totalizer Awal (Baru): <span style="font-family: monospace; font-size: 13px; font-weight: bold; color: #16a34a;">{{ number_format($adjustment->new_totalizer_initial, 2) }}</span>
      </td>
    </tr>
  </table>

  <div class="section-title">3. Kronologis & Alasan Teknis</div>
  <div style="border: 1px solid #cbd5e1; padding: 10px; background-color: #f8fafc; border-radius: 4px; min-height: 60px;">
    {{ $adjustment->reason }}
  </div>

  <!-- Signatures -->
  <table class="sig-table">
    <tr>
      <td>
        <div style="font-size: 10px; color: #64748b;">Dibuat Oleh (Staff Fuel / Mekanik)</div>
        <div class="sig-box"></div>
        <strong>{{ $adjustment->creator->nama_lengkap ?? $adjustment->creator->name ?? 'Staff Pembuat' }}</strong><br>
        <small style="color: #64748b;">Fuelman / Instrument Technician</small>
      </td>
      <td>
        <div style="font-size: 10px; color: #64748b;">Diperiksa Oleh (Supervisor Fuel)</div>
        <div class="sig-box"></div>
        <strong>Supervisor Fuel / Maintenance</strong><br>
        <small style="color: #64748b;">Fuel Supervisor</small>
      </td>
      <td>
        <div style="font-size: 10px; color: #64748b;">Disetujui Oleh (Manager Site)</div>
        <div class="sig-box"></div>
        <strong style="text-decoration: underline;">{{ $adjustment->signed_by_manager_name }}</strong><br>
        <small style="color: #64748b;">Site / Project Manager</small>
      </td>
    </tr>
  </table>

</body>
</html>
