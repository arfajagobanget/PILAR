<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    header('Location: /PILAR/views/auth/auth.php');
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include_once __DIR__ . '/../../koneksi.php';

$id_log = $_SESSION['id_user'] ?? 1; 
$query_side = mysqli_query($host, "SELECT nama, username, status_pengguna, foto FROM `user` WHERE id_user = '$id_log'");
$user_side = mysqli_fetch_assoc($query_side);

$nama_panggilan = $user_side['nama'] ?? 'Pelapor';
$inisial = strtoupper(substr($nama_panggilan, 0, 2));
$active = $active ?? 'dashboard';

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

<!-- =====================  SIDEBAR  ===================== -->
<aside class="sidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <img src="/PILAR/assets/img/logo_vertikal.png" alt="Logo PILAR" style="width: 80%; height: 100%; object-fit: contain;">
  </div>

  <!-- Nav -->
  <nav class="sidebar-nav">
    <?= navItem('Dashboard',       'layout-dashboard', '/PILAR/views/pelapor/dashboard/data.php',    $active, 'dashboard') ?>
    <?= navItem('Laporan Saya', 'file-text',        '/PILAR/views/pelapor/laporan_saya/data.php', $active, 'laporan') ?>
    <?= navItem('Profil Saya',  'user',             '/PILAR/views/pelapor/profil/data.php',       $active, 'profile') ?>
  </nav>

  <!-- User card -->
  <div class="sidebar-user float-anim">
    <div class="sidebar-user-inner">
      <?php if (!empty($user_side['foto']) && $user_side['foto'] !== 'default.png' && file_exists(__DIR__ . '/../../assets/uploads/user/' . $user_side['foto'])): ?>
        <img src="/PILAR/assets/uploads/user/<?= $user_side['foto'] ?>" class="avatar" style="width:32px; height:32px; border-radius:12px; object-fit: cover;">
      <?php else: ?>
        <div class="avatar global-profile-photo"><?= $inisial ?></div>
      <?php endif; ?>
      <div style="min-width:0">
        <p class="avatar-name global-profile-name"><?= htmlspecialchars($nama_panggilan) ?></p>
        <p class="avatar-role global-profile-category" style="text-transform: capitalize;"><?= htmlspecialchars($user_side['status_pengguna'] ?? 'Umum') ?></p>
      </div>
    </div>
  </div>

  <!-- Logout -->
  <a href="#" onclick="confirmAction('Logout'); return false;" class="sidebar-logout" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
      <i data-lucide="log-out" style="width:16px;height:16px"></i>
      Keluar
  </a>

</aside>

<!-- Mobile sidebar overlay (tap to close) -->
<div class="sidebar-overlay" onclick="closeSidebar()"></div>