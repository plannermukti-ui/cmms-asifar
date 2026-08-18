@extends('layouts.tabler')

@section('title', 'Manajemen Stok Tool - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Detail Stok Tool
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <a href="{{ route('tools.index') }}" class="btn btn-secondary">
        Kembali ke Master
      </a>
      @can('create_tool_stocks')
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-request-stock">
        Tambah Stok Manual
      </button>
      @endcan
    </div>
  </div>
</div>

<div class="card mt-3">
    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tool</th>
                    <th>Lokasi (Tipe)</th>
                    <th>Mekanik</th>
                    <th>Kuantitas</th>
                    <th>Cost (Rp)</th>
                    <th>Total Cost (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr>
                    <td>{{ $stock->id }}</td>
                    <td>{{ $stock->tool->name ?? '-' }}</td>
                    <td>
                        @if($stock->location_type == 'ToolRoom')
                            <span class="badge bg-primary">Tool Room</span>
                        @else
                            <span class="badge bg-indigo">Mekanik</span>
                        @endif
                    </td>
                    <td>{{ $stock->mechanic->nama_lengkap ?? '-' }}</td>
                    <td><strong>{{ $stock->quantity }}</strong></td>
                    <td>{{ number_format($stock->tool->price ?? 0, 0, ',', '.') }}</td>
                    <td><strong>{{ number_format(($stock->tool->price ?? 0) * $stock->quantity, 0, ',', '.') }}</strong></td>
                    <td>
                        @can('edit_tool_stocks')
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-qty-{{ $stock->id }}">Edit Qty</button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">Belum ada rincian stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stocks->hasPages())
      <div class="card-footer">
        {{ $stocks->links('pagination::bootstrap-5') }}
      </div>
    @endif
</div>

<div class="modal modal-blur fade" id="modal-request-stock" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Penambahan Stok Tool</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tool-stock-requests.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label required">Approval To</label>
                    <select name="approver_id" class="form-select" required>
                        <option value="">-- Pilih Atasan --</option>
                        @foreach($approvers as $approver)
                            <option value="{{ $approver->id }}">{{ $approver->name }} ({{ $approver->roles->pluck('name')->implode(', ') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Catatan Tambahan</label>
                    <input type="text" name="notes" class="form-control" placeholder="Opsional...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter table-bordered" id="request-items-table">
                    <thead>
                        <tr>
                            <th>Tool <span class="text-danger">*</span></th>
                            <th>Lokasi <span class="text-danger">*</span></th>
                            <th>Mekanik</th>
                            <th>Kuantitas <span class="text-danger">*</span></th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="items[0][tool_id]" class="form-select tool-select" required>
                                    <option value="">Pilih Tool</option>
                                    @foreach($tools as $tool)
                                        <option value="{{ $tool->id }}" {{ request('tool_id') == $tool->id ? 'selected' : '' }}>{{ $tool->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="items[0][location_type]" class="form-select location-select" required onchange="toggleMechanic(this)">
                                    <option value="ToolRoom">Tool Room</option>
                                    <option value="Mechanic">Mekanik</option>
                                </select>
                            </td>
                            <td>
                                <select name="items[0][mechanic_id]" class="form-select mechanic-select" disabled>
                                    <option value="">Pilih Mekanik</option>
                                    @foreach($mechanics as $mechanic)
                                        <option value="{{ $mechanic->id }}">{{ $mechanic->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-danger btn-sm btn-remove-row" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" id="btn-add-row">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                Tambah Baris
            </button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Ajukan Permintaan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
    let rowIndex = 1;
    
    function toggleMechanic(selectElement) {
        const tr = selectElement.closest('tr');
        const mechanicSelect = tr.querySelector('.mechanic-select');
        if (selectElement.value === 'Mechanic') {
            mechanicSelect.disabled = false;
            mechanicSelect.required = true;
        } else {
            mechanicSelect.disabled = true;
            mechanicSelect.required = false;
            mechanicSelect.value = '';
        }
    }

    document.getElementById('btn-add-row').addEventListener('click', function() {
        const tbody = document.querySelector('#request-items-table tbody');
        const firstRow = tbody.querySelector('tr');
        const newRow = firstRow.cloneNode(true);
        
        // Update names
        newRow.querySelector('.tool-select').name = `items[${rowIndex}][tool_id]`;
        const locSelect = newRow.querySelector('.location-select');
        locSelect.name = `items[${rowIndex}][location_type]`;
        locSelect.value = 'ToolRoom';
        
        const mechSelect = newRow.querySelector('.mechanic-select');
        mechSelect.name = `items[${rowIndex}][mechanic_id]`;
        mechSelect.disabled = true;
        mechSelect.required = false;
        mechSelect.value = '';
        
        newRow.querySelector('input[type="number"]').name = `items[${rowIndex}][quantity]`;
        newRow.querySelector('input[type="number"]').value = 1;
        
        const removeBtn = newRow.querySelector('.btn-remove-row');
        removeBtn.disabled = false;
        removeBtn.addEventListener('click', function() {
            newRow.remove();
        });
        
        tbody.appendChild(newRow);
        rowIndex++;
    });
</script>

@foreach($stocks as $stock)
<div class="modal modal-blur fade" id="modal-edit-qty-{{ $stock->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Kuantitas Stok Tool</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tool-stocks.update', $stock) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-muted">Nama Tool</label>
            <div class="form-control-plaintext fw-bold">{{ $stock->tool->name ?? '-' }}</div>
          </div>
          <div class="mb-3">
            <label class="form-label text-muted">Lokasi</label>
            <div class="form-control-plaintext">
              <span class="badge {{ $stock->location_type == 'ToolRoom' ? 'bg-primary' : 'bg-indigo' }} me-1">
                {{ $stock->location_type }}
              </span>
              {{ $stock->location_type == 'Mechanic' ? '('.($stock->mechanic->nama_lengkap ?? '-').')' : '' }}
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label required">Kuantitas Terkini</label>
            <input type="number" class="form-control" name="quantity" min="0" required value="{{ $stock->quantity }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update Stok</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection