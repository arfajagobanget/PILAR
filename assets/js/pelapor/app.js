/* ============================================================
   PILAR – Main JavaScript (Pelapor Dinamis DB)
   ============================================================ */

'use strict';

/* ---- Data Nyata dari Database ---- */
// Mengambil array data laporan yang di-inject oleh PHP di halaman views
const REPORTS = window.dbReports || [];

const STATUS_MAP = {
  menunggu:     { label: 'Menunggu',       cls: 'badge-menunggu' },
  diproses:       { label: 'Dalam Proses',   cls: 'badge-proses' },
  selesai:      { label: 'Selesai',        cls: 'badge-selesai' },
  ditolak:      { label: 'Ditolak',        cls: 'badge-ditolak' },
  diverifikasi: { label: 'Diverifikasi',   cls: 'badge-diverifikasi' },
};

/* ---- Mobile Sidebar ---- */
function openSidebar() {
  document.querySelectorAll('.sidebar').forEach(s => s.classList.add('open'));
  document.querySelectorAll('.sidebar-overlay').forEach(o => o.classList.add('open'));
}
function closeSidebar() {
  document.querySelectorAll('.sidebar').forEach(s => s.classList.remove('open'));
  document.querySelectorAll('.sidebar-overlay').forEach(o => o.classList.remove('open'));
}

/* ---- Count-up animation ---- */
function triggerCountUp() {
  document.querySelectorAll('.count-up').forEach(counter => {
    counter.textContent = '0';
    const target = +counter.getAttribute('data-target');
    if (!target) return;
    let current = 0;
    const step = Math.max(Math.floor(400 / target), 30);
    const timer = setInterval(() => {
      current++;
      counter.textContent = current;
      if (current >= target) {
        counter.textContent = target;
        clearInterval(timer);
      }
    }, step);
  });
}

/* ---- Dashboard: recent reports rendering helper ---- */
function renderReportsInContainer(reportsList) {
  const container = document.getElementById('recent-reports-container');
  if (!container) return;
  container.innerHTML = '';

  if (!reportsList || reportsList.length === 0) {
    container.innerHTML = '<p style="font-size:0.8125rem;color:var(--muted);text-align:center;padding:1rem;">Belum ada laporan terbaru.</p>';
    return;
  }

  reportsList.forEach(r => {
    const s = STATUS_MAP[r.status.toLowerCase()] || STATUS_MAP['menunggu'];
    const div = document.createElement('div');
    div.className = 'report-row';
    div.onclick = () => openDetailModal(r);
    div.innerHTML = `
      <div class="report-icon"><i data-lucide="alert-circle" class="w-5 h-5" style="color:var(--sky-deep)"></i></div>
      <div class="report-info">
        <p class="report-title-text">${r.title}</p>
        <p class="report-meta">${r.date} · ${r.loc.split('·')[0].trim()}</p>
      </div>
      <span class="badge ${s.cls}">${s.label}</span>
    `;
    container.appendChild(div);
  });
  lucide.createIcons();
}

/* ---- Laporan page ---- */
let currentFilter = 'semua';
let currentSearch = '';

function renderReports(filter) {
  currentFilter = filter || currentFilter;

  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.classList.remove('active-filter');
    if (btn.dataset.filter === currentFilter) btn.classList.add('active-filter');
  });

  const grid = document.getElementById('report-grid');
  if (!grid) return;

  let list = currentFilter === 'semua'
    ? [...REPORTS]
    : REPORTS.filter(r => r.status.toLowerCase() === currentFilter);

  if (currentSearch.trim()) {
    const q = currentSearch.toLowerCase();
    list = list.filter(r => r.title.toLowerCase().includes(q));
  }

  grid.innerHTML = '';

  if (list.length === 0) {
    grid.innerHTML = '<p style="grid-column:1/-1;text-align:center;color:var(--muted);font-size:0.875rem;padding:2rem;">Tidak ada laporan ditemukan.</p>';
    return;
  }

  list.forEach((r, i) => {
    const s = STATUS_MAP[r.status.toLowerCase()] || STATUS_MAP['menunggu'];
    const card = document.createElement('div');
    card.className = 'report-card slide-up';
    card.style.animationDelay = `${i * 0.05}s`;
    
    const thumbnailHTML = r.image && r.image !== 'default.png'
      ? `<img src="../../../assets/uploads/laporan/sebelum/${r.image}" style="width:100%;height:100%;object-fit:cover;border-radius:14px 14px 0 0;">`
      : `<i data-lucide="alert-circle" style="width:40px;height:40px;color:rgba(116,192,252,0.4)"></i>`;

    card.innerHTML = `
      <div class="report-card-thumb" onclick="openDetailModalById(${r.id})" style="position:relative; overflow:hidden; display:flex; align-items:center; justify-content:center;">
        ${thumbnailHTML}
        <div style="position:absolute;top:0.75rem;right:0.75rem;z-index:10;">
          <span class="badge ${s.cls}">${s.label}</span>
        </div>
      </div>
      <div class="report-card-body" style="display: flex; flex-direction: column; gap: 0.25rem;">
        <p class="report-card-title" onclick="openDetailModalById(${r.id})" style="font-weight: 600; color: var(--dark); margin: 0; cursor: pointer;">
          ${r.title ? r.title : 'Tanpa Judul'}
        </p>
        
        <p class="report-card-desc" style="font-size: 0.8125rem; color: var(--muted); margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
          ${r.desc ? r.desc : 'Tidak ada deskripsi tambahan.'}
        </p>

        <p class="report-card-loc" style="font-size: 0.75rem; color: var(--sky-deep); margin-top: 0.25rem; font-weight: 500;">
          <i data-lucide="map-pin" style="width:12px;height:12px;display:inline-block;vertical-align:middle;margin-right:2px;"></i>${r.loc}
        </p>
        
        <div style="margin-top: 0.5rem; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 0.5rem;">
          <button class="chat-link" onclick="openChat(${r.id})">
            <i data-lucide="message-circle" style="width:14px;height:14px"></i> Chat Room
          </button>
        </div>
      </div>`;
    grid.appendChild(card);
  });
  lucide.createIcons();
}

function handleReportSearch() {
  currentSearch = document.getElementById('report-search-bar')?.value || '';
  renderReports(currentFilter);
}

function openDetailModalById(id) {
  const r = REPORTS.find(item => Number(item.id) === Number(id));
  if (r) openDetailModal(r);
}

/* ---- Detail Modal ---- */
function openDetailModal(report) {
  const s = STATUS_MAP[report.status.toLowerCase()] || STATUS_MAP['menunggu'];
  const content = document.getElementById('detail-content');
  if(!content) return;

  content.innerHTML = `
    <div style="height:140px;background:linear-gradient(135deg,var(--light),rgba(165,216,255,0.2));border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;overflow:hidden">
      ${report.image && report.image !== 'default.png' 
        ? `<img src="../../../assets/uploads/laporan/sebelum/${report.image}" onclick="openFotoFullModal(this.src)" title="Klik untuk memperbesar" style="width:100%;height:100%;object-fit:cover;cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">` 
        : `<i data-lucide="alert-circle" style="width:56px;height:56px;color:rgba(116,192,252,0.4)"></i>`}
    </div>
   <div style="margin-bottom:1rem;text-align:left">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;flex-wrap:wrap;gap:0.5rem">
        <h3 style="font-size:1rem;font-weight:600;color:var(--dark)">${report.title}</h3>
        <span class="badge ${s.cls}">${s.label}</span>
      </div>
      <p style="font-size:0.6875rem;color:var(--muted);margin-bottom:0.75rem">${report.loc} · ${report.date}</p>
      
      <div style="margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px dashed rgba(165,216,255,0.2);">
        <p style="font-size:0.75rem; font-weight:600; color:var(--muted); margin-bottom:0.25rem; text-transform: uppercase; letter-spacing: 0.5px;">Detail Kerusakan:</p>
        <p style="font-size:0.875rem; color:var(--dark); line-height:1.5; margin:0;">
          ${report.desc ? report.desc : 'Tidak ada deskripsi tambahan.'}
        </p>
      </div>

      <p style="font-size:0.8125rem;color:var(--dark);line-height:1.6;margin:0;">
        Laporan ini sedang dalam tahap <b>${s.label}</b>. Gunakan fitur chat untuk menanyakan progress perbaikan fasilitas kepada admin.
      </p>
    </div>
    <div style="display:flex;gap:0.75rem;padding-top:1rem;border-top:1px solid rgba(165,216,255,0.15)">
      
      <a href="data.php?edit=${report.id}" class="btn-outline-edit" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; flex: 1;">
        <i data-lucide="pencil-line" style="width:16px;height:16px"></i> Edit Laporan
      </a>
      
      <button class="btn-outline-delete" onclick="confirmAction('delete', ${report.id})" style="flex: 1;">
        <i data-lucide="trash-2" style="width:16px;height:16px"></i> Hapus Laporan
      </button>
    </div>
  `;
  openModalEl('modal-detail');
}

