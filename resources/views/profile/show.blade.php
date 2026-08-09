@extends('layouts.tabler')

@section('title', 'Bio & Profil - ' . $user->nama_lengkap)

@section('content')
<div class="container-xl">
  <!-- Top Navigation Header -->
  <div class="page-header d-print-none mb-3">
    <div class="row align-items-center">
      <div class="col">
        <h2 class="page-title text-primary fw-bold">
          <svg class="icon icon-tabler icon-tabler-id me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
          Profil & Bio Pengguna
        </h2>
      </div>
      <div class="col-auto ms-auto">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
          <svg class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
          Kembali
        </a>
      </div>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
      <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <!-- Cover Banner -->
        <div class="card-cover text-center py-4 bg-primary text-white position-relative" style="background: linear-gradient(135deg, #1e293b, #0f172a); min-height: 120px;">
          <div class="hazard-stripe-top"></div>
        </div>

        <!-- Profile Body -->
        <div class="card-body text-center position-relative pt-0">
          <!-- Profile Picture (Avatar) -->
          <div class="mb-3" style="margin-top: -55px;">
            @if($user->avatar_url)
              <span class="avatar avatar-xl rounded-circle shadow-lg border border-4 border-white" style="background-image: url('{{ $user->avatar_url }}'); width: 110px; height: 110px; background-size: cover; background-position: center;"></span>
            @else
              <span class="avatar avatar-xl rounded-circle bg-primary text-white fw-bold fs-1 shadow-lg border border-4 border-white" style="width: 110px; height: 110px; line-height: 100px;">
                {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
              </span>
            @endif
          </div>

          <!-- User Name & Roles -->
          <h2 class="fw-bold mb-1 text-dark">{{ $user->nama_lengkap }}</h2>
          <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <span class="badge bg-primary-lt px-3 py-1 fs-4">{{ $user->getRoleNames()->first() ?? 'User' }}</span>
            @if($user->site)
              <span class="badge bg-azure-lt px-3 py-1 fs-4">{{ $user->site->name }}</span>
            @endif
            @if($user->department)
              <span class="badge bg-purple-lt px-3 py-1 fs-4">{{ $user->department->name }}</span>
            @endif
          </div>

          <!-- Bio Section -->
          <div class="my-4 p-3 bg-light rounded-3 border border-secondary-subtle text-start">
            <div class="fw-bold small text-muted text-uppercase mb-2 d-flex align-items-center gap-1">
              <svg class="icon text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6v13" /><path d="M12 6v13" /><path d="M21 6v13" /></svg>
              Bio / Deskripsi Pengguna:
            </div>
            @if($user->bio)
              <div class="fst-italic text-dark fs-3" style="line-height: 1.6;">
                "{{ $user->bio }}"
              </div>
            @else
              <div class="text-muted small fst-italic">Pengguna ini belum menambahkan deskripsi bio.</div>
            @endif
          </div>

          <!-- Key User Information Grid -->
          <div class="row g-3 text-start mb-4">
            <!-- NIK -->
            <div class="col-sm-6">
              <div class="p-3 border rounded-3 bg-white h-100 shadow-xs">
                <div class="text-muted small mb-1 d-flex align-items-center gap-1">
                  <svg class="icon icon-tabler icon-tabler-id text-warning" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                  Nomor Induk Karyawan (NIK)
                </div>
                <div class="fw-bold fs-3 text-dark">{{ $user->nik ?? '-' }}</div>
              </div>
            </div>

            <!-- Email -->
            <div class="col-sm-6">
              <div class="p-3 border rounded-3 bg-white h-100 shadow-xs">
                <div class="text-muted small mb-1 d-flex align-items-center gap-1">
                  <svg class="icon icon-tabler icon-tabler-mail text-info" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                  Alamat Email
                </div>
                <div class="fw-bold fs-3 text-dark text-truncate">{{ $user->email }}</div>
              </div>
            </div>

            <!-- WhatsApp -->
            <div class="col-sm-6">
              <div class="p-3 border rounded-3 bg-white h-100 shadow-xs">
                <div class="text-muted small mb-1 d-flex align-items-center gap-1">
                  <svg class="icon icon-tabler icon-tabler-brand-whatsapp text-success" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                  Nomor WhatsApp
                </div>
                <div class="fw-bold fs-3 text-dark">{{ $user->no_whatsapp ?? '-' }}</div>
              </div>
            </div>

            <!-- Jabatan & Departemen -->
            <div class="col-sm-6">
              <div class="p-3 border rounded-3 bg-white h-100 shadow-xs">
                <div class="text-muted small mb-1 d-flex align-items-center gap-1">
                  <svg class="icon icon-tabler icon-tabler-briefcase text-purple" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /></svg>
                  Jabatan
                </div>
                <div class="fw-bold fs-3 text-dark">{{ $user->jabatan->name ?? '-' }}</div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex flex-wrap gap-2 justify-content-center">
            @if(auth()->id() !== $user->id)
              <a href="{{ route('chat.index') }}?user={{ $user->id }}" class="btn btn-primary px-4 d-flex align-items-center gap-2">
                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M8 9h8" /><path d="M8 13h6" /></svg>
                <span>Kirim Pesan Chat</span>
              </a>

              @if($user->no_whatsapp)
                @php
                  // Clean whatsapp number format
                  $waNum = preg_replace('/[^0-9]/', '', $user->no_whatsapp);
                  if (str_starts_with($waNum, '0')) {
                      $waNum = '62' . substr($waNum, 1);
                  }
                @endphp
                <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener noreferrer" class="btn btn-success px-4 d-flex align-items-center gap-2">
                  <svg class="icon icon-tabler icon-tabler-brand-whatsapp" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                  <span>Chat via WhatsApp</span>
                </a>
              @endif
            @else
              <a href="{{ route('profile.edit') }}" class="btn btn-warning px-4 d-flex align-items-center gap-2">
                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                <span>Edit Profil Saya</span>
              </a>
            @endif
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
