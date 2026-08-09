@extends('layouts.tabler')

@section('title', 'Pengaturan Sistem - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Pengaturan Sistem</h2>
      <div class="text-secondary mt-1">Konfigurasi identitas aplikasi dan email.</div>
    </div>
  </div>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row mt-3">
  <!-- Identitas Aplikasi -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title">🏢 Identitas Perusahaan</h3></div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Nama Aplikasi</label>
          <input type="text" class="form-control" name="app_name" value="{{ $settings['app_name'] ?? 'CMMS Aisfar' }}">
          <small class="form-hint text-info">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
            Pengaturan Site (Lokasi) dilakukan terpisah melalui menu <a href="{{ route('sites.index') }}" class="fw-bold">Administrator &rarr; Site (Lokasi)</a>.
          </small>
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat Perusahaan</label>
          <textarea class="form-control" name="app_address" rows="3">{{ $settings['app_address'] ?? '' }}</textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Logo Aplikasi</label>
          <p class="text-secondary small">Format: JPG, PNG, SVG. Ukuran optimal: 200×60px (maks. 2MB).</p>
          @if(isset($settings['app_logo']))
          <div class="mb-2 p-2 bg-dark rounded d-inline-block">
            <img src="{{ asset('storage/logos/' . $settings['app_logo']) }}" alt="Logo" height="40">
          </div>
          <br>
          @endif
          <input type="file" class="form-control" name="app_logo" accept="image/*">
        </div>
      </div>
    </div>
  </div>

  <!-- Konfigurasi SMTP -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title">📧 Konfigurasi Email (SMTP)</h3></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8 mb-3">
            <label class="form-label">SMTP Host</label>
            <input type="text" class="form-control" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" placeholder="smtp.gmail.com">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Port</label>
            <input type="number" class="form-control" name="mail_port" value="{{ $settings['mail_port'] ?? 587 }}" placeholder="587">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Username / Email</label>
          <input type="text" class="form-control" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" placeholder="your@gmail.com">
        </div>
        <div class="mb-3">
          <label class="form-label">Password / App Password</label>
          <input type="password" class="form-control" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" placeholder="••••••••">
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Encryption</label>
            <select name="mail_encryption" class="form-select">
              <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
              <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">From Name</label>
            <input type="text" class="form-control" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? 'CMMS Aisfar' }}" placeholder="CMMS Aisfar">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">From Email</label>
          <input type="email" class="form-control" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="noreply@company.com">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="mt-3 d-flex gap-2">
  <button type="submit" class="btn btn-primary">Simpan Semua Pengaturan</button>
</div>
</form>

<!-- Test Email Form -->
<div class="card mt-3">
  <div class="card-header"><h3 class="card-title">🧪 Uji Coba Kirim Email</h3></div>
  <div class="card-body">
    <p class="text-secondary">Simpan pengaturan SMTP terlebih dahulu, lalu uji coba pengiriman email ke alamat tujuan.</p>
    <form action="{{ route('settings.test-email') }}" method="POST" class="row g-2">
      @csrf
      <div class="col-md-5">
        <input type="email" name="test_email" class="form-control" placeholder="Alamat email tujuan uji coba" required>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-secondary">Kirim Email Test</button>
      </div>
    </form>
  </div>
</div>
@endsection
