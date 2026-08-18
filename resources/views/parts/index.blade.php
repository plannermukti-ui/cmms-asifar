@extends('layouts.tabler')
@section('title', 'Master Part - CMMS')
@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col"><h2 class="page-title">Master Part</h2></div>
    <div class="col-auto ms-auto d-print-none">
      @can('create_parts')
      <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-tambah-part">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Tambah Part
      </a>
      @endcan
    </div>
  </div>
</div>
<div class="card mt-3">
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap">
      <thead>
        <tr>
          <th>Part Number</th>
          <th>Deskripsi</th>
          <th>Satuan</th>
          <th>Target (Hrs)</th>
          <th>Cost</th>
          <th>Kat.1</th>
          <th>Kat.2</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($parts as $part)
        <tr>
          <td class="fw-bold">{{ $part->part_number }}</td>
          <td>{{ $part->part_description }}</td>
          <td>{{ $part->satuan ?? '-' }}</td>
          <td>{{ $part->target ?? '-' }}</td>
          <td>{{ number_format($part->cost, 0, ',', '.') }}</td>
          <td>{{ $part->kategori1->name ?? '-' }}</td>
          <td>{{ $part->kategori2->name ?? '-' }}</td>
          <td>
            @can('edit_parts')
            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-part-{{ $part->id }}">Edit</a>
            @endcan
            @can('delete_parts')
            <form action="{{ route('parts.destroy', $part) }}" method="post" class="d-inline" onsubmit="return confirm('Hapus part ini?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
            @endcan
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center">Belum ada data part.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($parts->hasPages())
  <div class="card-footer">{{ $parts->links('pagination::bootstrap-5') }}</div>
  @endif
</div>

