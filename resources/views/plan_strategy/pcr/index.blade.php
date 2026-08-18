@extends('layouts.tabler')

@section('title', 'Plan Component Replacement (PCR)')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">Plan Component Replacement (PCR)</h2>
        <div class="text-muted mt-1">Laporan estimasi penggantian komponen berdasar Target Life dan HM Terakhir.</div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">

    @if($pcrData->contains('opr_warning', true))
    <div class="alert alert-warning alert-dismissible" role="alert">
      <div class="d-flex">
        <div>
          <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
        </div>
        <div>
          <h4 class="alert-title">Perhatian: Opr Hrs/Day Belum Disetting</h4>
          <div class="text-muted">Beberapa Model Unit belum memiliki konfigurasi Opr Hrs/Day di menu PM Template. Sistem menggunakan nilai default 24 jam/hari untuk kalkulasi Date Plan.</div>
        </div>
      </div>
      <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
    @endif

    <div class="card mb-3">
      <div class="card-body">
        <form id="filter-form" action="{{ route('plan-strategy.pcr.index') }}" method="GET">
          <div class="row g-3">
            <div class="col-md-2">
              <label class="form-label">Unit</label>
              <select name="unit_no" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Unit</option>
                @foreach($filterUnits as $unit)
                  <option value="{{ $unit }}" {{ request('unit_no') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Model</label>
              <select name="model" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Model</option>
                @foreach($filterModels as $model)
                  <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>{{ $model }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Component</label>
              <select name="component" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Component</option>
                @foreach($filterComponents as $comp)
                  <option value="{{ $comp }}" {{ request('component') == $comp ? 'selected' : '' }}>{{ $comp }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Range Date Plan</label>
              <div class="input-group input-group-sm">
                <input type="date" class="form-control" name="date_start" value="{{ request('date_start') }}">
                <span class="input-group-text">-</span>
                <input type="date" class="form-control" name="date_end" value="{{ request('date_end') }}">
              </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-sm btn-primary w-100 me-2">Filter</button>
              <a href="{{ route('plan-strategy.pcr.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-body border-bottom py-3">
        <div class="d-flex">
          <div class="text-muted">
            Menampilkan {{ $pcrData->count() }} data
          </div>
        </div>
      </div>

      <div class="table-responsive">
          <table class="table card-table table-vcenter text-nowrap datatable table-bordered table-hover">
            <thead class="table-light text-center">
              <tr>
                <th rowspan="2" class="align-middle">No</th>
                
                <th rowspan="2" class="align-middle">
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'unit_no', 'direction' => $currentSort === 'unit_no' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none">
                    Unit @if($currentSort === 'unit_no') {!! $currentDir === 'asc' ? '↑' : '↓' !!} @endif
                  </a>
                </th>

                <th rowspan="2" class="align-middle">
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'model', 'direction' => $currentSort === 'model' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none">
                    Model @if($currentSort === 'model') {!! $currentDir === 'asc' ? '↑' : '↓' !!} @endif
                  </a>
                </th>

                <th rowspan="2" class="align-middle">
                  <a href="{{ request()->fullUrlWithQuery(['sort' => 'component', 'direction' => $currentSort === 'component' ? $nextDir : 'asc']) }}" class="text-reset text-decoration-none">
                    Component @if($currentSort === 'component') {!! $currentDir === 'asc' ? '↑' : '↓' !!} @endif
                  </a>
                </th>

                <th rowspan="2" class="align-middle">Target Life (Hrs)</th>
                <th rowspan="2" class="align-middle">Current Life</th>
                <th rowspan="2" class="align-middle">Current HM</th>
                <th rowspan="2" class="align-middle">Date Plan</th>
                <th rowspan="2" class="align-middle border-end">Plan SMU</th>
                <th rowspan="2" class="align-middle border-end">Remain</th>

                <th colspan="5" class="text-center bg-primary-lt">Last Change out</th>
              </tr>
              <tr>
                <th class="bg-primary-lt border-bottom-0">Last Date</th>
                <th class="bg-primary-lt border-bottom-0">Last HM</th>
                <th class="bg-primary-lt border-bottom-0">Brand Part</th>
                <th class="bg-primary-lt border-bottom-0">Remarks part</th>
                <th class="bg-primary-lt border-bottom-0">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pcrData as $row)
                @php $rowCount = $row['components']->count(); @endphp
                @foreach($row['components'] as $index => $comp)
                <tr>
                  @if($index === 0)
                  <td class="text-center align-middle" rowspan="{{ $rowCount }}">{{ $loop->parent->iteration }}</td>
                  <td class="fw-bold align-middle" rowspan="{{ $rowCount }}">{{ $row['unit_no'] }}</td>
                  <td class="align-middle" rowspan="{{ $rowCount }}">{{ $row['model'] }}</td>
                  @endif
                  
                  <td>{{ $comp['component'] }}</td>
                  <td class="text-end">{{ number_format($comp['target_life'], 1, ',', '.') }}</td>
                  <td class="text-end">{{ number_format($comp['current_life'], 1, ',', '.') }}</td>
                  <td class="text-end fw-bold text-primary">{{ number_format($row['current_hm'], 1, ',', '.') }}</td>
                  <td class="text-center fw-bold">{{ $comp['date_plan'] ? $comp['date_plan']->format('d/m/Y') : '-' }}</td>
                  <td class="text-end fw-bold">{{ number_format($comp['plan_smu'], 1, ',', '.') }}</td>
                  <td class="text-end {{ $comp['remain'] < 0 ? 'text-danger fw-bold' : '' }} border-end">
                    {{ number_format($comp['remain'], 1, ',', '.') }}
                  </td>
                  <td class="text-center">{{ $comp['last_date'] ? \Carbon\Carbon::parse($comp['last_date'])->format('d/m/Y') : '-' }}</td>
                  <td class="text-end">{{ $comp['last_hm'] > 0 ? number_format($comp['last_hm'], 1, ',', '.') : '-' }}</td>
                  <td>{{ $comp['brand_part'] }}</td>
                  <td>{{ $comp['remarks_part'] }}</td>
                  <td class="text-center">
                    @if($comp['wo_subtask_part_id'])
                      <button type="button" class="btn btn-sm btn-outline-primary btn-icon" data-bs-toggle="modal" data-bs-target="#modal-edit-part" onclick="editPart({{ $comp['wo_subtask_part_id'] }}, '{{ addslashes($comp['brand_part']) }}', '{{ addslashes($comp['remarks_part']) }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                      </button>
                    @endif
                  </td>
                </tr>
                @endforeach
              @empty
              <tr>
                <td colspan="15" class="text-center text-muted py-4">Data tidak ditemukan.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
      </div>
    </div>
    
  </div>
</div>

<!-- Modal Edit Part -->
<div class="modal modal-blur fade" id="modal-edit-part" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Keterangan Part</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Brand Part</label>
          <input type="text" class="form-control" id="edit_brand_part">
        </div>
        <div>
          <label class="form-label">Remarks part</label>
          <input type="text" class="form-control" id="edit_remarks_part">
        </div>
        <input type="hidden" id="edit_part_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="savePart()">Simpan</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function editPart(id, brand, remarks) {
    document.getElementById('edit_part_id').value = id;
    document.getElementById('edit_brand_part').value = brand !== '-' ? brand : '';
    document.getElementById('edit_remarks_part').value = remarks !== '-' ? remarks : '';
}

function savePart() {
    const id = document.getElementById('edit_part_id').value;
    const brand = document.getElementById('edit_brand_part').value;
    const remarks = document.getElementById('edit_remarks_part').value;

    fetch('{{ route("plan-strategy.pcr.updateManual") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            id: id,
            brand_part: brand,
            remarks_part: remarks
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert('Gagal menyimpan data.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data.');
    });
}
</script>
@endpush
@endsection
