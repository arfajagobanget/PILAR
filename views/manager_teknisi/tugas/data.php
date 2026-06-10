<?php 
session_start();
if (!isset($_SESSION['id_user'])) {
    header('Location: /PILAR/views/auth/auth.php');
    exit;
}
if ($_SESSION['role'] !== 'manager_teknisi') {
    die("Akses ditolak! Anda bukan manager teknisi.");
}

$active_page = 'tasks'; 
include '../../../koneksi.php';
include '../../../template/manager_teknisi/header.php';

$id_log = $_SESSION['id_user']; 

$query = "SELECT ld.*, l.judul_laporan, r.nama_ruangan, l.status 
          FROM laporan_detail ld 
          JOIN laporan l ON ld.id_laporan = l.id_laporan 
          LEFT JOIN ruangan r ON l.id_ruangan = r.id_ruangan 
          WHERE ld.id_manager_teknisi = '$id_log' 
          ORDER BY ld.tanggal_penugasan DESC";

$tasks = mysqli_query($host, $query);
?>

<div class="h-full w-full overflow-auto">
    <div class="flex h-full w-full">
        <?php include '../../../template/manager_teknisi/sidebar.php'; ?>

        <main class="flex-1 overflow-auto bg-kf-cream/50 p-8">
            <header class="mb-6">
                <h1 class="font-script text-4xl text-kf-dark">Meja Kerja Penugasan</h1>
            </header>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl p-6 border shadow-sm">
                    <h2 class="text-xs font-bold uppercase mb-4 text-kf-dark">Tugas Berjalan</h2>
                    <?php 
                    $has_data = false;
                    mysqli_data_seek($tasks, 0);
                    while($row = mysqli_fetch_assoc($tasks)): 
                        if($row['status'] !== 'selesai'): 
                            $has_data = true;
                    ?>
                        <div class="p-4 bg-kf-light/50 border rounded-2xl mb-3 flex justify-between items-center">
                            <div>
                                <p class="font-bold text-sm"><?= htmlspecialchars($row['judul_laporan'] ?? 'Tanpa Judul') ?></p>
                                <p class="text-[10px] text-kf-muted"><?= htmlspecialchars($row['nama_ruangan'] ?? '-') ?></p>
                            </div>
                            <a href="?modal=<?= $row['id_laporan'] ?>" class="bg-amber-500 text-white px-4 py-2 rounded-xl text-xs font-bold">Selesaikan</a>
                        </div>
                    <?php endif; endwhile; 
                    if (!$has_data) echo "<p class='text-xs text-gray-400 italic'>Tidak ada tugas berjalan.</p>";
                    ?>
                </div>

                <div class="bg-white rounded-3xl p-6 border shadow-sm">
                    <h2 class="text-xs font-bold uppercase mb-4 text-kf-dark">Dokumentasi Riwayat Selesai</h2>
                    <?php 
                    $has_done = false;
                    mysqli_data_seek($tasks, 0);
                    while($row = mysqli_fetch_assoc($tasks)): 
                        if($row['status'] === 'selesai'): 
                            $has_done = true;
                    ?>
                        <div class="p-4 border border-green-100 bg-green-50 rounded-2xl mb-3 flex items-start gap-3">
                            <?php if (!empty($row['foto_sesudah'])): ?>
                                <img src="../../../assets/uploads/laporan/sesudah/<?= htmlspecialchars($row['foto_sesudah']) ?>" 
                                    class="w-12 h-12 rounded-xl object-cover border border-green-200" 
                                    alt="Bukti">
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-xl bg-gray-200 flex items-center justify-center text-[9px] text-gray-500">No</div>
                            <?php endif; ?>
                            
                            <div class="flex-1">
                                <p class="font-bold text-sm"><?= htmlspecialchars($row['judul_laporan'] ?? 'Tanpa Judul') ?></p>
                                <p class="text-[10px] text-green-600">Status: Selesai</p>
                                
                                <div class="flex gap-2 mt-2">
                                    <a href="?modal=<?= $row['id_laporan'] ?>" class="text-[10px] text-kf-skyDeep font-bold underline">Edit</a>
                                    <form action="../../../controllers/Tugas.php?action=batalkan_tugas" method="POST" onsubmit="return confirm('Yakin ingin membatalkan penyelesaian tugas ini?')">
                                        <input type="hidden" name="id_laporan" value="<?= $row['id_laporan'] ?>">
                                        <button type="submit" class="text-[10px] text-red-600 font-bold underline">Batalkan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; endwhile; 
                    if (!$has_done) echo "<p class='text-xs text-gray-400 italic'>Belum ada riwayat selesai.</p>";
                    ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php if (isset($_GET['modal'])): 
    $id_lap = mysqli_real_escape_string($host, $_GET['modal']);
    $data = mysqli_fetch_assoc(mysqli_query($host, "SELECT ld.*, l.status FROM laporan_detail ld JOIN laporan l ON ld.id_laporan = l.id_laporan WHERE ld.id_laporan = '$id_lap'"));
    $is_edit = ($data['status'] === 'selesai');
?>
<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white p-6 rounded-3xl w-full max-w-sm shadow-2xl relative">
        <a href="data.php" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></a>
        <h2 class="font-bold text-base mb-6 text-kf-dark"><?= $is_edit ? 'Edit Tugas' : 'Upload Bukti' ?></h2>
        <form action="../../../controllers/Tugas.php?action=selesaikan_tugas" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_laporan" value="<?= htmlspecialchars($id_lap) ?>">
            <div class="mb-6">
                <input type="file" name="foto" class="w-full text-xs border p-3 rounded-xl" accept="image/*" <?= !$is_edit ? 'required' : '' ?>>
            </div>
            <button type="submit" class="w-full py-3 bg-green-600 text-white rounded-2xl font-bold text-sm"><?= $is_edit ? 'Simpan' : 'Upload' ?></button>
        </form>
    </div>
</div>
<?php endif; ?>

<div id="toast" class="toast-notification"></div>
<style>
.toast-notification { position: fixed; bottom: 24px; right: 24px; background: #1f2937; color: #fff; padding: 12px 20px; border-radius: 16px; font-size: 11px; z-index: 99999; opacity: 0; transform: translateY(10px); transition: all 0.3s ease; pointer-events: none; }
.toast-notification.show { opacity: 1; transform: translateY(0); }
</style>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status === 'success') { showToast('Tugas Berhasil Diselesaikan! ✅'); }
    else if (status === 'cancelled') { showToast('Penyelesaian Tugas Dibatalkan! ↩️'); }
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg; t.classList.add('show');
        setTimeout(() => { t.classList.remove('show'); }, 3000);
    }
    if (status) { window.history.replaceState({}, document.title, window.location.pathname); }
</script>

<?php include '../../../template/manager_teknisi/footer.php';?>