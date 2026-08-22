@extends('layouts.tabler')

@section('title', 'Sesi Distribusi BBM: ' . $shift->shift_doc_number)

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle text-uppercase font-monospace text-primary">Shift Session Detail</div>
        <h2 class="page-title d-flex align-items-center gap-2">
          {{ $shift->shift_doc_number }}
          @if($shift->status == 'Closed')
            <span class="badge bg-success text-white fw-bold fs-5 px-2 py-0.5">CLOSED</span>
          @else
            <span class="badge bg-warning text-dark fw-bold fs-5 px-2 py-0.5">OPEN (BERJALAN)</span>
          @endif
        </h2>
      </div>
      <div class="col-auto ms-auto d-flex flex-wrap gap-2">
        <a href="{{ route('fuel.distributions.pdf', $shift) }}" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
          Cetak Laporan (PDF)
        </a>

        @if($shift->status == 'Closed' && (auth()->user()->hasRole('Super Admin') || auth()->user()->can('manage_fuel_distributions')))
        <form action="{{ route('fuel.distributions.reopen', $shift) }}" method="POST"
              data-tabler-confirm="Buka kembali (Reopen) sesi shift <strong>{{ $shift->shift_doc_number }}</strong>?<br><br>Stok unit Fuel Truck akan dikembalikan sehingga data pengisian dapat direvisi kembali."
              data-tabler-confirm-title="Buka Kembali Sesi Shift"
              data-tabler-confirm-type="warning"
              data-tabler-confirm-btn="Ya, Buka Kembali Shift">
          @csrf
          <button type="submit" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
            Reopen Shift
          </button>
        </form>
        @endif

        @if(auth()->user()->hasRole('Super Admin'))
        <form action="{{ route('fuel.distributions.destroy', $shift) }}" method="POST"
              data-tabler-confirm="Batalkan dan hapus seluruh sesi shift <strong>{{ $shift->shift_doc_number }}</strong>?<br><br>@if($shift->status === 'Closed')<span class='text-danger fw-bold'>Perhatian:</span> Stok Fuel Truck akan dikembalikan dan kartu stok dibersihkan,@endif dan seluruh {{ $shift->distributions->count() }} data pengisian di dalamnya akan dihapus."
              data-tabler-confirm-title="Batalkan & Hapus Sesi Shift"
              data-tabler-confirm-type="danger"
              data-tabler-confirm-btn="Ya, Batalkan & Hapus Shift">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
            Batalkan & Hapus Shift
          </button>
        </form>
        @endif

        <a href="{{ route('fuel.distributions.index') }}" class="btn btn-outline-secondary btn-sm">
          &laquo; Kembali
        </a>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    
    @if(session('success'))
      <div class="alert alert-success alert-dismissible mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible mb-3">{{ session('error') }}</div>
    @endif

    <!-- Header Summary Card -->
    <div class="card mb-3 shadow-sm border">
      <div class="card-body p-3">
        <div class="row g-3 align-items-center">
          <div class="col-md-3">
            <div class="text-muted small">Fuel Truck</div>
            <div class="fw-bold fs-3 text-warning">
              <span class="badge bg-yellow-lt font-monospace px-2 py-0.5 me-1">FT</span>
              {{ $shift->fuelTruck->masterUnit->nomor_unit ?? '-' }}
            </div>
            <div class="text-muted small">Fuelman: <strong>{{ $shift->fuelman_name }}</strong></div>
          </div>

          <div class="col-md-2 border-start ps-3">
            <div class="text-muted small">Tanggal & Shift</div>
            <div class="fw-bold text-body">{{ $shift->date ? $shift->date->format('d/m/Y') : '-' }}</div>
            <span class="badge bg-blue-lt small">{{ $shift->shift }}</span>
          </div>

          <div class="col-md-3 border-start ps-3">
            <div class="text-muted small">Totalizer Flowmeter Shift</div>
            <div class="font-monospace">
              Awal: <strong>{{ number_format($shift->totalizer_start, 2) }}</strong>
            </div>
            <div class="font-monospace">
              Akhir: <strong>{{ $shift->totalizer_end ? number_format($shift->totalizer_end, 2) : '(Belum Ditutup)' }}</strong>
            </div>
            @if($shift->totalizer_end)
              <div class="small text-primary fw-bold">Delta Meter: {{ number_format($shift->total_liters_flowmeter, 0, ',', '.') }} L</div>
            @endif
          </div>

          <div class="col-md-2 border-start ps-3">
            <div class="text-muted small">Total Terdistribusi</div>
            <div class="fs-2 fw-bold text-success font-monospace">{{ number_format($shift->distributions->sum('volume_liters'), 0, ',', '.') }} <span class="fs-4 text-muted">L</span></div>
            <div class="small text-muted">{{ $shift->distributions->count() }} Unit Terisi</div>
          </div>

          <div class="col-md-2 border-start ps-3 text-end">
            @if($shift->status == 'Open')
              <button type="button" class="btn btn-danger w-100 fw-bold py-2" data-bs-toggle="modal" data-bs-target="#modal-close-shift">
                🔒 Tutup Shift Ini
              </button>
            @else
              <div class="text-success small fw-bold">Telah Ditutup Oleh:</div>
              <div class="small fw-semibold">{{ $shift->closer->nama_lengkap ?? $shift->closer->name ?? '-' }}</div>
              <div class="text-muted" style="font-size: 0.7rem;">{{ $shift->closed_at ? $shift->closed_at->format('d/m H:i') : '' }}</div>
              @if($shift->variance_liters != 0)
                <div class="small {{ $shift->variance_liters < 0 ? 'text-danger' : 'text-warning' }} fw-bold mt-1">
                  Selisih: {{ ($shift->variance_liters > 0 ? '+' : '') . number_format($shift->variance_liters, 0) }} L
                </div>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- FORM INPUT PENGISIAN UNIT (HANYA MUNCUL JIKA SHIFT STATUS OPEN) -->
    @if($shift->status == 'Open')
    <div class="card mb-4 shadow-sm border border-success">
      <div class="card-header bg-success-lt border-0 py-2">
        <h3 class="card-title fw-bold text-success m-0">+ Input Pengisian BBM ke Unit</h3>
      </div>
      <div class="card-body p-3">
        <form action="{{ route('fuel.distributions.items.store', $shift) }}" method="POST">
          @csrf
          <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
              <label class="form-label required small">Unit Operasional</label>
              <select name="master_unit_id" class="form-select form-select-sm" required>
                <option value="">-- Pilih Unit --</option>
                @foreach($units as $u)
                  <option value="{{ $u->id }}">{{ $u->nomor_unit }} ({{ $u->type->name ?? '-' }})</option>
                @endforeach
              </select>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label required small">Waktu Pengisian</label>
              <input type="datetime-local" class="form-control form-control-sm" name="dispense_time" value="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label small">Reading Meter (HM/KM)</label>
              <div class="input-group input-group-sm">
                <input type="number" step="0.1" class="form-control" name="meter_reading" placeholder="Angka Meter">
                <select name="meter_type" class="form-select" style="max-width: 65px;">
                  <option value="HM">HM</option>
                  <option value="KM">KM</option>
                </select>
              </div>
            </div>

            <div class="col-6 col-md-2">
              <label class="form-label small">Nama Operator Unit</label>
              <input type="text" class="form-control form-control-sm" name="unit_operator_name" placeholder="Operator">
            </div>

            <div class="col-6 col-md-1.5" style="max-width: 140px;">
              <label class="form-label required small">Volume (Liter)</label>
              <input type="number" step="0.1" class="form-control form-control-sm fw-bold text-success" name="volume_liters" placeholder="Liter" required>
            </div>

            <div class="col-6 col-md-1.5">
              <label class="form-label small">Lokasi / Pit</label>
              <input type="text" class="form-control form-control-sm" name="location" placeholder="Pit / Front">
            </div>

            <div class="col-12 col-md-12 d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
              <div class="col-md-9">
                <input type="text" class="form-control form-control-sm" name="notes" placeholder="Catatan tambahan (opsional)...">
              </div>
              <button type="submit" class="btn btn-success btn-sm px-4 fw-bold">
                + Tambahkan Unit
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    @endif

    <!-- TABEL RINCIAN PENGISIAN UNIT -->
    <div class="card shadow-sm border">
      <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold text-primary">Rincian Unit yang Diisi pada Sesi Shift Ini</h3>
        <span class="badge bg-secondary-lt font-monospace">{{ $shift->distributions->count() }} Baris Pengisian</span>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
          <thead>
            <tr>
              <th style="width: 40px;">#</th>
              <th>Nomor Unit</th>
              <th>Tipe Unit</th>
              <th>Waktu Pengisian</th>
              <th class="text-end">Reading Meter (HM/KM)</th>
              <th>Operator Unit</th>
              <th class="text-end">Volume Diisi (Liter)</th>
              <th>Lokasi / Pit</th>
              <th>Catatan</th>
              @if($shift->status == 'Open')
                <th class="text-end" style="width: 50px;">Aksi</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @forelse($shift->distributions as $idx => $item)
            <tr>
              <td class="text-muted small">{{ $idx + 1 }}</td>
              <td>
                <span class="badge bg-blue-lt font-monospace px-2 py-0.5 me-1">UNIT</span>
                <span class="fw-bold text-body">{{ $item->masterUnit->nomor_unit ?? '-' }}</span>
              </td>
              <td class="text-muted small">{{ $item->masterUnit->type->name ?? '-' }}</td>
              <td>{{ $item->dispense_time ? $item->dispense_time->format('H:i') : '-' }} WITA</td>
              <td class="text-end font-monospace">
                @if($item->meter_reading)
                  {{ number_format($item->meter_reading, 1) }} <span class="badge bg-secondary-lt small">{{ $item->meter_type }}</span>
                @else
                  -
                @endif
              </td>
              <td class="fw-semibold">{{ $item->unit_operator_name ?? '-' }}</td>
              <td class="text-end font-monospace fw-bold fs-4 text-success">
                {{ number_format($item->volume_liters, 1, ',', '.') }} L
              </td>
              <td>{{ $item->location ?? '-' }}</td>
              <td class="small text-secondary">{{ $item->notes ?? '-' }}</td>
              @if($shift->status == 'Open')
              <td class="text-end">
                <form action="{{ route('fuel.distributions.items.destroy', $item) }}" method="POST" class="d-inline"
                      data-tabler-confirm="Hapus catatan pengisian unit <strong>{{ $item->masterUnit->nomor_unit ?? 'Unit' }}</strong> ({{ number_format($item->volume_liters, 1) }} L)?"
                      data-tabler-confirm-title="Hapus Rincian Pengisian"
                      data-tabler-confirm-type="danger"
                      data-tabler-confirm-btn="Ya, Hapus">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-xs btn-outline-danger">✕</button>
                </form>
              </td>
              @endif
            </tr>
            @empty
            <tr>
              <td colspan="{{ $shift->status == 'Open' ? 10 : 9 }}" class="text-center text-muted py-4">
                Belum ada unit yang diisi pada sesi shift ini. Gunakan form di atas untuk menambahkan pengisian unit.
              </td>
            </tr>
            @endforelse
          </tbody>
          @if($shift->distributions->count() > 0)
          <tfoot>
            <tr class="bg-body-tertiary fw-bold">
              <td colspan="6" class="text-end text-uppercase">TOTAL DISTRIBUSI:</td>
              <td class="text-end font-monospace fs-3 text-success">
                {{ number_format($shift->distributions->sum('volume_liters'), 1, ',', '.') }} L
              </td>
              <td colspan="{{ $shift->status == 'Open' ? 3 : 2 }}"></td>
            </tr>
          </tfoot>
          @endif
        </table>
      </div>
    </div>

  </div>
