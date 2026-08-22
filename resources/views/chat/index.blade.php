@extends('layouts.tabler')

@section('title', 'Pesan Instan - CMMS Aisfar')

@section('content')
<style>
  /* ── Chat Container Shell ── */
  .chat-shell {
    height: calc(100vh - 175px);
    min-height: 480px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.08);
  }
  .chat-contact-pane {
    min-width: 320px;
    background: #ffffff;
    min-height: 0;
    border-right: 1px solid rgba(0, 0, 0, 0.08);
  }
  .chat-conversation-pane {
    min-width: 0;
    min-height: 0;
    background: #f8fafc;
  }
  #chatMessages {
    min-height: 0;
    background: #f1f5f9 !important;
    flex: 1;
    overflow-y: auto;
  }
  
  /* ── Site Grouping in Contact List ── */
  .chat-site-header {
    background: #f8fafc;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
  }
  .user-item {
    transition: all .15s ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.04);
  }
  .user-item:hover {
    background: rgba(32, 107, 196, 0.04) !important;
  }
  .user-item.active-chat {
    background: rgba(32, 107, 196, 0.08) !important;
    border-left: 4px solid #206bc4 !important;
  }

  /* ── Message Bubbles ── */
  .message-bubble {
    max-width: min(78%, 700px);
    overflow-wrap: anywhere;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }
  .message-bubble-mine {
    background: linear-gradient(135deg, #206bc4, #1a569d) !important;
    color: #ffffff !important;
    border-radius: 12px 12px 2px 12px;
  }
  .message-bubble-other {
    background: #ffffff !important;
    color: #1e293b !important;
    border: 1px solid #e2e8f0;
    border-radius: 12px 12px 12px 2px;
  }

  /* ── Image & Document Attachments ── */
  .chat-attachment-img {
    max-width: 100%;
    max-height: 280px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .chat-attachment-img:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  .chat-doc-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
  }
  .chat-doc-card-mine {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
  }
  .chat-doc-card-mine:hover {
    background: rgba(255, 255, 255, 0.25);
  }
  .chat-doc-card-other {
    background: #f8fafc;
    color: #1e293b !important;
    border: 1px solid #cbd5e1;
  }
  .chat-doc-card-other:hover {
    background: #f1f5f9;
  }
  .chat-attachment-preview {
    background: #f8fafc;
    border-top: 1px dashed #cbd5e1;
    border-bottom: 1px dashed #cbd5e1;
  }

  /* ── Responsive Mobile ── */
  @media (max-width: 767.98px) {
    .chat-shell { height: calc(100vh - 140px); min-height: 380px; }
    .chat-contact-pane { height: 100%; min-width: 0; border-right: 0; border-bottom: 0; }
    .chat-conversation-pane { display: none !important; height: 100%; }
    .message-bubble { max-width: 90%; }
    #chatHeader { padding: .65rem !important; }
    #chatHeader .btn span { display: none; }
    
    /* State saat chat dibuka di HP */
    .chat-shell.mobile-chat-active .chat-contact-pane { display: none !important; }
    .chat-shell.mobile-chat-active .chat-conversation-pane { display: flex !important; }
  }

  /* ── Dark Mode Harmonization ── */
  [data-bs-theme="dark"] .chat-shell {
    border-color: rgba(255, 255, 255, 0.08);
  }
  [data-bs-theme="dark"] .chat-contact-pane {
    background: #182234;
    border-color: rgba(255, 255, 255, 0.08);
  }
  [data-bs-theme="dark"] .chat-site-header {
    background: #131c2c;
    color: #94a3b8;
    border-color: rgba(255, 255, 255, 0.06);
  }
  [data-bs-theme="dark"] .user-item {
    border-color: rgba(255, 255, 255, 0.05);
  }
  [data-bs-theme="dark"] .user-item:hover {
    background: rgba(255, 255, 255, 0.04) !important;
  }
  [data-bs-theme="dark"] .user-item.active-chat {
    background: rgba(32, 107, 196, 0.2) !important;
    border-left-color: #206bc4 !important;
  }
  [data-bs-theme="dark"] .chat-conversation-pane {
    background: #0f172a;
  }
  [data-bs-theme="dark"] #chatHeader {
    background: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] #chatUserName {
    color: #f8fafc !important;
  }
  [data-bs-theme="dark"] #chatMessages {
    background: #0b1320 !important;
  }
  [data-bs-theme="dark"] .message-bubble-other {
    background: #1e293b !important;
    color: #f8fafc !important;
    border-color: rgba(255, 255, 255, 0.08);
  }
  [data-bs-theme="dark"] #chatInputArea {
    background: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] #messageInput {
    background: #131c2c !important;
    color: #f8fafc !important;
    border-color: rgba(255, 255, 255, 0.12) !important;
  }
  [data-bs-theme="dark"] #messageInput::placeholder {
    color: #64748b !important;
  }
  [data-bs-theme="dark"] .chat-attachment-preview {
    background: #131c2c;
    border-color: rgba(255, 255, 255, 0.1);
  }
  [data-bs-theme="dark"] .chat-doc-card-other {
    background: #131c2c;
    color: #f8fafc !important;
    border-color: rgba(255, 255, 255, 0.1);
  }
  [data-bs-theme="dark"] .chat-doc-card-other:hover {
    background: #1a2538;
  }
  [data-bs-theme="dark"] #searchUser {
    background: #131c2c;
    border-color: rgba(255, 255, 255, 0.1);
    color: #f8fafc;
  }
