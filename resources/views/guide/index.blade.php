<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panduan lengkap CMMS AISFAR - Sistem Manajemen Pemeliharaan Aset Pertambangan. Pelajari Work Order, PM, KPI, HSE, ToolRoom, Sparepart Inventory, Fleet Management, dan lebih banyak lagi.">
    <title>Pusat Dokumentasi & Panduan — CMMS AISFAR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --accent: #06b6d4;
            --accent2: #8b5cf6;
            --accent3: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --orange: #f97316;
            --indigo: #6366f1;
            --teal: #14b8a6;
            --rose: #f43f5e;
            --surface: #ffffff;
            --surface2: #f8fafc;
            --surface3: #f1f5f9;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 6px;
            --shadow: 0 4px 24px rgba(0,0,0,0.07);
            --shadow-lg: 0 20px 60px rgba(0,0,0,0.12);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--surface2); color:var(--text-primary); line-height:1.6; overflow-x:hidden; }

        /* ===== PROGRESS BAR ===== */
        .progress-bar { position:fixed; top:64px; left:0; height:3px; background:linear-gradient(90deg,var(--primary),var(--accent2),var(--orange)); z-index:999; width:0%; transition:width 0.1s; }

        /* ===== TOP NAV ===== */
        .top-nav { position:fixed; top:0; left:0; right:0; z-index:1000; background:rgba(255,255,255,0.92); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border-bottom:1px solid rgba(226,232,240,0.8); padding:0 2rem; height:64px; display:flex; align-items:center; justify-content:space-between; transition:box-shadow 0.3s; }
        .top-nav.scrolled { box-shadow:0 4px 20px rgba(0,0,0,0.08); }
        .nav-brand { display:flex; align-items:center; gap:12px; text-decoration:none; }
        .nav-logo { width:36px; height:36px; background:linear-gradient(135deg,var(--primary),var(--accent2)); border-radius:10px; display:flex; align-items:center; justify-content:center; }
        .nav-logo svg { color:#fff; }
        .nav-brand-name { font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.05rem; color:var(--text-primary); letter-spacing:-.3px; }
        .nav-brand-name span { color:var(--primary); }
        .nav-actions { display:flex; align-items:center; gap:10px; }
        .btn-nav { display:inline-flex; align-items:center; gap:7px; padding:8px 18px; border-radius:100px; font-size:.84rem; font-weight:600; text-decoration:none; transition:all .2s ease; border:none; cursor:pointer; }
        .btn-nav-outline { background:transparent; border:1.5px solid var(--border); color:var(--text-secondary); }
        .btn-nav-outline:hover { background:var(--surface3); color:var(--text-primary); }
        .btn-nav-primary { background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:#fff; box-shadow:0 4px 12px rgba(37,99,235,.28); }
        .btn-nav-primary:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(37,99,235,.38); }

        /* ===== HERO ===== */
        .hero { margin-top:64px; background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 50%,#0f172a 100%); padding:100px 2rem 80px; position:relative; overflow:hidden; }
        .hero::before { content:''; position:absolute; top:-50%; left:-20%; width:600px; height:600px; background:radial-gradient(circle,rgba(37,99,235,.15) 0%,transparent 70%); pointer-events:none; }
        .hero::after { content:''; position:absolute; bottom:-30%; right:-10%; width:500px; height:500px; background:radial-gradient(circle,rgba(139,92,246,.12) 0%,transparent 70%); pointer-events:none; }
        .hero-grid { position:absolute; inset:0; background-image:linear-gradient(rgba(37,99,235,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(37,99,235,.06) 1px,transparent 1px); background-size:40px 40px; }
        .hero-inner { max-width:860px; margin:0 auto; text-align:center; position:relative; z-index:1; }
        .hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(37,99,235,.15); border:1px solid rgba(37,99,235,.3); color:#93c5fd; padding:6px 16px; border-radius:100px; font-size:.76rem; font-weight:600; letter-spacing:.5px; text-transform:uppercase; margin-bottom:26px; }
        .hero-badge-dot { width:6px; height:6px; background:#60a5fa; border-radius:50%; animation:pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:.6; transform:scale(.8); } }
        .hero h1 { font-family:'Plus Jakarta Sans',sans-serif; font-size:clamp(2rem,5vw,3.5rem); font-weight:900; color:#fff; line-height:1.1; letter-spacing:-1px; margin-bottom:18px; }
        .hero h1 .g-text { background:linear-gradient(135deg,#60a5fa,#a78bfa,#34d399); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .hero-sub { font-size:clamp(.95rem,2vw,1.15rem); color:#94a3b8; margin-bottom:38px; max-width:640px; margin-left:auto; margin-right:auto; line-height:1.7; }
        .hero-ctas { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }
        .btn-hero { display:inline-flex; align-items:center; gap:9px; padding:13px 26px; border-radius:100px; font-size:.9rem; font-weight:700; text-decoration:none; transition:all .3s ease; border:none; cursor:pointer; }
        .btn-hero-primary { background:linear-gradient(135deg,var(--primary),var(--accent2)); color:#fff; box-shadow:0 8px 24px rgba(37,99,235,.35); }
        .btn-hero-primary:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(37,99,235,.45); }
        .btn-hero-outline { background:rgba(255,255,255,.08); color:#fff; border:1.5px solid rgba(255,255,255,.22); backdrop-filter:blur(10px); }
        .btn-hero-outline:hover { background:rgba(255,255,255,.15); border-color:rgba(255,255,255,.4); }
        .hero-stats { display:flex; justify-content:center; gap:40px; flex-wrap:wrap; margin-top:56px; border-top:1px solid rgba(255,255,255,.07); padding-top:40px; }
        .hero-stat-num { font-family:'Plus Jakarta Sans',sans-serif; font-size:2rem; font-weight:900; background:linear-gradient(135deg,#60a5fa,#a78bfa); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; line-height:1; margin-bottom:4px; }
        .hero-stat-label { font-size:.74rem; color:#64748b; text-transform:uppercase; letter-spacing:.5px; font-weight:500; }
        .hero-divider { width:1px; height:48px; background:rgba(255,255,255,.1); align-self:center; }

        /* ===== CONTAINER ===== */
        .container { max-width:1280px; margin:0 auto; padding:0 1.5rem; }

        /* ===== SECTION HEADER ===== */
        .sec-header { text-align:center; margin-bottom:52px; }
        .sec-eyebrow { display:inline-flex; align-items:center; gap:7px; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--primary); background:var(--primary-light); padding:5px 14px; border-radius:100px; margin-bottom:14px; }
        .sec-title { font-family:'Plus Jakarta Sans',sans-serif; font-size:clamp(1.6rem,3vw,2.3rem); font-weight:800; color:var(--text-primary); letter-spacing:-.5px; line-height:1.2; margin-bottom:10px; }
        .sec-desc { color:var(--text-secondary); font-size:.95rem; max-width:560px; margin:0 auto; line-height:1.7; }

        /* ===== MODULES OVERVIEW ===== */
        .modules-section { padding:80px 0; background:var(--surface); }
        .modules-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(270px,1fr)); gap:22px; }
        .mod-card { background:var(--surface); border:1.5px solid var(--border); border-radius:var(--radius); padding:26px; cursor:pointer; transition:transform .3s cubic-bezier(.4,0,.2,1),box-shadow .3s,border-color .3s; position:relative; overflow:hidden; text-decoration:none; display:block; color:inherit; }
        .mod-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--cc,var(--primary)); transform:scaleX(0); transition:transform .3s ease; transform-origin:left; }
        .mod-card:hover { transform:translateY(-6px); box-shadow:0 18px 40px rgba(0,0,0,0.09); border-color:var(--cc,var(--primary)); }
        .mod-card:hover::before { transform:scaleX(1); }
        .mod-icon { width:52px; height:52px; border-radius:13px; display:flex; align-items:center; justify-content:center; margin-bottom:16px; transition:transform .3s; }
        .mod-card:hover .mod-icon { transform:scale(1.08); }
        .mod-eyebrow { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:7px; opacity:.85; }
        .mod-card h3 { font-family:'Plus Jakarta Sans',sans-serif; font-size:1.02rem; font-weight:700; color:var(--text-primary); margin-bottom:7px; line-height:1.3; }
        .mod-card p { font-size:.84rem; color:var(--text-secondary); line-height:1.6; }
        .mod-arrow { position:absolute; bottom:18px; right:18px; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:all .3s; opacity:0; transform:translateX(-6px); }
        .mod-card:hover .mod-arrow { opacity:1; transform:translateX(0); }
        .mod-pill-badge { position:absolute; top:18px; right:18px; display:inline-flex; align-items:center; gap:5px; font-size:.66rem; font-weight:700; letter-spacing:.3px; padding:3px 9px; border-radius:100px; background:var(--surface3); color:var(--text-muted); border:1px solid var(--border); transition:all .2s; }
        .mod-card:hover .mod-pill-badge { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:var(--primary); border-color:#bfdbfe; }

        /* ===== MODERN POPUP MODAL ===== */
        .m-modal-overlay { position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.72); backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px); display:flex; align-items:center; justify-content:center; padding:1.5rem; opacity:0; pointer-events:none; transition:opacity .3s cubic-bezier(0.16,1,0.3,1); }
        .m-modal-overlay.active { opacity:1; pointer-events:auto; }
        .m-modal-container { background:var(--surface); border:1.5px solid rgba(255,255,255,0.6); box-shadow:0 25px 70px rgba(0,0,0,0.35),0 0 0 1px rgba(226,232,240,0.6); border-radius:22px; width:100%; max-width:920px; max-height:88vh; display:flex; flex-direction:column; overflow:hidden; transform:scale(0.92) translateY(24px); transition:transform .35s cubic-bezier(0.16,1,0.3,1); }
        .m-modal-overlay.active .m-modal-container { transform:scale(1) translateY(0); }
        .m-modal-header { padding:18px 26px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; background:var(--surface); position:relative; z-index:2; gap:14px; }
        .m-modal-brand { display:flex; align-items:center; gap:14px; }
        .m-modal-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .m-modal-eyebrow { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:2px; }
        .m-modal-title { font-family:'Plus Jakarta Sans',sans-serif; font-size:1.25rem; font-weight:800; color:var(--text-primary); line-height:1.2; }
        .m-modal-ctrls { display:flex; align-items:center; gap:8px; }
        .m-nav-btn { width:34px; height:34px; border-radius:10px; border:1.5px solid var(--border); background:var(--surface3); color:var(--text-secondary); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s; }
        .m-nav-btn:hover { background:var(--primary-light); color:var(--primary); border-color:var(--primary); }
        .m-index-pill { font-size:.75rem; font-weight:700; color:var(--text-muted); background:var(--surface2); padding:4px 10px; border-radius:100px; border:1px solid var(--border); }
        .m-close-btn { width:36px; height:36px; border-radius:10px; border:1.5px solid var(--border); background:var(--surface3); color:var(--text-secondary); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s; margin-left:4px; }
        .m-close-btn:hover { background:#fee2e2; color:#ef4444; border-color:#fca5a5; transform:rotate(90deg); }
        .m-modal-body { overflow-y:auto; padding:26px 28px; flex:1; background:var(--surface2); }
        .m-modal-body::-webkit-scrollbar { width:7px; }
        .m-modal-body::-webkit-scrollbar-track { background:var(--surface2); }
        .m-modal-body::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:10px; }
        .m-modal-body::-webkit-scrollbar-thumb:hover { background:#94a3b8; }
        .m-modal-footer { padding:14px 28px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; background:var(--surface); flex-wrap:wrap; gap:10px; }
        .btn-m-outline { display:inline-flex; align-items:center; gap:7px; padding:8px 16px; border-radius:100px; font-size:.82rem; font-weight:600; border:1.5px solid var(--border); background:transparent; color:var(--text-secondary); cursor:pointer; transition:all .2s; }
        .btn-m-outline:hover { background:var(--surface3); color:var(--text-primary); border-color:#cbd5e1; }
        .m-footer-right { display:flex; align-items:center; gap:10px; }
        .btn-m-close { padding:8px 18px; border-radius:100px; font-size:.82rem; font-weight:600; border:1.5px solid var(--border); background:var(--surface3); color:var(--text-secondary); cursor:pointer; transition:all .2s; }
        .btn-m-close:hover { background:var(--border); color:var(--text-primary); }
        .btn-m-next { display:inline-flex; align-items:center; gap:6px; padding:8px 20px; border-radius:100px; font-size:.82rem; font-weight:700; border:none; background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:#fff; cursor:pointer; box-shadow:0 4px 12px rgba(37,99,235,.25); transition:all .2s; }
        .btn-m-next:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(37,99,235,.35); }

        /* ===== GUIDE SECTION ===== */
        .guide-section-wrap { padding:80px 0; }
        .guide-layout { display:grid; grid-template-columns:280px 1fr; gap:36px; align-items:start; }

        /* SIDEBAR */
        .g-sidebar { position:sticky; top:80px; }
        .sidebar-box { background:var(--surface); border:1.5px solid var(--border); border-radius:var(--radius); padding:18px; box-shadow:var(--shadow); max-height:calc(100vh - 100px); overflow-y:auto; }
        .sb-group-title { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--text-muted); padding:0 8px; margin-bottom:5px; margin-top:14px; }
        .sb-group-title:first-child { margin-top:0; }
        .sb-link { display:flex; align-items:center; gap:9px; padding:8px 10px; border-radius:9px; font-size:.84rem; font-weight:500; color:var(--text-secondary); transition:all .2s; cursor:pointer; border:none; background:none; width:100%; text-align:left; }
        .sb-link:hover { background:var(--surface3); color:var(--text-primary); }
        .sb-link.active { background:linear-gradient(135deg,#eff6ff,#dbeafe); color:var(--primary); font-weight:600; }
        .sb-icon { width:26px; height:26px; border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }

        /* CONTENT AREA */
        .content-area { min-height:500px; }
        .g-section { display:none; animation:fsi .4s ease; }
        .g-section.active { display:block; }
        @keyframes fsi { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

        /* SECTION CARD */
        .s-card { background:var(--surface); border:1.5px solid var(--border); border-radius:var(--radius); overflow:hidden; margin-bottom:24px; box-shadow:var(--shadow); }
        .s-card-head { padding:28px 32px; border-bottom:1px solid var(--border); position:relative; }
        .s-card-head::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:var(--sc,var(--primary)); }
        .s-card-body { padding:28px 32px; }
        .s-title-row { display:flex; align-items:center; gap:12px; margin-bottom:10px; }
        .s-icon-wrap { width:46px; height:46px; border-radius:11px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .s-eyebrow { font-size:.66rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:3px; }
        .s-h2 { font-family:'Plus Jakarta Sans',sans-serif; font-size:1.45rem; font-weight:800; color:var(--text-primary); letter-spacing:-.3px; line-height:1.2; }
        .s-lead { color:var(--text-secondary); font-size:.92rem; line-height:1.7; max-width:760px; }

        /* SUBSECTION CARD */
        .sub-block { background:var(--surface2); border:1.5px solid var(--border); border-radius:var(--radius-sm); padding:22px; margin-bottom:20px; }
        .sub-block-title { font-family:'Plus Jakarta Sans',sans-serif; font-size:1rem; font-weight:700; color:var(--text-primary); margin-bottom:12px; display:flex; align-items:center; gap:8px; }

        /* INFO GRID */
        .info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:14px; margin:20px 0; }
        .i-card { border-radius:var(--radius-sm); padding:18px; border:1.5px solid transparent; }
        .i-icon { width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; margin-bottom:10px; }
        .i-card h4 { font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; font-weight:700; color:var(--text-primary); margin-bottom:5px; }
        .i-card p { font-size:.8rem; color:var(--text-secondary); line-height:1.55; }

        /* STEP FLOW */
        .step-flow { display:flex; flex-direction:column; gap:0; margin:22px 0; }
        .step-item { display:flex; gap:18px; position:relative; }
        .step-item:not(:last-child)::after { content:''; position:absolute; left:19px; top:42px; bottom:0; width:2px; background:linear-gradient(to bottom,var(--primary-light),transparent); }
        .step-num { width:38px; height:38px; background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.84rem; flex-shrink:0; position:relative; z-index:1; box-shadow:0 4px 12px rgba(37,99,235,.28); }
        .step-body { padding-bottom:24px; flex:1; }
        .step-body h4 { font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.95rem; color:var(--text-primary); margin-bottom:5px; margin-top:7px; }
        .step-body p, .step-body ul { font-size:.84rem; color:var(--text-secondary); line-height:1.7; }
        .step-body ul { padding-left:18px; }
        .step-body ul li { margin-bottom:3px; }

        /* ROLE GRID */
        .role-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:14px; margin-top:14px; }
        .role-card { border-radius:var(--radius-sm); padding:18px; text-align:center; border:1.5px solid var(--border); transition:transform .2s,box-shadow .2s; }
        .role-card:hover { transform:translateY(-4px); box-shadow:var(--shadow); }
        .role-emoji { font-size:1.9rem; margin-bottom:8px; display:block; }
        .role-title { font-weight:700; font-size:.86rem; color:var(--text-primary); margin-bottom:5px; }
        .role-desc { font-size:.74rem; color:var(--text-secondary); line-height:1.5; }

        /* FEATURE LIST */
        .f-list { list-style:none; display:flex; flex-direction:column; gap:9px; }
        .f-list li { display:flex; gap:10px; align-items:flex-start; }
        .f-check { width:18px; height:18px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
        .f-list li span { font-size:.84rem; color:var(--text-secondary); line-height:1.5; }
        .f-list li strong { color:var(--text-primary); }

        /* HIGHLIGHT BOX */
        .h-box { border-radius:var(--radius-sm); padding:18px 22px; border:1px solid transparent; margin:18px 0; }
        .h-box-title { font-weight:700; font-size:.86rem; margin-bottom:5px; display:flex; align-items:center; gap:7px; }
        .h-box p { font-size:.84rem; line-height:1.6; }

        /* TECH GRID */
        .tech-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:10px; margin-bottom:24px; }
        .tech-badge { display:flex; align-items:center; gap:10px; padding:11px 14px; background:var(--surface3); border-radius:var(--radius-sm); border:1px solid var(--border); }
        .tech-b-icon { width:34px; height:34px; border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .tech-b-info strong { display:block; font-size:.78rem; font-weight:700; color:var(--text-primary); }
        .tech-b-info span { font-size:.7rem; color:var(--text-muted); }

        /* 3-STEP ROW */
        .step-row { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin:22px 0; }
        .step-box { text-align:center; padding:22px 14px; background:var(--surface3); border-radius:var(--radius-sm); border:1.5px solid var(--border); }
        .step-box-icon { width:50px; height:50px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 12px; }
        .step-box strong { font-size:.86rem; }
        .step-box p { font-size:.75rem; color:var(--text-secondary); margin-top:5px; }

        /* 2-COL */
        .two-col { display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:18px; }
        .three-col { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:18px; }
        .four-col { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:18px; }

        /* METRIC PILL CARD */
        .metric-pill { background:var(--surface); border:1.5px solid var(--border); border-radius:var(--radius-sm); padding:16px; display:flex; align-items:center; gap:14px; }
        .metric-pill-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .metric-pill-val { font-family:'Plus Jakarta Sans',sans-serif; font-size:1.15rem; font-weight:800; color:var(--text-primary); }
        .metric-pill-lbl { font-size:.73rem; color:var(--text-muted); text-transform:uppercase; font-weight:600; letter-spacing:.5px; }

        /* HSE 3-COL */
        .hse-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
        .hse-card { border-radius:var(--radius-sm); padding:18px; text-align:center; }
        .hse-card .hse-emoji { font-size:2rem; margin-bottom:8px; }
        .hse-card strong { display:block; font-size:.86rem; font-weight:700; margin-bottom:6px; }
        .hse-card p { font-size:.75rem; color:var(--text-secondary); }

        /* CTA */
        .cta-section { background:linear-gradient(135deg,#0f172a,#1e3a5f); padding:80px 2rem; text-align:center; position:relative; overflow:hidden; }
        .cta-section::before { content:''; position:absolute; inset:0; background-image:linear-gradient(rgba(37,99,235,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(37,99,235,.06) 1px,transparent 1px); background-size:40px 40px; }
        .cta-inner { position:relative; z-index:1; }
        .cta-section h2 { font-family:'Plus Jakarta Sans',sans-serif; font-size:clamp(1.7rem,3vw,2.6rem); font-weight:900; color:#fff; margin-bottom:14px; letter-spacing:-.5px; }
        .cta-section p { color:#94a3b8; font-size:.95rem; margin-bottom:36px; max-width:480px; margin-left:auto; margin-right:auto; line-height:1.7; }
        .cta-btns { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }
        .btn-cta { display:inline-flex; align-items:center; gap:9px; padding:13px 26px; border-radius:100px; font-size:.9rem; font-weight:700; text-decoration:none; transition:all .3s; border:none; cursor:pointer; }
        .btn-cta-primary { background:linear-gradient(135deg,var(--primary),var(--accent2)); color:#fff; box-shadow:0 8px 24px rgba(37,99,235,.38); }
        .btn-cta-primary:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(37,99,235,.48); }
        .btn-cta-outline { background:rgba(255,255,255,.08); color:#fff; border:1.5px solid rgba(255,255,255,.22); backdrop-filter:blur(10px); }
        .btn-cta-outline:hover { background:rgba(255,255,255,.15); border-color:rgba(255,255,255,.4); }

        /* FOOTER */
        .g-footer { background:var(--surface); border-top:1px solid var(--border); padding:24px 2rem; text-align:center; }
        .g-footer p { font-size:.8rem; color:var(--text-muted); }
        .g-footer a { color:var(--primary); text-decoration:none; font-weight:600; }
        .g-footer a:hover { text-decoration:underline; }

        /* COLORS */
        .c-blue { color:var(--primary); } .c-cyan { color:var(--accent); } .c-purple { color:var(--accent2); }
        .c-amber { color:var(--accent3); } .c-green { color:var(--success); } .c-red { color:var(--danger); }
        .c-orange { color:var(--orange); } .c-indigo { color:var(--indigo); } .c-teal { color:var(--teal); } .c-rose { color:var(--rose); }
        .c-sky { color:#0284c7; }
        .bg-blue-s { background:#eff6ff; border-color:#bfdbfe !important; }
        .bg-cyan-s { background:#ecfeff; border-color:#a5f3fc !important; }
        .bg-purple-s { background:#f5f3ff; border-color:#ddd6fe !important; }
        .bg-amber-s { background:#fffbeb; border-color:#fde68a !important; }
        .bg-green-s { background:#ecfdf5; border-color:#a7f3d0 !important; }
        .bg-red-s { background:#fef2f2; border-color:#fecaca !important; }
        .bg-orange-s { background:#fff7ed; border-color:#fed7aa !important; }
        .bg-indigo-s { background:#eef2ff; border-color:#c7d2fe !important; }
        .bg-teal-s { background:#f0fdfa; border-color:#99f6e4 !important; }
        .bg-sky-s { background:#f0f9ff; border-color:#bae6fd !important; }

        /* FMS INFOGRAPHIC STYLES */
        .fms-diagram-box { background:linear-gradient(135deg,#0b192c,#1e293b); border:1.5px solid #334155; border-radius:14px; padding:28px 24px; color:#f8fafc; margin:22px 0; position:relative; overflow:hidden; }
        .fms-diagram-box::before { content:''; position:absolute; inset:0; background-image:radial-gradient(rgba(14,165,233,.12) 1px,transparent 1px); background-size:24px 24px; }
        .fms-diagram-inner { position:relative; z-index:1; }
        .fms-flow-track { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; position:relative; margin-top:20px; }
        .fms-step-card { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.12); border-radius:10px; padding:16px 14px; backdrop-filter:blur(6px); transition:all .25s ease; position:relative; }
        .fms-step-card:hover { transform:translateY(-3px); border-color:#38bdf8; background:rgba(255,255,255,.08); box-shadow:0 8px 24px rgba(14,165,233,.2); }
        .fms-step-badge { width:24px; height:24px; border-radius:50%; background:linear-gradient(135deg,#0284c7,#38bdf8); color:#fff; font-size:.72rem; font-weight:800; display:inline-flex; align-items:center; justify-content:center; margin-bottom:8px; }
        .fms-step-title { font-family:'Plus Jakarta Sans',sans-serif; font-size:.85rem; font-weight:700; color:#e2e8f0; margin-bottom:6px; }
        .fms-step-desc { font-size:.74rem; color:#94a3b8; line-height:1.5; }
        .fms-badge-tag { display:inline-block; font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; padding:2px 7px; border-radius:4px; margin-top:8px; }
        @media (max-width:1024px) {
            .guide-layout { grid-template-columns:1fr; }
            .g-sidebar { position:static; }
            .sidebar-box { display:flex; flex-wrap:wrap; gap:6px; padding:12px; max-height:none; }
            .sb-group-title { display:none; }
            .sb-link { padding:6px 12px; font-size:.78rem; flex:0 0 auto; }
            .sb-icon { display:none; }
        }
        @media (max-width:768px) {
            .hero { padding:70px 1.5rem 56px; }
            .hero-stats { gap:20px; }
            .hero-divider { display:none; }
            .modules-section, .guide-section-wrap { padding:56px 0; }
            .s-card-head, .s-card-body { padding:20px; }
            .two-col, .three-col, .four-col, .step-row, .hse-grid { grid-template-columns:1fr; }
            .m-modal-header { padding:14px 18px; }
            .m-modal-body { padding:18px; }
            .m-modal-footer { padding:12px 18px; flex-direction:column; align-items:stretch; }
            .m-footer-right { justify-content:space-between; }
        }
        @media (max-width:480px) {
            .hero h1 { font-size:1.8rem; }
            .nav-actions .btn-nav-outline { display:none; }
        }
    </style>
</head>
<body>

<div class="progress-bar" id="progressBar"></div>

<!-- TOP NAV -->
<nav class="top-nav" id="topNav">
    <a href="{{ route('login') }}" class="nav-brand">
        <div class="nav-logo">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3"/>
                <path d="M9 12l2 2l4 -4"/>
            </svg>
        </div>
        <div class="nav-brand-name">CMMS <span>AISFAR</span></div>
    </a>
    <div class="nav-actions">
        <a href="{{ route('guide') }}" class="btn-nav btn-nav-outline">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
            Panduan
        </a>
        @auth
        <a href="{{ route('dashboard') }}" class="btn-nav btn-nav-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        @else
        <a href="{{ route('login') }}" class="btn-nav btn-nav-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10,17 15,12 10,7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            Login
        </a>
        @endauth
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-grid"></div>
    <div class="hero-inner">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            Platform CMMS Enterprise — Mining & Heavy Equipment
        </div>
        <h1>Panduan Lengkap<br><span class="g-text">CMMS AISFAR</span></h1>
        <p class="hero-sub">Sistem Manajemen Pemeliharaan Aset & Logistik Gudang komprehensif untuk industri pertambangan. Dari Work Order, ToolRoom & Sparepart Inventory, hingga Analitik KPI.</p>
        <div class="hero-ctas">
            <a href="#modules-overview" class="btn-hero btn-hero-primary" onclick="document.getElementById('modules-overview').scrollIntoView({behavior:'smooth'}); return false;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                Jelajahi Fitur
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn-hero btn-hero-outline">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Daftar Akun
            </a>
            @endguest
        </div>
        <div class="hero-stats">
            <div><div class="hero-stat-num">12+</div><div class="hero-stat-label">Modul Terintegrasi</div></div>
            <div class="hero-divider"></div>
            <div><div class="hero-stat-num">ISO</div><div class="hero-stat-label">8601 Compliance</div></div>
            <div class="hero-divider"></div>
            <div><div class="hero-stat-num">100%</div><div class="hero-stat-label">Paperless Digital</div></div>
            <div class="hero-divider"></div>
            <div><div class="hero-stat-num">360°</div><div class="hero-stat-label">Asset & Part Tracking</div></div>
        </div>
    </div>
</section>

<!-- MODULES OVERVIEW -->
<section class="modules-section" id="modules-overview">
    <div class="container">
        <div class="sec-header">
            <div class="sec-eyebrow">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12,2 15,9 22,9 16,14 18,21 12,17 6,21 8,14 2,9 9,9"/></svg>
                Semua Fitur Platform
            </div>
            <h2 class="sec-title">Ekosistem Modul CMMS Lengkap</h2>
            <p class="sec-desc">Klik modul di bawah untuk membuka ringkasan interaktif instan atau jelajahi dokumentasi lengkap</p>
        </div>
        <div class="modules-grid">

            <div class="mod-card" style="--cc:#2563eb;" onclick="openModuleModal('work-order')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#eff6ff;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg></div>
                <div class="mod-eyebrow c-blue">Maintenance Core</div>
                <h3>Work Order Management</h3>
                <p>Single Source of Truth untuk seluruh perbaikan unit. Terintegrasi dengan Part, HSE, dan Digital Signature.</p>
                <div class="mod-arrow" style="background:#eff6ff;color:#2563eb;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#f97316;" onclick="openModuleModal('toolroom')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#fff7ed;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                <div class="mod-eyebrow c-orange">Inventory & Logistik</div>
                <h3>ToolRoom & Stok Part Inventory</h3>
                <p>Peminjaman SST, kalibrasi alat, Bin Location rak gudang, auto deduct WO, dan kanibalisasi part resmi.</p>
                <div class="mod-arrow" style="background:#fff7ed;color:#f97316;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#06b6d4;" onclick="openModuleModal('production')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#ecfeff;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3"/><rect x="9" y="11" width="14" height="10" rx="2"/><circle cx="12" cy="21" r="1"/><circle cx="20" cy="21" r="1"/></svg></div>
                <div class="mod-eyebrow c-cyan">Fleet & Production</div>
                <h3>Laporan Produksi Harian</h3>
                <p>Pencatatan Digger & Hauler per shift dengan ritasi per jam dan kalkulasi tonase otomatis.</p>
                <div class="mod-arrow" style="background:#ecfeff;color:#06b6d4;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#8b5cf6;" onclick="openModuleModal('master-units')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#f5f3ff;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><line x1="2" y1="12" x2="22" y2="12"/></svg></div>
                <div class="mod-eyebrow c-purple">Asset Intelligence</div>
                <h3>Populasi Unit 360° History</h3>
                <p>Rekam jejak lengkap setiap unit: WO, PM, FAR, JWO, hingga Life Cycle Cost secara real-time.</p>
                <div class="mod-arrow" style="background:#f5f3ff;color:#8b5cf6;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#f59e0b;" onclick="openModuleModal('kpi-iso')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#fffbeb;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><path d="M3 20h18"/></svg></div>
                <div class="mod-eyebrow c-amber">Analytics & KPI</div>
                <h3>KPI Analysis & ISO 8601</h3>
                <p>Filter ISO Week, MTTR/MTBF, Physical Availability, dan export Excel profesional.</p>
                <div class="mod-arrow" style="background:#fffbeb;color:#f59e0b;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#10b981;" onclick="openModuleModal('hse')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#ecfdf5;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9,12 11,14 15,10"/></svg></div>
                <div class="mod-eyebrow c-green">Safety & HSE</div>
                <h3>Keselamatan K3 (JSA, PTW, LOTO)</h3>
                <p>Tiga pilar keselamatan kerja terintegrasi dalam setiap WO untuk compliance pertambangan.</p>
                <div class="mod-arrow" style="background:#ecfdf5;color:#10b981;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#ef4444;" onclick="openModuleModal('far')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#fef2f2;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                <div class="mod-eyebrow c-red">Failure Analysis</div>
                <h3>Failure Analysis Report (FAR)</h3>
                <p>Investigasi RCA 4 pilar, auto-fetch Part Number, dan digital signature PDF resmi.</p>
                <div class="mod-arrow" style="background:#fef2f2;color:#ef4444;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#06b6d4;" onclick="openModuleModal('jwo')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#ecfeff;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/><line x1="20" y1="8" x2="20" y2="14"/></svg></div>
                <div class="mod-eyebrow c-cyan">Vendor Management</div>
                <h3>Job Work Order & Vendor</h3>
                <p>Kelola perbaikan outsource dengan SPK, tracking komponen, estimasi vs actual cost.</p>
                <div class="mod-arrow" style="background:#ecfeff;color:#06b6d4;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#f59e0b;" onclick="openModuleModal('budget')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#fffbeb;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                <div class="mod-eyebrow c-amber">Cost Management</div>
                <h3>Budget & Cost Control</h3>
                <p>Plan Budget bulanan dengan BoQ, tracking Actual vs Planned, dan evaluasi PA target unit.</p>
                <div class="mod-arrow" style="background:#fffbeb;color:#f59e0b;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#6366f1;" onclick="openModuleModal('pm')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#eef2ff;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div>
                <div class="mod-eyebrow c-indigo">Preventive Maintenance</div>
                <h3>PM Template & Schedule</h3>
                <p>Jadwal servis berkala PS1–PS4 dengan auto-generate WO 1-klik dan HM Due warning.</p>
                <div class="mod-arrow" style="background:#eef2ff;color:#6366f1;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#6366f1;" onclick="openModuleModal('collaboration')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#eef2ff;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
                <div class="mod-eyebrow c-indigo">Collaboration</div>
                <h3>Live Chat & Real-time</h3>
                <p>Floating chat widget, share link dokumen 1-klik, status online/offline real-time.</p>
                <div class="mod-arrow" style="background:#eef2ff;color:#6366f1;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#0284c7;" onclick="openModuleModal('fuel-management')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#f0f9ff;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2"><path d="M3 2v20h6v-8h2v8h4V8l-4-4H3z"/><path d="M15 16h2a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"/><circle cx="6" cy="7" r="1"/></svg></div>
                <div class="mod-eyebrow c-sky">Fuel & Energy</div>
                <h3>Fuel Management System (FMS)</h3>
                <p>Siklus penuh BBM: Inbound & Sonding, Mutasi Tangki, Refill Fuel Truck, Distribusi Unit per Shift & BA Flowmeter.</p>
                <div class="mod-arrow" style="background:#f0f9ff;color:#0284c7;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

            <div class="mod-card" style="--cc:#10b981;" onclick="openModuleModal('architecture')">
                <div class="mod-pill-badge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Quick View</div>
                <div class="mod-icon" style="background:#ecfdf5;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><rect x="2" y="2" width="6" height="6" rx="1"/><rect x="16" y="2" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="16" y="16" width="6" height="6" rx="1"/></svg></div>
                <div class="mod-eyebrow c-green">Technology Stack</div>
                <h3>Arsitektur Enterprise</h3>
                <p>Laravel, RBAC, Anti-IDOR Hashids, Spatie Audit Log, DomPDF — keamanan berlevel enterprise.</p>
                <div class="mod-arrow" style="background:#ecfdf5;color:#10b981;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg></div>
            </div>

        </div>
    </div>
</section>

<!-- MODERN POPUP MODAL DIALOG -->
<div id="moduleModal" class="m-modal-overlay" onclick="closeModuleModalOnBackdrop(event)">
    <div class="m-modal-container" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="m-modal-header">
            <div class="m-modal-brand">
                <div id="modalIcon" class="m-modal-icon"></div>
                <div>
                    <div id="modalEyebrow" class="m-modal-eyebrow"></div>
                    <h3 id="modalTitle" class="m-modal-title"></h3>
                </div>
            </div>
            <div class="m-modal-ctrls">
                <button type="button" class="m-nav-btn" onclick="prevModalModule()" title="Modul Sebelumnya (←)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15,18 9,12 15,6"/></svg>
                </button>
                <span id="modalIndexIndicator" class="m-index-pill">1 / 12</span>
                <button type="button" class="m-nav-btn" onclick="nextModalModule()" title="Modul Selanjutnya (→)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9,18 15,12 9,6"/></svg>
                </button>
                <button type="button" class="m-close-btn" onclick="closeModuleModal()" title="Tutup Pop-up (ESC)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div id="modalBody" class="m-modal-body"></div>

        <!-- Footer -->
        <div class="m-modal-footer">
            <button type="button" class="btn-m-outline" onclick="jumpToFullDocFromModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9,15 12,18 15,15"/></svg>
                Buka Dokumentasi Lengkap di Bawah
            </button>
            <div class="m-footer-right">
                <button type="button" class="btn-m-close" onclick="closeModuleModal()">Tutup</button>
                <button type="button" class="btn-m-next" onclick="nextModalModule()">Modul Berikutnya →</button>
            </div>
        </div>
    </div>
</div>

<!-- DETAILED GUIDE -->
<section class="guide-section-wrap">
    <div class="container">
        <div class="guide-layout">

            <!-- SIDEBAR -->
            <aside class="g-sidebar">
                <div class="sidebar-box">
                    <div class="sb-group-title">Memulai</div>
                    <button class="sb-link active" onclick="activateSection('quick-start')">
                        <div class="sb-icon" style="background:#eff6ff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,8 12,12 14,14"/></svg></div>
                        Langkah Pertama
                    </button>

                    <div class="sb-group-title">Produksi & Asset</div>
                    <button class="sb-link" onclick="activateSection('production')">
                        <div class="sb-icon" style="background:#ecfeff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3"/><rect x="9" y="11" width="14" height="10" rx="2"/></svg></div>
                        Laporan Produksi
                    </button>
                    <button class="sb-link" onclick="activateSection('master-units')">
                        <div class="sb-icon" style="background:#f5f3ff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg></div>
                        Populasi Unit 360°
                    </button>

                    <div class="sb-group-title">Modul Maintenance & Gudang</div>
                    <button class="sb-link" onclick="activateSection('work-order')">
                        <div class="sb-icon" style="background:#eff6ff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg></div>
                        Work Order
                    </button>
                    <button class="sb-link" onclick="activateSection('toolroom')">
                        <div class="sb-icon" style="background:#fff7ed;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                        ToolRoom & Stok Part
                    </button>
                    <button class="sb-link" onclick="activateSection('kpi-iso')">
                        <div class="sb-icon" style="background:#fffbeb;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
                        KPI & ISO 8601
                    </button>
                    <button class="sb-link" onclick="activateSection('far')">
                        <div class="sb-icon" style="background:#fef2f2;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg></div>
                        FAR
                    </button>
                    <button class="sb-link" onclick="activateSection('jwo')">
                        <div class="sb-icon" style="background:#ecfeff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
                        JWO & Vendor
                    </button>
                    <button class="sb-link" onclick="activateSection('hse')">
                        <div class="sb-icon" style="background:#ecfdf5;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                        K3 / HSE
                    </button>
                    <button class="sb-link" onclick="activateSection('budget')">
                        <div class="sb-icon" style="background:#fffbeb;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                        Budget & Cost
                    </button>
                    <button class="sb-link" onclick="activateSection('pm')">
                        <div class="sb-icon" style="background:#eef2ff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div>
                        Preventive Maintenance
                    </button>

                    <div class="sb-group-title">Fuel & Energi</div>
                    <button class="sb-link" onclick="activateSection('fuel-management')">
                        <div class="sb-icon" style="background:#f0f9ff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5"><path d="M3 2v20h6v-8h2v8h4V8l-4-4H3z"/><path d="M15 16h2a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"/><circle cx="6" cy="7" r="1"/></svg></div>
                        Fuel Management (FMS)
                    </button>

                    <div class="sb-group-title">Fitur Tambahan</div>
                    <button class="sb-link" onclick="activateSection('collaboration')">
                        <div class="sb-icon" style="background:#eef2ff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
                        Live Chat
                    </button>
                    <button class="sb-link" onclick="activateSection('signatures')">
                        <div class="sb-icon" style="background:#fdf4ff;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></div>
                        Digital Signature
                    </button>
                    <button class="sb-link" onclick="activateSection('architecture')">
                        <div class="sb-icon" style="background:#ecfdf5;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                        Teknologi & Keamanan
                    </button>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="content-area">

                <!-- QUICK START -->
                <div id="quick-start" class="g-section active">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#2563eb;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#eff6ff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,8 12,12 14,14"/></svg></div>
                                <div><div class="s-eyebrow c-blue">Mulai Dari Sini</div><h2 class="s-h2">Langkah Pertama Menggunakan Sistem</h2></div>
                            </div>
                            <p class="s-lead">Selamat datang di <strong>CMMS AISFAR</strong>! Ikuti langkah-langkah ini untuk memulai menggunakan platform secara efisien sejak hari pertama.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="step-flow">
                                <div class="step-item">
                                    <div class="step-num">1</div>
                                    <div class="step-body">
                                        <h4>Lengkapi Profil Anda</h4>
                                        <p>Identitas jelas sangat penting untuk koordinasi tim di lapangan.</p>
                                        <ul>
                                            <li>Klik nama Anda di pojok kanan atas → <strong>Profil Saya</strong></li>
                                            <li>Unggah <strong>Foto Profil</strong> dengan wajah terlihat jelas</li>
                                            <li>Isi nomor <strong>WhatsApp</strong> agar mudah dihubungi saat WO darurat</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-num">2</div>
                                    <div class="step-body">
                                        <h4>Pahami Peran (Role) Anda</h4>
                                        <p>Tampilan menu sidebar menyesuaikan peran yang diberikan Admin.</p>
                                        <div class="role-grid">
                                            <div class="role-card"><span class="role-emoji">👷</span><div class="role-title">Mekanik / Operator</div><div class="role-desc">Work Order, JWO, Pinjam SST, Input Hour Meter</div></div>
                                            <div class="role-card"><span class="role-emoji">📦</span><div class="role-title">Toolman & Warehouse</div><div class="role-desc">ToolRoom, Stok Part, Issue Part WO, Stock Opname</div></div>
                                            <div class="role-card"><span class="role-emoji">🚜</span><div class="role-title">Engineer & Production</div><div class="role-desc">Laporan Produksi Harian (Digger & Hauler), Monitoring Unit</div></div>
                                            <div class="role-card"><span class="role-emoji">👨‍💼</span><div class="role-title">Supervisor / Admin</div><div class="role-desc">Approval, Master Data, Parts, Unit, Pengaturan Sistem</div></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-num">3</div>
                                    <div class="step-body">
                                        <h4>Pantau Notifikasi & Live Chat</h4>
                                        <p>Setiap penugasan WO atau approval dokumen menghasilkan notifikasi di ikon lonceng (kanan atas). Periksa <strong>Live Chat</strong> untuk koordinasi real-time dengan tim.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="h-box" style="background:#eff6ff;border-color:#bfdbfe;">
                                <div class="h-box-title c-blue"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>Tips</div>
                                <p style="color:#1e40af;">Jika menu yang Anda butuhkan tidak muncul, hubungi Administrator untuk mendapatkan hak akses sesuai tanggung jawab Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOOLROOM & INVENTORY (POWERFUL EXPANDED SECTION) -->
                <div id="toolroom" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#f97316;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#fff7ed;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg></div>
                                <div><div class="s-eyebrow c-orange">Inventory & Logistik Workshop</div><h2 class="s-h2">ToolRoom & Stok Part Inventory Management</h2></div>
                            </div>
                            <p class="s-lead">Pusat kendali logistik gudang pemeliharaan terlengkap: mengelola siklus sirkulasi Special Service Tools (SST), sertifikasi kalibrasi alat ukur, hierarki Bin Location multi-rak, pemotongan stok otomatis terikat WO, hingga tata kelola kanibalisasi suku cadang resmi.</p>
                        </div>
                        <div class="s-card-body">

                            <!-- KEY METRICS BANNER -->
                            <div class="four-col">
                                <div class="metric-pill">
                                    <div class="metric-pill-icon" style="background:#fff7ed;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div>
                                    <div><div class="metric-pill-val">IRA > 98%</div><div class="metric-pill-lbl">Inventory Accuracy</div></div>
                                </div>
                                <div class="metric-pill">
                                    <div class="metric-pill-icon" style="background:#eff6ff;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div>
                                    <div><div class="metric-pill-val">0 Delay</div><div class="metric-pill-lbl">Tool Check-out</div></div>
                                </div>
                                <div class="metric-pill">
                                    <div class="metric-pill-icon" style="background:#ecfdf5;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9,12 11,14 15,10"/></svg></div>
                                    <div><div class="metric-pill-val">ISO/IEC</div><div class="metric-pill-lbl">17025 Calibration</div></div>
                                </div>
                                <div class="metric-pill">
                                    <div class="metric-pill-icon" style="background:#fef2f2;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
                                    <div><div class="metric-pill-val">&lt; 1%</div><div class="metric-pill-lbl">Stockout Rate</div></div>
                                </div>
                            </div>

                            <!-- 1. TOOLROOM & SST LIFECYCLE -->
                            <div class="sub-block">
                                <div class="sub-block-title c-orange">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                    1. Modul ToolRoom & Sirkulasi Special Service Tools (SST)
                                </div>
                                <p style="font-size:.86rem;color:var(--text-secondary);margin-bottom:14px;">Alat berat tambang (Excavator 100T–400T, Dozer, Hauler) memerlukan ratusan special tools presisi tinggi berharga mahal (Hydraulic Puller, Torque Multiplier, Diagnostic Reader, Injector Tester). CMMS AISFAR mengontrol sirkulasi ini dari tangan ke tangan tanpa celah kehilangan.</p>
                                <div class="info-grid">
                                    <div class="i-card bg-orange-s">
                                        <div class="i-icon" style="background:#fed7aa50;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="7" y2="8.01"/><line x1="11" y1="8" x2="11" y2="8.01"/><line x1="15" y1="8" x2="15" y2="8.01"/><line x1="7" y1="12" x2="17" y2="12"/></svg></div>
                                        <h4>Barcode / QR Check-in & Check-out</h4>
                                        <p>Toolman memindai QR Code SST dan kartu NIK mekanik untuk peminjaman instan dalam hitungan detik terhubung ke nomor WO target.</p>
                                    </div>
                                    <div class="i-card bg-orange-s">
                                        <div class="i-icon" style="background:#fed7aa50;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div>
                                        <h4>Log Tracking & Due Date Warning</h4>
                                        <p>Mencatat tanggal pinjam, batas waktu kembali per shift, dan mengirim alert otomatis saat perkakas terlambat dikembalikan ke ToolRoom.</p>
                                    </div>
                                    <div class="i-card bg-orange-s">
                                        <div class="i-icon" style="background:#fed7aa50;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><polyline points="9,11 12,14 22,4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
                                        <h4>Grading Kondisi Pengembalian</h4>
                                        <p>Inspeksi fisik 4 tingkatan: <strong>Grade A</strong> (Sempurna), <strong>Grade B</strong> (Aus Wajar), <strong>Grade C</strong> (Perlu Rekondisi/Servis), <strong>Grade D</strong> (Rusak Parah / Hilang).</p>
                                    </div>
                                    <div class="i-card bg-orange-s">
                                        <div class="i-icon" style="background:#fed7aa50;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="10" r="3"/></svg></div>
                                        <h4>Tool Kit Box Assignment</h4>
                                        <p>Alokasi kotak perkakas mekanik personal (Toolbox Set) dengan checklist inventaris berkala untuk pencegahan FOD (Foreign Object Debris).</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. KALIBRASI & SERTIFIKASI ALAT UKUR -->
                            <div class="sub-block">
                                <div class="sub-block-title c-teal">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9,12 11,14 15,10"/></svg>
                                    2. Sertifikasi & Kalibrasi Alat Ukur Presisi
                                </div>
                                <div class="two-col">
                                    <div>
                                        <p style="font-size:.85rem;color:var(--text-secondary);line-height:1.7;margin-bottom:12px;">Kesalahan torsi baut Track Shoe atau Cylinder Head dapat berakibat fatal. CMMS mengelola seluruh alat ukur presisi dengan standar kalibrasi ketat:</p>
                                        <ul class="f-list">
                                            <li><div class="f-check" style="background:#ccfbf1;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Torque Wrench & Multiplier</strong> — kalibrasi periodik 6 bulan (ISO 6789)</span></li>
                                            <li><div class="f-check" style="background:#ccfbf1;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Hydraulic Pressure Gauges (0–600 Bar)</strong> — sertifikat uji lab terakreditasi KAN</span></li>
                                            <li><div class="f-check" style="background:#ccfbf1;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Insulation Tester (Megger) & Multimeter Fluke</strong> — sertifikasi electrical safety</span></li>
                                        </ul>
                                    </div>
                                    <div class="i-card bg-teal-s" style="margin-top:0;">
                                        <h4 class="c-teal" style="margin-bottom:8px;">⚠️ Auto-Lockout Tool Kadaluarsa</h4>
                                        <p style="color:#115e59;font-size:.82rem;line-height:1.6;">Sistem otomatis <strong>mengunci (Lockout)</strong> perkakas yang telah melewati masa berlaku kalibrasi sehingga tidak dapat di-checkout oleh Toolman sampai sertifikat uji kalibrasi baru diunggah ke sistem.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. STOK PART & MULTI-WAREHOUSE BIN LOCATION -->
                            <div class="sub-block">
                                <div class="sub-block-title c-orange">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                    3. Gudang Multi-Site & Hierarki Bin Location Rak
                                </div>
                                <div class="info-grid">
                                    <div class="i-card" style="background:var(--surface);border:1.5px solid var(--border);">
                                        <div class="i-icon" style="background:#eff6ff;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></div>
                                        <h4>Hierarki Bin Location</h4>
                                        <p>Format presisi: <code>[Gudang]-[Lorong/Aisle]-[Rak/Rack]-[Tingkat/Tier]-[Kotak/Bin]</code> (Contoh: <code>WH-MAIN-A-03-B2</code>). Mempersingkat waktu pencarian mekanik di gudang.</p>
                                    </div>
                                    <div class="i-card" style="background:var(--surface);border:1.5px solid var(--border);">
                                        <div class="i-icon" style="background:#fffbeb;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><polygon points="12,2 15,9 22,9 16,14 18,21 12,17 6,21 8,14 2,9 9,9"/></svg></div>
                                        <h4>Multi-Site Warehouse</h4>
                                        <p>Kelola stok tersebar di Main Warehouse, Site Pit Pitstop, Fuel Station, dan Kontainer Mobile Workshop dengan visibilitas stok terpusat.</p>
                                    </div>
                                    <div class="i-card" style="background:var(--surface);border:1.5px solid var(--border);">
                                        <div class="i-icon" style="background:#fef2f2;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"><polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/></svg></div>
                                        <h4>Interchangeable & Cross Ref</h4>
                                        <p>Dukungan part alternatif (OEM vs Komatsu / Caterpillar / Hitachi Cross-Part Number) saat part utama mengalami out-of-stock.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. WO INTEGRATION & DIRECT AUTO-DEDUCT -->
                            <div class="sub-block">
                                <div class="sub-block-title c-blue">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/></svg>
                                    4. Integrasi Work Order & Pemotongan Stok Otomatis
                                </div>
                                <div class="step-flow">
                                    <div class="step-item">
                                        <div class="step-num">1</div>
                                        <div class="step-body">
                                            <h4>Permintaan Part di Work Order (Part Requisition)</h4>
                                            <p>Mekanik / Planner memilih part dari Master Part Database langsung di form WO. Sistem otomatis menampilkan stok tersedia dan lokasi Bin rak.</p>
                                        </div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-num">2</div>
                                        <div class="step-body">
                                            <h4>Pengeluaran Gudang (Goods Issue Note - GIN)</h4>
                                            <p>Toolman / Warehouseman memverifikasi nomor WO dan menyerahkan part. Stok gudang otomatis <strong>terpotong secara real-time</strong>.</p>
                                        </div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-num">3</div>
                                        <div class="step-body">
                                            <h4>Kalkulasi Maintenance Cost Unit Otomatis</h4>
                                            <p>Harga beli rata-rata part (Moving Average Price) otomatis dijumlahkan ke total biaya pemeliharaan unit yang bersangkutan.</p>
                                        </div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-num">4</div>
                                        <div class="step-body">
                                            <h4>Pengembalian Sisa Part (Return to Warehouse)</h4>
                                            <p>Part cadangan atau sisa yang tidak terpasang dapat dikembalikan ke gudang melalui fitur <em>Part Return</em>, memulihkan angka saldo stok secara transparan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. KANIBALISASI PART RESMI (CANNIBALIZATION) -->
                            <div class="sub-block">
                                <div class="sub-block-title c-red">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 3h5v5"/><path d="M4 20L21 3"/><path d="M21 16v5h-5"/><path d="M15 15l6 6"/><path d="M4 4l5 5"/></svg>
                                    5. Tata Kelola Kanibalisasi Suku Cadang (Part Swapping)
                                </div>
                                <p style="font-size:.85rem;color:var(--text-secondary);margin-bottom:12px;">Dalam kondisi darurat operasional pit, pemindahan komponen dari unit standby/breakdown ke unit prioritas produksi sering terjadi. CMMS AISFAR memiliki modul khusus untuk melegalkan proses ini secara tertib:</p>
                                <div class="two-col">
                                    <div class="i-card bg-red-s" style="margin-top:0;">
                                        <h4 class="c-red" style="margin-bottom:6px;">📋 Berita Acara Kanibalisasi Digital</h4>
                                        <p style="color:#991b1b;font-size:.82rem;line-height:1.6;">Mencatat <strong>Unit Donor</strong> (pemberi part), <strong>Unit Receiver</strong> (penerima part), nama part, serial number, alasan operasional, serta persetujuan resmi Maintenance Superintendent.</p>
                                    </div>
                                    <div class="i-card bg-red-s" style="margin-top:0;">
                                        <h4 class="c-red" style="margin-bottom:6px;">🔄 Auto Backlog Trigger</h4>
                                        <p style="color:#991b1b;font-size:.82rem;line-height:1.6;">Saat kanibalisasi disetujui, sistem otomatis membuat <strong>Backlog WO & PR (Purchase Requisition)</strong> untuk unit donor agar part pengganti segera dipesan dan tidak terlupakan.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 6. MANAJEMEN PELUMAS & FLUID (LUBRICANT CONTROL) -->
                            <div class="sub-block">
                                <div class="sub-block-title c-amber">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                                    6. Pengendalian Bulk Oil, Lubricant & Fluid
                                </div>
                                <div class="three-col">
                                    <div class="i-card bg-amber-s" style="margin:0;">
                                        <h4 class="c-amber">🛢️ Bulk Tank Level</h4>
                                        <p>Monitoring volume tangki induk Oli Mesin (15W-40), Oli Hidrolik (ISO VG 46/68), Oli Transmisi (SAE 30/50), dan Coolant secara liter.</p>
                                    </div>
                                    <div class="i-card bg-amber-s" style="margin:0;">
                                        <h4 class="c-amber">⏱️ Lube Dispense Log</h4>
                                        <p>Pencatatan meteran pengisian oli per unit pada servis berkala PM maupun top-up harian untuk mendeteksi unit yang boros oli.</p>
                                    </div>
                                    <div class="i-card bg-amber-s" style="margin:0;">
                                        <h4 class="c-amber">🧪 Oil Sampling (PAP)</h4>
                                        <p>Pencatatan sampel Program Analisa Pelumas (PAP/SOS) untuk memprediksi keausan komponen internal engine/transmission.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 7. ABC ANALYSIS & STOCK OPNAME -->
                            <div class="two-col">
                                <div class="i-card" style="background:var(--surface3);border:1.5px solid var(--border);">
                                    <h4 style="margin-bottom:8px;">📊 Klasifikasi ABC & FSN Analytics</h4>
                                    <ul class="f-list" style="gap:6px;">
                                        <li><div class="f-check" style="background:#fed7aa;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Kelas A</strong> — 20% item bernilai tinggi (70-80% total nilai gudang)</span></li>
                                        <li><div class="f-check" style="background:#fed7aa;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Kelas B</strong> — 30% item bernilai menengah (15-20% total nilai)</span></li>
                                        <li><div class="f-check" style="background:#fed7aa;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Fast / Slow / Non-Moving (FSN)</strong> — identifikasi dead stock > 180 hari</span></li>
                                    </ul>
                                </div>
                                <div class="i-card" style="background:var(--surface3);border:1.5px solid var(--border);">
                                    <h4 style="margin-bottom:8px;">📱 Stock Opname Digital Berbasis Barcode</h4>
                                    <ul class="f-list" style="gap:6px;">
                                        <li><div class="f-check" style="background:#c7d2fe;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Stock opname berkala (Bulanan / Triwulan) via scanner smartphone</span></li>
                                        <li><div class="f-check" style="background:#c7d2fe;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Selisih fisik vs sistem terhitung otomatis (Variance Report)</span></li>
                                        <li><div class="f-check" style="background:#c7d2fe;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Audit trail lengkap untuk kepatuhan akuntansi & audit eksternal</span></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- PRODUCTION -->
                <div id="production" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#06b6d4;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#ecfeff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3"/><rect x="9" y="11" width="14" height="10" rx="2"/><circle cx="12" cy="21" r="1"/><circle cx="20" cy="21" r="1"/></svg></div>
                                <div><div class="s-eyebrow c-cyan">Fleet & Production</div><h2 class="s-h2">Laporan Produksi Harian</h2></div>
                            </div>
                            <p class="s-lead">Modul khusus Engineer & Produksi Pertambangan untuk mencatat aktivitas operasional Overburden dan Coal Mining secara terstruktur.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="info-grid">
                                <div class="i-card bg-cyan-s"><div class="i-icon" style="background:#a5f3fc30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg></div><h4>1 Form per Shift</h4><p>Cukup 1 form untuk Day Shift atau Night Shift. Tambahkan sebanyak mungkin Digger & Hauler dalam satu sesi.</p></div>
                                <div class="i-card bg-cyan-s"><div class="i-icon" style="background:#a5f3fc30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div><h4>Ritasi Per Jam (1–12)</h4><p>Ritasi tiap Dump Truck wajib diisi per jam. Sistem otomatis kalkulasi tonase (Ritasi × Payload ton/BCM).</p></div>
                                <div class="i-card bg-cyan-s"><div class="i-icon" style="background:#a5f3fc30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/></svg></div><h4>Multi Material Tambang</h4><p>Support OB, Coal, Top Soil, Mud, Sub Soil, Waste — lengkap dengan jarak angkut (KM).</p></div>
                                <div class="i-card bg-cyan-s"><div class="i-icon" style="background:#a5f3fc30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h4>Alat Support & Delay</h4><p>HM Awal/Akhir unit Support (Dozer, Grader, Compactor) dan pencatatan kendala (Rain, Slippery, Breakdown).</p></div>
                            </div>
                            <div class="h-box" style="background:#ecfeff;border-color:#a5f3fc;">
                                <div class="h-box-title c-cyan"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>Format Cetak PDF Resmi</div>
                                <p style="color:#164e63;">Laporan dilengkapi tampilan cetak PDF ber-Kop Surat, ringkasan 4 indikator eksekutif, dan 3 kolom Pengesahan (Dispatcher, Supervisor, Superintendent Produksi).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MASTER UNITS -->
                <div id="master-units" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#8b5cf6;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#f5f3ff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><line x1="2" y1="12" x2="22" y2="12"/></svg></div>
                                <div><div class="s-eyebrow c-purple">Asset Intelligence</div><h2 class="s-h2">Populasi Unit & 360° History Tracking</h2></div>
                            </div>
                            <p class="s-lead">Setiap unit memiliki halaman Detail & Histori 360 Derajat — rekam jejak operasional dan pemeliharaan sejak unit pertama terdaftar.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="two-col">
                                <div class="i-card bg-purple-s"><h4 class="c-purple" style="margin-bottom:10px;">🔧 Spesifikasi Teknis Lengkap</h4><ul class="f-list"><li><div class="f-check" style="background:#f5f3ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>S/N Chassis & Engine, Power (HP/KW), Tahun Perakitan</span></li><li><div class="f-check" style="background:#f5f3ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Kapasitas Munjung, No Polisi/Lambung, Site Operasional</span></li><li><div class="f-check" style="background:#f5f3ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Status real-time: RFU, Breakdown, In Maintenance, Standby</span></li></ul></div>
                                <div class="i-card" style="background:#f5f3ff;border:1.5px solid #ddd6fe;"><h4 class="c-purple" style="margin-bottom:10px;">🔄 5 Tab Riwayat 360°</h4><ul class="f-list" style="gap:6px;"><li><div class="f-check" style="background:#ede9fe;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>WO History</strong> — perbaikan internal</span></li><li><div class="f-check" style="background:#ede9fe;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Produksi</strong> — log jam operasional</span></li><li><div class="f-check" style="background:#ede9fe;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>FAR & JWO</strong> — kerusakan & vendor</span></li><li><div class="f-check" style="background:#ede9fe;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>PM & HM Log</strong> — interval berkala</span></li><li><div class="f-check" style="background:#ede9fe;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Audit Trail</strong> — log perubahan transparan</span></li></ul></div>
                            </div>
                            <div class="h-box" style="background:#fef2f2;border-color:#fecaca;">
                                <div class="h-box-title c-red"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>Life Cycle Costing — Identifikasi Bad Actor Unit</div>
                                <p style="color:#991b1b;">Sistem mengkalkulasi otomatis total biaya perbaikan unit (WO Internal + JWO Vendor + Part Gudang) untuk mengidentifikasi unit dengan biaya pemeliharaan berlebih.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WORK ORDER -->
                <div id="work-order" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#2563eb;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#eff6ff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/></svg></div>
                                <div><div class="s-eyebrow c-blue">Maintenance Core</div><h2 class="s-h2">Work Order (WO) & Multi-Category</h2></div>
                            </div>
                            <p class="s-lead">Work Order adalah <strong>fondasi absolut</strong> sistem CMMS. Seluruh Breakdown Info wajib terikat pada WO untuk menjadi Backlog yang valid dan traceable.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="h-box" style="background:#eff6ff;border-color:#bfdbfe;">
                                <div class="h-box-title c-blue"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>WO Sebagai Single Source of Truth</div>
                                <p style="color:#1e40af;">Dari data WO, Planner menyusun Maintenance Scheduling — mengakomodasi Short-Term (insidental) maupun Long-Term (berbasis interval Hour Meter / PM Schedule).</p>
                            </div>
                            <div class="step-row">
                                <div class="step-box"><div class="step-box-icon" style="background:#eff6ff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div><strong>Buat WO</strong><p>Catat masalah, lokasi, kategorisasi Level 1–5</p></div>
                                <div class="step-box"><div class="step-box-icon" style="background:#fff7ed;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg></div><strong>Alokasi & Eksekusi</strong><p>Tugaskan mekanik, catat alat SST & sparepart</p></div>
                                <div class="step-box"><div class="step-box-icon" style="background:#ecfdf5;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg></div><strong>Close WO</strong><p>TTD digital 3 tingkat, unit kembali RFU</p></div>
                            </div>
                            <ul class="f-list">
                                <li><div class="f-check" style="background:#eff6ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Kategorisasi 5 Level hierarki komponen (Sistem Utama → Detail Sub-Komponen)</span></li>
                                <li><div class="f-check" style="background:#eff6ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Integrasi dokumen Safety (JSA/PTW/LOTO) langsung dalam satu form WO</span></li>
                                <li><div class="f-check" style="background:#eff6ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Manajemen Part — order baru, potong stok gudang, maupun alokasi Part Cannibal/Swap antar unit</span></li>
                                <li><div class="f-check" style="background:#eff6ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Kalkulasi otomatis Response Time sejak unit BD hingga mulai dieksekusi</span></li>
                                <li><div class="f-check" style="background:#eff6ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Board view Kanban untuk monitoring status WO secara visual per unit/site</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- KPI ISO -->
                <div id="kpi-iso" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#f59e0b;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#fffbeb;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><path d="M3 20h18"/></svg></div>
                                <div><div class="s-eyebrow c-amber">Analytics & KPI</div><h2 class="s-h2">Analitik KPI & Filter ISO 8601</h2></div>
                            </div>
                            <p class="s-lead">Analisis Master Data KPI untuk memantau reliabilitas alat berat, waktu breakdown, dan durasi perbaikan dengan standar kalender internasional.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="info-grid">
                                <div class="i-card bg-amber-s"><div class="i-icon" style="background:#fde68a30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><h4>ISO 8601 Week Filter</h4><p>Filter Week (Minggu Ke-X) otomatis sesuai standar Senin–Minggu, tanpa pilih tanggal manual.</p></div>
                                <div class="i-card bg-amber-s"><div class="i-icon" style="background:#fde68a30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/></svg></div><h4>MTTR & MTBF Analysis</h4><p>Kalkulasi Mean Time To Repair dan Mean Time Between Failures untuk evaluasi keandalan armada.</p></div>
                                <div class="i-card bg-amber-s"><div class="i-icon" style="background:#fde68a30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></div><h4>Export Excel Profesional</h4><p>Download tabel lengkap: WO, Status, Tipe, Breakdown Type, Kategori L1–L5, Durasi BD.</p></div>
                                <div class="i-card bg-amber-s"><div class="i-icon" style="background:#fde68a30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg></div><h4>Clear Filter 1-Klik</h4><p>Reset seluruh filter pencarian ke kondisi awal dengan satu klik tanpa reload halaman.</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAR -->
                <div id="far" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#ef4444;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#fef2f2;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
                                <div><div class="s-eyebrow c-red">Failure Analysis</div><h2 class="s-h2">Failure Analysis Report (FAR)</h2></div>
                            </div>
                            <p class="s-lead">Investigasi mendalam berstandar industri untuk kerusakan fatal, kegagalan komponen utama (Major Component Failure), atau insiden unit yang berulang.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="two-col">
                                <div>
                                    <h4 style="font-weight:700;color:var(--text-primary);margin-bottom:14px;font-family:'Plus Jakarta Sans',sans-serif;">Komponen Inti FAR</h4>
                                    <div class="step-flow">
                                        <div class="step-item"><div class="step-num" style="background:linear-gradient(135deg,#ef4444,#dc2626);box-shadow:0 4px 12px rgba(239,68,68,.28);">1</div><div class="step-body"><h4>Integrasi WO Langsung</h4><p>FAR wajib merujuk Nomor WO untuk auto-fetch data unit.</p></div></div>
                                        <div class="step-item"><div class="step-num" style="background:linear-gradient(135deg,#ef4444,#dc2626);box-shadow:0 4px 12px rgba(239,68,68,.28);">2</div><div class="step-body"><h4>Auto Part Number DB</h4><p>Deskripsi Part otomatis tarik dari Master Data saat PN diketik.</p></div></div>
                                        <div class="step-item"><div class="step-num" style="background:linear-gradient(135deg,#ef4444,#dc2626);box-shadow:0 4px 12px rgba(239,68,68,.28);">3</div><div class="step-body"><h4>4 Pilar Analisa RCA</h4><p>Gambaran → Latar Belakang → Analisa Teknis → Kesimpulan/Rekomendasi</p></div></div>
                                    </div>
                                </div>
                                <div class="i-card bg-red-s" style="height:fit-content;margin-top:0;">
                                    <h4 class="c-red" style="margin-bottom:12px;">Teknologi Pendukung</h4>
                                    <ul class="f-list">
                                        <li><div class="f-check" style="background:#fee2e2;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Smart Auto-Compress</strong> — foto HP 15MB → &lt;2MB otomatis di browser</span></li>
                                        <li><div class="f-check" style="background:#fee2e2;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>TTD Digital</strong> — 3 tingkat tanda tangan, cetak PDF ber-Kop Surat resmi</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- JWO -->
                <div id="jwo" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#06b6d4;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#ecfeff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="23" y1="11" x2="17" y2="11"/><line x1="20" y1="8" x2="20" y2="14"/></svg></div>
                                <div><div class="s-eyebrow c-cyan">Vendor Management</div><h2 class="s-h2">Job Work Order (JWO) & Vendor</h2></div>
                            </div>
                            <p class="s-lead">Kelola perbaikan komponen yang dialihkan ke pihak ketiga. JWO memastikan kendali penuh terhadap pengerjaan luar, waktu, dan akuntabilitas biaya.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="info-grid">
                                <div class="i-card bg-cyan-s"><div class="i-icon" style="background:#a5f3fc30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></div><h4>Konektivitas WO Utama</h4><p>Setiap JWO terikat langsung dengan nomor WO internal untuk menjaga traceability perbaikan.</p></div>
                                <div class="i-card bg-cyan-s"><div class="i-icon" style="background:#a5f3fc30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg></div><h4>Nomor PO / SPK Vendor</h4><p>Pencatatan legalitas kerja: Nomor PO, SPK, dan identitas kontraktor/bengkel pelaksana.</p></div>
                                <div class="i-card bg-cyan-s"><div class="i-icon" style="background:#a5f3fc30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><polyline points="22,12 16,12 13,21 7,3 4,12 0,12"/></svg></div><h4>Tracking Status Komponen</h4><p>Dikirim → Dalam Pengerjaan → Selesai/Diterima Kembali di Site.</p></div>
                                <div class="i-card bg-cyan-s"><div class="i-icon" style="background:#a5f3fc30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><h4>Estimasi vs Actual Cost</h4><p>Nilai biaya jasa vendor terintegrasi otomatis ke laporan Maintenance Cost unit.</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HSE -->
                <div id="hse" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#10b981;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#ecfdf5;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9,12 11,14 15,10"/></svg></div>
                                <div><div class="s-eyebrow c-green">Safety & K3</div><h2 class="s-h2">Manajemen Risiko K3 / HSE</h2></div>
                            </div>
                            <p class="s-lead">Tiga pilar keselamatan kerja terintegrasi dalam setiap Work Order, memastikan perbaikan alat berat mengutamakan keselamatan mekanik di lapangan pertambangan.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="hse-grid">
                                <div class="hse-card" style="background:#ecfdf5;border:1.5px solid #a7f3d0;border-radius:var(--radius-sm);"><div class="hse-emoji">📋</div><strong class="c-green">JSA</strong><p>Job Safety Analysis — identifikasi tahap kerja, bahaya, APD, dan tindakan pencegahan sebelum pekerjaan dimulai</p></div>
                                <div class="hse-card" style="background:#ecfdf5;border:1.5px solid #a7f3d0;border-radius:var(--radius-sm);"><div class="hse-emoji">📑</div><strong class="c-green">PTW</strong><p>Permit to Work — ijin khusus Hot Work, Confined Space, Working at Height, Electrical Isolation</p></div>
                                <div class="hse-card" style="background:#ecfdf5;border:1.5px solid #a7f3d0;border-radius:var(--radius-sm);"><div class="hse-emoji">🔒</div><strong class="c-green">LOTO</strong><p>Lockout/Tagout — isolasi sumber energi (Baterai, Hidrolik, Kunci Kontak) sebelum servis unit</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BUDGET -->
                <div id="budget" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#f59e0b;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#fffbeb;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                                <div><div class="s-eyebrow c-amber">Cost Management</div><h2 class="s-h2">Budget & Maintenance Cost Control</h2></div>
                            </div>
                            <p class="s-lead">Pengendalian finansial pemeliharaan presisi berbasis unit alat berat — menyelaraskan alokasi anggaran (Planned Cost) dengan kesiapan unit (PA%) dan pengeluaran riil (Actual).</p>
                        </div>
                        <div class="s-card-body">
                            <div class="two-col">
                                <div class="i-card" style="background:var(--surface3);border:1.5px solid var(--border);"><h4 style="margin-bottom:10px;">📋 Plan Budget Bulanan</h4><ul class="f-list"><li><div class="f-check" style="background:#fde68a40;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Target Physical Availability (PA%) per unit</span></li><li><div class="f-check" style="background:#fde68a40;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>BoQ Part: Part Number, Qty, estimasi harga item</span></li><li><div class="f-check" style="background:#fde68a40;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Total Baseline Cost per unit & per site proyek</span></li></ul></div>
                                <div class="i-card bg-amber-s"><h4 class="c-amber" style="margin-bottom:10px;">📊 Plan vs Actual Evaluasi</h4><ul class="f-list"><li><div class="f-check" style="background:#fde68a40;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Auto-tracking Actual Cost dari WO & Part gudang bulan berjalan</span></li><li><div class="f-check" style="background:#fde68a40;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Variance = Planned - Actual, indikator Over/Underbudget</span></li><li><div class="f-check" style="background:#fde68a40;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Matriks Cost-Effectiveness vs Target PA unit</span></li></ul></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PM -->
                <div id="pm" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#6366f1;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#eef2ff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div>
                                <div><div class="s-eyebrow c-indigo">Preventive Maintenance</div><h2 class="s-h2">PM Template & Schedule</h2></div>
                            </div>
                            <p class="s-lead">Sistem perawatan berkala otomatis untuk mencegah kerusakan prematur. Memastikan armada diservis tepat waktu sesuai rekomendasi pabrikan (OEM).</p>
                        </div>
                        <div class="s-card-body">
                            <div class="info-grid">
                                <div class="i-card bg-indigo-s"><div class="i-icon" style="background:#c7d2fe30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg></div><h4>Template PS1–PS4</h4><p>PS1 (250 HM), PS2 (500 HM), PS3 (1000 HM), PS4 (2000 HM Overhaul Inspection).</p></div>
                                <div class="i-card bg-indigo-s"><div class="i-icon" style="background:#c7d2fe30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><polyline points="9,11 12,14 22,4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div><h4>Checklist & Konsumsi Part</h4><p>Filter Oli, Solar, Udara, Pelumas terisi otomatis per template PM sesuai paket servis.</p></div>
                                <div class="i-card bg-indigo-s"><div class="i-icon" style="background:#c7d2fe30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div><h4>HM Due Warning</h4><p>Sistem bandingkan HM harian unit dengan jadwal servis berikutnya — peringatan dini otomatis.</p></div>
                                <div class="i-card bg-indigo-s"><div class="i-icon" style="background:#c7d2fe30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M13 2v7h7"/><path d="M12 18v-4"/><path d="M10 16h4"/></svg></div><h4>Auto-Generate WO 1-Klik</h4><p>Planner klik 1 tombol → WO PM diterbitkan lengkap dengan checklist dan part terisi otomatis.</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLLABORATION -->
                <div id="collaboration" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#6366f1;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#eef2ff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
                                <div><div class="s-eyebrow c-indigo">Collaboration</div><h2 class="s-h2">Live Chat & Kolaborasi Tim Real-time</h2></div>
                            </div>
                            <p class="s-lead">Komunikasi internal yang menghubungkan Planner, Supervisor, Mekanik, dan Manajer dalam satu jaringan kerja cepat tanpa hambatan.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="info-grid">
                                <div class="i-card bg-indigo-s"><div class="i-icon" style="background:#c7d2fe30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><h4>Instant Messaging</h4><p>Floating chat widget dari mana saja, indikator status Online/Offline real-time.</p></div>
                                <div class="i-card bg-indigo-s"><div class="i-icon" style="background:#c7d2fe30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></div><h4>1-Klik Share Link</h4><p>Salin tautan WO, FAR, JWO, atau Plan Budget ke ruang chat untuk koordinasi instan.</p></div>
                                <div class="i-card bg-indigo-s"><div class="i-icon" style="background:#c7d2fe30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg></div><h4>Notifikasi Real-time</h4><p>Alert pesan masuk dengan badge counter di navbar, tidak ada pesan terlewat.</p></div>
                                <div class="i-card bg-indigo-s"><div class="i-icon" style="background:#c7d2fe30;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div><h4>Pencarian Dokumen</h4><p>Cari dokumen (WO, FAR, JWO) langsung dari kotak chat dan bagikan ke lawan bicara.</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIGNATURES -->
                <div id="signatures" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#a855f7;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#fdf4ff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></div>
                                <div><div class="s-eyebrow" style="color:#a855f7;">Digital Signature</div><h2 class="s-h2">Tanda Tangan Digital & Paperless</h2></div>
                            </div>
                            <p class="s-lead">Pengesahan dokumen 100% paperless yang sah, cepat, dan transparan. Menghilangkan kebutuhan mencetak dokumen fisik untuk persetujuan perbaikan.</p>
                        </div>
                        <div class="s-card-body">
                            <div class="two-col">
                                <div>
                                    <h4 style="font-weight:700;color:var(--text-primary);margin-bottom:14px;font-family:'Plus Jakarta Sans',sans-serif;">Matriks Otorisasi 3 Tingkat</h4>
                                    <div class="step-flow">
                                        <div class="step-item"><div class="step-num" style="background:linear-gradient(135deg,#a855f7,#7c3aed);box-shadow:0 4px 12px rgba(168,85,247,.28);">1</div><div class="step-body"><h4>Prepared By</h4><p>Mekanik/Planner yang memproses WO atau Laporan</p></div></div>
                                        <div class="step-item"><div class="step-num" style="background:linear-gradient(135deg,#a855f7,#7c3aed);box-shadow:0 4px 12px rgba(168,85,247,.28);">2</div><div class="step-body"><h4>Reviewed By</h4><p>Supervisor/Pengawas Safety yang memverifikasi</p></div></div>
                                        <div class="step-item"><div class="step-num" style="background:linear-gradient(135deg,#a855f7,#7c3aed);box-shadow:0 4px 12px rgba(168,85,247,.28);">3</div><div class="step-body"><h4>Approved By</h4><p>Maintenance Superintendent/Manager yang mengesahkan</p></div></div>
                                    </div>
                                </div>
                                <div class="i-card" style="background:#fdf4ff;border:1.5px solid #e9d5ff;height:fit-content;margin-top:0;">
                                    <h4 style="color:#7c3aed;margin-bottom:10px;">Fitur Teknis</h4>
                                    <ul class="f-list">
                                        <li><div class="f-check" style="background:#e9d5ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Canvas interaktif — touchscreen HP/Tablet atau mouse</span></li>
                                        <li><div class="f-check" style="background:#e9d5ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Watermark digital — stempel approval otomatis pada PDF</span></li>
                                        <li><div class="f-check" style="background:#e9d5ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Penguncian dokumen setelah 3 TTD terisi permanen</span></li>
                                        <li><div class="f-check" style="background:#e9d5ff;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span>Print-ready PDF ber-Kop Surat perusahaan</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FUEL MANAGEMENT SYSTEM (FMS) -->
                <div id="fuel-management" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#0284c7;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#f0f9ff;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5"><path d="M3 2v20h6v-8h2v8h4V8l-4-4H3z"/><path d="M15 16h2a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2"/><circle cx="6" cy="7" r="1"/></svg></div>
                                <div><div class="s-eyebrow c-sky">Fuel & Energy Management</div><h2 class="s-h2">Fuel Management System (FMS) Terpadu</h2></div>
                            </div>
                            <p class="s-lead">Sistem tata kelola bahan bakar solar industri (HSD) tambang terintegrasi dari hulu ke hilir: penerimaan vendor & sonding fisik, mutasi multi-storage, isi ulang armada Fuel Truck, distribusi unit berbasis shift dengan rekonsiliasi flowmeter, berita acara tera meteran, hingga kalkulasi burn rate (L/HM) dan kartu stok otomatis.</p>
                        </div>
                        <div class="s-card-body">

                            <!-- KEY METRICS BANNER -->
                            <div class="four-col">
                                <div class="metric-pill">
                                    <div class="metric-pill-icon" style="background:#f0f9ff;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                                    <div><div class="metric-pill-val">&lt; 0.3%</div><div class="metric-pill-lbl">Losses Tolerance</div></div>
                                </div>
                                <div class="metric-pill">
                                    <div class="metric-pill-icon" style="background:#eff6ff;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><path d="M12 20v-6M6 20V10M18 20V4"/></svg></div>
                                    <div><div class="metric-pill-val">L / HM</div><div class="metric-pill-lbl">Unit Burn Rate</div></div>
                                </div>
                                <div class="metric-pill">
                                    <div class="metric-pill-icon" style="background:#ecfdf5;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
                                    <div><div class="metric-pill-val">100% Audit</div><div class="metric-pill-lbl">Stock Card Trail</div></div>
                                </div>
                                <div class="metric-pill">
                                    <div class="metric-pill-icon" style="background:#fffbeb;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div>
                                    <div><div class="metric-pill-val">0 Variance</div><div class="metric-pill-lbl">Shift Reconciliation</div></div>
                                </div>
                            </div>

                            <!-- INFOGRAFIS SIKLUS LENGKAP ALUR BBM -->
                            <div class="fms-diagram-box">
                                <div class="fms-diagram-inner">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="background:rgba(56,189,248,.2);color:#38bdf8;padding:4px 10px;border-radius:6px;font-size:.72rem;font-weight:800;letter-spacing:1px;text-transform:uppercase;">Infografis Interaktif</span>
                                            <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.15rem;font-weight:800;color:#fff;margin:0;">Siklus 6 Tahap Alur BBM: Hulu ke Hilir & Pelaporan</h3>
                                        </div>
                                        <span class="text-white-50" style="font-size:.75rem;">100% Terintegrasi Logistik Tambang</span>
                                    </div>
                                    <p style="font-size:.82rem;color:#94a3b8;line-height:1.6;margin-bottom:16px;">Setiap liter BBM solar yang masuk dari vendor supplier hingga dikonsumsi oleh Excavator, Hauler, dan Dozer dicatat dengan verifikasi meteran & otorisasi berjenjang:</p>
                                    
                                    <div class="fms-flow-track">
                                        <div class="fms-step-card">
                                            <div class="fms-step-badge">1</div>
                                            <div class="fms-step-title">Inbound & Sonding</div>
                                            <div class="fms-step-desc">Truk vendor tiba &rarr; Sonding cm, densitas & suhu &rarr; Upload Surat Jalan/BAP &rarr; Approval atasan.</div>
                                            <span class="fms-badge-tag" style="background:#0369a1;color:#e0f2fe;">Stok Masuk Tangki</span>
                                        </div>

                                        <div class="fms-step-card">
                                            <div class="fms-step-badge">2</div>
                                            <div class="fms-step-title">Mutasi Antar Tangki</div>
                                            <div class="fms-step-desc">Transfer BBM antar storage site via pipa pompa langsung atau diangkut unit Fuel Truck.</div>
                                            <span class="fms-badge-tag" style="background:#0f766e;color:#ccfbf1;">Balancing Storage</span>
                                        </div>

                                        <div class="fms-step-card">
                                            <div class="fms-step-badge">3</div>
                                            <div class="fms-step-title">Isi Ulang Fuel Truck</div>
                                            <div class="fms-step-desc">Pengisian BBM dari Storage ke armada Fuel Truck terdaftar &rarr; Catat totalizer awal/akhir & driver.</div>
                                            <span class="fms-badge-tag" style="background:#b45309;color:#fef3c7;">Mobile Stock Out</span>
                                        </div>

                                        <div class="fms-step-card">
                                            <div class="fms-step-badge">4</div>
                                            <div class="fms-step-title">Distribusi Shift Unit</div>
                                            <div class="fms-step-desc">Fuelman buka shift &rarr; Isi unit tambang (HM/KM, Jam, Lokasi, Liter) &rarr; Tutup shift & rekonsiliasi.</div>
                                            <span class="fms-badge-tag" style="background:#15803d;color:#dcfce7;">Unit Dispensing</span>
                                        </div>

                                        <div class="fms-step-card">
                                            <div class="fms-step-badge">5</div>
                                            <div class="fms-step-title">Berita Acara Flowmeter</div>
                                            <div class="fms-step-desc">Pencatatan kerusakan / tera meteran baru bertandatangan Site Manager & penyesuaian totalizer.</div>
                                            <span class="fms-badge-tag" style="background:#7e22ce;color:#f3e8ff;">BAP Flowmeter</span>
                                        </div>

                                        <div class="fms-step-card">
                                            <div class="fms-step-badge">6</div>
                                            <div class="fms-step-title">Laporan & Kartu Stok</div>
                                            <div class="fms-step-desc">Posisi stok real-time, burn rate L/HM, audit trail kartu stok, cetak PDF resmi Kop Surat `/settings`.</div>
                                            <span class="fms-badge-tag" style="background:#be123c;color:#ffe4e6;">Report & Analytics</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1. INBOUND PENERIMAAN BBM -->
                            <div class="sub-block">
                                <div class="sub-block-title c-sky">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9,15 12,18 15,15"/></svg>
                                    1. Penerimaan BBM dari Vendor Supplier & Pengukuran Sonding Fisik
                                </div>
                                <p style="font-size:.86rem;color:var(--text-secondary);margin-bottom:14px;">Mencegah manipulasi volume solar yang dikirimkan oleh supplier melalui pencatatan parameter fisik lengkap dan verifikasi approval berjenjang sebelum stok diakui oleh sistem.</p>
                                <div class="info-grid">
                                    <div class="i-card bg-sky-s">
                                        <div class="i-icon" style="background:#bae6fd80;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5"><path d="M3 2v20h6v-8h2v8h4V8l-4-4H3z"/><circle cx="6" cy="7" r="1"/></svg></div>
                                        <h4>Registrasi Truk Tangki Supplier</h4>
                                        <p>Data vendor, transportir ekspedisi, nomor polisi truk, nama driver, nomor handphone, dan kapasitas kompartemen terdata resmi.</p>
                                    </div>
                                    <div class="i-card bg-sky-s">
                                        <div class="i-icon" style="background:#bae6fd80;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                                        <h4>Sonding Fisik & Analisa Densitas</h4>
                                        <p>Mencatat ketinggian minyak sebelum/sesudah bongkar (cm), suhu tangki (°C), densitas minyak, dan volume DO surat jalan vs volume aktual.</p>
                                    </div>
                                    <div class="i-card bg-sky-s">
                                        <div class="i-icon" style="background:#bae6fd80;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>
                                        <h4>Pop-up Lampiran Dokumen Scan</h4>
                                        <p>Pratinjau instan berkas Surat Jalan / BAP fisik (PDF/Foto) dalam pop-up modal interaktif tanpa perlu berpindah tab browser.</p>
                                    </div>
                                    <div class="i-card bg-sky-s">
                                        <div class="i-icon" style="background:#bae6fd80;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
                                        <h4>Approval Berjenjang Atasan</h4>
                                        <p>Pembuat dokumen memilih pejabat approver. Stok tangki timbun baru bertambah setelah disetujui, lengkap dengan Berita Acara Penerimaan (PDF).</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. MUTASI TANGKI & ISI ULANG FUEL TRUCK -->
                            <div class="sub-block">
                                <div class="sub-block-title c-cyan">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                                    2. Mutasi Antar Tangki Timbun & Pengisian ke Mobile Fuel Truck
                                </div>
                                <div class="two-col">
                                    <div>
                                        <p style="font-size:.85rem;color:var(--text-secondary);line-height:1.7;margin-bottom:12px;">Operasional tambang dapat memiliki banyak titik penyimpanan (*Main Fuel Storage*, *Pit Station 1*, *Workshop Tank*):</p>
                                        <ul class="f-list">
                                            <li><div class="f-check" style="background:#ccfbf1;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Metode Mutasi Fleksibel:</strong> Opsi perpindahan langsung via pipa pompa (*Direct Pump*) atau diangkut menggunakan unit *Fuel Truck* mobile.</span></li>
                                            <li><div class="f-check" style="background:#ccfbf1;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Master Fuel Truck Terintegrasi:</strong> Unit armada Fuel Truck terhubung langsung ke Master Unit (Asset), kapasitas tangki, dan totalizer flowmeter dispenser.</span></li>
                                            <li><div class="f-check" style="background:#ccfbf1;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Akumulasi Flowmeter Monoton:</strong> Totalizer meteran tangki terus bertambah secara akumulatif baik saat BBM masuk maupun keluar.</span></li>
                                        </ul>
                                    </div>
                                    <div class="i-card bg-cyan-s" style="margin-top:0;">
                                        <h4 class="c-cyan" style="margin-bottom:8px;">⚖️ Otomatisasi Kartu Stok Dua Arah</h4>
                                        <p style="color:#155e75;font-size:.82rem;line-height:1.6;">Saat pengisian Fuel Truck disimpan, sistem otomatis memotong stok tangki sumber, menambah stok pada armada Fuel Truck, dan mencatat log mutasi lengkap dengan nomor referensi transaksi.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. DISTRIBUSI UNIT BERBASIS SHIFT -->
                            <div class="sub-block">
                                <div class="sub-block-title c-green">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                                    3. Sesi Shift Distribusi Lapangan & Pencatatan Unit (HM/KM)
                                </div>
                                <div class="step-flow">
                                    <div class="step-item">
                                        <div class="step-num" style="background:linear-gradient(135deg,#0284c7,#0369a1);">A</div>
                                        <div class="step-body">
                                            <h4>Buka Sesi Shift Distribusi (Open)</h4>
                                            <p>Fuelman memilih unit Fuel Truck, tanggal, shift kerja (Shift 1 / Shift 2), dan mencatat <strong>Totalizer Awal Shift</strong> pada nozzle dispenser.</p>
                                        </div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-num" style="background:linear-gradient(135deg,#0284c7,#0369a1);">B</div>
                                        <div class="step-body">
                                            <h4>Input Pengisian Setiap Unit Alat Berat</h4>
                                            <p>Setiap pengisian unit alat tambang dicatat rinci: <strong>Nomor Unit</strong> (Excavator/Hauler), <strong>Reading Meter</strong> (Hours Meter / Kilometer terpadu), <strong>Nama Operator Unit</strong>, <strong>Jam Pengisian</strong>, <strong>Lokasi Pit</strong>, dan <strong>Volume Liter</strong>.</p>
                                        </div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-num" style="background:linear-gradient(135deg,#0284c7,#0369a1);">C</div>
                                        <div class="step-body">
                                            <h4>Tutup Shift & Rekonsiliasi Variance Flowmeter</h4>
                                            <p>Di akhir shift, Fuelman menginput <strong>Totalizer Akhir</strong>. Sistem otomatis menghitung <em>Delta Flowmeter</em> vs <em>Total Liter Terdistribusi</em>, menghitung selisih (<em>Variance</em>), memotong stok armada Fuel Truck, dan men-generate Lembar Kontrol Distribusi Shift (PDF).</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. FITUR SUPER ADMIN ROLLBACK & TABLER MODAL -->
                            <div class="sub-block">
                                <div class="sub-block-title c-red">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9,12 11,14 15,10"/></svg>
                                    4. Otoritas Super Admin: Rollback Transaksi & Tabler UI Modal Bebas Popup
                                </div>
                                <div class="two-col">
                                    <div>
                                        <p style="font-size:.85rem;color:var(--text-secondary);line-height:1.7;margin-bottom:12px;">Untuk menjamin fleksibilitas operasional jika terjadi salah input tanpa merusak integritas stok:</p>
                                        <ul class="f-list">
                                            <li><div class="f-check" style="background:#fecaca;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Rollback Inbound & Mutasi:</strong> Membatalkan penerimaan atau transfer, otomatis mengembalikan saldo stok tangki, dan membersihkan seluruh log audit trail.</span></li>
                                            <li><div class="f-check" style="background:#fecaca;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="3"><polyline points="20,6 9,17 4,12"/></svg></div><span><strong>Reopen Shift Distribusi:</strong> Buka kembali shift yang sudah ditutup untuk revisi pengisian unit dengan pengembalian stok sementara ke Fuel Truck.</span></li>
                                        </ul>
                                    </div>
                                    <div class="i-card bg-red-s" style="margin-top:0;">
                                        <h4 class="c-red" style="margin-bottom:8px;">🎨 Tabler UI Confirmation Dialog</h4>
                                        <p style="color:#991b1b;font-size:.82rem;line-height:1.6;">Seluruh aksi konfirmasi kritis menggunakan modal dialog Tabler UI asli yang modern dengan badge status warna (*Danger / Warning / Success*), bebas dari pop-up alert bawaan browser (`window.confirm`).</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 5. STANDARISASI KOP SURAT TERPUSAT -->
                            <div class="sub-block">
                                <div class="sub-block-title c-purple">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                                    5. Standarisasi Dokumen PDF & Kop Surat Terpusat (`/settings`)
                                </div>
                                <p style="font-size:.85rem;color:var(--text-secondary);line-height:1.7;">Seluruh cetakan dokumen resmi FMS (Berita Acara Penerimaan, Lembar Kontrol Distribusi Shift, Berita Acara Flowmeter, Laporan Posisi Stok, dan Rekapitulasi Burn Rate) menggunakan template Kop Surat korporat terpusat yang otomatis membaca Logo Perusahaan (Base64), Nama Perusahaan, dan Alamat Site dari menu <code>/settings</code>.</p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ARCHITECTURE -->
                <div id="architecture" class="g-section">
                    <div class="s-card">
                        <div class="s-card-head" style="--sc:#10b981;">
                            <div class="s-title-row">
                                <div class="s-icon-wrap" style="background:#ecfdf5;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><rect x="2" y="2" width="6" height="6" rx="1"/><rect x="16" y="2" width="6" height="6" rx="1"/><rect x="9" y="9" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="16" y="16" width="6" height="6" rx="1"/></svg></div>
                                <div><div class="s-eyebrow c-green">Technology Stack</div><h2 class="s-h2">Arsitektur & Keamanan Enterprise</h2></div>
                            </div>
                            <p class="s-lead">Dibangun dengan fondasi kokoh menggunakan teknologi dan standar keamanan terkini untuk keandalan, privasi, dan perlindungan data maksimal.</p>
                        </div>
                        <div class="s-card-body">
                            <h4 style="font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;margin-bottom:14px;color:var(--text-primary);">Technology Stack</h4>
                            <div class="tech-grid">
                                <div class="tech-badge"><div class="tech-b-icon" style="background:#fef2f2;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div class="tech-b-info"><strong>Laravel (PHP)</strong><span>MVC Backend Framework</span></div></div>
                                <div class="tech-badge"><div class="tech-b-icon" style="background:#eff6ff;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></div><div class="tech-b-info"><strong>Tabler UI</strong><span>Modern Dashboard Interface</span></div></div>
                                <div class="tech-badge"><div class="tech-b-icon" style="background:#f5f3ff;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div class="tech-b-info"><strong>DomPDF</strong><span>PDF Document Generator</span></div></div>
                                <div class="tech-badge"><div class="tech-b-icon" style="background:#ecfdf5;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/></svg></div><div class="tech-b-info"><strong>Laravel Echo</strong><span>Real-time Broadcasting</span></div></div>
                            </div>
                            <h4 style="font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;margin-bottom:14px;color:var(--text-primary);">Lapisan Keamanan</h4>
                            <div class="info-grid">
                                <div class="i-card bg-green-s"><div class="i-icon" style="background:#a7f3d030;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9,12 11,14 15,10"/></svg></div><h4>Anti-IDOR (Hashids)</h4><p>ID database disembunyikan di URL (ID '1' → 'jR3xY') mencegah akses data orang lain.</p></div>
                                <div class="i-card bg-green-s"><div class="i-icon" style="background:#a7f3d030;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div><h4>Spatie Audit Log</h4><p>Setiap aksi Tambah/Ubah/Hapus tercatat lengkap — transparansi dan akuntabilitas penuh.</p></div>
                                <div class="i-card bg-green-s"><div class="i-icon" style="background:#a7f3d030;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div><h4>Spatie RBAC</h4><p>Role-Based Access Control ketat — hanya Approver yang melihat tombol Approve.</p></div>
                                <div class="i-card bg-green-s"><div class="i-icon" style="background:#a7f3d030;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><h4>CSRF & XSS Protection</h4><p>Perlindungan bawaan Laravel terhadap kerentanan web CSRF, XSS, dan SQL Injection.</p></div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-inner">
        <div class="hero-badge" style="margin:0 auto 22px;display:inline-flex;">
            <div class="hero-badge-dot"></div>
            Siap Memulai?
        </div>
        <h2>Mulai Kelola Aset Pertambangan<br>Lebih Cerdas & Efisien</h2>
        <p>Bergabunglah dengan tim yang sudah menggunakan CMMS AISFAR untuk efisiensi operasional pemeliharaan armada alat berat pertambangan.</p>
        <div class="cta-btns">
            @auth
            <a href="{{ route('dashboard') }}" class="btn-cta btn-cta-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Buka Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" class="btn-cta btn-cta-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10,17 15,12 10,7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Login ke Sistem
            </a>
            <a href="{{ route('register') }}" class="btn-cta btn-cta-outline">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Daftar Akun Baru
            </a>
            @endauth
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="g-footer">
    <p>
        &copy; {{ date('Y') }} <strong>CMMS AISFAR</strong> — Platform Manajemen Pemeliharaan Aset Pertambangan &nbsp;·&nbsp;
        @auth
        <a href="{{ route('dashboard') }}">Dashboard</a> &nbsp;·&nbsp;
        @else
        <a href="{{ route('login') }}">Login</a> &nbsp;·&nbsp; <a href="{{ route('register') }}">Daftar Akun</a> &nbsp;·&nbsp;
        @endauth
        <a href="{{ route('guide') }}">Dokumentasi</a>
    </p>
</footer>

<script>
const MODULE_LIST = [
    { id: 'work-order', name: 'Work Order Management', category: 'Maintenance Core', color: '#2563eb', bg: '#eff6ff' },
    { id: 'toolroom', name: 'ToolRoom & Stok Part Inventory', category: 'Inventory & Logistik', color: '#f97316', bg: '#fff7ed' },
    { id: 'production', name: 'Laporan Produksi Harian', category: 'Fleet & Production', color: '#06b6d4', bg: '#ecfeff' },
    { id: 'master-units', name: 'Populasi Unit 360° History', category: 'Asset Intelligence', color: '#8b5cf6', bg: '#f5f3ff' },
    { id: 'kpi-iso', name: 'KPI Analysis & ISO 8601', category: 'Analytics & KPI', color: '#f59e0b', bg: '#fffbeb' },
    { id: 'hse', name: 'Keselamatan K3 (JSA, PTW, LOTO)', category: 'Safety & HSE', color: '#10b981', bg: '#ecfdf5' },
    { id: 'far', name: 'Failure Analysis Report (FAR)', category: 'Failure Analysis', color: '#ef4444', bg: '#fef2f2' },
    { id: 'jwo', name: 'Job Work Order & Vendor', category: 'Vendor Management', color: '#06b6d4', bg: '#ecfeff' },
    { id: 'budget', name: 'Budget & Cost Control', category: 'Cost Management', color: '#f59e0b', bg: '#fffbeb' },
    { id: 'pm', name: 'PM Template & Schedule', category: 'Preventive Maintenance', color: '#6366f1', bg: '#eef2ff' },
    { id: 'collaboration', name: 'Live Chat & Real-time', category: 'Collaboration', color: '#6366f1', bg: '#eef2ff' },
    { id: 'fuel-management', name: 'Fuel Management System (FMS)', category: 'Fuel & Energy Management', color: '#0284c7', bg: '#f0f9ff' },
    { id: 'architecture', name: 'Arsitektur Enterprise', category: 'Technology Stack', color: '#10b981', bg: '#ecfdf5' }
];

let currentModalIndex = 0;

function openModuleModal(id) {
    const idx = MODULE_LIST.findIndex(m => m.id === id);
    if (idx !== -1) {
        currentModalIndex = idx;
    } else {
        currentModalIndex = 0;
    }
    renderModalContent();
    const modal = document.getElementById('moduleModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function renderModalContent() {
    const mod = MODULE_LIST[currentModalIndex];
    if (!mod) return;

    // Header info
    document.getElementById('modalEyebrow').textContent = mod.category;
    document.getElementById('modalEyebrow').style.color = mod.color;
    document.getElementById('modalTitle').textContent = mod.name;
    document.getElementById('modalIndexIndicator').textContent = `${currentModalIndex + 1} / ${MODULE_LIST.length}`;

    // Icon
    const cardEl = document.querySelector(`.mod-card[onclick*="'${mod.id}'"]`);
    const iconWrap = document.getElementById('modalIcon');
    if (cardEl) {
        const svgEl = cardEl.querySelector('.mod-icon svg');
        if (svgEl) {
            iconWrap.innerHTML = svgEl.outerHTML;
            iconWrap.style.background = mod.bg;
        }
    }

    // Body content from target section
    const secEl = document.getElementById(mod.id);
    const modalBody = document.getElementById('modalBody');
    if (secEl) {
        const leadEl = secEl.querySelector('.s-lead');
        const bodyEl = secEl.querySelector('.s-card-body');
        let html = '';
        if (leadEl) {
            html += `<p style="font-size:.95rem;color:var(--text-secondary);line-height:1.75;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">${leadEl.innerHTML}</p>`;
        }
        if (bodyEl) {
            html += bodyEl.innerHTML;
        }
        modalBody.innerHTML = html;
        modalBody.scrollTop = 0;
    }
}

function nextModalModule() {
    currentModalIndex = (currentModalIndex + 1) % MODULE_LIST.length;
    renderModalContent();
}

function prevModalModule() {
    currentModalIndex = (currentModalIndex - 1 + MODULE_LIST.length) % MODULE_LIST.length;
    renderModalContent();
}

function closeModuleModal() {
    const modal = document.getElementById('moduleModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function closeModuleModalOnBackdrop(e) {
    if (e.target.id === 'moduleModal') {
        closeModuleModal();
    }
}

function jumpToFullDocFromModal() {
    const mod = MODULE_LIST[currentModalIndex];
    closeModuleModal();
    if (mod) {
        setTimeout(() => {
            activateSection(mod.id);
        }, 150);
    }
}

// Keyboard shortcuts for modal
window.addEventListener('keydown', (e) => {
    const modal = document.getElementById('moduleModal');
    if (modal && modal.classList.contains('active')) {
        if (e.key === 'Escape') {
            closeModuleModal();
        } else if (e.key === 'ArrowRight') {
            nextModalModule();
        } else if (e.key === 'ArrowLeft') {
            prevModalModule();
        }
    }
});

function activateSection(id) {
    document.querySelectorAll('.sb-link').forEach(l => l.classList.remove('active'));
    document.querySelectorAll('.g-section').forEach(s => s.classList.remove('active'));
    const section = document.getElementById(id);
    if (section) {
        section.classList.add('active');
        const btn = document.querySelector(`.sb-link[onclick*="'${id}'"]`);
        if (btn) btn.classList.add('active');
        const wrap = document.querySelector('.guide-section-wrap');
        if (wrap) setTimeout(() => wrap.scrollIntoView({ behavior: 'smooth', block: 'start' }), 40);
    }
}

window.addEventListener('scroll', () => {
    const nav = document.getElementById('topNav');
    nav.classList.toggle('scrolled', window.scrollY > 20);
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    document.getElementById('progressBar').style.width = (docHeight > 0 ? (scrollTop / docHeight) * 100 : 0) + '%';
});

const obs = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
        if (e.isIntersecting) {
            setTimeout(() => { e.target.style.opacity = '1'; e.target.style.transform = 'translateY(0)'; }, i * 55);
            obs.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });

document.querySelectorAll('.mod-card').forEach((c, i) => {
    c.style.cssText += 'opacity:0;transform:translateY(22px);transition:opacity .5s ease,transform .5s ease,box-shadow .3s,border-color .3s;';
    obs.observe(c);
});
</script>
</body>
</html>