<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Distribusi Fuel - {{ $shift->shift_doc_number }}</title>
  <style>
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; line-height: 1.3; color: #1e293b; margin: 0; padding: 12px; }
    .title { font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 2px; }
    .subtitle { font-size: 10px; text-align: center; color: #64748b; margin-bottom: 10px; }
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 4px 6px; text-align: left; vertical-align: middle; }
    table.data-table th { background-color: #f8fafc; font-weight: bold; color: #334155; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .sig-table { width: 100%; margin-top: 25px; border-collapse: collapse; }
    .sig-table td { width: 33.33%; text-align: center; vertical-align: top; padding: 4px; }
    .sig-box { border: 1px dashed #94a3b8; height: 60px; margin: 6px 15px; border-radius: 4px; }
  </style>
</head>
<body>

  @include('partials.kop-surat-pdf', [
    'docTitle' => 'LEMBAR KONTROL DISTRIBUSI FUEL TRUCK',
    'docNumber' => $shift->shift_doc_number,
    'docDate' => $shift->date ? $shift->date->format('d/m/Y') : date('d/m/Y'),
    'siteName' => $shift->site->name ?? null,
  ])

  <!-- Shift Info Header -->
  <table class="data-table">
    <tr>
      <th style="width: 15%;">Unit Fuel Truck</th>
      <td style="width: 35%;"><strong>{{ $shift->fuelTruck->masterUnit->nomor_unit ?? '-' }}</strong></td>
      <th style="width: 15%;">Tanggal & Shift</th>
      <td style="width: 35%;">{{ $shift->date ? $shift->date->format('d/m/Y') : '-' }} ({{ $shift->shift }})</td>
    </tr>
    <tr>
      <th>Petugas Fuelman</th>
      <td><strong>{{ $shift->fuelman_name }}</strong></td>
      <th>Site Operasional</th>
      <td>{{ $shift->site->name ?? '-' }}</td>
    </tr>
    <tr>
      <th>Totalizer Awal Shift</th>
      <td style="font-family: monospace;">{{ number_format($shift->totalizer_start, 2) }}</td>
      <th>Totalizer Akhir Shift</th>
      <td style="font-family: monospace;">{{ $shift->totalizer_end ? number_format($shift->totalizer_end, 2) : '-' }}</td>
    </tr>
    <tr style="background-color: #f0fdf4;">
      <th>Delta Flowmeter</th>
      <td style="font-weight: bold; font-size: 11px;">{{ number_format($shift->total_liters_flowmeter, 0, ',', '.') }} Liter</td>
      <th>Total Terisi ke Unit</th>
      <td style="font-weight: bold; font-size: 11px; color: #15803d;">{{ number_format($shift->distributions->sum('volume_liters'), 1, ',', '.') }} Liter</td>
    </tr>
  </table>

  <!-- Items Table -->
  <div style="font-size: 10px; font-weight: bold; margin: 10px 0 4px 0; text-transform: uppercase;">
    Rincian Pengisian Bahan Bakar ke Unit Operasional:
  </div>

  <table class="data-table">
    <thead>
      <tr style="background-color: #f1f5f9;">
        <th style="width: 25px;" class="text-center">No</th>
        <th>No. Unit</th>
        <th>Tipe Unit</th>
        <th>Jam</th>
        <th class="text-right">Reading Meter (HM/KM)</th>
        <th>Operator Unit</th>
        <th class="text-right">Volume (L)</th>
        <th>Lokasi / Pit</th>
        <th>Catatan</th>
      </tr>
    </thead>
    <tbody>
      @forelse($shift->distributions as $idx => $item)
      <tr>
        <td class="text-center">{{ $idx + 1 }}</td>
        <td><strong>{{ $item->masterUnit->nomor_unit ?? '-' }}</strong></td>
        <td>{{ $item->masterUnit->type->name ?? '-' }}</td>
        <td>{{ $item->dispense_time ? $item->dispense_time->format('H:i') : '-' }}</td>
        <td class="text-right font-monospace">
          {{ $item->meter_reading ? number_format($item->meter_reading, 1) . ' ' . $item->meter_type : '-' }}
        </td>
        <td>{{ $item->unit_operator_name ?? '-' }}</td>
        <td class="text-right" style="font-weight: bold;">{{ number_format($item->volume_liters, 1, ',', '.') }}</td>
        <td>{{ $item->location ?? '-' }}</td>
        <td>{{ $item->notes ?? '-' }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="9" class="text-center" style="padding: 15px; color: #94a3b8;">Belum ada data pengisian unit.</td>
      </tr>
      @endforelse
    </tbody>
    <tfoot>
      <tr style="background-color: #f8fafc; font-weight: bold;">
        <td colspan="6" class="text-right">TOTAL DISTRIBUSI KESELURUHAN:</td>
        <td class="text-right" style="font-size: 11px; color: #15803d;">
          {{ number_format($shift->distributions->sum('volume_liters'), 1, ',', '.') }} L
        </td>
        <td colspan="2"></td>
      </tr>
    </tfoot>
  </table>

  <!-- Signatures -->
  <table class="sig-table">
    <tr>
      <td>
        <div style="font-size: 9px; color: #64748b;">Petugas Fuelman</div>
        <div class="sig-box"></div>
        <strong>{{ $shift->fuelman_name }}</strong><br>
        <small style="color: #64748b;">Operator Fuel Truck</small>
      </td>
      <td>
        <div style="font-size: 9px; color: #64748b;">Pengawas Lapangan (Supervisor)</div>
        <div class="sig-box"></div>
        <strong>{{ $shift->closer->nama_lengkap ?? $shift->closer->name ?? 'Supervisor Site' }}</strong><br>
        <small style="color: #64748b;">Fuel / Mining Supervisor</small>
      </td>
      <td>
        <div style="font-size: 9px; color: #64748b;">Mengetahui (Site Manager)</div>
        <div class="sig-box"></div>
        <strong>Manager Site</strong><br>
        <small style="color: #64748b;">Project / Site Manager</small>
      </td>
    </tr>
  </table>

</body>
</html>
