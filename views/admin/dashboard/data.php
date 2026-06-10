<?php 
$active_page = 'dashboard'; 
include '../../../koneksi.php'; // Pastikan path koneksi sudah benar
include '../../../template/admin/header.php'; 

// 1. Ambil data statistik dari tabel laporan
$stats = [];
$statuses = ['menunggu', 'diverifikasi', 'diproses', 'selesai', 'ditolak'];

if (!isset($_SESSION['id_user'])) {
    header('Location: ../../../views/auth/auth.php');
    exit;
}

$id_log = $_SESSION['id_user'];

// Ambil nama dari database
$query_user = mysqli_query($host, "SELECT nama FROM user WHERE id_user = '$id_log'");
$data_user = mysqli_fetch_assoc($query_user);
$nama_admin = $data_user['nama'] ?? 'Admin Fasilitas'; // Fallback jika nama tidak ditemukan

foreach ($statuses as $status) {
    $q = mysqli_query($host, "SELECT COUNT(*) as total FROM laporan WHERE status = '$status'");
    $data = mysqli_fetch_assoc($q);
    $stats[$status] = $data['total'];
}

// 2. Ambil data laporan "Mendesak" untuk Admin (status menunggu atau diverifikasi)
$urgent_query = mysqli_query($host, "SELECT l.*, r.nama_ruangan FROM laporan l 
                                     LEFT JOIN ruangan r ON l.id_ruangan = r.id_ruangan 
                                     WHERE l.status IN ('menunggu', 'diverifikasi') 
                                     ORDER BY l.tanggal_laporan DESC LIMIT 5");
?>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content">
        <header class="page-header">
            <h1 class="text-2xl font-bold text-kf-dark" id="dashboard-welcome-heading">
                Hi, <?= htmlspecialchars($nama_admin); ?>! 👋
            </h1>
            <p class="text-sm text-kf-muted mt-1">Sistem Pemantauan Aset dan Keluhan Civitas Kampus PILAR</p>
        </header>
        
        <div class="stat-grid">
            <?php foreach ($statuses as $status): 
                // Menentukan ikon dan warna berdasarkan status
                $icon = 'clock'; $bg = 'var(--sand)'; $color = '#E67700';
                if ($status == 'diverifikasi') { $icon = 'shield-check'; $bg = 'var(--lavender)'; $color = '#7B2D8E'; }
                if ($status == 'diproses') { $icon = 'loader'; $bg = 'var(--light)'; $color = 'var(--sky-deep)'; }
                if ($status == 'selesai') { $icon = 'check-circle'; $bg = 'var(--mint)'; $color = '#2B8A3E'; }
                if ($status == 'ditolak') { $icon = 'x-circle'; $bg = 'var(--blush)'; $color = '#C92A2A'; }
            ?>
            <a href="status_detail.php?status=<?= $status ?>" style="text-decoration: none; display: block; color: inherit;">
                <div class="stat-card">
                    <div class="stat-icon" style="background: <?= $bg ?>;"><i data-lucide="<?= $icon ?>" class="w-4 h-4" style="color: <?= $color ?>;"></i></div>
                    <p class="stat-label"><?= ucfirst($status) ?></p>
                    <p class="stat-value"><?= $stats[$status] ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm flex flex-col">
                <h2 class="text-sm font-bold text-kf-dark mb-4 flex items-center gap-2"><i data-lucide="alert-circle" class="text-orange-400 w-4 h-4"></i> Tindakan Administrasi Terdekat</h2>
                <div class="space-y-3 flex-1 overflow-y-auto max-h-[280px]">
                    <?php while ($r = mysqli_fetch_assoc($urgent_query)): ?>
                        <div class="p-3.5 rounded-2xl bg-kf-light/60 flex items-center justify-between text-xs border border-kf-sky/10">
                            <div>
                                <span class="text-[9px] <?= $r['status'] == 'menunggu' ? 'bg-kf-sand text-orange-800' : 'bg-kf-lavender text-purple-900' ?> px-2 py-0.5 rounded font-bold uppercase mb-1 inline-block"><?= $r['status'] ?></span>
                                <p class="font-bold text-kf-dark"><?= $r['judul_laporan'] ?></p>
                                <p class="text-[10px] text-kf-muted"><?= $r['nama_ruangan'] ?> • <?= $r['tanggal_laporan'] ?></p>
                            </div>
                            <button onclick="location.href='../verifikasi/data.php'" class="px-3 py-1.5 bg-white border border-kf-sky/30 rounded-xl font-bold text-kf-skyDeep">Buka</button>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-white to-kf-light rounded-3xl p-6 border border-kf-sky/20 flex flex-col justify-between shadow-sm">
                <div>
                    <h3 class="font-bold text-kf-dark text-base">Manajemen Kerja</h3>
                    <p class="text-xs text-kf-muted leading-relaxed mt-2">Verifikasi kelayakan laporan fisik terlebih dahulu, kemudian lakukan penugasan teknisi pada baris baris tabel terpisah.</p>
                </div>
                <button onclick="location.href='../verifikasi/data.php'" class="w-full mt-6 py-3.5 bg-kf-dark text-white rounded-2xl text-xs font-semibold flex items-center justify-center gap-2 shadow-md">Buka Tabel Verifikasi</button>
            </div>
        </div>
    </main>
</div>

<?php include '../../../template/admin/footer.php'; ?>