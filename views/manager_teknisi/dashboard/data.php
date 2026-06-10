<?php 
/**
 * dashboard/data.php
 */
$active_page = 'dashboard'; 
include '../../../koneksi.php';
include '../../../template/manager_teknisi/header.php'; 

if (session_status() === PHP_SESSION_NONE) session_start();
$id_log = $_SESSION['id_user'] ?? 1;

// 1. Ambil nama user untuk sambutan
$query_user = mysqli_query($host, "SELECT nama FROM user WHERE id_user = '$id_log'");
$data_user = mysqli_fetch_assoc($query_user);
$nama_user = $data_user['nama'] ?? 'User';

// 2. Ambil Statistik
// Proses: Laporan yang ditugaskan ke user ini dan belum selesai
$stats_proses = mysqli_fetch_assoc(mysqli_query($host,
"SELECT COUNT(*) as total
 FROM laporan_detail ld
 JOIN laporan l ON ld.id_laporan = l.id_laporan
 WHERE ld.id_manager_teknisi = '$id_log'
 AND l.status = 'diproses'"
))['total'];

$stats_selesai = mysqli_fetch_assoc(mysqli_query($host,
"SELECT COUNT(*) as total
 FROM laporan_detail ld
 JOIN laporan l ON ld.id_laporan = l.id_laporan
 WHERE ld.id_manager_teknisi = '$id_log'
 AND l.status = 'selesai'"
))['total'];
// Total: Semua laporan yang pernah ditugaskan ke user ini
$stats_total = mysqli_fetch_assoc(mysqli_query($host, "SELECT COUNT(*) as total FROM laporan_detail WHERE id_manager_teknisi = '$id_log'"))['total'];

// 3. Ambil Tugas Mendesak (Join 3 Tabel)
$urgent_query = mysqli_query($host, "
    SELECT 
        ld.id_laporan, 
        l.judul_laporan, 
        r.nama_ruangan,
        ld.tanggal_penugasan
    FROM laporan_detail ld
    JOIN laporan l ON ld.id_laporan = l.id_laporan
    JOIN ruangan r ON l.id_ruangan = r.id_ruangan
    WHERE ld.id_manager_teknisi = '$id_log'
    AND l.status = 'diproses'
    ORDER BY ld.tanggal_penugasan DESC 
    LIMIT 5
");
?>

<div class="h-full w-full overflow-auto">
    <div class="flex h-full w-full">
        
        <?php include '../../../template/manager_teknisi/sidebar.php'; ?>

        <main class="flex-1 overflow-auto bg-kf-cream/50 p-8">
            <header class="mb-8">
                <h1 class="text-2xl font-bold text-kf-dark">Hi, <?= htmlspecialchars($nama_user); ?>! 👋</h1>
                <p class="text-sm text-kf-muted mt-1">Ringkasan status performa perbaikan dan antrean kerja Anda.</p>
            </header>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-kf-sky/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-xl bg-kf-light flex items-center justify-center text-kf-skyDeep"><i data-lucide="loader" class="w-5 h-5"></i></div>
                        <span class="text-xs font-medium text-kf-muted">Dalam Proses</span>
                    </div>
                    <p class="text-3xl font-bold text-kf-dark"><?= $stats_proses ?></p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-kf-sky/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-xl bg-kf-mint flex items-center justify-center text-green-600"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
                        <span class="text-xs font-medium text-kf-muted">Selesai</span>
                    </div>
                    <p class="text-3xl font-bold text-kf-dark"><?= $stats_selesai ?></p>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-kf-sky/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-xl bg-kf-lavender flex items-center justify-center text-purple-600"><i data-lucide="briefcase" class="w-5 h-5"></i></div>
                        <span class="text-xs font-medium text-kf-muted">Total Penugasan</span>
                    </div>
                    <p class="text-3xl font-bold text-kf-dark"><?= $stats_total ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm flex flex-col">
                    <h2 class="text-sm font-bold text-kf-dark mb-4 flex items-center gap-2"><i data-lucide="alert-circle" class="text-amber-500 w-4 h-4"></i> Tugas Mendesak</h2>
                    <div class="space-y-3 flex-1 overflow-y-auto max-h-[250px] pr-1">
                        <?php while ($row = mysqli_fetch_assoc($urgent_query)): ?>
                            <div class="p-4 rounded-2xl bg-kf-light/50 border border-kf-sky/10 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-kf-dark text-xs"><?= htmlspecialchars($row['judul_laporan'] ?? 'Tanpa Judul') ?></p>
                                    <p class="text-[10px] text-kf-muted"><?= htmlspecialchars($row['nama_ruangan'] ?? 'Ruangan Tidak Ditemukan') ?></p>
                                </div>
                                <a href="../tugas/data.php" class="px-3 py-1.5 bg-white border border-kf-sky/20 rounded-xl text-[10px] font-bold text-kf-skyDeep">Detail</a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-white to-kf-light rounded-3xl p-6 border border-kf-sky/20 flex flex-col justify-between shadow-sm">
                    <div>
                        <h3 class="font-bold text-kf-dark text-base">Alur Kerja Perbaikan</h3>
                        <p class="text-xs text-kf-muted leading-relaxed mt-2">Periksa kerusakan di lapangan, lakukan perbaikan, unggah bukti foto aset terbaru, lalu ubah status.</p>
                    </div>
                    <a href="../tugas/data.php" class="w-full mt-6 py-3.5 bg-kf-dark text-white rounded-2xl text-xs font-semibold shadow-md flex items-center justify-center gap-2">Buka Daftar Tugas</a>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../../../template/manager_teknisi/footer.php';?>