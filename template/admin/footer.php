</div> <div id="modal-report-detail" class="fixed inset-0 z-50 hidden items-center justify-center modal-overlay">
    <div class="bg-white rounded-3xl w-full max-w-lg mx-4 shadow-2xl p-7">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-lg text-kf-dark">Rincian Dokumen Keluhan</h2>
        <button onclick="closeDetailModal()" class="w-8 h-8 rounded-xl bg-kf-light flex items-center justify-center"><i data-lucide="x" class="w-4 h-4"></i></button>
      </div>
      <div id="modal-detail-content" class="space-y-4 text-xs text-kf-dark"></div>
    </div>
  </div>

  <div id="modal-admin-action" class="fixed inset-0 z-50 hidden items-center justify-center modal-overlay">
    <div class="bg-white rounded-3xl w-full max-w-md mx-4 shadow-2xl p-7">
      <div class="flex items-center justify-between mb-4">
        <h2 id="admin-modal-title" class="font-bold text-base text-kf-dark">Validasi Aksi</h2>
        <button onclick="closeAdminModal()" class="w-8 h-8 rounded-xl bg-kf-light flex items-center justify-center"><i data-lucide="x" class="w-4 h-4"></i></button>
      </div>
      <div id="admin-modal-info" class="p-3 bg-kf-light rounded-xl text-[11px] mb-4 text-kf-dark"></div>
      <form id="admin-action-form" onsubmit="executeAdminAction(event)">
        <input type="hidden" id="admin-target-id">
        <input type="hidden" id="admin-target-type">
        <div id="manager-select-container" class="space-y-1.5 mb-4 hidden">
          <label class="text-xs font-bold text-kf-dark block">Pilih Manager / Teknisi Utama</label>
          <select id="admin-manager-select" class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs text-kf-dark outline-none border"></select>
        </div>
        <div class="space-y-1.5 mb-6">
          <label class="text-xs font-bold text-kf-dark block" id="admin-note-label">Catatan Validasi Admin</label>
          <textarea id="admin-note" required rows="3" class="w-full px-4 py-3 rounded-xl bg-kf-light border text-xs outline-none resize-none focus:border-kf-skyDeep"></textarea>
        </div>
        <button type="submit" id="admin-submit-btn" class="w-full py-3 rounded-xl text-white font-bold text-xs shadow-sm"></button>
      </form>
    </div>
  </div>

  <div id="modal-confirm" class="fixed inset-0 z-[100] hidden items-center justify-center modal-overlay">
      <div class="bg-white rounded-[32px] w-full max-w-xs p-8 text-center shadow-2xl">
          <div id="confirm-icon-container" class="w-14 h-14 mx-auto mb-4 flex items-center justify-center rounded-2xl"></div>
          <h3 id="confirm-title" class="text-lg font-bold text-kf-dark mb-1">Konfirmasi</h3>
          <p id="confirm-desc" class="text-xs text-kf-muted mb-6">Apakah anda yakin?</p>
          <div class="flex flex-col gap-2">
            <button id="confirm-btn-primary" class="w-full py-3 rounded-xl font-bold text-xs">Yakin</button>
            <button onclick="closeConfirm()" class="w-full py-3 rounded-xl bg-kf-light font-bold text-xs">Batal</button>
          </div>
      </div>
  </div>

  <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-kf-dark text-white px-6 py-3 rounded-2xl text-sm font-medium shadow-lg opacity-0 transition-all pointer-events-none z-50"></div>

  <script>
    const currentActivePage = '<?php echo $active_page; ?>';
  </script>
  <script src="../../../assets/js/admin/app.js"></script>
  
</body>
</html>