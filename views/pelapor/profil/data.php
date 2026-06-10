<?php
/**
 * views/pelapor/profil/data.php
 * Proyek PILAR - Form & Manajemen Akun Profil Pelapor Dinamis
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../../koneksi.php';

$active = 'profile';
$id_log = $_SESSION['id_user'] ?? 1;

$query_prof = mysqli_query($host, "SELECT * FROM `user` WHERE id_user = '$id_log'");
$p = mysqli_fetch_assoc($query_prof);

$inisial = strtoupper(substr($p['nama'] ?? 'P', 0, 2));
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya – PILAR</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
  <link rel="stylesheet" href="../../../assets/css/pelapor/style.css">

  <style>
    html, body { overflow: auto !important; height: auto !important; min-height: 100vh; margin: 0; padding: 0; }
    .screen, #screen-dashboard, #screen-laporan, #screen-manager-profile { display: block !important; opacity: 1 !important; visibility: visible !important; transform: none !important; width: 100%; min-height: 100vh; }
    @media (min-width: 769px) {
      html, body { overflow: hidden !important; height: 100% !important; }
      .screen, #screen-dashboard, #screen-laporan, #screen-manager-profile { display: flex !important; }
      .app-layout { display: flex !important; width: 100%; height: 100vh !important; overflow: hidden !important; }
      .sidebar { display: flex !important; flex-direction: column; height: 100vh !important; position: sticky !important; top: 0; background: #ffffff !important; border-right: 1px solid var(--blush) !important; }
      .main-content { flex: 1 !important; overflow-y: auto !important; height: 100vh !important; padding: 2rem; }
    }
    @media (max-width: 768px) {
      .app-layout { display: block !important; width: 100%; }
      .sidebar { height: 100vh !important; position: fixed !important; top: 0; left: 0; z-index: 9999 !important; background: #ffffff !important; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
      .sidebar.active { display: flex !important; }
      .main-content { display: block !important; width: 100% !important; padding: 1rem !important; padding-top: 5rem !important; overflow: visible !important; }
    }
  </style>
</head>
<body>

<div id="screen-manager-profile" class="screen">
  <div class="app-layout">

    <?php include '../../../template/pelapor/sidebar.php'; ?>
    <?php include '../../../template/pelapor/mobile_header.php'; ?>

    <main class="main-content">
      <header style="margin-bottom:1.5rem">
        <h1 class="page-script-title">Manajemen Akun Profil Saya</h1>
      </header>

      <div class="profile-card slide-up">
        <div class="profile-top">
          <div class="profile-avatar-wrap">
            <?php if(!empty($p['foto']) && $p['foto'] !== 'default.png' && file_exists('../../../assets/uploads/user/' . $p['foto'])): ?>
               <div class="profile-avatar profile-avatar-el" id="profile-card-photo" style="background-image: url('../../../assets/uploads/user/<?= $p['foto'] ?>'); background-size: cover; background-position: center;"></div>
            <?php else: ?>
               <div class="profile-avatar profile-avatar-el" id="profile-card-photo"><?= $inisial ?></div>
            <?php endif; ?>
            <label class="profile-cam-btn" title="Ganti foto">
              <i data-lucide="camera" style="width:14px;height:14px"></i>
              <input type="file" id="photo-input" accept="image/*" style="display:none" onchange="ajaxUpdatePhotoPelapor(event)">
            </label>
          </div>

          <div>
            <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.25rem">
              <span class="profile-info-name profile-card-name-el" id="profile-card-name"><?= htmlspecialchars($p['nama'] ?? 'Pelapor') ?></span>
              <span class="profile-badge profile-card-category-el" id="profile-card-category-badge" style="text-transform: capitalize;"><?= htmlspecialchars($p['status_pengguna'] ?? 'Umum') ?></span>
            </div>
            <p class="profile-role-label">
              <i data-lucide="user" style="width:13px;height:13px;color:#f59e0b;display:inline;vertical-align:middle"></i>
              Role = Pelapor
            </p>
            <p class="profile-username profile-card-username-el" id="profile-card-username-label">@<?= htmlspecialchars($p['username'] ?? 'username') ?></p>
          </div>
        </div>

        <form id="form-profil-pelapor" onsubmit="ajaxUpdateInfoPelapor(event)">
          <input type="hidden" name="id_user" value="<?= $p['id_user'] ?>">

          <div class="form-grid-2">
            <div>
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="nama" value="<?= htmlspecialchars($p['nama'] ?? '') ?>" required class="form-input-sm">
            </div>
            <div>
              <label class="form-label">Username Aplikasi</label>
              <input type="text" name="username" value="<?= htmlspecialchars($p['username'] ?? '') ?>" required class="form-input-sm">
            </div>
          </div>

          <div class="form-grid-2">
            <div>
              <label class="form-label">Nomor Telepon / WhatsApp</label>
              <input type="tel" name="no_tlp" value="<?= htmlspecialchars($p['no_tlp'] ?? '') ?>" class="form-input-sm" placeholder="Masukkan No. HP/WA">
            </div>
            <div>
              <label class="form-label">Alamat Email</label>
              <input type="email" name="email" value="<?= htmlspecialchars($p['email'] ?? '') ?>" required class="form-input-sm">
            </div>
          </div>

          <div class="form-grid-2">
            <div>
              <label class="form-label">Jabatan / Keahlian</label>
              <input type="text" value="Role = Pelapor" disabled class="form-input-sm" style="cursor:not-allowed;">
            </div>
            <div>
              <label class="form-label">Kategori Pengguna</label>
              <select name="status_pengguna" class="form-input-sm">
                <option value="mahasiswa" <?= ($p['status_pengguna'] === 'mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                <option value="dosen" <?= ($p['status_pengguna'] === 'dosen') ? 'selected' : '' ?>>Dosen</option>
                <option value="staff" <?= ($p['status_pengguna'] === 'staff') ? 'selected' : '' ?>>Staff Kampus</option>
              </select>
            </div>
          </div>

          <hr class="form-divider">
          
          <div style="margin-bottom:1rem">
            <label class="form-label">Kata Sandi Baru</label>
            <input type="password" name="password" placeholder="••••••••" class="form-input-sm">
          </div>

          <div style="display:flex;justify-content:flex-end">
            <button type="submit" class="btn-save">Simpan Perubahan Akun</button>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>

<?php include '../../../template/pelapor/modals.php'; ?>
<script src="../../../assets/js/pelapor/app.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (typeof lucide !== 'undefined') { lucide.createIcons(); }
  });

  function ajaxUpdateInfoPelapor(e) {
      e.preventDefault();
      const formData = new FormData(document.getElementById('form-profil-pelapor'));
      fetch('../../../controllers/Profil.php?action=update_info', { method: 'POST', body: formData })
      .then(res => res.json())
      .then(data => {
          if(data.status === 'success') {
              if (typeof showToast === 'function') {
                  showToast('Profil kamu berhasil diperbarui! ✨', 'success');
              } else {
                  alert('Profil kamu berhasil diperbarui! ✨');
              }
              setTimeout(() => { window.location.reload(); }, 1200);
          } else {
              if (typeof showToast === 'function') {
                  showToast('Gagal: ' + data.message, 'error');
              } else {
                  alert('Gagal: ' + data.message);
              }
          }
      }).catch(() => {
          if (typeof showToast === 'function') {
              showToast('Gagal memproses ke database ❌', 'error');
          } else {
              alert('Gagal memproses ke database ❌');
          }
      });
  }

  function ajaxUpdatePhotoPelapor(e) {
      if(e.target.files.length === 0) return;
      const formData = new FormData();
      formData.append('foto', e.target.files[0]);

      fetch('../../../controllers/Profil.php?action=update_foto', { method: 'POST', body: formData })
      .then(res => res.json())
      .then(data => {
          if(data.status === 'success') {
              if (typeof showToast === 'function') {
                  showToast('Foto profil berhasil diunggah! 📸', 'success');
              } else {
                  alert('Foto profil berhasil diunggah! 📸');
              }
              setTimeout(() => { window.location.reload(); }, 1200);
          } else {
              if (typeof showToast === 'function') {
                  showToast('Gagal: ' + data.message, 'error');
              } else {
                  alert('Gagal: ' + data.message);
              }
          }
      }).catch(() => {
          if (typeof showToast === 'function') {
              showToast('Gagal memproses berkas gambar ❌', 'error');
          } else {
              alert('Gagal memproses berkas gambar ❌');
          }
      });
  }
</script>
</body>
</html>