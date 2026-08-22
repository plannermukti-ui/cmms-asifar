<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Stok Terkini BBM</title>
  <style>
    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; line-height: 1.3; color: #1e293b; margin: 0; padding: 15px; }
    .title { font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; margin-bottom: 2px; }
    .subtitle { font-size: 10px; text-align: center; color: #64748b; margin-bottom: 12px; }
    .section-title { font-size: 10px; font-weight: bold; background-color: #f1f5f9; padding: 4px 6px; border-left: 3px solid #2563eb; margin: 10px 0 5px 0; text-transform: uppercase; }
    table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    table.data-table th, table.data-table td { border: 1px solid #cbd5e1; padding: 4px 6px; text-align: left; vertical-align: middle; }
    table.data-table th { background-color: #f8fafc; font-weight: bold; color: #334155; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
  </style>
</head>
<body>

  @php
      $selectedSite = $siteId ? \App\Models\Site::find($siteId)?->name : 'Semua Site';
  @endphp

  @include('partials.kop-surat-pdf', [
    'docTitle' => 'LAPORAN POSISI STOK BBM TERKINI',
    'docDate' => now()->format('d/m/Y H:i') . ' WITA',
    'siteName' => $selectedSite,
  ])

  <!-- SECTION 1: TANGKI TIMBUN -->
  <div class="section-title">1. Tangki Timbun & Fuel Station</div>
  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 10%;">Kode</th>
        <th style="width: 25%;">Nama Tangki</th>
        <th style="width: 15%;">Tipe</th>
        <th style="width: 15%;">Site & Lokasi</th>
        <th style="width: 10%;" class="text-right">Kapasitas (L)</th>
        <th style="width: 12%;" class="text-right">Stok Aktual (L)</th>
        <th style="width: 13%;" class="text-right">Totalizer Pompa</th>
      </tr>
    </thead>
    <tbody>
      @php $totStCap = 0; $totStStock = 0; @endphp
      @foreach($storages as $st)
      @php $totStCap += $st->capacity; $totStStock += $st->current_stock; @endphp
      <tr>
        <td><strong>{{ $st->code }}</strong></td>
        <td>{{ $st->name }}</td>
        <td>{{ $st->type }}</td>
        <td>{{ $st->site->name ?? '-' }} ({{ $st->location ?? '-' }})</td>
        <td class="text-right">{{ number_format($st->capacity, 0, ',', '.') }}</td>
        <td class="text-right" style="font-weight: bold; color: #1e40af;">{{ number_format($st->current_stock, 0, ',', '.') }}</td>
        <td class="text-right" style="font-family: monospace;">{{ number_format($st->current_totalizer, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr style="background-color: #f8fafc; font-weight: bold;">
        <td colspan="4" class="text-right">SUBTOTAL STOK TANGKI:</td>
        <td class="text-right">{{ number_format($totStCap, 0, ',', '.') }} L</td>
        <td class="text-right" style="color: #1e40af;">{{ number_format($totStStock, 0, ',', '.') }} L</td>
        <td></td>
      </tr>
    </tfoot>
  </table>

  <!-- SECTION 2: FUEL TRUCK -->
  <div class="section-title">2. Unit Mobile Fuel Truck</div>
  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 15%;">Nomor Unit FT</th>
        <th style="width: 25%;">Tipe & Model</th>
        <th style="width: 15%;">Site</th>
        <th style="width: 15%;">No Seri Flowmeter</th>
        <th style="width: 10%;" class="text-right">Kapasitas (L)</th>
        <th style="width: 12%;" class="text-right">Stok BBM (L)</th>
        <th style="width: 13%;" class="text-right">Totalizer Flowmeter</th>
      </tr>
    </thead>
    <tbody>
      @php $totFtCap = 0; $totFtStock = 0; @endphp
      @foreach($fuelTrucks as $ft)
      @php $totFtCap += $ft->capacity; $totFtStock += $ft->current_stock; @endphp
      <tr>
        <td><strong>{{ $ft->masterUnit->nomor_unit ?? '-' }}</strong></td>
        <td>{{ $ft->masterUnit->type->name ?? '-' }}</td>
        <td>{{ $ft->site->name ?? $ft->masterUnit->site->name ?? '-' }}</td>
        <td style="font-family: monospace;">{{ $ft->flowmeter_serial_number ?? '-' }}</td>
        <td class="text-right">{{ number_format($ft->capacity, 0, ',', '.') }}</td>
        <td class="text-right" style="font-weight: bold; color: #b45309;">{{ number_format($ft->current_stock, 0, ',', '.') }}</td>
        <td class="text-right" style="font-family: monospace;">{{ number_format($ft->current_totalizer, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr style="background-color: #f8fafc; font-weight: bold;">
        <td colspan="4" class="text-right">SUBTOTAL STOK FUEL TRUCK:</td>
        <td class="text-right">{{ number_format($totFtCap, 0, ',', '.') }} L</td>
        <td class="text-right" style="color: #b45309;">{{ number_format($totFtStock, 0, ',', '.') }} L</td>
        <td></td>
      </tr>
    </tfoot>
  </table>

  <!-- GRAND TOTAL SUMMARY -->
  <table class="data-table" style="margin-top: 15px; border: 2px solid #1e40af;">
    <tr style="background-color: #eff6ff; font-weight: bold; font-size: 11px;">
      <td style="padding: 8px;">GRAND TOTAL INVENTORY BBM SITE (TANGKI + FUEL TRUCK)</td>
      <td class="text-right" style="padding: 8px; color: #1e40af; font-size: 13px;">
        {{ number_format($totStStock + $totFtStock, 0, ',', '.') }} LITER
      </td>
    </tr>
  </table>

</body>
</html>