/* ---- Form Modal Openers ---- */
function openModal()         { openModalEl('modal-laporan'); }
function closeModal()        { closeModalEl('modal-laporan'); }
function closeDetailModal() { closeModalEl('modal-detail'); }

/* ---- Real-time Chat AJAX Core Engine ---- */
let currentActiveRoomId = null;
let chatIntervalTimer = null;

function openChat(reportId) {
  console.log("Tombol diklik, ID Laporan:", reportId);
  
  if (!reportId) {
      alert("Error: ID Laporan tidak terbaca!");
      return;
  }
  
  const msgContainer = document.getElementById('chat-messages');
  const inputMessage = document.getElementById('chat-input-message');
  
  if (inputMessage) {
    inputMessage.setAttribute('data-report-id', reportId);
  }
  
  // Update judul chat
  const reportObj = REPORTS.find(item => Number(item.id) === Number(reportId));
  if (reportObj && document.getElementById('chat-header-subtitle')) {
      document.getElementById('chat-header-subtitle').textContent = reportObj.title;
  }

  // Buka modal
  openModalEl('modal-chat');

  // PANGGIL FUNGSI FETCH SECARA LANGSUNG
  fetchRoomMessages(reportId);
}

function fetchRoomMessages(reportId, isPolling = false) {
    const container = document.getElementById('chat-messages');
    if (!container) return;

    // Gunakan URL relatif yang lebih aman (mundur 3 folder ke root, lalu ke controllers)
    const url = '../../../controllers/komunikasi.php?action=fetch_messages&id_laporan=' + reportId;

    fetch(url)
        .then(res => res.text()) // Ambil sebagai teks untuk mendeteksi error PHP
        .then(text => {
            console.log("Raw Response dari Server:", text); // LIHAT INI DI CONSOLE F12
                        try {
                const data = JSON.parse(text); // Baru di-parse jadi JSON
                                if (data.status === 'success') {
                    currentActiveRoomId = data.id_room;
                                    if (data.messages && data.messages.length > 0) {
                        container.innerHTML = '';
                        data.messages.forEach(m => {
                            const div = document.createElement('div');
                            const isSystem = m.text.includes('[SISTEM]') || m.id_pengirim === null;
                            const isMe = Number(m.id_pengirim) === Number(window.id_user_logged_in);
                            if (isSystem) {
                                div.style.cssText = 'display:flex; justify-content:center; margin:10px 0;';
                                div.innerHTML = `<div style="background:#fff9db; padding:8px; border-radius:10px; font-size:11px; color:#b06a00;">📢 ${m.text.replace('[SISTEM]', '')}</div>`;
                            } else if (isMe) {
                                div.style.cssText =
                                    'display:flex; justify-content:flex-end; margin:5px 0;';
                                div.innerHTML = `
                                    <div style="
                                        background:var(--lavender);
                                        padding:10px;
                                        border-radius:12px;
                                        font-size:12px;
                                        max-width:75%;
                                    ">
                                        <div style="
                                            font-size:10px;
                                            font-weight:bold;
                                            margin-bottom:4px;
                                            opacity:.8;
                                        ">
                                            ${m.pengirim} (${m.role})
                                        </div>
                                        <div>
                                            ${m.text}
                                        </div>
                                        <div style="
                                            font-size:9px;
                                            margin-top:4px;
                                            opacity:.7;
                                            text-align:right;
                                        ">
                                            ${m.waktu}
                                        </div>
                                    </div>
                                `;
                            } else {
                                div.style.cssText =
                                    'display:flex; justify-content:flex-start; margin:5px 0;';
                                div.innerHTML = `
                                    <div style="
                                        background:#e7f5ff;
                                        padding:10px;
                                        border-radius:12px;
                                        font-size:12px;
                                        max-width:75%;
                                    ">
                                        <div style="
                                            font-size:10px;
                                            font-weight:bold;
                                            margin-bottom:4px;
                                            opacity:.8;
                                        ">
                                            ${m.pengirim} (${m.role})
                                        </div>

                                        <div>
                                            ${m.text}
                                        </div>

                                        <div style="
                                            font-size:9px;
                                            margin-top:4px;
                                            opacity:.7;
                                            text-align:right;
                                        ">
                                            ${m.waktu}
                                        </div>
                                    </div>
                                `;
                            }
                            container.appendChild(div);
                        });
                        container.scrollTop = container.scrollHeight;
                    } else {
                        container.innerHTML = '<p style="text-align:center; font-size:12px;">Belum ada pesan.</p>';
                    }
                } else {
                    container.innerHTML = '<p style="text-align:center; color:red;">Status Error: ' + data.message + '</p>';
                }
            } catch (e) {
                // Jika muncul error di sini, berarti ada error PHP (seperti Warning/Notice) yang ikut terkirim
                container.innerHTML = '<div style="color:red; font-size:11px;">Error Parsing: ' + text + '</div>';
            }
        })
        .catch(err => {
            console.error("Fetch Error:", err);
            container.innerHTML = '<p style="text-align:center; color:red;">Gagal terhubung ke server!</p>';
        });
}

