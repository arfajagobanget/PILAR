<?php
/**
 * template/pelapor/modals.php
 * Proyek PILAR - Semua komponen modal overlays (laporan baru, detail, chat, confirm, zoom foto)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../koneksi.php';

$id_log = $_SESSION['id_user'] ?? 1;
$query_modal = mysqli_query($host, "SELECT nama FROM `user` WHERE id_user = '$id_log'");
$user_modal = mysqli_fetch_assoc($query_modal);

$mode_edit = $mode_edit ?? false;
$data_edit = $data_edit ?? [];
?>

<div id="modal-laporan" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <h2 class="modal-title"><?= $mode_edit ? 'Edit Laporan' : 'Laporan Baru'; ?></h2>
      
      <a href="data.php" class="modal-close" style="color: inherit; text-decoration: none;">
        <i data-lucide="x" style="width:20px;height:20px"></i>
      </a>
    </div>

    <form action="../../../controllers/laporanSaya.php<?= $mode_edit ? '?id_laporan='.$data_edit['id_laporan'] : ''; ?>" method="POST" enctype="multipart/form-data">

      <input type="hidden" name="id_pelapor" value="<?= $id_log; ?>">
      <?php if ($mode_edit): ?>
        <input type="hidden" name="foto_lama" value="<?= $data_edit['foto_sebelum']; ?>">
        <input type="hidden" name="status" value="<?= $data_edit['status']; ?>">
      <?php endif; ?>

      <div class="grid-cols-2-modal" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
        <input type="text" value="<?= htmlspecialchars($user_modal['nama'] ?? 'Pelapor'); ?>" disabled class="form-input" style="color:var(--muted);cursor:not-allowed;opacity:.65">
        <input type="text" value="<?= $mode_edit ? date('d M Y', strtotime($data_edit['tanggal_laporan'])) : date('d M Y'); ?>" disabled class="form-input" style="color:var(--muted);cursor:not-allowed;opacity:.65">
      </div>

      <select id="dropdown-kampus" name="id_kampus" class="form-select" style="margin-bottom:1rem" onchange="pilihKampus()" required>
        <option value="">Pilih Kampus</option>
        <?php
        $query_kampus = mysqli_query($host, "SELECT * FROM kampus");
        while ($k = mysqli_fetch_array($query_kampus)) {
            $selected = ($mode_edit && $k['id_kampus'] == $data_edit['id_kampus']) ? 'selected' : '';
            echo "<option value='".$k['id_kampus']."' $selected>".$k['nama_kampus']."</option>";
        }
        ?>
      </select>

      <div class="grid-cols-2-modal" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
        <select id="dropdown-gedung" name="id_gedung" class="form-select" onchange="pilihGedung()" required>
          <option value="">Pilih Gedung</option>
          <?php
          if ($mode_edit) {
              $query_gedung = mysqli_query($host, "SELECT * FROM gedung WHERE id_kampus = '".$data_edit['id_kampus']."'");
              while ($g = mysqli_fetch_array($query_gedung)) {
                  $selected = ($g['id_gedung'] == $data_edit['id_gedung']) ? 'selected' : '';
                  echo "<option value='".$g['id_gedung']."' $selected>".$g['nama_gedung']."</option>";
              }
          }
          ?>
        </select>
        
        <select id="dropdown-ruangan" name="id_ruangan" class="form-select" required>
          <option value="">Pilih Ruangan / Area</option>
          <?php
          if ($mode_edit) {
              $query_ruangan = mysqli_query($host, "SELECT * FROM ruangan WHERE id_gedung = '".$data_edit['id_gedung']."'");
              while ($r = mysqli_fetch_array($query_ruangan)) {
                  $selected = ($r['id_ruangan'] == $data_edit['id_ruangan']) ? 'selected' : '';
                  echo "<option value='".$r['id_ruangan']."' $selected>".$r['nama_ruangan']."</option>";
              }
          }
          ?>
        </select>
      </div>

      <input type="text" name="judul_laporan" placeholder="Judul Pengaduan" 
             value="<?= $mode_edit ? htmlspecialchars($data_edit['judul_laporan']) : ''; ?>" 
             class="form-input" style="margin-bottom:1rem;" required>

      <div class="upload-zone" onclick="document.getElementById('file-input-modal').click()" style="margin-bottom:1rem; cursor:pointer;">
        <input type="file" id="file-input-modal" name="foto_sebelum" accept="image/*" style="display:none" onchange="document.getElementById('file-placeholder').innerText = this.files[0].name">
        <i data-lucide="camera" style="width:32px;height:32px;color:var(--sky);margin:0 auto 0.5rem;display:block"></i>
        <p id="file-placeholder" style="font-size:0.75rem;color:var(--muted)">
          <?= $mode_edit ? 'File aktif: ' . $data_edit['foto_sebelum'] : 'Unggah Foto Kerusakan'; ?>
        </p>
      </div>

      <textarea name="deskripsi" placeholder="Detail kerusakan..." rows="3" 
                class="form-input" style="resize:none;margin-bottom:1.5rem" required><?= $mode_edit ? htmlspecialchars($data_edit['deskripsi']) : ''; ?></textarea>

      <button type="submit" name="<?= $mode_edit ? 'update' : 'tambah'; ?>" class="btn-primary" style="display:flex;align-items:center;justify-content:center;gap:0.5rem; width:100%;">
        <i data-lucide="<?= $mode_edit ? 'save' : 'send'; ?>" style="width:16px;height:16px"></i>
        <?= $mode_edit ? 'Simpan Perubahan' : 'Kirim Laporan'; ?>
      </button>
    </form>
  </div>
</div>

<script>
function pilihKampus() {
    const idKampus = document.getElementById('dropdown-kampus').value;
    const dropdownGedung = document.getElementById('dropdown-gedung');
    const dropdownRuangan = document.getElementById('dropdown-ruangan');

    if (!idKampus) {
        dropdownGedung.innerHTML = '<option value="">Pilih Gedung</option>';
        dropdownRuangan.innerHTML = '<option value="">Pilih Ruangan / Area</option>';
        return;
    }

    fetch(`/PILAR/controllers/laporanSaya.php?action=get_gedung&id_kampus=${idKampus}`)
        .then(response => response.text())
        .then(data => {
            dropdownGedung.innerHTML = data;
            dropdownRuangan.innerHTML = '<option value="">Pilih Ruangan / Area</option>'; 
        });
}

function pilihGedung() {
    const idGedung = document.getElementById('dropdown-gedung').value;
    const dropdownRuangan = document.getElementById('dropdown-ruangan');

    if (!idGedung) {
        dropdownRuangan.innerHTML = '<option value="">Pilih Ruangan / Area</option>';
        return;
    }

    fetch(`/PILAR/controllers/laporanSaya.php?action=get_ruangan&id_gedung=${idGedung}`)
        .then(response => response.text())
        .then(data => {
            dropdownRuangan.innerHTML = data;
        });
}
</script>

<div id="modal-detail" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <h2 class="modal-title">Detail Laporan</h2>
      <button onclick="closeDetailModal()" class="modal-close">
        <i data-lucide="x" style="width:20px;height:20px"></i>
      </button>
    </div>
    <div id="detail-content"></div>
  </div>
</div>

<div id="modal-chat" class="modal-overlay" style="z-index:60">
  <div class="chat-modal-box">
    <div class="chat-header">
      <p class="chat-header-title">PILAR – CHAT</p>
      <p class="chat-header-sub" id="chat-header-subtitle">AC Ruang Kelas Bocor</p>
      <p class="chat-header-participants" id="chat-header-participants">Admin, Pelapor, Teknisi</p>
      <button onclick="closeChat()" class="chat-close">
        <i data-lucide="x" style="width:20px;height:20px"></i>
      </button>
    </div>
    <div class="chat-body" id="chat-messages"></div>
    <div class="chat-footer">
      <div class="chat-input-wrap">
        <input type="text" id="chat-input-message" placeholder="Ketik pesanmu...">
        <button onclick="sendChatMessage()" class="chat-send">
          <i data-lucide="send" style="width:16px;height:16px"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<div id="modal-confirm" class="modal-overlay" style="z-index:100">
  <div class="confirm-box">
    <div class="confirm-icon-wrap" id="confirm-icon-container"></div>
    <h3 class="confirm-title" id="confirm-title">Konfirmasi</h3>
    <p class="confirm-desc" id="confirm-desc">Apakah anda yakin ingin melanjutkan tindakan ini?</p>
    <div class="confirm-actions">
      <button id="confirm-btn-primary" class="btn-confirm-primary"></button>
      <button onclick="closeConfirm()" class="btn-cancel">Batal</button>
    </div>
  </div>
</div>

<div id="toast"></div>

<div id="modal-foto-full" class="modal-overlay" onclick="closeFotoFullModal()" style="z-index: 150; justify-content: center; align-items: center;">
  <div style="position: relative; max-width: 90%; max-height: 90%; display: flex; justify-content: center; align-items: center;">
    <button onclick="closeFotoFullModal()" class="modal-close" style="position: absolute; top: -2.5rem; right: 0; color: white; background: rgba(0,0,0,0.5); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer;">
      <i data-lucide="x" style="width:20px;height:20px"></i>
    </button>
    <img id="foto-full-src" src="" alt="Foto Kerusakan Full" style="max-width: 100%; max-height: 80vh; object-fit: contain; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
  </div>
</div>

<script>
function openFotoFullModal(src) {
    const modal = document.getElementById('modal-foto-full');
    const img = document.getElementById('foto-full-src');
    if (modal && img) {
        img.src = src;
        modal.classList.add('open');
    }
}
function closeFotoFullModal() {
    const modal = document.getElementById('modal-foto-full');
    if (modal) {
        modal.classList.remove('open');
    }
}
</script>