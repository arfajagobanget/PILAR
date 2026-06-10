<?php
/**
 * template/admin/sidebar.php
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../koneksi.php';

$id_log = $_SESSION['id_user'] ?? 1;
// Join dengan tabel spesialis untuk mengambil jabatan manajer
$query_side = mysqli_query($host, "SELECT u.nama, u.username, u.role, u.foto, s.nama_spesialis 
                                   FROM `user` u 
                                   LEFT JOIN spesialis_manager s ON u.id_spesialis = s.id_spesialis 
                                   WHERE u.id_user = '$id_log'");
$user_side = mysqli_fetch_assoc($query_side);

$active_page = $active_page ?? 'dashboard';
$nama_panggilan = $user_side['nama'] ?? 'Administrator';
$inisial = strtoupper(substr($nama_panggilan, 0, 2));

function navItem(string $label, string $icon, string $url, string $current, string $key): string {
    $cls = ($current === $key) ? 'nav-item active' : 'nav-item';
    return <<<HTML
    <a href="{$url}" class="{$cls}" style="text-decoration: none; display: flex; align-items: center; gap: 0.75rem; width: 100%; border: none;">
        <i data-lucide="{$icon}"></i>
        <span>{$label}</span>
    </a>
HTML;
}
?>

<style>
  aside.sidebar {
    display: flex !important;
    flex-direction: column !important;
    height: 100vh !important;
    max-height: 100vh !important;
    position: sticky !important;
    top: 0 !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
  }
  
  .sidebar-scrollable-content {
    flex: 1 !important;
    overflow-y: auto !important;
    padding-bottom: 1rem !important;
  }

  .sidebar-fixed-bottom {
    margin-top: auto !important;
    background: inherit !important;
    padding-top: 0.5rem !important;
    z-index: 10 !important;
  }

  .sidebar-scrollable-content::-webkit-scrollbar { width: 4px; }
  .sidebar-scrollable-content::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.05); border-radius: 4px; }
</style>

<aside class="sidebar no-print">

  <div class="sidebar-scrollable-content">
    
    <div class="sidebar-brand" style="display: flex; flex-direction: column; gap: 0.4rem;">
      <div class="sidebar-brand-inner">
        <img src="/PILAR/assets/img/logo_vertikal.png" alt="Logo PILAR" style="width: 80%; height: 100%; object-fit: contain;">
      </div>
      <div>
        <span class="text-[9px] bg-kf-dark text-white px-2 py-0.5 rounded font-semibold uppercase tracking-wider">Admin Portal</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <?= navItem('Ringkasan Dasbor', 'layout-dashboard', '../dashboard/data.php', $active_page, 'dashboard') ?>
      <?= navItem('Verifikasi Laporan', 'check-square',    '../verifikasi/data.php',$active_page, 'verifikasi') ?>
      <?= navItem('Hub Chat 3-Arah',   'messages-square', '../chat/data.php',       $active_page, 'chat') ?>
      <?= navItem('Manajemen Lokasi',  'map-pin',         '../lokasi/data.php', $active_page, 'locations') ?>
      <?= navItem('Manajemen User',    'user-plus',           '../user/data.php',     $active_page, 'users') ?>
      <?= navItem('Cetak Laporan',     'printer',         '../cetak/data.php',     $active_page, 'cetak') ?>
      <?= navItem('Profil Saya',       'user-cog',        '../profil/data.php',    $active_page, 'profil') ?>
      
      <div style="margin-top: 1.5rem; margin-bottom: 0.5rem; border-top: 1px solid rgba(0,0,0,0.06); padding-top: 1rem;">
        <span style="font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; tracking-wider; padding-left: 0.5rem; display: block; margin-bottom: 0.5rem;">Data Master</span>
      </div>

      <?= navItem('Jabatan Manager',   'briefcase',       '../spesialis/data.php', $active_page, 'spesialis') ?>
      <?= navItem('Data Pelapor',      'users',       '../pelapor/data.php',   $active_page, 'pelapor_data') ?>
    </nav>

  </div> 
  
  <div class="sidebar-fixed-bottom">
    
    <div class="sidebar-user float-anim">
      <div class="sidebar-user-inner">
        <?php if (!empty($user_side['foto']) && $user_side['foto'] !== 'default.png' && file_exists(__DIR__ . '/../../assets/uploads/user/' . $user_side['foto'])): ?>
            <img src="/PILAR/assets/uploads/user/<?= $user_side['foto'] ?>" class="avatar" style="width:32px; height:32px; border-radius:12px; object-fit: cover;">
        <?php else: ?>
            <div class="avatar global-profile-photo" id="sidebar-avatar-initial"><?= $inisial ?></div>
        <?php endif; ?>
        
        <div style="min-width:0">
          <p class="avatar-name global-profile-name" id="sidebar-profile-name"><?= htmlspecialchars($nama_panggilan) ?></p>
          <p class="avatar-role global-profile-category" id="sidebar-profile-title"><?= htmlspecialchars($user_side['nama_spesialis'] ?? 'Administrator') ?></p>
        </div>
      </div>
    </div>

  <a href="#" onclick="confirmAction('Logout'); return false;" class="sidebar-logout" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
      <i data-lucide="log-out" style="width:16px;height:16px"></i>
      Keluar
  </a>

  </div>

</aside>

<div class="sidebar-overlay" onclick="closeSidebar()"></div>