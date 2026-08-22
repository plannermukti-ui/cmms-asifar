<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Rekapitulasi Pemakaian BBM</title>
  <style>
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; line-height: 1.3; color: #1e293b; margin: 0; padding: 15px; }
    .title { font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 2px; }
    .subtitle { font-size: 10px; text-align: center; color: #64748b; margin-bottom: 12px; }
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 5px 6px; text-align: left; vertical-align: middle; }
    table.data-table th { background-color: #f8fafc; font-weight: bold; color: #334155; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
  </style>
</head>
<body>

  @php
      $selectedSite = $siteId ? \App\Models\Site::find($siteId)?->name : null;
  @endphp

  @include('partials.kop-surat-pdf', [
    'docTitle' => 'LAPORAN REKAPITULASI KONSUMSI BBM',
    'docDate' => date('d/m/Y', strtotime($dateFrom)) . ' s/d ' . date('d/m/Y', strtotime($dateTo)),
    'siteName' => $selectedSite,
  ])

  <!-- Summary Cards Table -->
  <table class="data-table" style="margin-bottom: 15px;">
    <tr>
      <th style="width: 50%; text-align: center; background-color: #eff6ff;">TOTAL PENERIMAAN BBM (INBOUND)</th>
      <th style="width: 50%; text-align: center; background-color: #f0fdf4;">TOTAL DISTRIBUSI UNIT (OUTBOUND)</th>
    </tr>
    <tr>
      <td style="text-align: center; font-size: 14px; font-weight: bold; color: #1e40af;">
        {{ number_format($totalInbound, 0, ',', '.') }} Liter
      </td>
      <td style="text-align: center; font-size: 14px; font-weight: bold; color: #15803d;">
        {{ number_format($totalOutbound, 0, ',', '.') }} Liter
      </td>
    </tr>
  </table>

  <div style="font-size: 11px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">
    Rincian Konsumsi Bahan Bakar & Burn Rate per Unit Operasional:
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 30px;" class="text-center">No</th>
        <th>Nomor Unit</th>
        <th>Tipe & Model</th>
        <th class="text-center">Frekuensi</th>
        <th class="text-right">HM/KM Awal</th>
        <th class="text-right">HM/KM Akhir</th>
        <th class="text-right">Delta HM/KM</th>
        <th class="text-right">Total Liter</th>
        <th class="text-right">Burn Rate</th>
      </tr>
    </thead>
    <tbody>
      @forelse($unitDistributions as $idx => $ud)
      @php
          $deltaMeter = ($ud->max_meter && $ud->min_meter) ? max(0, $ud->max_meter - $ud->min_meter) : 0;
          $burnRate = ($deltaMeter > 0) ? round($ud->total_liters / $deltaMeter, 2) : 0;
      @endphp
      <tr>
        <td class="text-center">{{ $idx + 1 }}</td>
        <td><strong>{{ $ud->masterUnit->nomor_unit ?? '-' }}</strong></td>
        <td>{{ $ud->masterUnit->type->name ?? '-' }}</td>
        <td class="text-center">{{ $ud->fill_count }}x</td>
        <td class="text-right">{{ $ud->min_meter ? number_format($ud->min_meter, 1) : '-' }}</td>
        <td class="text-right">{{ $ud->max_meter ? number_format($ud->max_meter, 1) : '-' }}</td>
        <td class="text-right">{{ $deltaMeter > 0 ? number_format($deltaMeter, 1) : '-' }}</td>
        <td class="text-right" style="font-weight: bold; color: #15803d;">{{ number_format($ud->total_liters, 1, ',', '.') }}</td>
        <td class="text-right">{{ $burnRate > 0 ? number_format($burnRate, 2) . ' L/HM' : '-' }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="9" class="text-center" style="padding: 15px; color: #94a3b8;">Tidak ada data pemakaian unit pada periode ini.</td>
      </tr>
      @endforelse
    </tbody>
    <tfoot>
      <tr style="background-color: #f8fafc; font-weight: bold;">
        <td colspan="7" class="text-right">TOTAL KONSUMSI UNIT KESELURUHAN:</td>
        <td class="text-right" style="font-size: 11px; color: #15803d;">
          {{ number_format($unitDistributions->sum('total_liters'), 1, ',', '.') }} L
        </td>
        <td></td>
      </tr>
    </tfoot>
  </table>

</body>
</html>
