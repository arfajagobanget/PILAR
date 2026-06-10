<?php
/**
 * views/pelapor/laporan_saya/data.php
 * Proyek PILAR - Riwayat & Manajemen List Data Pengaduan Pelapor
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../../koneksi.php'; 

$active = 'laporan';
$id_log = $_SESSION['id_user'] ?? 1;

// Query pengambil histori laporan ter-filter kepemilikan akun pelapor yang login
$query_laporan = "SELECT l.id_laporan, l.judul_laporan, l.deskripsi, l.tanggal_laporan, l.status, l.foto_sebelum,
                         IFNULL(r.nama_ruangan, 'Belum Diset') AS nama_ruangan, 
                         IFNULL(g.nama_gedung, 'Belum Diset') AS nama_gedung 
                  FROM laporan l
                  LEFT JOIN ruangan r ON l.id_ruangan = r.id_ruangan
                  LEFT JOIN gedung g ON r.id_gedung = g.id_gedung
                  WHERE l.id_pelapor = '$id_log'
                  ORDER BY l.id_laporan DESC";
                  
$result = mysqli_query($host, $query_laporan);

$data_javascript = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data_javascript[] = [
            'id'    => (int)$row['id_laporan'],
            'title' => $row['judul_laporan'], 
            'desc'  => $row['deskripsi'],     
            'loc'   => $row['nama_gedung'] . ' · ' . $row['nama_ruangan'],
            'date'  => date('d M Y', strtotime($row['tanggal_laporan'])),
            'status'=> strtolower($row['status']), 
            'icon'  => 'alert-circle', 
            'image' => $row['foto_sebelum']
        ];
    }
}

$mode_edit = false;
$data_edit = [];

if (isset($_GET['edit'])) {
    $id_edit = mysqli_real_escape_string($host, $_GET['edit']);
    
    $query_edit = mysqli_query($host, "SELECT l.*, g.id_kampus, r.id_gedung 
                                       FROM laporan l
                                       LEFT JOIN ruangan r ON l.id_ruangan = r.id_ruangan
                                       LEFT JOIN gedung g ON r.id_gedung = g.id_gedung 
                                       WHERE l.id_laporan = '$id_edit' AND l.id_pelapor = '$id_log'");
    
    if ($query_edit && mysqli_num_rows($query_edit) > 0) {
        $mode_edit = true;
        $data_edit = mysqli_fetch_assoc($query_edit);
    }
}
?>
<script>
  // FIX: Sinkronisasi Identitas Global Pengguna Aktif untuk bubble chat mapper JS
  window.id_user_logged_in = <?= $id_log; ?>;
  window.dbReports = <?php echo json_encode($data_javascript); ?>;
</script>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Saya – PILAR</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
  <link rel="stylesheet" href="../../../assets/css/pelapor/style.css">

  <style>
    html, body { overflow: auto !important; height: auto !important; min-height: 100vh; margin: 0; padding: 0; }
    .screen, #screen-dashboard, #screen-laporan, #screen-manager-profile { display: block !important; opacity: 1 !important; visibility: visible !important; transform: none !important; width: 100%; min-height: 100vh; }
    
    <?php if ($mode_edit): ?>
    #modal-laporan { display: flex !important; opacity: 1 !important; visibility: visible !important; pointer-events: auto !important; }
    <?php endif; ?>

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

<div id="screen-laporan" class="screen">
  <div class="app-layout">

    <?php include '../../../template/pelapor/sidebar.php'; ?>
    <?php include '../../../template/pelapor/mobile_header.php'; ?>

    <main class="main-content">
      <div class="laporan-header">
        <div>
          <h1 class="page-script-title">Laporan Saya</h1>
          <p class="page-sub" style="margin-top:0.25rem">Semua riwayat pengaduan kamu 📋</p>
        </div>
        <button onclick="openModal()" class="btn-primary" style="width:auto;display:flex;align-items:center;gap:0.5rem;white-space:nowrap">
          <i data-lucide="plus-circle" style="width:16px;height:16px"></i>
          Buat Laporan Baru
        </button>
      </div>

      <div class="filter-toolbar">
        <div class="filter-pills" id="filter-bar">
          <?php
          $filters = ['semua' => 'Semua', 'menunggu' => 'Menunggu', 'diverifikasi' => 'Diverifikasi', 'diproses' => 'Proses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'];
          foreach ($filters as $key => $label):
            $isActive = $key === 'semua' ? ' active-filter' : '';
          ?>
          <button class="filter-btn<?= $isActive ?>" data-filter="<?= $key ?>" onclick="renderReports('<?= $key ?>')">
            <?= $label ?>
          </button>
          <?php endforeach; ?>
        </div>

        <div class="search-box">
          <i data-lucide="search" style="width:16px;height:16px;color:var(--muted);flex-shrink:0"></i>
          <input type="text" id="report-search-bar" oninput="handleReportSearch()" placeholder="Cari laporan berdasarkan judul...">
        </div>
      </div>

      <div class="report-grid" id="report-grid"></div>
    </main>
  </div>
</div>

<?php include '../../../template/pelapor/modals.php'; ?>
<script src="../../../assets/js/pelapor/app.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    if (typeof renderReports === 'function') { renderReports('semua'); }

    // DETEKSI URL PARAMETER UNTUK TOAST NOTIFIKASI
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status) {
        if (typeof showToast === 'function') {
            switch (status) {
                case 'sukses_tambah':
                    showToast('Laporan kerusakan berhasil dikirim! Mohon tunggu verifikasi admin. ✨', 'success');
                    break;
                case 'gagal_tambah':
                    showToast('Waduh, gagal mengirim laporan. Periksa kembali isian Anda. ⚠️', 'error');
                    break;
                case 'sukses_update':
                    showToast('Perubahan laporan berhasil disimpan! 📋', 'success');
                    break;
                case 'gagal_update':
                    showToast('Gagal memperbarui data laporan. Silakan coba lagi. ❌', 'error');
                    break;
                case 'sukses_hapus':
                    showToast('Laporan berhasil dihapus secara permanen dari sistem. 🗑️', 'info');
                    break;
                case 'gagal_hapus':
                    showToast('Gagal menghapus laporan dari database.', 'error');
                    break;
            }
        }
        window.history.replaceState({}, document.title, window.location.pathname);
    }
  });

  function getChatByLaporan($host, $id_laporan) {
    $q_room = mysqli_query($host, "SELECT id_room FROM chat_room WHERE id_laporan = '$id_laporan'");
    if(mysqli_num_rows($q_room) > 0) {
        $r = mysqli_fetch_assoc($q_room);
        $id_room = $r['id_room'];
        return mysqli_query($host, "SELECT p.*, u.nama FROM pesan p LEFT JOIN user u ON p.id_pengirim = u.id_user WHERE p.id_room = '$id_room' ORDER BY p.id_pesan ASC");
    }
    return false;
}

</script>
</body>
</html>