@auth
<!-- Floating Messenger Chat Widget -->
<style>
  .chat-widget-btn {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0084ff, #00c6ff);
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
    bottom: 92px;
    right: 24px;
    width: 370px;
    max-width: calc(100vw - 32px);
    height: 520px;
    max-height: calc(100vh - 120px);
    background: var(--tblr-card-bg, #ffffff);
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
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
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
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
  .widget-user-item:hover {
    background-color: rgba(0, 132, 255, 0.05);
  }
</style>

<div id="chatWidgetContainer" class="d-print-none">
  <!-- Floating Button -->
  <button id="chatWidgetToggle" class="chat-widget-btn" title="Pesan Instan">
    <svg class="icon icon-tabler icon-tabler-brand-messenger" width="28" height="28" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 20l1.3 -3.9a9 8 0 1 1 3.4 2.9l-4.7 1" /><path d="M8 13l3 -2l2 2l3 -3" /></svg>
    <span id="chatWidgetBadge" class="chat-widget-badge" style="display: none;">0</span>
  </button>

  <!-- Floating Messenger Card -->
  <div id="chatWidgetCard" class="chat-widget-card">
    <!-- Header -->
    <div class="p-3 text-white bg-primary d-flex align-items-center justify-content-between shadow-xs">
      <div class="d-flex align-items-center gap-2">
        <button id="btnWidgetBack" class="btn btn-sm btn-ghost-light p-1 me-1 text-white border-0" style="display: none;" title="Kembali ke Daftar Kontak">
          <svg class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
        </button>
        <span id="widgetHeaderAvatar" class="avatar avatar-sm bg-white text-primary fw-bold rounded-circle">CM</span>
        <div>
          <div id="widgetHeaderTitle" class="fw-bold" style="font-size: 14px;">Live Chat</div>
          <div id="widgetHeaderSubtitle" class="small opacity-75" style="font-size: 11px;">Pilih rekan kerja</div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-1">
        <a href="{{ route('chat.index') }}" class="btn btn-sm btn-ghost-light p-1 text-white me-1 border-0" title="Buka Halaman Penuh Chat">
          <svg class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6h6v6" /><path d="M10 14l8 -8" /><path d="M20 12v7a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h7" /></svg>
        </a>
        <button id="btnWidgetClose" class="btn btn-sm btn-ghost-light p-1 text-white border-0" title="Tutup Chat">
          <svg class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
        </button>
      </div>
    </div>

    <!-- Contact List View -->
    <div id="widgetContactView" class="flex-fill d-flex flex-column overflow-hidden">
      <div class="p-2 border-bottom">
        <div class="input-icon">
          <span class="input-icon-addon">
            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
          </span>
          <input type="text" id="widgetSearchUser" class="form-control form-control-sm" placeholder="Cari rekan kerja...">
        </div>
      </div>
      <div id="widgetUserList" class="flex-fill overflow-auto list-group list-group-flush">
        <!-- Contact list populated via AJAX -->
      </div>
    </div>

    <!-- Active Conversation View -->
    <div id="widgetConversationView" class="flex-fill flex-column" style="display: none!important;">
      <!-- Message Area -->
      <div id="widgetMessages" class="flex-fill overflow-auto p-3" style="background: #f8fafc !important;">
        <!-- Messages rendered here -->
      </div>

      <!-- Input Area -->
      <div class="p-2 border-top bg-white">
        <!-- Quick Emojis -->
        <div class="d-flex align-items-center gap-1 mb-2 pb-1 overflow-auto border-bottom">
          @foreach(['👍', '✅', '⚠️', '🛠️', '📦', '📋', '🚨', '💡', '😀', '🙏'] as $emo)
            <button type="button" class="btn btn-xs btn-ghost-secondary border-0 p-1 fs-5 btn-widget-emo" data-emoji="{{ $emo }}">{{ $emo }}</button>
          @endforeach
        </div>

        <form id="widgetChatForm" class="d-flex gap-2 align-items-center">
          <textarea id="widgetMessageInput" class="form-control form-control-sm" placeholder="Ketik pesan..." rows="1" style="resize:none; max-height: 80px;"></textarea>
          <button type="submit" class="btn btn-sm btn-primary px-3 d-flex align-items-center justify-content-center">
            <svg class="icon m-0" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
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

  // Load Users List
  function loadWidgetUsers() {
    fetch('/chat/users')
      .then(r => r.json())
      .then(users => {
        const userListContainer = document.getElementById('widgetUserList');
        userListContainer.innerHTML = '';
        let totalUnread = 0;

        users.forEach(user => {
          totalUnread += user.unread_count || 0;
          const item = document.createElement('a');
          item.href = '#';
          item.className = 'list-group-item list-group-item-action border-bottom p-2 d-flex align-items-center text-decoration-none widget-user-item';
          
          const avatarHtml = user.avatar_url 
            ? `<span class="avatar avatar-sm me-3 rounded-circle shadow-xs flex-shrink-0" style="background-image: url('${user.avatar_url}'); background-size: cover;"></span>`
            : `<span class="avatar avatar-sm me-3 text-white fw-bold flex-shrink-0" style="background-color: hsl(${Math.abs(crc32(user.email)) % 360}, 60%, 45%);">${user.nama_lengkap.charAt(0).toUpperCase()}</span>`;

          item.innerHTML = `
            ${avatarHtml}
            <div class="flex-fill text-truncate">
              <div class="fw-bold text-dark small d-flex justify-content-between align-items-center">
                <span>${user.nama_lengkap}</span>
                ${user.unread_count > 0 ? `<span class="badge bg-red text-white">${user.unread_count}</span>` : ''}
              </div>
              <div class="text-muted" style="font-size: 11px;">${user.email}</div>
            </div>
          `;

          item.addEventListener('click', function(e) {
            e.preventDefault();
            openWidgetConversation(user.id, user.nama_lengkap, user.avatar_url);
          });

          userListContainer.appendChild(item);
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

  function openWidgetConversation(userId, userName, userAvatar) {
    widgetSelectedUserId = userId;
    widgetSelectedUserName = userName;

    contactView.style.setProperty('display', 'none', 'important');
    conversationView.style.setProperty('display', 'flex', 'important');
    btnBack.style.display = 'inline-block';

    document.getElementById('widgetHeaderTitle').textContent = userName;
    document.getElementById('widgetHeaderSubtitle').textContent = 'Aktif';

    const headerAvatar = document.getElementById('widgetHeaderAvatar');
    if (userAvatar) {
      headerAvatar.style.backgroundImage = `url('${userAvatar}')`;
      headerAvatar.style.backgroundSize = 'cover';
      headerAvatar.textContent = '';
    } else {
      headerAvatar.style.backgroundImage = 'none';
      headerAvatar.textContent = userName.charAt(0).toUpperCase();
    }

    loadWidgetMessages();
    if (widgetPollingInterval) clearInterval(widgetPollingInterval);
    widgetPollingInterval = setInterval(loadWidgetMessages, 3000);
  }

  // Format Message Body with Placeholders
  function formatWidgetMessageBody(raw, isMine) {
    if (!raw) return '';
    let str = raw.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    const placeholders = [];

    const markdownLinkRegex = /\[([^\]]+)\]\((https?:\/\/[^\s\)]+|\/[^\s\)]+)\)/g;
    str = str.replace(markdownLinkRegex, function(match, label, url) {
      const isJwo = label.toLowerCase().includes('jwo');
      let iconSvg = isJwo
        ? `<svg class="icon icon-tabler icon-tabler-tools text-purple" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21h4l13 -13a1.5 1.5 0 0 0 -4 -4l-13 13v4" /><path d="M14.5 5.5l4 4" /><path d="M12 8l-5 -5l-4 4l5 5" /><path d="M7 8l-1.5 1.5" /><path d="M16 12l5 5l-4 4l-5 -5" /><path d="M16 17l-1.5 1.5" /></svg>`
        : `<svg class="icon icon-tabler icon-tabler-file-text text-primary" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>`;

      let cardHtml = `
      <a href="${url}" target="_blank" rel="noopener noreferrer" class="d-block text-decoration-none my-1 p-2 rounded bg-white text-dark shadow-xs border">
        <div class="d-flex align-items-center gap-2">
          <div class="avatar avatar-xs bg-light rounded flex-shrink-0">${iconSvg}</div>
          <div class="flex-fill overflow-hidden text-start">
            <div class="fw-bold text-dark text-truncate" style="font-size: 12px;">${label}</div>
            <div class="text-muted" style="font-size: 10px;">Klik untuk detail</div>
          </div>
        </div>
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

  function loadWidgetMessages() {
    if (!widgetSelectedUserId) return;
    fetch(`/chat/messages/${widgetSelectedUserId}`)
      .then(r => r.json())
      .then(messages => {
        const container = document.getElementById('widgetMessages');
        container.innerHTML = '';

        messages.forEach(msg => {
          const isMine = msg.sender_id == WIDGET_AUTH_ID;
          const body = formatWidgetMessageBody(msg.body, isMine);

          const bubble = document.createElement('div');
          bubble.className = `d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'} mb-2`;
          bubble.innerHTML = `
            <div class="rounded-3 p-2 px-3 shadow-xs ${isMine ? 'bg-primary text-white' : 'bg-white border text-dark'}" style="max-width:82%; font-size: 13px;">
              ${!isMine ? `<div class="fw-bold text-azure small mb-1">${msg.sender?.nama_lengkap ?? 'User'}</div>` : ''}
              <div>${body}</div>
              <div class="small mt-1 text-end ${isMine ? 'text-white-50' : 'text-muted'}" style="font-size: 10px;">
                ${new Date(msg.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
              </div>
            </div>`;
          container.appendChild(bubble);
        });

        container.scrollTop = container.scrollHeight;
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

  // Submit Message
  document.getElementById('widgetChatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const body = document.getElementById('widgetMessageInput').value.trim();
    if (!body || !widgetSelectedUserId) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch('/chat/send', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({receiver_id: widgetSelectedUserId, body: body})
    })
    .then(r => r.json())
    .then(() => {
      document.getElementById('widgetMessageInput').value = '';
      loadWidgetMessages();
    });
  });

  // Enter to send
  document.getElementById('widgetMessageInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      document.getElementById('widgetChatForm').dispatchEvent(new Event('submit'));
    }
  });

  // Filter User
  document.getElementById('widgetSearchUser').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.widget-user-item').forEach(el => {
      el.style.display = el.textContent.toLowerCase().includes(q) ? 'flex' : 'none';
    });
  });

  // Simple string hash for color
  function crc32(str) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
      hash = (hash << 5) - hash + str.charCodeAt(i);
      hash |= 0;
    }
    return hash;
  }

  // Global unread badge update every 10 sec
  setInterval(() => {
    if (!widgetCard.classList.contains('open')) {
      fetch('/chat/unread-count')
        .then(r => r.json())
        .then(data => {
          const badge = document.getElementById('chatWidgetBadge');
          if (data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline-block';
          } else {
            badge.style.display = 'none';
          }
        });
    }
  }, 10000);
});
</script>
@endauth
