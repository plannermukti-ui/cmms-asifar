@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-4">Buat Password Baru</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <div class="mb-3">
        <label class="form-label">Email address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email', $request->email) }}" required autofocus>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
               placeholder="Password baru Anda" required>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" 
               class="form-control" placeholder="Ulangi password baru" required>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
      </div>
    </form>
  </div>
</div>
<div class="text-center text-secondary mt-3">
  <a href="{{ route('login') }}" tabindex="-1">Kembali Login</a>
</div>
@endsection
