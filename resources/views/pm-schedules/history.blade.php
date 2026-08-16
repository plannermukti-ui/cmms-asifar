@extends('layouts.tabler')
@section('title', 'PM Schedule History - CMMS')
@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <div class="page-pretitle">Preventive Maintenance</div>
      <h2 class="page-title">Service History: {{ $pmSchedule->masterUnit->nomor_lambung ?? '-' }}</h2>
    </div>
    <div class="col-auto ms-auto">
      <a href="{{ route('pm-schedules.index') }}" class="btn btn-secondary">Kembali</a>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-history">Catat Eksekusi</button>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
          <thead>
            <tr>
              <th>Tanggal Eksekusi</th>
              <th>HM Service</th>
              <th>No Work Order</th>
              <th>Dicatat Oleh</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($histories as $h)
            <tr>
              <td>{{ $h->executed_at->format('d M Y') }}</td>
              <td><span class="badge bg-secondary-lt fw-bold">{{ $h->hm_service ?? '-' }}</span></td>
              <td>
                @if($h->work_order_no)
                  @if($h->workOrder)
                    <a href="{{ route('work-orders.show', $h->workOrder->id) }}">{{ $h->work_order_no }}</a>
                  @else
                    {{ $h->work_order_no }}
                  @endif
                @else
                  -
                @endif
              </td>
              <td>{{ $h->creator->name ?? 'System' }}</td>
              <td>{{ $h->notes ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">Belum ada history eksekusi.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($histories->hasPages())
        <div class="card-footer d-flex align-items-center">
          {{ $histories->links() }}
        </div>
      @endif
    </div>
  </div>
</div>

<!-- Modal Add -->
<div class="modal modal-blur fade" id="modal-add-history" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('pm-schedules.history.store', $pmSchedule) }}" method="post">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Catat Eksekusi PM</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Tanggal Eksekusi</label>
              <input type="date" class="form-control" name="executed_at" required value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">HM Service (Actual)</label>
              <input type="number" step="0.1" class="form-control" name="hm_service" required placeholder="Contoh: 12500.5">
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Nomor Work Order</label>
              <input type="text" class="form-control" name="work_order_no" placeholder="Contoh: WO-2026-08-001">
              <small class="form-hint">Kosongkan jika eksekusi tidak menggunakan WO formil.</small>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Catatan</label>
              <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan History</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
