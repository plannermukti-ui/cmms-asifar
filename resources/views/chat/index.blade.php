@extends('layouts.tabler')

@section('title', 'Live Chat - CMMS Aisfar')

@section('content')
<div class="page-header d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <h2 class="page-title">Pesan Instan</h2>
      <div class="text-secondary mt-1">Kirim pesan, emoji, dan link referensi secara real-time kepada rekan kerja.</div>
    </div>
  </div>
</div>

<div class="card mt-3" style="height: calc(100vh - 240px); min-height: 520px;">
  <div class="row g-0 h-100">
    <!-- Sidebar kontak -->
    <div class="col-md-3 border-end d-flex flex-column">
      <div class="p-3 border-bottom bg-light-lt">
        <div class="input-icon">
          <span class="input-icon-addon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
          </span>
          <input type="text" id="searchUser" class="form-control" placeholder="Cari rekan kerja...">
        </div>
      </div>
      <div id="userList" class="overflow-auto flex-fill">
        @foreach ($users as $user)
        <a href="#" class="user-item d-flex align-items-center p-3 border-bottom text-decoration-none text-reset position-relative"
           data-user-id="{{ $user->id }}" data-user-name="{{ $user->nama_lengkap }}" data-user-avatar="{{ $user->avatar_url }}">
           
          <div class="position-relative me-3">
            @if($user->avatar_url)
              <span class="avatar avatar-sm shadow-xs rounded-circle" style="background-image: url('{{ $user->avatar_url }}'); background-size: cover;"></span>
            @else
              <span class="avatar avatar-sm fw-bold text-white shadow-xs" style="background-color: hsl({{ crc32($user->email) % 360 }}, 60%, 45%);">
                {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
              </span>
            @endif
            @if($user->unread_count > 0)
              <span class="badge bg-red badge-notification badge-blink" id="unread-badge-{{ $user->id }}">{{ $user->unread_count }}</span>
            @endif
          </div>
          
          <div class="flex-fill text-truncate">
            <div class="fw-semibold d-flex justify-content-between align-items-center">
              <span class="text-truncate">{{ $user->nama_lengkap }}</span>
            </div>
            <div class="small text-secondary text-truncate">{{ $user->email }}</div>
          </div>
        </a>
        @endforeach
      </div>
    </div>

    <!-- Area chat -->
    <div class="col-md-9 d-flex flex-column">
      <div id="chatHeader" class="p-3 border-bottom d-flex align-items-center justify-content-between bg-white" style="display:none!important;">
        <div class="d-flex align-items-center">
          <span id="chatAvatar" class="avatar me-3 fw-bold bg-primary text-white"></span>
          <div>
            <div id="chatUserName" class="fw-bold fs-3"></div>
            <div class="small text-success d-flex align-items-center gap-1">
              <span class="badge bg-success p-1 rounded-circle"></span> Aktif
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a id="btnViewBio" href="#" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1" title="Lihat Profil & Bio Pengguna">
            <svg class="icon icon-tabler icon-tabler-id" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
            <span>Lihat Bio</span>
          </a>
          <button type="button" id="btnClearChat" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" title="Bersihkan riwayat chat ini">
            <svg class="icon icon-tabler icon-tabler-trash" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
            <span>Bersihkan Chat</span>
          </button>
        </div>
      </div>

      <div id="chatMessages" class="flex-fill overflow-auto p-3" style="background: #f4f6f8;">
        <div class="text-center text-muted my-5 py-5">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 text-primary opacity-50" width="64" height="64" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M9 18h-3a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-3l-3 3l-3 -3z" /></svg>
          <h4 class="fw-bold text-dark mb-1">Ruang Percakapan</h4>
          <p class="small text-muted">Pilih rekan kerja di sebelah kiri untuk mulai berkirim pesan, emoji, atau link Work Order.</p>
        </div>
      </div>

      <!-- Area Input Chat yang Lebih Powerful -->
      <div id="chatInputArea" class="p-3 border-top bg-white" style="display:none;">
        
        <!-- Toolbar Emoji Cepat & Link Tools -->
        <div class="d-flex align-items-center justify-content-end mb-2 pb-2 border-bottom">
          <!-- Tools tambahan (Link & Template) -->
          <div class="d-flex align-items-center gap-2">
            <!-- Dropdown Template Pesan Cepat -->
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-2" type="button" data-bs-toggle="dropdown">
                📋 Pesan Cepat
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item btn-template" href="#" data-text="🛠️ Work Order telah selesai dikerjakan, mohon dicek.">🛠️ WO Selesai</a>
                <a class="dropdown-item btn-template" href="#" data-text="⚠️ Unit butuh inspeksi & perbaikan segera.">⚠️ Unit Breakdown Urgent</a>
                <a class="dropdown-item btn-template" href="#" data-text="📦 Sparepart telah siap di toolroom/gudang.">📦 Sparepart Ready</a>
                <a class="dropdown-item btn-template" href="#" data-text="📋 Mohon approval untuk Work Order ini.">📋 Request Approval WO</a>
                <a class="dropdown-item btn-template" href="#" data-text="👍 Siaapp, dimengerti!">👍 Siaapp, Dimengerti</a>
              </div>
            </div>

            <!-- Tombol Lampirkan Dokumen (WO/JWO) -->
            <button type="button" class="btn btn-sm btn-outline-info px-2" data-bs-toggle="modal" data-bs-target="#modal-attach-doc">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-search me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" /><path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" /><path d="M18.5 19.5l2.5 2.5" /></svg>
              Lampirkan Dokumen
            </button>

            <!-- Tombol Sisipkan Link -->
            <button type="button" class="btn btn-sm btn-outline-primary px-2" data-bs-toggle="modal" data-bs-target="#modal-insert-link">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.463a5 5 0 0 1 7.071 7.071l-.534 .534" /><path d="M13 18l-.397 .534a5 5 0 0 1 -7.071 -7.071l.534 -.534" /></svg>
              Sisipkan Link
            </button>
          </div>
        </div>

        <!-- Form Input Utama -->
        <form id="chatForm" class="d-flex gap-2 position-relative">
          <!-- Popover Picker Full Emoji -->
          <div class="dropdown">
            <button type="button" class="btn btn-light btn-icon" data-bs-toggle="dropdown" title="Pilih Emoji Full">
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

          <textarea id="messageInput" class="form-control" placeholder="Ketik pesan... (Gunakan [Judul](URL) atau langsung ketik link)" rows="1" style="resize:none; max-height: 100px;"></textarea>
          
          <button type="submit" class="btn btn-primary px-3 d-flex align-items-center gap-1">
            <span>Kirim</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
          </button>
        </form>
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
let pollingInterval = null;

// Select user
document.querySelectorAll('.user-item').forEach(function(el) {
  el.addEventListener('click', function(e) {
    e.preventDefault();
    document.querySelectorAll('.user-item').forEach(i => i.classList.remove('bg-blue-lt', 'border-primary'));
    this.classList.add('bg-blue-lt', 'border-primary');

    selectedUserId = this.dataset.userId;
    selectedUserName = this.dataset.userName;
    const selectedUserAvatar = this.dataset.userAvatar;

    document.getElementById('btnViewBio').href = `/profile/${selectedUserId}`;
    document.getElementById('chatHeader').style.display = 'flex';
    document.getElementById('chatUserName').textContent = selectedUserName;

    const avatarEl = document.getElementById('chatAvatar');
    if (selectedUserAvatar) {
      avatarEl.style.backgroundImage = `url('${selectedUserAvatar}')`;
      avatarEl.style.backgroundSize = 'cover';
      avatarEl.textContent = '';
    } else {
      avatarEl.style.backgroundImage = 'none';
      avatarEl.textContent = selectedUserName.charAt(0).toUpperCase();
    }

    document.getElementById('chatInputArea').style.display = 'block';

    loadMessages();

    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(loadMessages, 3000);
  });
});

// Format Message Body: Escape HTML, Parse Markdown Links & Plain URLs safely using Placeholders
function formatMessageBody(raw, isMine) {
  if (!raw) return '';

  // 1. Sanitize HTML
  let str = raw
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");

  const placeholders = [];

  // 2. Parse Markdown Links: [Title](URL)
  const markdownLinkRegex = /\[([^\]]+)\]\((https?:\/\/[^\s\)]+|\/[^\s\)]+)\)/g;
  str = str.replace(markdownLinkRegex, function(match, label, url) {
    const isJwo = label.toLowerCase().includes('jwo');
    
    // Choose icon
    let iconSvg = isJwo
      ? `<svg class="icon icon-tabler icon-tabler-tools text-purple" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21h4l13 -13a1.5 1.5 0 0 0 -4 -4l-13 13v4" /><path d="M14.5 5.5l4 4" /><path d="M12 8l-5 -5l-4 4l5 5" /><path d="M7 8l-1.5 1.5" /><path d="M16 12l5 5l-4 4l-5 -5" /><path d="M16 17l-1.5 1.5" /></svg>`
      : `<svg class="icon icon-tabler icon-tabler-file-text text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>`;

    let cardHtml = '';
    if (isMine) {
      cardHtml = `
      <a href="${url}" target="_blank" rel="noopener noreferrer" class="d-block text-decoration-none my-2 p-2 px-3 rounded-3 bg-white text-dark shadow-sm border hover-shadow transition">
        <div class="d-flex align-items-center gap-3">
          <div class="avatar bg-blue-lt text-primary rounded-2 flex-shrink-0">
            ${iconSvg}
          </div>
          <div class="flex-fill overflow-hidden text-start">
            <div class="fw-bold text-dark text-truncate" style="font-size: 13px;">${label}</div>
            <div class="text-secondary" style="font-size: 11px;">Lampiran Dokumen &bull; Klik untuk Detail</div>
          </div>
          <div class="text-muted flex-shrink-0">
            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
          </div>
        </div>
      </a>`;
    } else {
      cardHtml = `
      <a href="${url}" target="_blank" rel="noopener noreferrer" class="d-block text-decoration-none my-2 p-2 px-3 rounded-3 bg-white text-dark shadow-sm border border-primary-subtle hover-shadow transition">
        <div class="d-flex align-items-center gap-3">
          <div class="avatar bg-primary-lt text-primary rounded-2 flex-shrink-0">
            ${iconSvg}
          </div>
          <div class="flex-fill overflow-hidden text-start">
            <div class="fw-bold text-primary text-truncate" style="font-size: 13px;">${label}</div>
            <div class="text-muted" style="font-size: 11px;">Lampiran Dokumen &bull; Klik untuk Detail</div>
          </div>
          <div class="text-primary flex-shrink-0">
            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
          </div>
        </div>
      </a>`;
    }

    const placeholder = `___PLACEHOLDER_MD_LINK_${placeholders.length}___`;
    placeholders.push({ placeholder, html: cardHtml });
    return placeholder;
  });

  // 3. Parse Plain URLs (http://, https://, www.)
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

  // 4. Preserve Newlines
  str = str.replace(/\n/g, '<br>');

  // 5. Restore Placeholders safely
  placeholders.forEach(item => {
    str = str.replace(item.placeholder, item.html);
  });

  return str;
}

