@extends('layouts.tabler')

@section('title', 'Manajemen User - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">
        Manajemen User
      </h2>
      <div class="text-secondary mt-1">{{ $users->total() }} users terdaftar</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
      <div class="btn-list">
        <a href="{{ route('users.create') }}" class="btn btn-primary d-none d-sm-inline-block">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
          Tambah User
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row row-cards mt-3">
  <div class="col-12">
    <div class="card">
      <div class="table-responsive">
        <table class="table table-vcenter card-table">
          <thead>
            <tr>
              <th>NIK</th>
              <th>Nama Lengkap</th>
              <th>Email</th>
              <th>Site</th>
              <th>Status</th>
              <th>Role</th>
              <th class="w-1">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
            <tr>
              <td>{{ $user->nik }}</td>
              <td>{{ $user->nama_lengkap }}</td>
              <td class="text-secondary">{{ $user->email }}</td>
              <td>
                @if($user->site)
                  <span class="badge bg-blue-lt">{{ $user->site->code }}</span>
                @else
                  <span class="badge bg-dark-lt">All Sites</span>
                @endif
              </td>
              <td>
                @if($user->status === 'active')
                  <span class="badge bg-success me-1"></span> Active
                @elseif($user->status === 'pending')
                  <span class="badge bg-warning me-1"></span> Pending
                @else
                  <span class="badge bg-danger me-1"></span> Rejected
                @endif
              </td>
              <td>
                {{ $user->getRoleNames()->implode(', ') ?: '-' }}
              </td>
              <td class="d-flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('users.destroy', $user) }}" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center">Belum ada data user.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer d-flex align-items-center">
        {{ $users->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection
