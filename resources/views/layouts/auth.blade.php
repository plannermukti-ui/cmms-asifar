<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>@yield('title', 'Authentication') - CMMS Aisfar</title>
    <!-- CSS files -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        --industrial-yellow: #f59e0b;
        --industrial-dark: #0f172a;
        --industrial-slate: #1e293b;
        --industrial-orange: #ea580c;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
        overflow-x: hidden;
        background-color: #0f172a;
        background-image: 
          radial-gradient(circle at 50% 30%, rgba(245, 158, 11, 0.12), transparent 60%),
          radial-gradient(circle at 10% 80%, rgba(234, 88, 12, 0.08), transparent 40%),
          radial-gradient(circle at 90% 20%, rgba(59, 130, 246, 0.08), transparent 40%),
          linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
          linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 100% 100%, 100% 100%, 100% 100%, 36px 36px, 36px 36px;
        position: relative;
        min-height: 100vh;
        color: #f8fafc;
      }

      /* Top & Bottom Industrial Hazard Stripe Bar */
      .hazard-stripe-top {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: repeating-linear-gradient(
          -45deg,
          #f59e0b,
          #f59e0b 14px,
          #0f172a 14px,
          #0f172a 28px
        );
        z-index: 9999;
        box-shadow: 0 2px 10px rgba(245, 158, 11, 0.4);
      }

      /* Floating & Rotating Industrial Gears */
      .bg-gear {
        position: fixed;
        color: rgba(245, 158, 11, 0.06);
        pointer-events: none;
        z-index: 0;
      }
      .bg-gear-1 {
        top: -80px;
        left: -80px;
        width: 380px;
        height: 380px;
        animation: spinGear 35s linear infinite;
      }
      .bg-gear-2 {
        bottom: -100px;
        right: -100px;
        width: 450px;
        height: 450px;
        animation: spinGearRev 45s linear infinite;
        color: rgba(255, 255, 255, 0.03);
      }
      .bg-gear-3 {
        bottom: 20%;
        left: 5%;
        width: 200px;
        height: 200px;
        animation: spinGear 25s linear infinite;
        color: rgba(245, 158, 11, 0.04);
      }

      @keyframes spinGear {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
      @keyframes spinGearRev {
        from { transform: rotate(360deg); }
        to { transform: rotate(0deg); }
      }
      
      /* ===== Stage & Mascot Styles ===== */
      .stage {
          position: relative;
          width: 100%;
          max-width: 450px;
          margin: 0 auto;
          animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
          opacity: 0;
          transform: translateY(20px);
          z-index: 10;
      }
      @keyframes slideUpFade {
          to { opacity: 1; transform: translateY(0); }
      }

      .card-auth {
          border-radius: 20px;
          border: 1px solid rgba(245, 158, 11, 0.25);
          box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.6),
            0 0 25px rgba(245, 158, 11, 0.1);
          background: rgba(30, 41, 59, 0.88);
          backdrop-filter: blur(20px);
          -webkit-backdrop-filter: blur(20px);
          position: relative;
          z-index: 2;
          color: #f1f5f9;
          transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
      }
      .card-auth:hover {
          border-color: rgba(245, 158, 11, 0.5);
          box-shadow: 
            0 30px 60px -12px rgba(0, 0, 0, 0.7),
            0 0 35px rgba(245, 158, 11, 0.2);
      }

      /* Card Header Industrial Badge */
      .card-auth .card-body {
          padding: 2.25rem 2rem;
      }
      .card-auth h2 {
          color: #ffffff !important;
          font-weight: 700;
      }
      .card-auth .form-label {
          color: #cbd5e1;
          font-weight: 600;
      }
      .card-auth .form-control {
          background: rgba(15, 23, 42, 0.7);
          border: 1px solid #334155;
          color: #ffffff;
          border-radius: 10px;
          padding: 0.65rem 0.9rem;
      }
      .card-auth .form-control:focus {
          border-color: #f59e0b;
          box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
          background: rgba(15, 23, 42, 0.9);
          color: #ffffff;
      }
      .card-auth .form-control::placeholder {
          color: #64748b;
      }
      .card-auth .btn-primary {
          background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
          border: none;
          color: #0f172a;
          font-weight: 700;
          letter-spacing: 0.5px;
          padding: 0.75rem;
          border-radius: 10px;
          box-shadow: 0 6px 18px rgba(245, 158, 11, 0.35);
          transition: all 0.3s ease;
      }
      .card-auth .btn-primary:hover {
          background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
          transform: translateY(-2px);
          box-shadow: 0 10px 22px rgba(245, 158, 11, 0.5);
          color: #000000;
      }
      .card-auth a {
          color: #38bdf8;
      }
      .card-auth a:hover {
          color: #7dd3fc;
      }

      .mascot-wrap {
          position: absolute;
          top: 50%;
          left: 98%; /* Geser lebih ke kanan */
          transform: translate(-12%, -50%);
          width: 320px;
          z-index: 10; /* Di atas form */
          pointer-events: none;
          animation: pointLeft 2.6s ease-in-out infinite;
      }
      .mascot-wrap img {
          width: 100%;
          height: auto;
          filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.5));
      }
      @keyframes pointLeft {
          0%, 100% { transform: translate(-12%, -50%) translateX(0); }
          50%      { transform: translate(-12%, -50%) translateX(-12px); }
      }

      .deco-star {
          position: absolute;
          color: var(--industrial-yellow);
          opacity: 0.95;
          animation: floatStar 3s ease-in-out infinite;
          z-index: 4;
      }
      .deco-star.s1 { top: 0px;  left: 78%;  width: 24px; animation-delay: 0s; color: #fbbf24; }
      .deco-star.s2 { top: 30%;    left: 120%; width: 18px;   animation-delay: 0.6s; color: #f97316; }
      .deco-star.s3 { bottom: 0px; left: 90%;  width: 20px; animation-delay: 1.2s; color: #38bdf8; }
      @keyframes floatStar {
          0%, 100% { transform: translateY(0) rotate(0deg); }
          50%      { transform: translateY(-10px) rotate(15deg); }
      }

      .heavy-equipment-badge {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          background: rgba(245, 158, 11, 0.15);
          border: 1px solid rgba(245, 158, 11, 0.4);
          color: #fbbf24;
          font-size: 0.75rem;
          font-weight: 700;
          letter-spacing: 1px;
          text-transform: uppercase;
          padding: 4px 12px;
          border-radius: 50px;
          margin-bottom: 1rem;
      }

      @media (max-width: 767.98px) {
          body {
              padding: 20px 10px;
          }
          .stage { max-width: 100%; padding: 0 5px; margin-top: 10px; }
          .mascot-wrap {
              display: none !important;
          }
          .navbar-brand {
              display: none !important;
          }
          @keyframes pointDown {
              0%, 100% { transform: translateY(0); }
              50%      { transform: translateY(5px); }
          }
          .deco-star.s2 { left: 82%; }
          
          /* Mobile-First Form Adjustments */
          .card-auth {
              border-radius: 24px; /* Sudut melengkung halus */
              border-color: rgba(245, 158, 11, 0.4);
          }
          .card-auth .card-body {
              padding: 2rem 1.25rem; /* Padding samping dikurangi */
          }
          .card-auth h2 {
              font-size: 1.45rem;
          }
          .card-auth .form-control {
              font-size: 16px !important; /* Mencegah auto-zoom di iPhone/iOS */
              padding: 0.85rem 1rem; /* Target sentuh lebih tinggi */
              border-radius: 12px;
          }
          .card-auth .btn-primary {
              padding: 0.9rem; /* Tombol lebih besar untuk jempol */
              font-size: 1.1rem;
              border-radius: 12px;
          }
          .card-auth .form-label {
              font-size: 0.95rem;
              margin-bottom: 0.4rem;
          }
          .text-center.mt-3 a {
              padding: 10px 20px;
              display: block;
              margin-bottom: 10px;
          }
      }
    </style>
  </head>
  <body class="d-flex flex-column">
    <!-- Top Safety Hazard Bar -->
    <div class="hazard-stripe-top"></div>

    <!-- Rotating Industrial Background Gears -->
    <svg class="bg-gear bg-gear-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97c0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1c0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66Z"/>
    </svg>

    <svg class="bg-gear bg-gear-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97c0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1c0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66Z"/>
    </svg>

    <svg class="bg-gear bg-gear-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
      <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5a3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97c0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1c0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66Z"/>
    </svg>

    <div class="page page-center" style="position: relative; z-index: 1;">
      <div class="container container-tight py-4">
        <div class="text-center mb-3">
          <div class="heavy-equipment-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            Heavy Equipment Maintenance System
          </div>
          <br>
          <a href="." class="navbar-brand navbar-brand-autodark">
            @php
                $appLogo = \App\Models\AppSetting::where('key', 'app_logo')->first()?->value;
                $appName = \App\Models\AppSetting::where('key', 'app_name')->first()?->value ?? 'CMMS Aisfar';
            @endphp
            @if($appLogo)
                <img src="{{ asset('storage/logos/' . $appLogo) }}" alt="{{ $appName }}" height="54">
            @else
                <h1 class="fw-bold text-warning mb-0" style="letter-spacing: -0.5px;">{{ $appName }}</h1>
            @endif
          </a>
        </div>
        @yield('content')
      </div>
    </div>
    <!-- Top Progress Bar -->
    <div id="industrialTopProgress" style="position: fixed; top: 0; left: 0; width: 0%; height: 3.5px; background: linear-gradient(90deg, #f59e0b, #ea580c, #f59e0b); background-size: 200% 100%; z-index: 100000; box-shadow: 0 0 12px rgba(245, 158, 11, 0.9); transition: width 0.25s ease-out, opacity 0.3s ease; opacity: 0; pointer-events: none;"></div>

    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js" defer></script>
    <script>
      document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form) return;
        const bar = document.getElementById('industrialTopProgress');
        if (bar) {
          bar.style.opacity = '1';
          bar.style.width = '70%';
        }
        const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('input[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';
          form.submit();
        }
      });
    </script>
  </body>
</html>
