// --- STATE & DATA ---
// let currentChatReportId = null;
// let simulatedPhotoBase64 = "";

// --- DASHBOARD & COUNT-UP LOGIC ---
function initAllCountUp() {
  document.querySelectorAll('.count-up').forEach(element => {
    const targetValue = parseInt(element.getAttribute('data-target')) || 0;
    const duration = 1000;
    const startTime = performance.now();

    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      element.textContent = Math.floor(targetValue * (progress * (2 - progress)));
      if (progress < 1) requestAnimationFrame(update);
      else element.textContent = targetValue;
    }
    requestAnimationFrame(update);
  });
}

async function renderManagerChatChannels() {

    const list =
        document.getElementById(
            'manager-chat-channels-list'
        );

    if (!list) return;

    list.innerHTML =
        '<p class="text-xs p-3">Memuat laporan...</p>';

    try {

        const response = await fetch(
            '../../../controllers/komunikasi.php?action=list_reports_manager'
        );

        const result =
            await response.json();

        if (result.status !== 'success') {

            list.innerHTML =
                '<p class="text-xs p-3">Gagal memuat data.</p>';

            return;
        }

        list.innerHTML = '';

        result.reports.forEach(r => {

            const btn =
                document.createElement('button');

            btn.className =
                'w-full text-left p-3 rounded-2xl border bg-white hover:bg-kf-light';

            btn.innerHTML = `
                <div>
                    <p class="font-bold text-xs">
                        ${r.judul_laporan}
                    </p>
                    <p class="text-[10px] text-gray-500">
                        ${r.pelapor}
                    </p>
                </div>
            `;

            btn.onclick = () => {
                selectManagerChatChannel(
                    parseInt(r.id_laporan)
                );
            };

            list.appendChild(btn);

        });

    } catch(err) {

        console.error(err);

        list.innerHTML =
            '<p class="text-xs p-3 text-red-500">Tidak dapat terhubung ke server.</p>';
    }
}

function refreshStatsAndWidgets() {
  const activeTasks = reports.filter(r => r.status === 'proses');
  
  // Update Stats
  ['proses', 'selesai', 'total'].forEach(id => {
    const el = document.getElementById(`count-${id}`);
    if (el) {
      const val = id === 'proses' ? activeTasks.length : (id === 'selesai' ? reports.filter(r => r.status === 'selesai').length : reports.length);
      el.setAttribute('data-target', val);
    }
  });
  initAllCountUp();

  // Update Urgent Box
  const urgentBox = document.getElementById('manager-urgent-box');
  if (urgentBox) {
    urgentBox.innerHTML = activeTasks.length === 0 ? 
      `<p class="text-xs text-kf-muted italic text-center py-6">Semua pekerjaan rampung! ✨</p>` :
      activeTasks.map(t => `
        <div class="p-3.5 rounded-2xl bg-kf-light/60 flex items-center justify-between text-xs border border-kf-sky/10">
          <div><span class="text-[9px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold uppercase mb-1 inline-block">Proses</span><p class="font-bold text-kf-dark">${t.title}</p></div>
          <a href="tasks.php" class="px-3 py-1.5 bg-white border border-kf-sky/30 rounded-xl font-bold text-kf-skyDeep hover:bg-kf-light transition">Tangani</a>
        </div>
      `).join('');
  }
}

// --- TASKS PAGE LOGIC ---
function renderManagerTasks() {
  const activeBox = document.getElementById('manager-active-tasks');
  const finishedBox = document.getElementById('manager-finished-tasks');
  if (!activeBox || !finishedBox) return;

  activeBox.innerHTML = reports.filter(r => r.status === 'proses').map(t => `
    <div class="bg-white p-5 rounded-2xl border border-kf-sky/20 shadow-sm space-y-3">
      <div class="flex justify-between"><span class="text-[9px] bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded uppercase">Penugasan Terbuka</span><span class="text-[10px] text-kf-muted">${t.date}</span></div>
      <div><h3 class="text-xs font-bold text-kf-dark">${t.title}</h3><p class="text-[11px] text-kf-muted mt-0.5">📍 ${t.loc}</p><p class="text-[11px] text-kf-dark bg-kf-cream/50 p-2.5 rounded-xl mt-2 border border-dashed">"${t.desc}"</p></div>
      <div class="flex gap-2 pt-1 border-t border-gray-100">
        <button onclick="openFinishModal(${t.id})" class="flex-1 py-2 bg-green-600 text-white rounded-xl text-[11px] font-bold shadow-sm flex items-center justify-center gap-1"><i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Selesai</button>
        <a href="komunikasi.php" class="px-3 py-2 bg-kf-light border rounded-xl"><i data-lucide="message-square" class="w-3.5 h-3.5"></i></a>
      </div>
    </div>
  `).join('');

  finishedBox.innerHTML = reports.filter(r => r.status === 'selesai').map(t => `
    <div class="bg-kf-light/30 p-4 rounded-2xl border text-xs">
      <div class="flex justify-between mb-2"><span class="text-[9px] bg-green-100 text-green-800 font-bold px-2 py-0.5 rounded uppercase">Selesai</span><span class="text-[10px] text-kf-muted">#PLR-${t.id}</span></div>
      <div class="flex gap-3">
        ${t.repairPhoto ? `<div class="w-12 h-12 rounded-lg bg-cover bg-center border" style="background-image: url('${t.repairPhoto}')"></div>` : ''}
        <div class="min-w-0 flex-1"><h4 class="font-bold text-kf-dark truncate">${t.title}</h4><p class="text-[10px] text-gray-500 italic mt-1 line-clamp-1">Hasil: ${t.note}</p></div>
      </div>
    </div>
  `).join('');
  
  if (typeof lucide !== 'undefined') lucide.createIcons();
}

