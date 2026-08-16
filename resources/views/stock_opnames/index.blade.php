@extends('layouts.tabler')

@section('title', 'Stock Opname - CMMS')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Riwayat Stock Opname
      </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
      @can('create_stock_opnames')
      <a href="{{ route('stock-opnames.create') }}" class="btn btn-primary">
        Mulai Stock Opname Baru
      </a>
      @endcan
    </div>
  </div>
</div>

<div class="card mt-3">
    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th>ID Audit</th>
                    <th>Tanggal Audit</th>
                    <th>Tipe Lokasi</th>
                    <th>Mekanik</th>
                    <th>Auditor</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opnames as $opname)
                <tr>
                    <td>#AUD-{{ str_pad($opname->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($opname->tanggal_audit)->format('d M Y') }}</td>
                    <td>{{ $opname->tipe_audit }}</td>
                    <td>{{ $opname->mechanic->nama_lengkap ?? '-' }}</td>
                    <td>{{ $opname->auditor->name ?? '-' }}</td>
                    <td>
                        @if($opname->status == 'Approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($opname->status == 'Pending Approval')
                            <span class="badge bg-warning">Pending Approval</span>
                            @if($opname->approver)
                                <div class="text-muted small mt-1">To: {{ $opname->approver->name }}</div>
                            @endif
                        @elseif($opname->status == 'Rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-list flex-nowrap">
                            <a href="{{ route('stock-opnames.show', $opname) }}" class="btn btn-sm btn-info">Detail / Print</a>
                            
                            @if($opname->status == 'Pending Approval')
                                @if(!$opname->signed_document)
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $opname->id }}">
                                        Upload Dokumen
                                    </button>
                                @endif

                                @if($opname->approver_id == auth()->id() || auth()->user()->hasRole('Super Admin'))
                                    @if($opname->signed_document)
                                        <form action="{{ route('stock-opnames.approve', $opname) }}" method="post" class="d-inline" onsubmit="return confirm('Approve Berita Acara ini dan sesuaikan stok sistem?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve & Update Stok</button>
                                        </form>
                                        <form action="{{ route('stock-opnames.reject', $opname) }}" method="post" class="d-inline" onsubmit="return confirm('Reject Berita Acara ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary disabled" title="Menunggu upload dokumen">Menunggu Dokumen</button>
                                    @endif
                                @endif
                            @endif

                            @if($opname->signed_document)
                                <a href="{{ asset('storage/stock_opnames/' . $opname->signed_document) }}" target="_blank" class="btn btn-sm btn-outline-success">Lihat PDF</a>
                            @endif

                            @can('delete_stock_opnames')
                            <form action="{{ route('stock-opnames.destroy', $opname) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin menghapus data stock opname ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>

                <!-- Modal Upload Dokumen -->
                <div class="modal modal-blur fade" id="uploadModal{{ $opname->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Upload Dokumen Berita Acara</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="{{ route('stock-opnames.upload', $opname) }}" method="post" enctype="multipart/form-data">
                          @csrf
                          <div class="modal-body">
                              <p>Silakan upload file PDF Berita Acara yang telah ditandatangani.</p>
                              <div class="mb-3">
                                  <label class="form-label">File PDF</label>
                                  <input type="file" class="form-control" name="signed_document" accept="application/pdf" required>
                                  <small class="text-muted">Maksimal 5MB.</small>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-primary">Upload Dokumen</button>
                          </div>
                      </form>
                    </div>
                  </div>
                </div>
                @empty
                <tr><td colspan="6" class="text-center">Belum ada data stock opname.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($opnames->hasPages())
      <div class="card-footer">
        {{ $opnames->links('pagination::bootstrap-5') }}
      </div>
    @endif
</div>
@endsection