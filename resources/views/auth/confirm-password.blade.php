@extends('layouts.auth')

@section('title', 'Konfirmasi Password')

@section('content')
<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-4">Konfirmasi Password</h2>
    <p class="text-secondary text-center mb-4">Ini adalah area yang dilindungi. Mohon konfirmasi password Anda sebelum melanjutkan.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            Password yang Anda masukkan salah.
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autofocus>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Konfirmasi</button>
      </div>
    </form>
  </div>
</div>
@endsection
