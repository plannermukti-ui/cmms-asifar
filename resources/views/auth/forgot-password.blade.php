@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-4">Lupa Password</h2>
    <p class="text-secondary text-center mb-4">Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset password.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
               placeholder="your@email.com" value="{{ old('email') }}" required autofocus>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Kirim Link Reset Password</button>
      </div>
    </form>
  </div>
</div>
<div class="text-center text-secondary mt-3">
  Ingat password Anda? <a href="{{ route('login') }}" tabindex="-1">Kembali Login</a>
</div>
@endsection
