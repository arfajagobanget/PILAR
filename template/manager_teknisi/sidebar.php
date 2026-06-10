<?php
/**
 * template/manager/sidebar.php
 */
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../koneksi.php';

$id_log = $_SESSION['id_user'] ?? 1;
$query_side = mysqli_query($host, "SELECT u.nama, u.foto, s.nama_spesialis 
                                   FROM `user` u 
                                   LEFT JOIN spesialis_manager s ON u.id_spesialis = s.id_spesialis 
                                   WHERE u.id_user = '$id_log'");
$user_side = mysqli_fetch_assoc($query_side);

$active_page = $active_page ?? 'dashboard';
$nama_panggilan = $user_side['nama'] ?? 'User';
$inisial = strtoupper(substr($nama_panggilan, 0, 2));

// Helper function untuk item navigasi
function navItem(string $label, string $icon, string $url, string $current, string $key): string {
    $activeCls = ($current === $key) ? 'active-nav' : '';
    return <<<HTML
    <a href="{$url}" class="sidebar-item {$activeCls} w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm text-left text-kf-dark hover:bg-kf-sky/5 transition-all"> 
        <i data-lucide="{$icon}" class="w-[18px] h-[18px]"></i> {$label}
    </a>
HTML;
}
?>

<aside class="w-64 bg-white/90 backdrop-blur-sm border-r border-kf-sky/15 flex flex-col shrink-0 h-screen sticky top-0">
  <div class="p-6 pb-4">
    <div class="flex flex-col gap-2">
      <div class="sidebar-brand flex items-center justify-start">
        <img src="../../../assets/img/logo_vertikal.png" alt="Logo PILAR" style="width: 80%; height: 100%; object-fit: contain;">
      </div>
      <div class="flex items-center">
        <span class="text-[10px] bg-amber-600 text-white px-2.5 py-0.5 rounded font-semibold uppercase tracking-wider shadow-2xs">
          Manager
        </span>
      </div>
    </div>
  </div>
  
  <nav class="flex-1 px-3 space-y-1 mt-4 overflow-y-auto">
    <?= navItem('Ringkasan Dasbor', 'layout-dashboard', '../dashboard/data.php', $active_page, 'dashboard') ?>
    <?= navItem('Tugas & Penugasan', 'briefcase', '../tugas/data.php', $active_page, 'tasks') ?>
    <?= navItem('Hub Chat 3-Arah', 'message-square', '../chat/data.php', $active_page, 'chat') ?>
    <?= navItem('Profil Saya', 'user-cog', '../profil/data.php', $active_page, 'profile') ?>
  </nav>
  
  <div class="p-4 mx-3 mb-4 bg-kf-light rounded-2xl float-anim border border-kf-sky/15">
    <div class="flex items-center gap-3">
      <?php if (!empty($user_side['foto']) && file_exists(__DIR__ . '/../../assets/uploads/user/' . $user_side['foto'])): ?>
          <img src="../../../assets/uploads/user/<?= $user_side['foto'] ?>" class="w-9 h-9 rounded-full object-cover">
      <?php else: ?>
          <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-500 to-kf-dark flex items-center justify-center text-white text-xs font-bold">
            <?= $inisial ?>
          </div>
      <?php endif; ?>
      
      <div class="min-w-0">
        <p class="text-xs font-semibold text-kf-dark truncate"><?= htmlspecialchars($nama_panggilan) ?></p>
        <p class="text-[10px] text-kf-muted truncate"><?= htmlspecialchars($user_side['nama_spesialis'] ?? 'Manager') ?></p>
      </div>
    </div>
  </div>
  
  <button onclick="confirmAction('Logout')" class="mx-3 mb-4 flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-xs text-kf-muted hover:bg-kf-blush/30 hover:text-red-400 transition"> 
    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar 
  </button>
</aside>