</style>

<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary icon-tabler icon-tabler-messages" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" /><path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" /></svg>
        Pesan Instan (Live Chat)
      </h2>
      <div class="text-secondary mt-1">Kirim pesan, gambar, dokumen, emoji, dan tautan referensi secara real-time kepada rekan kerja.</div>
    </div>
  </div>
</div>

<div class="card mt-2 chat-shell shadow-sm">
  <div class="row g-0 flex-fill flex-column flex-md-row" style="min-height: 0; flex-wrap: nowrap;">
    
    <!-- ── Sidebar Kontak (Grouped by Site) ── -->
    <div class="col-md-4 col-lg-3 d-flex flex-column chat-contact-pane">
      <div class="p-3 border-bottom">
        <div class="input-icon">
          <span class="input-icon-addon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
          </span>
          <input type="text" id="searchUser" class="form-control form-control-sm" placeholder="Cari rekan kerja / site...">
        </div>
      </div>

      <div id="userList" class="overflow-auto flex-fill">
        @forelse ($groupedUsers as $siteName => $siteUsers)
          <div class="chat-site-group" data-site="{{ strtolower($siteName) }}">
            <!-- Header Kategori Site -->
            <div class="chat-site-header px-3 py-1.5 text-uppercase">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin text-primary me-1" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="11" r="3"/><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/></svg>
              <span>{{ $siteName }}</span>
              <span class="badge bg-secondary-lt ms-auto" style="font-size: 10px;">{{ $siteUsers->count() }}</span>
            </div>

            <!-- List User dalam Site -->
            @foreach ($siteUsers as $user)
              <a href="#" class="user-item d-flex align-items-center p-2.5 px-3 text-decoration-none text-reset position-relative"
                 data-user-id="{{ $user->id }}" 
                 data-user-name="{{ $user->nama_lengkap }}" 
                 data-user-avatar="{{ $user->avatar_url }}"
                 data-user-site="{{ $user->site_name }}"
                 data-user-code="{{ $user->site_code }}"
                 id="user-item-{{ $user->id }}">
                 
                <div class="position-relative me-2.5 flex-shrink-0">
                  @if($user->avatar_url)
                    <span class="avatar avatar-sm shadow-xs rounded-circle" style="background-image: url('{{ $user->avatar_url }}'); background-size: cover;"></span>
                  @else
                    <span class="avatar avatar-sm fw-bold text-white shadow-xs rounded-circle" style="background-color: hsl({{ abs(crc32($user->email)) % 360 }}, 60%, 45%);">
                      {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                    </span>
                  @endif
                  @if($user->unread_count > 0)
                    <span class="badge bg-red badge-notification badge-blink" id="unread-badge-{{ $user->id }}">{{ $user->unread_count }}</span>
                  @endif
                </div>
                
                <div class="flex-fill overflow-hidden">
                  <div class="fw-semibold d-flex justify-content-between align-items-center text-truncate">
                    <span class="text-truncate" style="font-size: 13px;">{{ $user->nama_lengkap }}</span>
                    <span class="badge bg-blue-lt text-primary ms-1 flex-shrink-0" style="font-size: 9px;">{{ $user->site_code }}</span>
                  </div>
                  <div class="small text-muted text-truncate" style="font-size: 11px;">{{ $user->email }}</div>
                </div>
              </a>
            @endforeach
          </div>
        @empty
          <div class="text-center text-muted py-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 opacity-50" width="32" height="32" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>Belum ada rekan kerja terdaftar.</div>
          </div>
        @endforelse
      </div>
    </div>

    <!-- ── Area Percakapan ── -->
    <div class="col-md-8 col-lg-9 d-flex flex-column chat-conversation-pane">
      <!-- Chat Header -->
      <div id="chatHeader" class="p-3 border-bottom d-none align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <!-- Back button (Mobile only) -->
          <button type="button" class="btn btn-sm btn-icon btn-ghost-secondary d-md-none me-1" id="btnBackToContacts" style="border: none; padding: 0;">
            <svg class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
          </button>
          
          <span id="chatAvatar" class="avatar avatar-md fw-bold bg-primary text-white shadow-xs rounded-circle"></span>
          <div>
            <div id="chatUserName" class="fw-bold fs-3"></div>
            <div class="small text-muted d-flex align-items-center gap-1.5" style="font-size: 11px;">
              <span class="badge bg-success p-1 rounded-circle" style="width: 7px; height: 7px;"></span>
              <span id="chatUserSite" class="fw-semibold text-primary"></span>
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a id="btnViewBio" href="#" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1 shadow-none" title="Lihat Profil Pengguna">
            <svg class="icon icon-tabler icon-tabler-id" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
            <span>Lihat Bio</span>
          </a>
          <button type="button" id="btnClearChat" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 shadow-none" title="Bersihkan riwayat chat ini">
            <svg class="icon icon-tabler icon-tabler-trash" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
            <span>Bersihkan Chat</span>
          </button>
        </div>
      </div>

      <!-- Message History List -->
      <div id="chatMessages" class="flex-fill overflow-auto p-3 p-lg-4">
        <div class="text-center text-muted my-5 py-5">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 text-primary opacity-50" width="54" height="54" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M9 18h-3a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-3l-3 3l-3 -3z" /></svg>
          <h4 class="fw-bold mb-1">Ruang Percakapan</h4>
          <p class="small text-muted">Pilih rekan kerja di sebelah kiri untuk mulai berkirim pesan, gambar, dokumen, atau link referensi.</p>
        </div>
      </div>

      <!-- ── Area Input Chat ── -->
      <div id="chatInputArea" class="p-3 border-top" style="display:none;">
        
        <!-- Attachment Live Preview Box -->
        <div id="chatAttachmentPreviewBox" class="chat-attachment-preview p-2 px-3 mb-2 rounded-3 d-none align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2 overflow-hidden">
            <div id="previewThumbnailContainer"></div>
            <div class="overflow-hidden">
              <div id="previewFileName" class="fw-bold text-truncate small" style="max-width: 320px;"></div>
              <div id="previewFileSize" class="text-muted" style="font-size: 10px;"></div>
            </div>
          </div>
          <button type="button" id="btnRemoveAttachment" class="btn btn-sm btn-outline-danger btn-icon shadow-none" title="Hapus Lampiran">
            <svg class="icon m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>
          </button>
        </div>

        <!-- Toolbar Media & Tools -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-2 pb-2 border-bottom">
          <!-- Tombol Upload Media (Gambar & Dokumen) -->
          <div class="d-flex align-items-center gap-1.5">
            <!-- Hidden File Inputs -->
            <input type="file" id="chatImageInput" class="d-none" accept="image/jpeg,image/png,image/gif,image/webp,image/svg+xml">
            <input type="file" id="chatDocInput" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt,.csv">

            <!-- Kirim Gambar -->
            <button type="button" class="btn btn-sm btn-outline-primary px-2 shadow-none" onclick="document.getElementById('chatImageInput').click()" title="Kirim Gambar / Foto (Max 20MB)">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo me-1 text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="8" x2="15.01" y2="8" /><rect x="4" y="4" width="16" height="16" rx="3" /><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" /><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" /></svg>
              Gambar
            </button>

            <!-- Kirim Dokumen -->
            <button type="button" class="btn btn-sm btn-outline-info px-2 shadow-none" onclick="document.getElementById('chatDocInput').click()" title="Kirim Dokumen (PDF, Excel, Word, ZIP max 20MB)">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-paperclip me-1 text-info" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" /></svg>
              Dokumen
            </button>
          </div>

          <!-- Tools tambahan (Link & Template) -->
          <div class="d-flex align-items-center gap-1.5">
            <!-- Dropdown Template Pesan Cepat -->
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-2 shadow-none" type="button" data-bs-toggle="dropdown">
                📋 Pesan Cepat
              </button>
              <div class="dropdown-menu dropdown-menu-end shadow-sm">
                <a class="dropdown-item btn-template" href="#" data-text="🛠️ Work Order telah selesai dikerjakan, mohon dicek.">🛠️ WO Selesai</a>
                <a class="dropdown-item btn-template" href="#" data-text="⚠️ Unit butuh inspeksi & perbaikan segera.">⚠️ Unit Breakdown Urgent</a>
                <a class="dropdown-item btn-template" href="#" data-text="📦 Sparepart telah siap di toolroom/gudang.">📦 Sparepart Ready</a>
                <a class="dropdown-item btn-template" href="#" data-text="📋 Mohon approval untuk Work Order ini.">📋 Request Approval WO</a>
                <a class="dropdown-item btn-template" href="#" data-text="👍 Siaapp, dimengerti!">👍 Siaapp, Dimengerti</a>
              </div>
            </div>

            <!-- Tombol Lampirkan Referensi Dokumen (WO/JWO) -->
            <button type="button" class="btn btn-sm btn-outline-secondary px-2 shadow-none" data-bs-toggle="modal" data-bs-target="#modal-attach-doc" title="Cari referensi WO/JWO">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-search me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" /><path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" /><path d="M18.5 19.5l2.5 2.5" /></svg>
              Link WO/JWO
            </button>

            <!-- Tombol Sisipkan Link -->
            <button type="button" class="btn btn-sm btn-outline-secondary px-2 shadow-none" data-bs-toggle="modal" data-bs-target="#modal-insert-link" title="Sisipkan tautan link web">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>
              Link Web
            </button>
          </div>
        </div>

        <!-- Form Input Utama -->
        <form id="chatForm" data-no-loader class="d-flex gap-2 position-relative align-items-end">
          <!-- Popover Picker Full Emoji -->
          <div class="dropdown">
            <button type="button" class="btn btn-light btn-icon shadow-none" data-bs-toggle="dropdown" title="Pilih Emoji">
              😊
            </button>
            <div class="dropdown-menu p-3 shadow-lg" style="width: 280px; max-height: 220px; overflow-y: auto;">
              <div class="fw-bold small text-muted mb-2">Pilih Emoji:</div>
              <div class="d-flex flex-wrap gap-1" id="fullEmojiPicker">
                @foreach(['😀','😁','😂','😃','😄','😅','😆','😉','😊','😋','😎','😍','😘','🥰','😗','😙','😚','🙂','🤗','🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥','😮','🤐','😯','😪','😫','🥱','😴','😌','😛','😜','😝','🤤','😒','😓','😔','😕','🙃','🫠','🤑','😲','☹️','🙁','😖','😞','😟','😤','😢','😭','😦','😧','📁','📄','📅','⚙️','🚜','🚘','⛽','🔋','🔨','🛠️','🔧','🔩','📍','🚩','❤️','🔥','✨','💯','🎉','🚀'] as $emo)
                  <button type="button" class="btn btn-sm btn-ghost-light p-1 fs-3 btn-emoji" data-emoji="{{ $emo }}">{{ $emo }}</button>
                @endforeach
              </div>
            </div>
          </div>

          <textarea id="messageInput" class="form-control" placeholder="Ketik pesan... (Bisa Ctrl+V untuk paste gambar)" rows="1" style="resize:none; max-height: 100px;"></textarea>
          
          <button type="submit" id="btnSendMessage" class="btn btn-primary px-3 d-flex align-items-center gap-1 shadow-none">
            <span>Kirim</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<!-- Modal Image Lightbox Preview -->
<div class="modal modal-blur fade" id="modal-image-preview" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content bg-dark text-white border-0">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title" id="lightboxTitle">Preview Gambar</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-3">
        <img id="lightboxImage" src="" class="img-fluid rounded shadow" style="max-height: 75vh; object-fit: contain;">
      </div>
      <div class="modal-footer border-0 pt-0">
        <a id="lightboxDownloadBtn" href="#" download class="btn btn-primary btn-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
          Unduh Gambar
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Modal Sisipkan Link -->
<div class="modal modal-blur fade" id="modal-insert-link" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>
          Sisipkan Link / Tautan
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label required">Teks Tautan (Judul Link)</label>
          <input type="text" id="linkTitleInput" class="form-control" placeholder="Contoh: Lihat Detail Work Order #WO-002">
        </div>
        <div class="mb-3">
          <label class="form-label required">URL / Link Web</label>
          <input type="url" id="linkUrlInput" class="form-control" placeholder="http://127.0.0.1:8000/work-orders/1">
          <small class="form-hint">Dapat berupa link ke halaman Work Order, Unit, atau URL eksternal.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="btnConfirmInsertLink" class="btn btn-primary">Sisipkan ke Pesan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Lampirkan Dokumen -->
<div class="modal modal-blur fade" id="modal-attach-doc" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" /><path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" /><path d="M18.5 19.5l2.5 2.5" /></svg>
          Cari & Lampirkan Dokumen
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Cari Work Order / JWO</label>
          <div class="input-icon mb-3">
            <span class="input-icon-addon">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
            </span>
            <input type="text" id="docSearchInput" class="form-control" placeholder="Ketik No WO atau No JWO (min. 2 karakter)...">
          </div>
          <div id="docSearchResults" class="list-group list-group-flush border rounded" style="max-height: 250px; overflow-y: auto; display: none;">
            <!-- Hasil pencarian akan muncul di sini -->
          </div>
          <div id="docSearchLoading" class="text-center py-3 text-muted" style="display: none;">
            <div class="spinner-border spinner-border-sm text-primary" role="status"></div> Mencari...
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
const AUTH_ID = {{ auth()->id() }};
let selectedUserId = null;
let selectedUserName = '';
let selectedUserSite = '';
let pollingInterval = null;
let pendingAttachment = null; // Store pending file object for upload

// Select user
document.querySelectorAll('.user-item').forEach(function(el) {
  el.addEventListener('click', function(e) {
    e.preventDefault();
    selectUserConversation(this.dataset.userId, this.dataset.userName, this.dataset.userAvatar, this.dataset.userSite);
  });
});

function selectUserConversation(userId, userName, userAvatar, userSite) {
  document.querySelectorAll('.user-item').forEach(i => i.classList.remove('active-chat'));
  const targetItem = document.getElementById(`user-item-${userId}`);
  if (targetItem) targetItem.classList.add('active-chat');

  selectedUserId = userId;
  selectedUserName = userName;
  selectedUserSite = userSite || '';

  document.getElementById('btnViewBio').href = `/profile/${selectedUserId}`;
  document.getElementById('chatHeader').classList.remove('d-none');
  document.getElementById('chatHeader').classList.add('d-flex');
  document.getElementById('chatUserName').textContent = selectedUserName;
  document.getElementById('chatUserSite').textContent = selectedUserSite ? `Site: ${selectedUserSite}` : 'Head Office';

  const avatarEl = document.getElementById('chatAvatar');
  if (userAvatar) {
    avatarEl.style.backgroundImage = `url('${userAvatar}')`;
    avatarEl.style.backgroundSize = 'cover';
    avatarEl.textContent = '';
  } else {
    avatarEl.style.backgroundImage = 'none';
    avatarEl.textContent = selectedUserName.charAt(0).toUpperCase();
  }

  document.getElementById('chatInputArea').style.display = 'block';
  
  // Mobile UI toggle (SPA feel)
  document.querySelector('.chat-shell').classList.add('mobile-chat-active');

  // Reset attachment
  clearPendingAttachment();

  loadMessages();

  if (pollingInterval) clearInterval(pollingInterval);
  pollingInterval = setInterval(loadMessages, 3000);
}

// Back button handler for mobile
document.getElementById('btnBackToContacts')?.addEventListener('click', function() {
  document.querySelector('.chat-shell').classList.remove('mobile-chat-active');
  selectedUserId = null;
  if (pollingInterval) clearInterval(pollingInterval);
});

// ── Attachment Handling (Image & Doc) ──
const chatImageInput = document.getElementById('chatImageInput');
const chatDocInput = document.getElementById('chatDocInput');
const attachmentPreviewBox = document.getElementById('chatAttachmentPreviewBox');
const previewThumbnailContainer = document.getElementById('previewThumbnailContainer');
const previewFileName = document.getElementById('previewFileName');
const previewFileSize = document.getElementById('previewFileSize');
const btnRemoveAttachment = document.getElementById('btnRemoveAttachment');

function setPendingAttachment(file) {
  if (!file) return;
  if (file.size > 20 * 1024 * 1024) {
    alert('Ukuran berkas maksimal adalah 20 MB.');
    return;
  }

  pendingAttachment = file;
  previewFileName.textContent = file.name;
  previewFileSize.textContent = formatBytes(file.size);

  const isImg = file.type.startsWith('image/');
  if (isImg) {
    const reader = new FileReader();
    reader.onload = function(e) {
      previewThumbnailContainer.innerHTML = `<img src="${e.target.result}" class="rounded" style="width: 38px; height: 38px; object-fit: cover;">`;
    };
    reader.readAsDataURL(file);
  } else {
    previewThumbnailContainer.innerHTML = `<div class="avatar avatar-sm bg-blue-lt text-primary rounded"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg></div>`;
  }

  attachmentPreviewBox.classList.remove('d-none');
  attachmentPreviewBox.classList.add('d-flex');
  document.getElementById('messageInput').focus();
}

function clearPendingAttachment() {
  pendingAttachment = null;
  chatImageInput.value = '';
  chatDocInput.value = '';
  attachmentPreviewBox.classList.remove('d-flex');
  attachmentPreviewBox.classList.add('d-none');
  previewThumbnailContainer.innerHTML = '';
}

chatImageInput.addEventListener('change', function(e) {
  if (this.files && this.files[0]) setPendingAttachment(this.files[0]);
});

chatDocInput.addEventListener('change', function(e) {
  if (this.files && this.files[0]) setPendingAttachment(this.files[0]);
});

btnRemoveAttachment.addEventListener('click', function() {
  clearPendingAttachment();
});

// Paste image from clipboard support
document.getElementById('messageInput').addEventListener('paste', function(e) {
  const items = (e.clipboardData || e.originalEvent.clipboardData).items;
  for (let i = 0; i < items.length; i++) {
    if (items[i].type.indexOf('image') !== -1) {
      const blob = items[i].getAsFile();
      setPendingAttachment(blob);
      break;
    }
  }
});

function formatBytes(bytes) {
  if (!bytes) return '0 B';
  if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
  if (bytes >= 1024) return (bytes / 1024).toFixed(0) + ' KB';
  return bytes + ' B';
}

// Lightbox modal opener
window.previewImage = function(src, title) {
  document.getElementById('lightboxImage').src = src;
  document.getElementById('lightboxTitle').textContent = title || 'Preview Gambar';
  document.getElementById('lightboxDownloadBtn').href = src;
  const modal = new bootstrap.Modal(document.getElementById('modal-image-preview'));
  modal.show();
};

// ── Format Message Body ──
function formatMessageBody(raw, isMine) {
  if (!raw) return '';

  let str = raw
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");

  const placeholders = [];

  // Parse Markdown Links: [Title](URL)
  const markdownLinkRegex = /\[([^\]]+)\]\((https?:\/\/[^\s\)]+|\/[^\s\)]+)\)/g;
  str = str.replace(markdownLinkRegex, function(match, label, url) {
    const isJwo = label.toLowerCase().includes('jwo');
    
    let iconSvg = isJwo
      ? `<svg class="icon icon-tabler icon-tabler-tools text-purple" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21h4l13 -13a1.5 1.5 0 0 0 -4 -4l-13 13v4" /><path d="M14.5 5.5l4 4" /><path d="M12 8l-5 -5l-4 4l5 5" /><path d="M7 8l-1.5 1.5" /><path d="M16 12l5 5l-4 4l-5 -5" /><path d="M16 17l-1.5 1.5" /></svg>`
      : `<svg class="icon icon-tabler icon-tabler-file-text text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>`;

    let cardHtml = `
      <a href="${url}" target="_blank" rel="noopener noreferrer" class="d-block text-decoration-none my-1.5 p-2 px-3 rounded-3 ${isMine ? 'bg-white text-dark' : 'bg-surface text-body border'} shadow-xs hover-shadow transition">
        <div class="d-flex align-items-center gap-2.5">
          <div class="avatar avatar-sm bg-blue-lt text-primary rounded-2 flex-shrink-0">
            ${iconSvg}
          </div>
          <div class="flex-fill overflow-hidden text-start">
            <div class="fw-bold text-truncate" style="font-size: 13px;">${label}</div>
            <div class="text-muted" style="font-size: 10px;">Lampiran Dokumen &bull; Klik untuk Detail</div>
          </div>
          <div class="text-muted flex-shrink-0">
            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
          </div>
        </div>
      </a>`;

    const placeholder = `___PLACEHOLDER_MD_LINK_${placeholders.length}___`;
    placeholders.push({ placeholder, html: cardHtml });
    return placeholder;
  });

  // Parse Plain URLs
  const plainUrlRegex = /(https?:\/\/[^\s<]+|www\.[^\s<]+)/gi;
  str = str.replace(plainUrlRegex, function(url) {
    const targetUrl = url.startsWith('www.') ? 'http://' + url : url;
    const linkClass = isMine ? 'text-white text-decoration-underline fw-bold' : 'text-primary text-decoration-underline fw-bold';
    
    const linkHtml = `<a href="${targetUrl}" target="_blank" rel="noopener noreferrer" class="${linkClass}">
      <svg class="icon icon-tabler icon-tabler-link" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg> ${url}
    </a>`;

    const placeholder = `___PLACEHOLDER_PLAIN_LINK_${placeholders.length}___`;
    placeholders.push({ placeholder, html: linkHtml });
    return placeholder;
  });

  str = str.replace(/\n/g, '<br>');
  placeholders.forEach(item => { str = str.replace(item.placeholder, item.html); });
  return str;
}