function confirmAction(type) {

    const iconWrap =
        document.getElementById('confirm-icon-container');

    const title =
        document.getElementById('confirm-title');

    const desc =
        document.getElementById('confirm-desc');

    const btn =
        document.getElementById('confirm-btn-primary');

    if (type === 'Logout') {

        iconWrap.style.background =
            '#ffe3e3';

        iconWrap.innerHTML =
            '<i data-lucide="log-out" style="width:40px;height:40px;color:#e03131"></i>';

        title.textContent =
            'Keluar Akun';

        desc.textContent =
            'Apakah anda yakin ingin keluar dari aplikasi PILAR?';

        btn.textContent =
            'Keluar';

        btn.style.cssText =
            'background:#e03131;color:white;';

        btn.onclick = () => {

            window.location.href =
                '../../../controllers/logout.php';

        };
    }

    document
        .getElementById('modal-confirm')
        .classList
        .remove('hidden');

    document
        .getElementById('modal-confirm')
        .classList
        .add('flex');

    lucide.createIcons();
}

function closeConfirm() {
    const modal =
        document.getElementById('modal-confirm');
    modal.classList.add('hidden');
    modal.classList.remove('flex');

}

// --- CHAT & MODAL HANDLERS ---
function selectManagerChatChannel(id) {

    currentChatReportId = id;

    document
        .getElementById('manager-chat-empty-state')
        .classList.add('hidden');

    document
        .getElementById('manager-chat-active-box')
        .classList.remove('hidden');

    document.getElementById(
        'manager-chat-box-title'
    ).textContent = `Laporan #${id}`;

    renderManagerChatBoxMessages();
}

async function renderManagerChatBoxMessages() {

    const container =
        document.getElementById(
            'manager-chat-box-messages-container'
        );

    if (!container || !currentChatReportId)
        return;

    try {

        const response = await fetch(
            `../../../controllers/komunikasi.php?action=fetch_messages&id_laporan=${currentChatReportId}`
        );

        const result =
            await response.json();

        container.innerHTML = '';

        if (result.status !== 'success') {

            container.innerHTML =
                '<p class="text-xs text-red-500">Gagal memuat pesan.</p>';

            return;
        }

        result.messages.forEach(msg => {

            const isMe =
                msg.role === 'manager_teknisi';

            const wrapper =
                document.createElement('div');

            wrapper.className =
                isMe
                ? 'flex justify-end'
                : 'flex justify-start';

            wrapper.innerHTML = `
                <div class="
                    max-w-[80%]
                    p-3
                    rounded-2xl
                    text-xs
                    ${isMe
                        ? 'bg-amber-600 text-white'
                        : 'bg-kf-light text-kf-dark'}
                ">
                    <div class="text-[9px] font-bold mb-1">
                        ${msg.pengirim}
                        (${msg.role})
                    </div>

                    <div>
                        ${msg.text}
                    </div>

                    <div class="text-[8px] mt-1 opacity-70">
                        ${msg.waktu}
                    </div>
                </div>
            `;

            container.appendChild(wrapper);

        });

    } catch(err) {

        console.error(err);

        container.innerHTML =
            '<p class="text-xs text-red-500">Gagal terhubung ke server.</p>';
    }
}

async function sendManagerPageChatMessage() {

    const input =
        document.getElementById(
            'manager-chat-box-input'
        );

    const txt = input.value.trim();

    if (!txt || !currentChatReportId)
        return;

    try {

        const roomResponse = await fetch(
            `../../../controllers/komunikasi.php?action=fetch_messages&id_laporan=${currentChatReportId}`
        );

        const roomResult =
            await roomResponse.json();

        const formData =
            new FormData();

        formData.append(
            'id_room',
            roomResult.id_room
        );

        formData.append(
            'isi_pesan',
            txt
        );

        const sendResponse = await fetch(
            '../../../controllers/komunikasi.php?action=send_message',
            {
                method: 'POST',
                body: formData
            }
        );

        const sendResult =
            await sendResponse.json();

        if (sendResult.status === 'success') {

            input.value = '';

            renderManagerChatBoxMessages();

        }

    } catch(err) {

        console.error(err);
    }
}

// --- GLOBAL UTILS ---
function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.opacity = '1';
  setTimeout(() => t.style.opacity = '0', 2500);
}

document.addEventListener('DOMContentLoaded', () => {
  if (typeof currentActivePage !== 'undefined') {
    if (currentActivePage === 'dashboard') refreshStatsAndWidgets();
    else if (currentActivePage === 'tasks') renderManagerTasks();
    else if (currentActivePage === 'chat') renderManagerChatChannels();
  }
  if (typeof lucide !== 'undefined') lucide.createIcons();
});

const wasNearBottom =
          container.scrollHeight -
          container.scrollTop -
          container.clientHeight
          < 50;
        setInterval(() => {
            if (
                currentActivePage === 'chat' &&
                currentChatReportId
            ) {
                renderManagerChatBoxMessages();
            }
        }, 3000);
        if (wasNearBottom) {
            container.scrollTop =
                container.scrollHeight;
        }