</div>

<!-- Modal Tutup Shift -->
<div class="modal modal-blur fade" id="modal-close-shift" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('fuel.distributions.close', $shift) }}" method="POST">
        @csrf
        <div class="modal-header bg-danger-lt">
          <h5 class="modal-title text-danger fw-bold">🔒 Tutup Sesi Shift: {{ $shift->shift_doc_number }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small">
            Saat sesi shift ditutup, Anda wajib menginput <strong>Totalizer Flowmeter Akhir Shift</strong>. Sistem akan menghitung delta flowmeter dan mengurangi stok Fuel Truck secara otomatis.
          </p>

          <div class="p-3 bg-body-tertiary rounded border mb-3">
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">Totalizer Awal Shift:</span>
              <strong class="font-monospace">{{ number_format($shift->totalizer_start, 2) }}</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Total Rincian Unit:</span>
              <strong class="text-success font-monospace">{{ number_format($shift->distributions->sum('volume_liters'), 1) }} Liter</strong>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label required fw-bold">Angka Totalizer Flowmeter Akhir Shift</label>
            <input type="number" step="0.01" class="form-control form-control-lg font-monospace fw-bold text-primary" name="totalizer_end" placeholder="Masukkan totalizer akhir..." min="{{ $shift->totalizer_start }}" required>
            <small class="text-muted">Harus lebih besar atau sama dengan {{ number_format($shift->totalizer_start, 2) }}.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger fw-bold">Konfirmasi Tutup Shift & Potong Stok</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