// Notification chime
function playChatNotificationSound() {
  try {
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.type = 'sine';
    osc.frequency.setValueAtTime(587.33, audioCtx.currentTime);
    osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.08);
    gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
    osc.connect(gain);
    gain.connect(audioCtx.destination);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.3);
  } catch (e) {}
}

let lastMessagesJson = '';
let lastMessageIds = new Set();
let isFirstLoad = true;

// Load messages
function loadMessages() {
  if (!selectedUserId) return;
  fetch(`/chat/messages/${selectedUserId}`, {
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  })
  .then(r => {
    if (!r.ok) throw new Error('Network response was not ok: ' + r.statusText);
    return r.json();
  })
  .then(messages => {
    const currentJson = JSON.stringify(messages.map(m => ({ id: m.id, read: m.read_at !== null })));
    
    if (!isFirstLoad) {
      const hasNewIncoming = messages.some(m => m.sender_id != AUTH_ID && !lastMessageIds.has(m.id));
      if (hasNewIncoming) {
        playChatNotificationSound();
      }
    }
    
    lastMessageIds = new Set(messages.map(m => m.id));

    if (currentJson === lastMessagesJson && !isFirstLoad) {
      return;
    }
    lastMessagesJson = currentJson;
    isFirstLoad = false;

    const container = document.getElementById('chatMessages');
    const isAtBottom = (container.scrollHeight - container.clientHeight <= container.scrollTop + 70);

    container.innerHTML = '';
    messages.forEach(function(msg) {
      const isMine = msg.sender_id == AUTH_ID;
      const isRead = msg.read_at !== null;
      const tickIcon = isMine 
        ? (isRead ? '<span class="text-cyan ms-1 fw-bold" style="font-size: 11px;" title="Dibaca">✓✓</span>' : '<span class="text-white-50 ms-1" style="font-size: 11px;" title="Terkirim">✓</span>') 
        : '';
      
      const formattedBody = formatMessageBody(msg.body, isMine);

      // Render Attachment
      let attachmentHtml = '';
      if (msg.attachment_url) {
        if (msg.attachment_type === 'image') {
          attachmentHtml = `
            <div class="my-1.5">
              <img src="${msg.attachment_url}" class="chat-attachment-img" alt="${msg.attachment_name || 'Gambar'}" onclick="previewImage('${msg.attachment_url}', '${msg.attachment_name || 'Gambar'}')">
            </div>
          `;
        } else {
          const docClass = isMine ? 'chat-doc-card-mine' : 'chat-doc-card-other';
          attachmentHtml = `
            <div class="my-1.5">
              <a href="${msg.attachment_url}" target="_blank" download="${msg.attachment_name || 'Dokumen'}" class="chat-doc-card ${docClass}">
                <div class="avatar avatar-sm ${isMine ? 'bg-white-lt text-white' : 'bg-primary-lt text-primary'} rounded flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download" width="22" height="22" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg>
                </div>
                <div class="flex-fill overflow-hidden text-start">
                  <div class="fw-bold text-truncate" style="font-size: 13px;">${msg.attachment_name || 'Dokumen'}</div>
                  <div class="opacity-75" style="font-size: 10px;">${msg.formatted_attachment_size || ''} &bull; Klik untuk Unduh</div>
                </div>
                <div class="flex-shrink-0 opacity-75">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                </div>
              </a>
            </div>
          `;
        }
      }

      const bubbleClass = isMine ? 'message-bubble-mine' : 'message-bubble-other';
      const bubble = document.createElement('div');
      bubble.className = `d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'} mb-3`;
      bubble.innerHTML = `
        <div class="message-bubble ${bubbleClass} p-2.5 px-3">
          ${!isMine ? `<div class="small fw-bold text-azure mb-1">${msg.sender?.nama_lengkap ?? 'User'}</div>` : ''}
          ${attachmentHtml}
          ${formattedBody ? `<div class="message-text" style="font-size: 13.5px; line-height: 1.5;">${formattedBody}</div>` : ''}
          <div class="small mt-1 d-flex justify-content-end align-items-center ${isMine ? 'text-white-50' : 'text-muted'}" style="font-size: 10.5px;">
            ${new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
            ${tickIcon}
          </div>
        </div>`;
      container.appendChild(bubble);
    });

    if (isAtBottom || container.children.length <= 5) {
      container.scrollTop = container.scrollHeight;
    }
  })
  .catch(err => {
    console.error('Error loading messages:', err);
  });
}

