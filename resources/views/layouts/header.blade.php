<header class="navbar navbar-expand-md d-print-none">
  <div class="container-xl">
    <!-- Sidebar Toggler on Mobile -->
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-label="Toggle navigation" style="border: none; padding: 0;">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <!-- Brand / Logo in Header -->
    <div class="navbar-brand navbar-brand-autodark me-auto ms-3 ms-lg-0" style="position: static !important; transform: none !important; left: auto;">
      <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none text-reset">
        @php
            $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
            $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS Aisfar';
            $siteCode = auth()->check() ? (auth()->user()->site?->code ?? session('site_code')) : null;
            if ($siteCode) {
                $appName .= ' - ' . $siteCode;
            }
        @endphp
        @if($appLogo)
            <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="{{ $appName }}" class="navbar-brand-image me-2" style="max-height: 32px;">
        @else
            <span class="avatar avatar-sm bg-warning text-dark me-2 fw-bold" style="border-radius: 8px;">C</span>
        @endif
        <span class="fw-bold fs-2 tracking-wide text-warning">{{ $appName }}</span>
      </a>
    </div>

    <div class="navbar-nav flex-row order-md-last">
      <div class="d-none d-md-flex">
        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
		   data-bs-placement="bottom" onclick="event.preventDefault(); localStorage.setItem('tablerTheme', 'dark'); document.body.setAttribute('data-bs-theme', 'dark');">
          <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
        </a>
        <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
		   data-bs-placement="bottom" onclick="event.preventDefault(); localStorage.setItem('tablerTheme', 'light'); document.body.setAttribute('data-bs-theme', 'light');">
          <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
        </a>
        <div class="nav-item dropdown d-none d-md-flex me-3">
          <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
            @php
                $pendingCount = \App\Models\User::where('status', 'pending')->count();
                $unreadMessages = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count();
                
                // Hitung notifikasi Tanda Tangan Digital yang tertunda berdasarkan Role
                $pendingSignatures = 0;
                $userRoles = auth()->user()->getRoleNames()->toArray();
                
                // Cari WO yang belum selesai/cancel
                $activeWOs = \App\Models\WorkOrder::whereNotIn('status_wo', ['Completed', 'Cancel'])->get();
                
                foreach ($activeWOs as $wo) {
                    $sigs = $wo->signatures->keyBy('sign_type');
                    
                    if (in_array('Foreman', $userRoles) && !$sigs->has('diperiksa')) {
                        $pendingSignatures++;
                    }
                    if (in_array('Supervisor', $userRoles) && !$sigs->has('ditinjau')) {
                        $pendingSignatures++;
                    }
                    if ((in_array('Superintendent', $userRoles) || in_array('Manager', $userRoles)) && !$sigs->has('disetujui')) {
                        $pendingSignatures++;
                    }
                }

                $totalNotif = $pendingCount + $unreadMessages + $pendingSignatures;
            @endphp
            @if($totalNotif > 0)
              <span class="badge bg-red" id="notif-badge">{{ $totalNotif }}</span>
            @else
              <span class="badge bg-red d-none" id="notif-badge">0</span>
            @endif
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Notifikasi</h3>
              </div>
              <div class="list-group list-group-flush list-group-hoverable">
                @if($pendingSignatures > 0)
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col text-truncate">
                      <a href="{{ route('work-orders.index') }}" class="text-body d-block text-primary fw-bold">Tanda Tangan Tertunda</a>
                      <div class="d-block text-secondary text-truncate mt-n1">
                        Ada <strong>{{ $pendingSignatures }}</strong> Work Order butuh stempel digital Anda.
                      </div>
                    </div>
                  </div>
                </div>
                @endif
                @if($pendingCount > 0)
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col text-truncate">
                      <a href="{{ route('users.index') }}" class="text-body d-block">Pendaftar Baru</a>
                      <div class="d-block text-secondary text-truncate mt-n1">
                        Ada <strong>{{ $pendingCount }}</strong> user menunggu persetujuan.
                      </div>
                    </div>
                  </div>
                </div>
                @endif
                @if($unreadMessages > 0)
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col text-truncate">
                      <a href="{{ route('chat.index') }}" class="text-body d-block">Pesan Baru</a>
                      <div class="d-block text-secondary text-truncate mt-n1">
                        Anda memiliki <strong>{{ $unreadMessages }}</strong> pesan belum dibaca.
                      </div>
                    </div>
                  </div>
                </div>
                @endif
                @if($totalNotif == 0)
                <div class="list-group-item text-center text-muted py-3">
                  Tidak ada notifikasi baru.
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          @if(Auth::check() && Auth::user()->avatar && Storage::disk('public')->exists('avatars/' . Auth::user()->avatar))
            <span class="avatar avatar-sm" style="background-image: url('{{ asset('storage/avatars/' . Auth::user()->avatar) }}'); background-size: cover; background-position: center;"></span>
          @else
            <span class="avatar avatar-sm bg-warning text-dark font-weight-bold">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'G', 0, 1)) }}</span>
          @endif
          <div class="d-none d-xl-block ps-2">
            <div>{{ Auth::user()->nama_lengkap ?? 'Guest' }}</div>
            <div class="mt-1 small text-secondary">{{ Auth::check() ? Auth::user()->getRoleNames()->first() ?? 'User' : 'Administrator' }}</div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="{{ route('profile.show', Auth::id()) }}" class="dropdown-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
            Lihat Bio Publik Saya
          </a>
          <a href="{{ route('profile.edit') }}" class="dropdown-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-secondary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
            Edit Profil Saya
          </a>
          <a href="{{ route('guide') }}" class="dropdown-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1 text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6v13" /><path d="M12 6v13" /><path d="M21 6v13" /></svg>
            Panduan & User Manual
          </a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
              @csrf
              <a href="{{ route('logout') }}" class="dropdown-item text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
                Logout
              </a>
          </form>
        </div>
      </div>
    </div>
    <div class="collapse navbar-collapse" id="navbar-menu">
      <div>
        <form action="./" method="get" autocomplete="off" novalidate>
          <div class="input-icon">
            <span class="input-icon-addon">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
            </span>
            <input type="text" value="" class="form-control" placeholder="Search…" aria-label="Search in website">
          </div>
        </form>
      </div>
    </div>
  </div>
</header>
