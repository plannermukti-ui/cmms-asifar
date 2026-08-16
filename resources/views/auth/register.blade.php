@extends('layouts.auth')

@section('title', 'Register')

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
        <h2 class="h2 text-center mb-4">Buat akun baru</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                Terdapat kesalahan pada inputan Anda.
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" autocomplete="off">
          @csrf
          
          <div class="mb-3">
            <label class="form-label required">NIK</label>
            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="Nomor Induk Karyawan" value="{{ old('nik') }}" required autofocus>
            @error('nik')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label required">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" placeholder="Nama lengkap Anda" value="{{ old('nama_lengkap') }}" required>
            @error('nama_lengkap')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label required">Email address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="your@email.com" value="{{ old('email') }}" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label required">No WhatsApp</label>
            <input type="text" name="no_whatsapp" class="form-control @error('no_whatsapp') is-invalid @enderror" placeholder="08xxxxxxxxx" value="{{ old('no_whatsapp') }}" required>
            @error('no_whatsapp')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label required">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label class="form-label required">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
          </div>
          
          <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
          </div>
        </form>
      </div>
    </div>
    <div class="text-center mt-3">
      <a href="{{ route('guide') }}" class="d-inline-flex align-items-center gap-1 text-decoration-none" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#2563eb;border:1.5px solid #bfdbfe;padding:7px 18px;border-radius:100px;font-size:.82rem;font-weight:600;transition:all .2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(37,99,235,.2)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>
        Pelajari Fitur CMMS AISFAR
      </a>
    </div>
    <div class="text-center text-secondary mt-2">
      Sudah punya akun? <a href="{{ route('login') }}" tabindex="-1">Sign in</a>
    </div>
</div>
@endsection
