@auth
<!-- Floating Messenger Chat Widget -->
<style>
  .chat-widget-btn {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0084ff, #0060df);
    color: white;
    box-shadow: 0 8px 24px rgba(0, 132, 255, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 9998;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: none;
  }
  .chat-widget-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 28px rgba(0, 132, 255, 0.5);
  }
  .chat-widget-card {
    position: fixed;
    bottom: 88px;
    right: 24px;
    width: 380px;
    max-width: calc(100vw - 32px);
    height: 540px;
    max-height: calc(100vh - 110px);
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.22);
    display: none;
    flex-direction: column;
    z-index: 9999;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.1);
  }
  .chat-widget-card.open {
    display: flex !important;
    animation: popupWidget 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
  }
  @keyframes popupWidget {
    from { opacity: 0; transform: translateY(16px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }
  .chat-widget-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #ff3b30;
    color: white;
    font-size: 11px;
    font-weight: bold;
    border-radius: 10px;
    padding: 2px 7px;
    border: 2px solid white;
  }
  .widget-user-item {
    transition: background-color 0.15s ease;
  }
  .widget-user-item:hover {
    background-color: rgba(0, 132, 255, 0.06);
  }
  .widget-site-header {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    padding: 6px 12px;
    border-top: 1px solid rgba(0,0,0,0.05);
    border-bottom: 1px solid rgba(0,0,0,0.05);
  }
  .widget-msg-bubble-mine {
    background: linear-gradient(135deg, #206bc4, #1a569d) !important;
    color: #ffffff !important;
    border-radius: 12px 12px 2px 12px;
  }
  .widget-msg-bubble-other {
    background: #ffffff !important;
    color: #1e293b !important;
    border: 1px solid #e2e8f0;
    border-radius: 12px 12px 12px 2px;
  }

  /* Dark mode */
  [data-bs-theme="dark"] .chat-widget-card {
    background: #182234;
    border-color: rgba(255, 255, 255, 0.1);
  }
  [data-bs-theme="dark"] .widget-site-header {
    background: #131c2c;
    color: #94a3b8;
    border-color: rgba(255, 255, 255, 0.06);
  }
  [data-bs-theme="dark"] .widget-user-item:hover {
    background-color: rgba(255, 255, 255, 0.05);
  }
  [data-bs-theme="dark"] #widgetMessages {
    background: #0b1320 !important;
  }
  [data-bs-theme="dark"] .widget-msg-bubble-other {
    background: #1e293b !important;
    color: #f8fafc !important;
    border-color: rgba(255, 255, 255, 0.08);
  }
  [data-bs-theme="dark"] #widgetInputCard {
    background: #182234 !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  [data-bs-theme="dark"] #widgetMessageInput {
    background: #131c2c !important;
    color: #f8fafc !important;
    border-color: rgba(255, 255, 255, 0.12) !important;
  }
  [data-bs-theme="dark"] #widgetSearchUser {
    background: #131c2c;
    border-color: rgba(255, 255, 255, 0.1);
    color: #f8fafc;
  }
</style>

