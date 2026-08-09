@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="stage">
    
    <!-- Maskot -->
    <div class="mascot-wrap">
        <svg class="deco-star s1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
        <svg class="deco-star s2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
        <svg class="deco-star s3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
        <img src="{{ asset('img/mascot.png') }}" alt="Mascot CMMS">
    </div>

    <div class="card card-md card-auth">
      <div class="card-body">
        <h2 class="h2 text-center mb-4">Login ke akun Anda</h2>
        
        @if (session('status'))
            <div class="alert alert-info">{{ session('status') }}</div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                Login Gagal. Pastikan email dan password Anda benar.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" autocomplete="off">
          @csrf
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="your@email.com" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-2">
            <label class="form-label">
              Password
              @if (Route::has('password.request'))
              <span class="form-label-description">
                <a href="{{ route('password.request') }}">Lupa Password?</a>
              </span>
              @endif
            </label>
            <div class="input-group input-group-flat">
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">
            </div>
            @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-2">
            <label class="form-check">
              <input type="checkbox" class="form-check-input" name="remember"/>
              <span class="form-check-label">Ingat perangkat saya</span>
            </label>
          </div>
          <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Sign in</button>
          </div>
        </form>
      </div>
    </div>
    <div class="text-center text-secondary mt-3">
      Belum punya akun? <a href="{{ route('register') }}" tabindex="-1">Daftar sekarang</a>
    </div>
</div>
@endsection
