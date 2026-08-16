@extends('layouts.tabler')
@section('title', 'Daftar PM Template - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Pengaturan PM Template</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('pm-templates.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Buat Template Baru
      </a>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <form method="GET" action="{{ route('pm-templates.index') }}" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label small fw-bold">Model Unit</label>
        <select class="form-select form-select-sm" name="unit_model_id">
          <option value="">Semua Model</option>
          @foreach($unitModels as $model)
            <option value="{{ $model->id }}" {{ request('unit_model_id') == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm flex-fill">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            Filter
          </button>
          @if(request('unit_model_id'))
            <a href="{{ route('pm-templates.index') }}" class="btn btn-outline-secondary btn-sm">
              Reset
            </a>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card mt-2">
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap">
      <thead class="table-light">
        <tr>
          <th>Site</th>
          <th>Model Unit</th>
          <th>Nama Template</th>
          <th>Tipe Interval</th>
          <th>Nilai Interval</th>
          <th>Opr Hrs/Day</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($templates as $template)
        <tr>
          <td>
            @if($template->site)
                <span class="badge bg-purple-lt">{{ $template->site->name }}</span>
            @else
                <span class="badge bg-secondary-lt">Global</span>
            @endif
          </td>
          <td>{{ $template->unitModel->name ?? '-' }}</td>
          <td class="fw-bold">{{ $template->name }}</td>
          <td>
            @if($template->interval_type == 'hour_meter')
                Hour Meter (HM)
            @elseif($template->interval_type == 'kilometer')
                Kilometer (KM)
            @else
                Hari (Days)
            @endif
          </td>
          <td>{{ number_format($template->interval_value) }}</td>
          <td>{{ $template->opr_hrs_per_day ?? '-' }}</td>
          <td>
            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#copyModal" data-id="{{ $template->id }}" data-name="{{ $template->name }}" data-site="{{ $template->site_id }}" data-model="{{ $template->unit_model_id }}">Copy</button>
            <a href="{{ route('pm-templates.edit', $template) }}" class="btn btn-sm btn-primary">Edit</a>
            <form action="{{ route('pm-templates.destroy', $template) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus template ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">Belum ada data PM Template.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($templates->hasPages())
  <div class="card-footer">
    {{ $templates->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

<div class="modal modal-blur fade" id="copyModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <form id="copyForm" action="" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Copy PM Template</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label required">Nama Template Baru</label>
          <input type="text" name="name" id="copy_name" class="form-control" required>
        </div>
        @if(!auth()->user()->site_id)
        <div class="mb-3">
          <label class="form-label required">Site (Lokasi)</label>
          <select name="site_id" id="copy_site_id" class="form-select select2-copy" required>
              <option value="">-- Pilih Site --</option>
              @foreach($sites as $site)
                  <option value="{{ $site->id }}">{{ $site->name }} ({{ $site->code }})</option>
              @endforeach
          </select>
        </div>
        @endif
        <div class="mb-3">
          <label class="form-label required">Model Unit</label>
          <select name="unit_model_id" id="copy_unit_model_id" class="form-select select2-copy" required>
              <option value="">-- Pilih Model Unit --</option>
              @foreach($unitModels as $model)
                  <option value="{{ $model->id }}">{{ $model->name }} ({{ $model->type->name ?? '' }})</option>
              @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Copy</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-copy').select2({
                dropdownParent: $('#copyModal'),
                theme: 'bootstrap-5',
                width: '100%'
            });
        }

        var copyModal = document.getElementById('copyModal');
        if(copyModal) {
            copyModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var site = button.getAttribute('data-site');
                var model = button.getAttribute('data-model');

                var form = document.getElementById('copyForm');
                form.action = "{{ url('pm-templates') }}/" + id + "/copy";

                document.getElementById('copy_name').value = name + ' (Copy)';

                if (typeof $ !== 'undefined' && $.fn.select2) {
                    if (document.getElementById('copy_site_id')) {
                        $('#copy_site_id').val(site).trigger('change');
                    }
                    $('#copy_unit_model_id').val(model).trigger('change');
                } else {
                    if (document.getElementById('copy_site_id')) {
                        document.getElementById('copy_site_id').value = site;
                    }
                    document.getElementById('copy_unit_model_id').value = model;
                }
            });
        }
    });
</script>
@endpush