function sendChatMessage() {
  const input = document.getElementById('chat-input-message');
  const val = input?.value.trim();
  if (!val || !currentActiveRoomId) return;

  const params = new URLSearchParams();
  params.append('id_room', currentActiveRoomId);
  params.append('isi_pesan', val);

  fetch('/PILAR/controllers/komunikasi.php?action=send_message', { method: 'POST', body: params })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            input.value = '';
            const inputMessage = document.getElementById('chat-input-message');
            const reportId = parseInt(inputMessage.getAttribute('data-report-id'));
            fetchRoomMessages(reportId);
        }
    });
}

function closeChat() { 
  if (chatIntervalTimer) clearInterval(chatIntervalTimer);
  closeModalEl('modal-chat'); 
}

/* ---- Kustom Confirm Dialog ---- */
function confirmAction(type, id = null) {
  const iconWrap = document.getElementById('confirm-icon-container');
  const title    = document.getElementById('confirm-title');
  const desc     = document.getElementById('confirm-desc');
  const btn      = document.getElementById('confirm-btn-primary');

  if (type === 'Logout') {
    iconWrap.style.background = 'var(--blush)';
    iconWrap.innerHTML = '<i data-lucide="log-out" style="width:40px;height:40px;color:#e03131"></i>';
    title.textContent = 'Keluar Akun';
    desc.textContent  = 'Apakah anda yakin ingin keluar dari aplikasi PILAR?';
    btn.textContent   = 'Keluar';
    btn.style.cssText = 'background:#e03131;color:white;box-shadow:0 4px 12px rgba(224,49,49,0.3)';
    btn.onclick       = () => { window.location.href = '../../../controllers/logout.php'; };
  } else if (type === 'delete') {
    iconWrap.style.background = 'var(--sand)';
    iconWrap.innerHTML = '<i data-lucide="trash-2" style="width:40px;height:40px;color:#f59f00"></i>';
    title.textContent = 'Hapus Laporan';
    desc.textContent  = 'Tindakan ini tidak bisa dibatalkan. Hapus pengaduan ini?';
    btn.textContent   = 'Hapus';
    btn.style.cssText = 'background:#f59f00;color:white;box-shadow:0 4px 12px rgba(245,159,0,0.3)';
    btn.onclick       = () => { window.location.href = `../../../controllers/laporanSaya.php?delete=${id}`; };
  }

  openModalEl('modal-confirm');
}
function closeConfirm() { closeModalEl('modal-confirm'); }