<div id="chatWidgetContainer" class="d-print-none">
  <!-- Floating Button -->
  <button id="chatWidgetToggle" class="chat-widget-btn" title="Pesan Instan">
    <svg class="icon icon-tabler icon-tabler-brand-messenger" width="26" height="26" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1" /><path d="M8 13l3 -2l2 2l3 -3" /></svg>
    <span id="chatWidgetBadge" class="chat-widget-badge" style="display: none;">0</span>
  </button>

  <!-- Floating Messenger Card -->
  <div id="chatWidgetCard" class="chat-widget-card">
    <!-- Header -->
    <div class="p-2.5 px-3 text-white bg-primary d-flex align-items-center justify-content-between shadow-xs">
      <div class="d-flex align-items-center gap-2 overflow-hidden">
        <button id="btnWidgetBack" class="btn btn-sm btn-ghost-light p-1 text-white border-0" style="display: none;" title="Kembali ke Daftar Kontak">
          <svg class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
        </button>
        <span id="widgetHeaderAvatar" class="avatar avatar-sm bg-white text-primary fw-bold rounded-circle flex-shrink-0">CM</span>
        <div class="overflow-hidden">
          <div id="widgetHeaderTitle" class="fw-bold text-truncate" style="font-size: 13px;">Live Chat</div>
          <div id="widgetHeaderSubtitle" class="small opacity-75 text-truncate" style="font-size: 10.5px;">Pilih rekan kerja</div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-1 flex-shrink-0">
        <a href="{{ route('chat.index') }}" class="btn btn-sm btn-ghost-light p-1 text-white border-0" title="Buka Halaman Penuh Chat">
          <svg class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h6v6" /><path d="M10 14l8 -8" /><path d="M20 12v7a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h7" /></svg>
        </a>
        <button id="btnWidgetClose" class="btn btn-sm btn-ghost-light p-1 text-white border-0" title="Tutup Chat">
          <svg class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
        </button>
      </div>
    </div>

    <!-- Contact List View -->
    <div id="widgetContactView" class="flex-fill d-flex flex-column overflow-hidden">
      <div class="p-2 border-bottom">
        <div class="input-icon">
          <span class="input-icon-addon">
            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
          </span>
          <input type="text" id="widgetSearchUser" class="form-control form-control-sm" placeholder="Cari rekan kerja / site...">
        </div>
      </div>
      <div id="widgetUserList" class="flex-fill overflow-auto">
        <!-- Contact list populated via AJAX grouped by Site -->
      </div>
    </div>

    <!-- Active Conversation View -->
    <div id="widgetConversationView" class="flex-fill flex-column overflow-hidden" style="display: none!important;">
      <!-- Message Area -->
      <div id="widgetMessages" class="flex-fill overflow-auto p-2.5" style="background: #f1f5f9 !important;">
        <!-- Messages rendered here -->
      </div>

      <!-- Input Area -->
      <div id="widgetInputCard" class="p-2 border-top bg-white">
        <!-- Widget Attachment Preview -->
        <div id="widgetAttachmentPreview" class="d-none align-items-center justify-content-between p-1.5 px-2 mb-1.5 rounded bg-light border" style="font-size: 11px;">
          <div class="d-flex align-items-center gap-1.5 overflow-hidden">
            <span id="widgetPreviewThumb"></span>
            <span id="widgetPreviewName" class="fw-semibold text-truncate" style="max-width: 200px;"></span>
          </div>
          <button type="button" id="btnWidgetRemoveAttachment" class="btn-close" style="font-size: 10px;"></button>
        </div>

        <!-- Quick Toolbar (Media + Emojis) -->
        <div class="d-flex align-items-center justify-content-between gap-1 mb-1 pb-1 border-bottom">
          <div class="d-flex align-items-center gap-1">
            <input type="file" id="widgetImageInput" class="d-none" accept="image/*">
            <input type="file" id="widgetDocInput" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt">
            
            <button type="button" class="btn btn-xs btn-ghost-primary p-1" onclick="document.getElementById('widgetImageInput').click()" title="Kirim Gambar">
              <svg class="icon text-primary" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><rect x="4" y="4" width="16" height="16" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5"/><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2"/></svg>
            </button>
            <button type="button" class="btn btn-xs btn-ghost-info p-1" onclick="document.getElementById('widgetDocInput').click()" title="Kirim Dokumen">
              <svg class="icon text-info" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5"/></svg>
            </button>
          </div>
          <div class="d-flex align-items-center gap-1 overflow-auto">
            @foreach(['👍', '✅', '⚠️', '🛠️', '📦', '📋', '🚨', '😀'] as $emo)
              <button type="button" class="btn btn-xs btn-ghost-secondary border-0 p-0.5 fs-5 btn-widget-emo" data-emoji="{{ $emo }}">{{ $emo }}</button>
            @endforeach
          </div>
        </div>

        <form id="widgetChatForm" data-no-loader class="d-flex gap-1.5 align-items-center">
          <textarea id="widgetMessageInput" class="form-control form-control-sm" placeholder="Ketik pesan..." rows="1" style="resize:none; max-height: 70px;"></textarea>
          <button type="submit" id="btnWidgetSubmit" class="btn btn-sm btn-primary px-2.5 d-flex align-items-center justify-content-center shadow-none">
            <svg class="icon m-0" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const WIDGET_AUTH_ID = {{ auth()->id() }};
  let widgetSelectedUserId = null;
  let widgetSelectedUserName = '';
  let widgetPollingInterval = null;
  let widgetPendingAttachment = null;

  const toggleBtn = document.getElementById('chatWidgetToggle');
  const widgetCard = document.getElementById('chatWidgetCard');
  const btnClose = document.getElementById('btnWidgetClose');
  const btnBack = document.getElementById('btnWidgetBack');
  const contactView = document.getElementById('widgetContactView');
  const conversationView = document.getElementById('widgetConversationView');

  // Toggle open/close
  toggleBtn.addEventListener('click', function() {
    if (widgetCard.classList.contains('open')) {
      widgetCard.classList.remove('open');
      if (widgetPollingInterval) clearInterval(widgetPollingInterval);
    } else {
      widgetCard.classList.add('open');
      loadWidgetUsers();
    }
  });

  btnClose.addEventListener('click', function() {
    widgetCard.classList.remove('open');
    if (widgetPollingInterval) clearInterval(widgetPollingInterval);
  });

  btnBack.addEventListener('click', function() {
    widgetSelectedUserId = null;
    conversationView.style.setProperty('display', 'none', 'important');
    contactView.style.setProperty('display', 'flex', 'important');
    btnBack.style.display = 'none';
    document.getElementById('widgetHeaderTitle').textContent = 'Live Chat';
    document.getElementById('widgetHeaderSubtitle').textContent = 'Pilih rekan kerja';
    document.getElementById('widgetHeaderAvatar').textContent = 'CM';
    if (widgetPollingInterval) clearInterval(widgetPollingInterval);
    loadWidgetUsers();
  });

  // Load Users List Grouped by Site
  function loadWidgetUsers() {
    fetch('/chat/users')
      .then(r => r.json())
      .then(users => {
        const userListContainer = document.getElementById('widgetUserList');
        userListContainer.innerHTML = '';
        let totalUnread = 0;

        // Group by site
        const grouped = {};
        users.forEach(u => {
          const site = u.site_name || 'Head Office / Superadmin';
          if (!grouped[site]) grouped[site] = [];
          grouped[site].push(u);
          totalUnread += u.unread_count || 0;
        });

        Object.keys(grouped).forEach(siteName => {
          const groupDiv = document.createElement('div');
          groupDiv.className = 'widget-site-group';
          groupDiv.dataset.site = siteName.toLowerCase();

          const headerDiv = document.createElement('div');
          headerDiv.className = 'widget-site-header text-uppercase d-flex justify-content-between align-items-center';
          headerDiv.innerHTML = `<span>📍 ${siteName}</span><span class="badge bg-secondary-lt" style="font-size:9px;">${grouped[siteName].length}</span>`;
          groupDiv.appendChild(headerDiv);

          grouped[siteName].forEach(user => {
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'list-group-item list-group-item-action border-bottom p-2 d-flex align-items-center text-decoration-none widget-user-item';
            item.dataset.search = (user.nama_lengkap + ' ' + (user.site_name || '') + ' ' + user.email).toLowerCase();

            const avatarHtml = user.avatar_url 
              ? `<span class="avatar avatar-sm me-2.5 rounded-circle shadow-xs flex-shrink-0" style="background-image: url('${user.avatar_url}'); background-size: cover;"></span>`
              : `<span class="avatar avatar-sm me-2.5 text-white fw-bold rounded-circle flex-shrink-0" style="background-color: hsl(${Math.abs(crc32(user.email)) % 360}, 60%, 45%); font-size:12px;">${user.nama_lengkap.charAt(0).toUpperCase()}</span>`;

            item.innerHTML = `
              ${avatarHtml}
              <div class="flex-fill text-truncate">
                <div class="fw-semibold text-truncate small d-flex justify-content-between align-items-center">
                  <span class="text-truncate" style="font-size:12.5px;">${user.nama_lengkap}</span>
                  ${user.unread_count > 0 ? `<span class="badge bg-red text-white" style="font-size:10px;">${user.unread_count}</span>` : ''}
                </div>
                <div class="text-muted text-truncate" style="font-size: 10.5px;">${user.email}</div>
              </div>
            `;

            item.addEventListener('click', function(e) {
              e.preventDefault();
              openWidgetConversation(user.id, user.nama_lengkap, user.avatar_url, user.site_name);
            });

            groupDiv.appendChild(item);
          });

          userListContainer.appendChild(groupDiv);
        });

        const badge = document.getElementById('chatWidgetBadge');
        if (totalUnread > 0) {
          badge.textContent = totalUnread;
          badge.style.display = 'inline-block';
        } else {
          badge.style.display = 'none';
        }
      });
  }

  function openWidgetConversation(userId, userName, userAvatar, userSite) {
    widgetSelectedUserId = userId;
    widgetSelectedUserName = userName;

    contactView.style.setProperty('display', 'none', 'important');
    conversationView.style.setProperty('display', 'flex', 'important');
    btnBack.style.display = 'inline-block';

    document.getElementById('widgetHeaderTitle').textContent = userName;
    document.getElementById('widgetHeaderSubtitle').textContent = userSite || 'Online';

    const headerAvatar = document.getElementById('widgetHeaderAvatar');
    if (userAvatar) {
      headerAvatar.style.backgroundImage = `url('${userAvatar}')`;
      headerAvatar.style.backgroundSize = 'cover';
      headerAvatar.textContent = '';
    } else {
      headerAvatar.style.backgroundImage = 'none';
      headerAvatar.textContent = userName.charAt(0).toUpperCase();
    }

    clearWidgetAttachment();
    loadWidgetMessages();
    if (widgetPollingInterval) clearInterval(widgetPollingInterval);
    widgetPollingInterval = setInterval(loadWidgetMessages, 3000);
  }

  // Attachment handling
  const widgetImageInput = document.getElementById('widgetImageInput');
  const widgetDocInput = document.getElementById('widgetDocInput');
  const widgetAttachBox = document.getElementById('widgetAttachmentPreview');
  const widgetPreviewThumb = document.getElementById('widgetPreviewThumb');
  const widgetPreviewName = document.getElementById('widgetPreviewName');
  const btnWidgetRemoveAttachment = document.getElementById('btnWidgetRemoveAttachment');

  function setWidgetAttachment(file) {
    if (!file) return;
    if (file.size > 20 * 1024 * 1024) {
      alert('Ukuran berkas maksimal 20MB.');
      return;
    }
    widgetPendingAttachment = file;
    widgetPreviewName.textContent = file.name;

    if (file.type.startsWith('image/')) {
      widgetPreviewThumb.innerHTML = '📷';
    } else {
      widgetPreviewThumb.innerHTML = '📎';
    }
    widgetAttachBox.classList.remove('d-none');
    widgetAttachBox.classList.add('d-flex');
    document.getElementById('widgetMessageInput').focus();
  }

  function clearWidgetAttachment() {
    widgetPendingAttachment = null;
    widgetImageInput.value = '';
    widgetDocInput.value = '';
    widgetAttachBox.classList.remove('d-flex');
    widgetAttachBox.classList.add('d-none');
  }

  widgetImageInput.addEventListener('change', function() {
    if (this.files && this.files[0]) setWidgetAttachment(this.files[0]);
  });
  widgetDocInput.addEventListener('change', function() {
    if (this.files && this.files[0]) setWidgetAttachment(this.files[0]);
  });
  btnWidgetRemoveAttachment.addEventListener('click', clearWidgetAttachment);

  // Format Message Body with Placeholders
  function formatWidgetMessageBody(raw, isMine) {
    if (!raw) return '';
    let str = raw.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    const placeholders = [];

    const markdownLinkRegex = /\[([^\]]+)\]\((https?:\/\/[^\s\)]+|\/[^\s\)]+)\)/g;
    str = str.replace(markdownLinkRegex, function(match, label, url) {
      let cardHtml = `
      <a href="${url}" target="_blank" rel="noopener noreferrer" class="d-block text-decoration-none my-1 p-1.5 px-2 rounded ${isMine ? 'bg-white text-dark' : 'bg-light text-body'} shadow-xs border" style="font-size:11.5px;">
        <div class="fw-bold text-truncate">${label}</div>
        <div class="text-muted" style="font-size: 9.5px;">Klik untuk detail &rarr;</div>
      </a>`;

      const placeholder = `___WIDGET_MD_${placeholders.length}___`;
      placeholders.push({ placeholder, html: cardHtml });
      return placeholder;
    });

    const plainUrlRegex = /(https?:\/\/[^\s<]+|www\.[^\s<]+)/gi;
    str = str.replace(plainUrlRegex, function(url) {
      const targetUrl = url.startsWith('www.') ? 'http://' + url : url;
      const linkHtml = `<a href="${targetUrl}" target="_blank" class="text-decoration-underline fw-bold">${url}</a>`;
      const placeholder = `___WIDGET_PLAIN_${placeholders.length}___`;
      placeholders.push({ placeholder, html: linkHtml });
      return placeholder;
    });

    str = str.replace(/\n/g, '<br>');
    placeholders.forEach(item => { str = str.replace(item.placeholder, item.html); });
    return str;
  }

  let lastWidgetMessagesJson = '';
  let lastWidgetMsgIds = new Set();
  let isFirstWidgetLoad = true;

  function loadWidgetMessages() {
    if (!widgetSelectedUserId) return;
    fetch(`/chat/messages/${widgetSelectedUserId}`)
      .then(r => r.json())
      .then(messages => {
        const currentJson = JSON.stringify(messages.map(m => ({ id: m.id, read: m.read_at !== null })));

        if (!isFirstWidgetLoad) {
          const hasNewIncoming = messages.some(m => m.sender_id != WIDGET_AUTH_ID && !lastWidgetMsgIds.has(m.id));
          if (hasNewIncoming) {
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
        }
        lastWidgetMsgIds = new Set(messages.map(m => m.id));

        if (currentJson === lastWidgetMessagesJson && !isFirstWidgetLoad) {
          return;
        }
        lastWidgetMessagesJson = currentJson;
        isFirstWidgetLoad = false;

        const container = document.getElementById('widgetMessages');
        const isAtBottom = (container.scrollHeight - container.clientHeight <= container.scrollTop + 50);

        container.innerHTML = '';

        messages.forEach(msg => {
          const isMine = msg.sender_id == WIDGET_AUTH_ID;
          const body = formatWidgetMessageBody(msg.body, isMine);

          let attachHtml = '';
          if (msg.attachment_url) {
            if (msg.attachment_type === 'image') {
              attachHtml = `<div class="my-1"><img src="${msg.attachment_url}" class="img-fluid rounded border cursor-pointer" style="max-height: 180px; object-fit: cover;" onclick="window.open('${msg.attachment_url}', '_blank')"></div>`;
            } else {
              attachHtml = `<div class="my-1"><a href="${msg.attachment_url}" target="_blank" download="${msg.attachment_name || 'Dokumen'}" class="btn btn-xs btn-outline-primary d-flex align-items-center gap-1 text-truncate"><svg class="icon m-0" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M12 17v-6"/><path d="M9.5 14.5l2.5 2.5l2.5 -2.5"/></svg> <span class="text-truncate">${msg.attachment_name || 'Dokumen'}</span></a></div>`;
            }
          }

          const bubbleClass = isMine ? 'widget-msg-bubble-mine' : 'widget-msg-bubble-other';
          const bubble = document.createElement('div');
          bubble.className = `d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'} mb-2`;
          bubble.innerHTML = `
            <div class="rounded-3 p-2 px-2.5 shadow-xs ${bubbleClass}" style="max-width:84%; font-size: 12.5px;">
              ${!isMine ? `<div class="fw-bold text-azure small mb-0.5" style="font-size:11px;">${msg.sender?.nama_lengkap ?? 'User'}</div>` : ''}
              ${attachHtml}
              ${body ? `<div>${body}</div>` : ''}
              <div class="small mt-1 text-end ${isMine ? 'text-white-50' : 'text-muted'}" style="font-size: 9.5px;">
                ${new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
              </div>
            </div>`;
          container.appendChild(bubble);
        });

        if (isAtBottom || container.children.length <= 5) {
          container.scrollTop = container.scrollHeight;
        }
      });
  }

  // Quick Emoji
  document.addEventListener('click', function(e) {
    const emoBtn = e.target.closest('.btn-widget-emo');
    if (emoBtn) {
      const input = document.getElementById('widgetMessageInput');
      input.value += emoBtn.dataset.emoji;
      input.focus();
    }
  });

  // Submit Message & Attachment
  document.getElementById('widgetChatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const bodyInput = document.getElementById('widgetMessageInput');
    const body = bodyInput.value.trim();
    if (!body && !widgetPendingAttachment) return;
    if (!widgetSelectedUserId) return;

    const btnSubmit = document.getElementById('btnWidgetSubmit');
    btnSubmit.disabled = true;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const formData = new FormData();
    formData.append('receiver_id', widgetSelectedUserId);
    if (body) formData.append('body', body);
    if (widgetPendingAttachment) formData.append('file', widgetPendingAttachment);

    fetch('/chat/send', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    })
    .then(r => r.json())
    .then(() => {
      btnSubmit.disabled = false;
      bodyInput.value = '';
      clearWidgetAttachment();
      loadWidgetMessages();
    })
    .catch(err => {
      btnSubmit.disabled = false;
      console.error(err);
      alert('Gagal mengirim pesan');
    });
  });

  // Enter to send
  document.getElementById('widgetMessageInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      document.getElementById('widgetChatForm').dispatchEvent(new Event('submit'));
    }
  });

  // Filter User in widget
  document.getElementById('widgetSearchUser').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.widget-site-group').forEach(group => {
      let hasMatch = false;
      group.querySelectorAll('.widget-user-item').forEach(el => {
        const match = (el.dataset.search || '').includes(q);
        el.style.display = match ? 'flex' : 'none';
        if (match) hasMatch = true;
      });
      group.style.display = hasMatch ? 'block' : 'none';
    });
  });

  function crc32(str) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
      hash = (hash << 5) - hash + str.charCodeAt(i);
      hash |= 0;
    }
    return hash;
  }

  // Global unread badge update every 10 sec
  let lastUnreadCount = null;
  setInterval(() => {
    if (!widgetCard.classList.contains('open')) {
      fetch('/chat/unread-count')
        .then(r => r.json())
        .then(data => {
          const badge = document.getElementById('chatWidgetBadge');
          if (data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline-block';
            
            if (lastUnreadCount !== null && data.count > lastUnreadCount) {
              if (typeof Swal !== 'undefined') {
                Swal.fire({
                  toast: true,
                  position: 'bottom-end',
                  icon: 'info',
                  title: 'Pesan Baru Masuk',
                  text: 'Anda menerima pesan baru dari rekan kerja.',
                  showConfirmButton: false,
                  timer: 4000,
                  timerProgressBar: true,
                  customClass: {
                    container: 'd-print-none'
                  }
                });
              }
            }
          } else {
            badge.style.display = 'none';
          }
          lastUnreadCount = data.count;
        });
    }
  }, 10000);
});
</script>
@endauth
