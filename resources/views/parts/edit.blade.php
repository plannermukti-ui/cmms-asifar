@extends('layouts.tabler')
@section('title', 'Edit Part - CMMS')
@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col"><h2 class="page-title">Edit Part</h2></div>
    <div class="col-auto ms-auto"><a href="{{ route('parts.index') }}" class="btn btn-secondary">Kembali</a></div>
  </div>
</div>
<div class="row mt-3">
  <div class="col-md-8">
    <div class="card"><div class="card-body">
      <form action="{{ route('parts.update', $part) }}" method="post">
        @csrf @method('PUT')
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
          <div class="col-md-6 mb-3">
            <label class="form-label required">Part Description</label>
            <input type="text" class="form-control" name="part_description" value="{{ $part->part_description }}" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Satuan</label>
            <input type="text" class="form-control" name="satuan" value="{{ $part->satuan }}">
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
              <select name="kategori_{{ $i }}_id" id="kategori_{{ $i }}_select" class="form-select">
                <option value="">-- Pilih Kategori {{ $i }} --</option>
                @if(isset($categories["kategori_$i"]))
                  @foreach($categories["kategori_$i"] as $cat)
                    <option value="{{ $cat->id }}" {{ old($katId, $part->$katId) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </form>
    </div></div>
  </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection