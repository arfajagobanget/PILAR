</div> <!-- Akhir dari #app -->

  <!-- MODAL: UPDATE STATUS SELESAI -->
  <div id="modal-manager-finish" class="fixed inset-0 z-50 hidden items-center justify-center modal-overlay">
    <div class="bg-white rounded-3xl w-full max-w-md mx-4 shadow-2xl p-7">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-base text-kf-dark">Selesaikan & Perbarui Aset</h2>
        <button onclick="closeFinishModal()" class="w-8 h-8 rounded-xl bg-kf-light flex items-center justify-center"><i data-lucide="x" class="w-4 h-4"></i></button>
      </div>
      <div id="manager-modal-info" class="p-3 bg-kf-light rounded-xl text-[11px] mb-4 text-kf-dark"></div>
      
      <form onsubmit="executeFinishTask(event)">
        <input type="hidden" id="manager-target-id">
        <div class="space-y-1.5 mb-4">
          <label class="text-xs font-bold text-kf-dark block">Unggah Foto Hasil Perbaikan Terkini</label>
          <div class="flex items-center justify-center w-full">
            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-kf-sky/50 rounded-2xl cursor-pointer bg-kf-light/40 hover:bg-kf-light/80 transition">
              <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                <i data-lucide="image-plus" class="w-7 h-7 text-kf-skyDeep mb-1"></i>
                <p class="text-xs text-kf-dark font-medium" id="upload-label">Klik untuk lampirkan file foto fisik</p>
                <p class="text-[9px] text-kf-muted mt-0.5">Format Gambar JPG, JPEG, atau PNG</p>
              </div>
              <input type="file" id="manager-file-input" accept="image/*" class="hidden" onchange="handleSimulatedPhotoUpload(event)" required />
            </label>
          </div>
          <div id="photo-preview-box" class="hidden mt-2 p-2 bg-gray-50 rounded-xl border flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-cover bg-center border" id="photo-preview-thumbnail"></div>
            <p class="text-[10px] text-green-600 font-semibold flex-1">Foto aset terbaru terlampir ✔️</p>
          </div>
        </div>
        <div class="space-y-1.5 mb-6">
          <label class="text-xs font-bold text-kf-dark block">Catatan Akhir Penanganan Kerja</label>
          <textarea id="manager-note" required rows="3" placeholder="Tulis rincian tindakan perbaikan fisik di sini..." class="w-full px-4 py-3 rounded-xl bg-kf-light border text-xs outline-none resize-none focus:border-amber-500"></textarea>
        </div>
        <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-500 text-white font-bold text-xs shadow-md">Kirim Dokumen Penyelesaian</button>
      </form>
    </div>
  </div>

  <!-- MODAL CONFIRM SYSTEM -->
  <div id="modal-confirm" class="fixed inset-0 z-[100] hidden items-center justify-center modal-overlay">
      <div class="bg-white rounded-[32px] w-full max-w-xs p-8 text-center shadow-2xl">
          <div id="confirm-icon-container" class="w-14 h-14 mx-auto mb-4 flex items-center justify-center rounded-2xl"></div>
          <h3 id="confirm-title" class="text-lg font-bold text-kf-dark mb-1">Konfirmasi</h3>
          <p id="confirm-desc" class="text-xs text-kf-muted mb-6">Apakah anda yakin?</p>
          <div class="flex flex-col gap-2">
              <button id="confirm-btn-primary" class="w-full py-3 rounded-xl font-bold text-xs"></button>
              <button onclick="closeConfirm()" class="w-full py-3 rounded-xl bg-kf-light font-bold text-xs">Batal</button>
          </div>
      </div>
  </div>

  <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-kf-dark text-white px-6 py-3 rounded-2xl text-sm font-medium shadow-lg opacity-0 transition-all pointer-events-none z-50"></div>

  <script>
    const currentActivePage = '<?php echo $active_page; ?>';
  </script>

  <script src="../../../assets/js/manager_teknisi/app.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
  </script>
</body>
</html>