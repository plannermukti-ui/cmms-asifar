@extends('layouts.tabler')

@section('title', 'Review Berita Acara - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Review Berita Acara #BA-{{ str_pad($incidentReport->id, 4, '0', STR_PAD_LEFT) }}
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('incident-reports.index') }}" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <p><strong>Mekanik:</strong> {{ $incidentReport->mechanic->nama_lengkap ?? '-' }}</p>
        <p><strong>Tool:</strong> {{ $incidentReport->transaction->tool->name ?? '-' }}</p>
        <p><strong>Kronologi:</strong> <br> {{ $incidentReport->kronologi }}</p>
        
        <form action="{{ route('incident-reports.update', $incidentReport) }}" method="post" class="mt-4">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label required">Status Approval</label>
            <select name="status_approval" class="form-select" required>
              <option value="Pending" {{ $incidentReport->status_approval == 'Pending' ? 'selected' : '' }}>Pending</option>
              <option value="Approved" {{ $incidentReport->status_approval == 'Approved' ? 'selected' : '' }}>Approve</option>
              <option value="Rejected" {{ $incidentReport->status_approval == 'Rejected' ? 'selected' : '' }}>Reject</option>
            </select>
          </div>
          <div class="form-footer">
            <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection