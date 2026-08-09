@extends('layouts.tabler')

@section('title', 'Profil Saya')

@section('content')
<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle me-2" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                </svg>
                Pengaturan Profil Saya
            </h2>
            <div class="text-secondary mt-1">Kelola data pribadi, foto profil, dan kata sandi akun Anda</div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <!-- Kolom Kiri: Informasi & Foto Profil -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar))
                        <span class="avatar avatar-xl rounded-circle" style="background-image: url('{{ asset('storage/avatars/' . $user->avatar) }}'); width: 110px; height: 110px; background-size: cover; background-position: center; border: 3px solid #f59e0b;"></span>
                    @else
                        <span class="avatar avatar-xl rounded-circle bg-warning text-dark font-weight-bold fs-1" style="width: 110px; height: 110px; line-height: 110px; border: 3px solid #f59e0b;">
                            {{ strtoupper(substr($user->nama_lengkap ?? 'U', 0, 1)) }}
                        </span>
                    @endif
                </div>
                <h3 class="m-0 mb-1"><a href="#">{{ $user->nama_lengkap }}</a></h3>
                <div class="text-secondary mb-2">{{ $user->email }}</div>
                <div class="mb-3">
                    <span class="badge bg-warning-lt">{{ $user->getRoleNames()->first() ?? 'User' }}</span>
                    @if($user->site)
                        <span class="badge bg-blue-lt ms-1">{{ $user->site->name }}</span>
                    @endif
                </div>

                <div class="border-top pt-3 text-start">
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">NIK:</div>
                        <div class="col-7 font-weight-bold">{{ $user->nik ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">WhatsApp:</div>
                        <div class="col-7 font-weight-bold">{{ $user->no_whatsapp ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-secondary">Status:</div>
                        <div class="col-7">
                            <span class="badge bg-success">{{ strtoupper($user->status) }}</span>
                        </div>
                    </div>
                    @if($user->bio)
                    <div class="mt-3 p-2 bg-light rounded text-muted small">
                        <strong>Bio:</strong> "{{ $user->bio }}"
                    </div>
                    @endif
                    <div class="mt-3 text-center">
                        <a href="{{ route('profile.show', $user) }}" class="btn btn-sm btn-outline-warning w-100">
                            <svg class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                            Lihat Halaman Bio Publik Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Form Update Profil & Password -->
    <div class="col-md-8">
        <!-- Form Profil & Avatar -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Perbarui Informasi Profil & Foto</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" name="no_whatsapp" class="form-control @error('no_whatsapp') is-invalid @enderror" value="{{ old('no_whatsapp', $user->no_whatsapp) }}" placeholder="08xxxxxxxxxx">
                        @error('no_whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bio / Deskripsi Singkat</label>
                        <textarea name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror" placeholder="Tuliskan posisi, keahlian, atau pesan singkat Anda...">{{ old('bio', $user->bio) }}</textarea>
                        <small class="form-hint">Bio ini dapat dilihat oleh pengguna lain saat melihat profil/bio Anda.</small>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unggah Foto Profil (Avatar)</label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        <div class="form-hint">Format yang didukung: JPG, PNG, GIF, WEBP. Ukuran maksimal: 2MB.</div>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Ubah Password -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ubah Kata Sandi (Password)</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label required">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-footer text-end">
                        <button type="submit" class="btn btn-warning text-dark font-weight-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /></svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