<div class="modal modal-blur fade" id="modal-tambah-part" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Part Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('parts.store') }}" method="post">
        @csrf
        <div class="modal-body">
          <div class="row">
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-12 mb-3">
              <label class="form-label required">Site</label>
              <select name="site_id" class="form-select" required>
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}">{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="col-md-6 mb-3">
              <label class="form-label required">Part Number</label>
              <input type="text" class="form-control" name="part_number" required>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Unit Model Terkait</label>
              <select name="unit_models[]" class="form-select tom-select-unit-model" multiple data-placeholder="Pilih Unit Model Terkait...">
                @foreach($unitModels as $model)
                  <option value="{{ $model->id }}">{{ $model->name }}</option>
                @endforeach
              </select>
              <small class="text-muted">Pilih satu atau beberapa unit model yang kompatibel</small>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Part Description</label>
              <input type="text" class="form-control" name="part_description" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Satuan</label>
              <input type="text" class="form-control" name="satuan" placeholder="pcs, set, liter...">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Target Life (Hrs)</label>
              <input type="number" class="form-control" name="target" min="0" step="0.01" placeholder="Contoh: 15000">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Cost (Rp)</label>
              <input type="number" class="form-control" name="cost" min="0" step="0.01" value="0">
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Expenditure Type</label>
              <select name="expenditure_type" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Capex">Capex</option>
                <option value="Opex">Opex</option>
              </select>
            </div>
            @for($i = 1; $i <= 4; $i++)
            <div class="col-md-6 mb-3">
              <label class="form-label">Kategori {{ $i }}</label>
              <div class="input-group">
                <select name="kategori_{{ $i }}_id" id="kategori_{{ $i }}_select" class="form-select">
                  <option value="">-- Pilih Kategori {{ $i }} --</option>
                  @if(isset($categories["kategori_$i"]))
                    @foreach($categories["kategori_$i"] as $cat)
                      <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                  @endif
                </select>
                <button type="button" class="btn btn-outline-primary px-2 btn-add-category" data-type="kategori_{{ $i }}" data-target="kategori_{{ $i }}_select" title="Tambah Kategori {{ $i }}">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                </button>
              </div>
            </div>
            @endfor
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Part</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($parts as $part)
<div class="modal modal-blur fade" id="modal-edit-part-{{ $part->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Part: {{ $part->part_number }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('parts.update', $part) }}" method="post">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="row">
            @if(is_null(auth()->user()->site_id))
            <div class="col-md-12 mb-3">
              <label class="form-label required">Site</label>
              <select name="site_id" class="form-select" required>
                <option value="">-- Pilih Site --</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id', $part->site_id) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="col-md-6 mb-3">
              <label class="form-label required">Part Number</label>
              <input type="text" class="form-control" name="part_number" value="{{ $part->part_number }}" required>
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Unit Model Terkait</label>
              <select name="unit_models[]" class="form-select tom-select-unit-model" multiple data-placeholder="Pilih Unit Model Terkait...">
                @foreach($unitModels as $model)
                  <option value="{{ $model->id }}" {{ $part->unitModels->contains($model->id) ? 'selected' : '' }}>
                    {{ $model->name }}
                  </option>
                @endforeach
              </select>
              <small class="text-muted">Pilih satu atau beberapa unit model yang kompatibel</small>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label required">Part Description</label>
              <input type="text" class="form-control" name="part_description" value="{{ $part->part_description }}" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Satuan</label>
              <input type="text" class="form-control" name="satuan" value="{{ $part->satuan }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Target Life (Hrs)</label>
              <input type="number" class="form-control" name="target" value="{{ $part->target }}" min="0" step="0.01">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Cost (Rp)</label>
              <input type="number" class="form-control" name="cost" value="{{ $part->cost }}" min="0" step="0.01">
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Expenditure Type</label>
              <select name="expenditure_type" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Capex" {{ old('expenditure_type', $part->expenditure_type) == 'Capex' ? 'selected' : '' }}>Capex</option>
                <option value="Opex" {{ old('expenditure_type', $part->expenditure_type) == 'Opex' ? 'selected' : '' }}>Opex</option>
              </select>
            </div>
            @for($i = 1; $i <= 4; $i++)
            @php $katId = 'kategori_' . $i . '_id'; @endphp
            <div class="col-md-6 mb-3">
              <label class="form-label">Kategori {{ $i }}</label>
              <div class="input-group">
                <select name="kategori_{{ $i }}_id" id="edit_kategori_{{ $i }}_{{ $part->id }}_select" class="form-select">
                  <option value="">-- Pilih Kategori {{ $i }} --</option>
                  @if(isset($categories["kategori_$i"]))
                    @foreach($categories["kategori_$i"] as $cat)
                      <option value="{{ $cat->id }}" {{ old($katId, $part->$katId) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                  @endif
                </select>
                <button type="button" class="btn btn-outline-primary px-2 btn-add-category" data-type="kategori_{{ $i }}" data-target="edit_kategori_{{ $i }}_{{ $part->id }}_select" title="Tambah Kategori {{ $i }}">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                </button>
              </div>
            </div>
            @endfor
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi TomSelect untuk semua dropdown Unit Model
    function initTomSelect(el) {
        if (!el || el.tomselect) return;
        new TomSelect(el, {
            plugins: ['remove_button'],
            maxItems: null,
            placeholder: el.getAttribute('data-placeholder') || 'Pilih Unit Model Terkait...',
            closeAfterSelect: false,
            hidePlaceholder: true,
        });
    }

    // Init pada modal tambah saat dibuka
    const modalTambah = document.getElementById('modal-tambah-part');
    if (modalTambah) {
        modalTambah.addEventListener('shown.bs.modal', function() {
            modalTambah.querySelectorAll('.tom-select-unit-model').forEach(initTomSelect);
        });
    }

    // Init pada setiap modal edit saat dibuka
    document.querySelectorAll('[id^="modal-edit-part-"]').forEach(modalEdit => {
        modalEdit.addEventListener('shown.bs.modal', function() {
            modalEdit.querySelectorAll('.tom-select-unit-model').forEach(initTomSelect);
        });
    });

    // Handle tambah kategori inline via prompt
    document.querySelectorAll('.btn-add-category').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            const targetId = this.dataset.target;
            
            let label = 'Kategori ' + type.split('_')[1];
            
            let val = prompt('Masukkan nama ' + label + ' baru:');
            if (val && val.trim() !== '') {
                fetch('{{ route("parts.category.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ type: type, name: val.trim() })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById(targetId);
                        const opt = document.createElement('option');
                        opt.value = data.category.id;
                        opt.textContent = data.category.name;
                        opt.selected = true;
                        select.appendChild(opt);
                    } else {
                        alert('Gagal menambahkan kategori.');
                    }
                })
                .catch(e => console.error(e));
            }
        });
    });
});
</script>
@endpush
@endsection