function loadMessages() {
  if (!selectedUserId) return;
  fetch(`/chat/messages/${selectedUserId}`, {
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  })
  .then(r => r.json())
  .then(messages => {
    const container = document.getElementById('chatMessages');
    const isAtBottom = (container.scrollHeight - container.clientHeight <= container.scrollTop + 50);

    container.innerHTML = '';
    messages.forEach(function(msg) {
      const isMine = msg.sender_id == AUTH_ID;
      const isRead = msg.read_at !== null;
      const tickIcon = isMine 
        ? (isRead ? '<span class="text-info ms-1 fw-bold" style="font-size: 11px;">✓✓</span>' : '<span class="text-white-50 ms-1" style="font-size: 11px;">✓</span>') 
        : '';
      
      const formattedBody = formatMessageBody(msg.body, isMine);

      const bubble = document.createElement('div');
      bubble.className = `d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'} mb-3`;
      bubble.innerHTML = `
        <div class="rounded-3 p-3 shadow-xs ${isMine ? 'bg-primary text-white' : 'bg-white border text-dark'}" style="max-width:75%; word-wrap:break-word;">
          ${!isMine ? `<div class="small fw-bold text-azure mb-1">${msg.sender?.nama_lengkap ?? 'User'}</div>` : ''}
          <div class="fs-3" style="line-height: 1.5;">${formattedBody}</div>
          <div class="small mt-1 d-flex justify-content-end align-items-center ${isMine ? 'text-white-50' : 'text-muted'}" style="font-size: 11px;">
            ${new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
            ${tickIcon}
          </div>
        </div>`;
      container.appendChild(bubble);
    });

    // Auto scroll down if user was at bottom or initially loaded
    container.scrollTop = container.scrollHeight;
  });
}

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

  // Reset & Hide Modal
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
              
              // Hide modal
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

// Send message
document.getElementById('chatForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const body = document.getElementById('messageInput').value.trim();
  if (!body || !selectedUserId) return;

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

  fetch('/chat/send', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({receiver_id: selectedUserId, body: body})
  })
  .then(r => r.json())
  .then(function() {
    document.getElementById('messageInput').value = '';
    loadMessages();
  });
});

// Support Shift+Enter for newline, Enter for submit
document.getElementById('messageInput').addEventListener('keydown', function(e) {
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault();
    document.getElementById('chatForm').dispatchEvent(new Event('submit'));
  }
});

// Search user
document.getElementById('searchUser').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.user-item').forEach(function(el) {
    const name = el.dataset.userName.toLowerCase();
    el.style.display = name.includes(q) ? '' : 'none';
  });
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
  const targetItem = document.querySelector(`.user-item[data-user-id="${autoUserId}"]`);
  if (targetItem) {
    targetItem.click();
  }
}
</script>
@endsection