// ── Send Message & Attachment ──
document.getElementById('chatForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const bodyInput = document.getElementById('messageInput');
  const body = bodyInput.value.trim();
  
  if (!body && !pendingAttachment) return;
  if (!selectedUserId) return;

  const btnSend = document.getElementById('btnSendMessage');
  btnSend.disabled = true;

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
  const formData = new FormData();
  formData.append('receiver_id', selectedUserId);
  if (body) formData.append('body', body);
  if (pendingAttachment) formData.append('file', pendingAttachment);

  fetch('/chat/send', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: formData
  })
  .then(r => {
    if (!r.ok) throw new Error('Send failed: ' + r.statusText);
    return r.json();
  })
  .then(function() {
    btnSend.disabled = false;
    bodyInput.value = '';
    clearPendingAttachment();
    loadMessages();
  })
  .catch(err => {
    btnSend.disabled = false;
    console.error('Error sending message:', err);
    alert('Gagal mengirim pesan / lampiran.');
  });
});

// Support Shift+Enter for newline, Enter for submit
document.getElementById('messageInput').addEventListener('keydown', function(e) {
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault();
    document.getElementById('chatForm').dispatchEvent(new Event('submit'));
  }
});

// Search user / Site filter
document.getElementById('searchUser').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  
  document.querySelectorAll('.chat-site-group').forEach(function(group) {
    let hasVisibleUser = false;
    group.querySelectorAll('.user-item').forEach(function(el) {
      const name = (el.dataset.userName || '').toLowerCase();
      const site = (el.dataset.userSite || '').toLowerCase();
      const code = (el.dataset.userCode || '').toLowerCase();
      
      const match = name.includes(q) || site.includes(q) || code.includes(q);
      el.style.display = match ? 'flex' : 'none';
      if (match) hasVisibleUser = true;
    });
    group.style.display = hasVisibleUser ? 'block' : 'none';
  });
});