/* ---- PILAR Toast Notification System Framework ---- */
function showToast(message, type = 'success') {
  const toastContainer = document.getElementById('toast');
  if (!toastContainer) return;

  let bgStyle = 'background: #e6fcf5; color: #0ca678; border: 1px solid #c3fae8;'; 
  let iconName = 'check-circle';

  if (type === 'error' || type === 'danger') {
    bgStyle = 'background: #fff5f5; color: #e03131; border: 1px solid #ffe3e3;'; 
    iconName = 'x-circle';
  } else if (type === 'warning') {
    bgStyle = 'background: #fff9db; color: #f08c00; border: 1px solid #fff3bf;'; 
    iconName = 'alert-triangle';
  } else if (type === 'info') {
    bgStyle = 'background: #e7f5ff; color: #1c7ed6; border: 1px solid #d0ebff;'; 
    iconName = 'info';
  }

  const toastItem = document.createElement('div');
  toastItem.className = 'toast-item';
  toastItem.style = `
    display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; margin-top: 0.5rem;
    border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 0.875rem; font-weight: 500;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04); opacity: 0; transform: translateY(-20px);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); ${bgStyle}
  `;

  toastItem.innerHTML = `
    <i data-lucide="${iconName}" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
    <span style="flex-grow: 1;">${message}</span>
  `;

  toastContainer.appendChild(toastItem);
  if (typeof lucide !== 'undefined') { lucide.createIcons(); }

  setTimeout(() => {
    toastItem.style.opacity = '1';
    toastItem.style.transform = 'translateY(0)';
  }, 50);

  setTimeout(() => {
    toastItem.style.opacity = '0';
    toastItem.style.transform = 'translateY(-10px)';
    setTimeout(() => { toastItem.remove(); }, 400);
  }, 3500);
}

/* ---- Modal Open Helpers ---- */
function openModalEl(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.add('open');
  lucide.createIcons();
}
function closeModalEl(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('open');
}

/* ---- Overlay Click Dismisser ---- */
document.addEventListener('click', e => {
  ['modal-laporan', 'modal-detail', 'modal-chat', 'modal-confirm', 'modal-foto-full'].forEach(id => {
    const el = document.getElementById(id);
    if (el && e.target === el) {
        if(id === 'modal-chat') { closeChat(); } else { closeModalEl(id); }
    }
  });
});

/* ---- Event Key Listener ---- */
document.addEventListener('keydown', e => {
  if (e.key === 'Enter' && document.getElementById('modal-chat')?.classList.contains('open')) {
    const focused = document.getElementById('chat-input-message');
    if (document.activeElement === focused) sendChatMessage();
  }
});

/* ---- Document Initialization ---- */
document.addEventListener('DOMContentLoaded', () => {
  if (typeof lucide !== 'undefined') { lucide.createIcons(); }
  if (window.dbReports && window.dbReports.length > 0) {
    renderReportsInContainer(window.dbReports.slice(0, 3));
  }
  setTimeout(triggerCountUp, 150);
});