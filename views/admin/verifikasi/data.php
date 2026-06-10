<?php 
/**
 * views/admin/verifikasi/data.php
 */
$active_page = 'verifikasi'; 
require_once __DIR__ . '/../../../controllers/Verifikasi.php';
require_once __DIR__ . '/../../../template/admin/header.php'; 



$laporan_list = getLaporanData($host);
?>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content">
        <header class="mb-6"><h1 class="font-script text-4xl text-kf-dark">Meja Pemeriksaan & Log Penugasan</h1></header>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-kf-sky/10 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-kf-sky/10 text-xs font-bold text-kf-muted uppercase bg-kf-light/50">
                            <th class="p-4">Laporan</th>
                            <th class="p-4">Lokasi</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Tahap 1: Verifikasi</th>
                            <th class="p-4 text-center">Tahap 2: Penugasan</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-kf-sky/5">
                        <?php while ($r = mysqli_fetch_assoc($laporan_list)): ?>
                        <tr class="hover:bg-kf-light/30 transition">
                            <td class="p-4">
                                <p class="font-bold text-kf-dark"><?= htmlspecialchars($r['judul_laporan']) ?></p>
                                <p class="text-[10px] text-kf-muted">Oleh: <?= htmlspecialchars($r['nama_pelapor']) ?></p>
                            </td>
                            <td class="p-4"><?= htmlspecialchars($r['nama_ruangan']) ?></td>
                            <td class="p-4">
                                <span class="status-<?= $r['status'] ?> font-bold px-2 py-0.5 rounded-full uppercase">
                                    <?= htmlspecialchars($r['status']) ?>
                                </span>
                            </td>
                            
                            <!-- Tahap 1: Verifikasi (Kode Lama) -->
                            <td class="p-4 text-center">
                                <?php if ($r['status'] == 'menunggu'): ?>
                                    <div class="flex justify-center gap-1">
                                        <form action="../../../controllers/Verifikasi.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $r['id_laporan'] ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="p-2 bg-kf-mint text-green-700 rounded-xl"><i data-lucide="check" class="w-3.5 h-3.5"></i></button>
                                        </form>
                                        <button type="button" onclick="showRejectModal(<?= $r['id_laporan'] ?>)" class="p-2 bg-kf-blush text-red-700 rounded-xl"><i data-lucide="x" class="w-3.5 h-3.5"></i></button>
                                    </div>
                                <?php elseif ($r['status'] == 'diverifikasi' || $r['status'] == 'ditolak'): ?>
                                    <form action="../../../controllers/Verifikasi.php" method="POST">
                                        <input type="hidden" name="id" value="<?= $r['id_laporan'] ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <button type="submit" class="text-amber-600 font-bold text-[10px]"><i data-lucide="undo-2" class="w-3.5 h-3.5"></i> Batal</button>
                                    </form>
                                <?php endif; ?>
                            </td>

                            <!-- Tahap 2: Penugasan (Kode Baru) -->
                            <td class="p-4 text-center">
                                <?php if ($r['status'] == 'diverifikasi'): ?>
                                    <button type="button" onclick="showAssignModal(<?= $r['id_laporan'] ?>)" class="px-3 py-1.5 bg-kf-dark text-white rounded-xl text-[11px] font-medium shadow-sm">Tugaskan</button>
                                <?php elseif ($r['status'] == 'diproses'): ?>
                                    <div class="flex flex-col gap-1">
                                        <button type="button" onclick="showAssignModal(<?= $r['id_laporan'] ?>)" class="text-[10px] font-bold text-kf-dark underline">Edit</button>
                                        <form action="../../../controllers/Verifikasi.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $r['id_laporan'] ?>">
                                            <input type="hidden" name="action" value="cancel_process">
                                            <button type="submit" class="text-[10px] font-bold text-red-600 underline">Batalkan Penugasan</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div id="modal-reject-custom" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-3xl w-full max-w-sm p-6 m-4 shadow-2xl relative">
        <button onclick="hideRejectModal()" class="absolute top-4 right-4 p-2 bg-gray-100 rounded-xl hover:bg-gray-200">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
        <h3 class="font-bold text-base mb-4">Alasan Penolakan</h3>
        <form action="../../../controllers/Verifikasi.php" method="POST">
            <input type="hidden" name="id" id="reject-id-val">
            <input type="hidden" name="action" value="reject">
            <textarea name="catatan" class="w-full p-3 border rounded-xl text-xs mb-4" rows="3" required placeholder="Tulis alasan..."></textarea>
            <div class="flex gap-2">
                <button type="button" onclick="hideRejectModal()" class="w-full py-2 bg-gray-100 rounded-xl text-xs font-bold">Batal</button>
                <button type="submit" class="w-full py-2 bg-red-600 text-white rounded-xl text-xs font-bold">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-assign-custom" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-3xl w-full max-w-sm p-6 m-4 shadow-2xl relative">
        <button onclick="hideAssignModal()" class="absolute top-4 right-4 p-2 bg-gray-100 rounded-xl hover:bg-gray-200">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
        <h3 class="font-bold text-base mb-4">Penugasan Teknisi</h3>
        <form action="../../../controllers/Verifikasi.php" method="POST">
            <input type="hidden" name="id" id="assign-id-val">
            <input type="hidden" name="action" value="assign">
            <select name="manager_id" class="w-full p-3 border rounded-xl text-xs mb-3" required>
                <option value="">-- Pilih Manager Teknisi --</option>
                <?php
                // Pastikan variabel $host (koneksi) sudah tersedia
                // Jika tidak muncul, coba tambahkan global $host; di atas
                $mgrs = mysqli_query($host, "SELECT id_user, nama FROM user WHERE role = 'manager_teknisi'");
                
                // Debugging: Cek apakah query berhasil
                if (!$mgrs) {
                    echo "<option disabled>Error: " . mysqli_error($host) . "</option>";
                } else {
                    while($m = mysqli_fetch_assoc($mgrs)) {
                        echo "<option value='".$m['id_user']."'>".htmlspecialchars($m['nama'])."</option>";
                    }
                }
                ?>
            </select>
            <textarea name="catatan_tugas" class="w-full p-3 border rounded-xl text-xs mb-4" rows="3" placeholder="Instruksi tugas..." required></textarea>
            <div class="flex gap-2">
                <button type="button" onclick="hideAssignModal()" class="w-full py-2 bg-gray-100 rounded-xl text-xs font-bold">Batal</button>
                <button type="submit" class="w-full py-2 bg-kf-dark text-white rounded-xl text-xs font-bold">Kirim Tugas</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(id) { document.getElementById('reject-id-val').value = id; document.getElementById('modal-reject-custom').classList.replace('hidden', 'flex'); }
function hideRejectModal() { document.getElementById('modal-reject-custom').classList.add('hidden'); document.getElementById('modal-reject-custom').classList.remove('flex'); }

function showAssignModal(id) { document.getElementById('assign-id-val').value = id; document.getElementById('modal-assign-custom').classList.replace('hidden', 'flex'); }
function hideAssignModal() { document.getElementById('modal-assign-custom').classList.add('hidden'); document.getElementById('modal-assign-custom').classList.remove('flex'); }
</script>

<?php include '../../../template/admin/footer.php'; ?>