// Live Contact List Updater
function updateContactList() {
  fetch('/chat/users', {
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  })
  .then(r => r.json())
  .then(users => {
    let totalUnread = 0;
    users.forEach(user => {
      totalUnread += user.unread_count || 0;
      const badge = document.getElementById(`unread-badge-${user.id}`);
      if (badge) {
        if (user.unread_count > 0 && user.id != selectedUserId) {
          badge.textContent = user.unread_count;
          badge.style.display = 'inline-block';
        } else {
          badge.style.display = 'none';
        }
      }
    });

    if (totalUnread > 0) {
      document.title = `(${totalUnread}) Pesan Baru - CMMS Aisfar`;
    } else {
      document.title = 'Pesan Instan - CMMS Aisfar';
    }
  })
  .catch(err => console.error('Error updating contacts:', err));
}
setInterval(updateContactList, 4000);

document.addEventListener('visibilitychange', function() {
  if (document.visibilityState === 'visible') {
    if (selectedUserId) loadMessages();
    updateContactList();
  }
});

// Handle Insert Emoji
document.addEventListener('click', function(e) {
  const emojiBtn = e.target.closest('.btn-emoji');
  if (emojiBtn) {
    const emo = emojiBtn.dataset.emoji;
    const input = document.getElementById('messageInput');
    const start = input.selectionStart;
    const end = input.selectionEnd;
    input.value = input.value.substring(0, start) + emo + input.value.substring(end);
    input.focus();
    input.selectionStart = input.selectionEnd = start + emo.length;
  }

  const tmplBtn = e.target.closest('.btn-template');
  if (tmplBtn) {
    e.preventDefault();
    const text = tmplBtn.dataset.text;
    const input = document.getElementById('messageInput');
    input.value = input.value ? input.value + ' ' + text : text;
    input.focus();
  }
});

// Handle Insert Link Confirmation
document.getElementById('btnConfirmInsertLink').addEventListener('click', function() {
  const title = document.getElementById('linkTitleInput').value.trim();
  const url = document.getElementById('linkUrlInput').value.trim();

  if (!url) {
    alert('URL Link wajib diisi!');
    return;
  }

  const formattedLink = title ? `[${title}](${url})` : url;
  const input = document.getElementById('messageInput');
  input.value = input.value ? input.value + ' ' + formattedLink : formattedLink;

  document.getElementById('linkTitleInput').value = '';
  document.getElementById('linkUrlInput').value = '';
  const modalEl = document.getElementById('modal-insert-link');
  const modal = bootstrap.Modal.getInstance(modalEl);
  if (modal) modal.hide();
  input.focus();
});

// Search Document Logic
let docSearchTimeout = null;
document.getElementById('docSearchInput').addEventListener('input', function() {
  const query = this.value.trim();
  const resultsContainer = document.getElementById('docSearchResults');
  const loadingIndicator = document.getElementById('docSearchLoading');
  
  if (docSearchTimeout) clearTimeout(docSearchTimeout);
  
  if (query.length < 2) {
    resultsContainer.style.display = 'none';
    loadingIndicator.style.display = 'none';
    return;
  }

  resultsContainer.style.display = 'none';
  loadingIndicator.style.display = 'block';

  docSearchTimeout = setTimeout(() => {
    fetch(`/chat/search-document?q=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        loadingIndicator.style.display = 'none';
        resultsContainer.innerHTML = '';
        
        if (data.length === 0) {
          resultsContainer.innerHTML = '<div class="list-group-item text-muted text-center py-3">Tidak ditemukan dokumen.</div>';
        } else {
          data.forEach(doc => {
            const icon = doc.type === 'Work Order' 
              ? `<span class="badge bg-primary-lt me-2">WO</span>`
              : `<span class="badge bg-purple-lt me-2">JWO</span>`;
              
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'list-group-item list-group-item-action py-2';
            item.innerHTML = `
              <div class="d-flex w-100 align-items-center">
                ${icon}
                <div class="flex-fill text-truncate">
                  <div class="fw-bold mb-1">${doc.title}</div>
                  <div class="small text-muted text-truncate">${doc.desc}</div>
                </div>
              </div>
            `;
            
            item.addEventListener('click', function(e) {
              e.preventDefault();
              const formattedLink = `[${doc.type}: ${doc.title}](${doc.url})`;
              const input = document.getElementById('messageInput');
              input.value = input.value ? input.value + ' ' + formattedLink : formattedLink;
              
              const modalEl = document.getElementById('modal-attach-doc');
              const modal = bootstrap.Modal.getInstance(modalEl);
              if (modal) modal.hide();
              
              document.getElementById('docSearchInput').value = '';
              resultsContainer.style.display = 'none';
              input.focus();
            });
            
            resultsContainer.appendChild(item);
          });
        }
        resultsContainer.style.display = 'block';
      })
      .catch(err => {
        loadingIndicator.style.display = 'none';
        console.error(err);
      });
  }, 500);
});

// Clear Chat Logic
document.getElementById('btnClearChat').addEventListener('click', function() {
  if (!selectedUserId) return;

  if (confirm(`Apakah Anda yakin ingin membersihkan seluruh riwayat percakapan dengan ${selectedUserName}?`)) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    fetch(`/chat/clear/${selectedUserId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(r => r.json())
    .then(data => {
      loadMessages();
    })
    .catch(err => console.error(err));
  }
});

// Auto select user if ?user=ID is in URL
const urlParams = new URLSearchParams(window.location.search);
const autoUserId = urlParams.get('user');
if (autoUserId) {
  const targetItem = document.getElementById(`user-item-${autoUserId}`);
  if (targetItem) {
    targetItem.click();
  }
}
</script>
@endsection
