<?php 
$active_page = 'cetak'; 
require_once '../../../koneksi.php'; // Pastikan koneksi tersedia
include '../../../template/admin/header.php'; 
?>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content bg-white p-6 sm:p-12 print-page">
        <!-- Form Filter Dinamis -->
        <form method="GET" class="border border-gray-200 bg-gray-50/50 p-5 rounded-2xl mb-8 no-print space-y-4 shadow-2xs">
            <div><h1 class="text-base font-bold text-kf-dark">Penyaringan Komprehensif Berkas Rekapitulasi</h1></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                <div><label class="text-[10px] font-bold text-kf-dark block mb-1">Tgl Mulai</label><input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>" class="w-full px-3 py-2 bg-white border rounded-xl text-xs outline-none"></div>
                <div><label class="text-[10px] font-bold text-kf-dark block mb-1">Tgl Akhir</label><input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>" class="w-full px-3 py-2 bg-white border rounded-xl text-xs outline-none"></div>
                
                <div><label class="text-[10px] font-bold text-kf-dark block mb-1">Status Laporan</label>
                    <select name="status" class="w-full px-3 py-2 bg-white border rounded-xl text-xs outline-none">
                        <option value="">Semua Status</option>
                        <?php 
                        $status_list = ['menunggu', 'diverifikasi', 'ditugaskan', 'diproses', 'selesai', 'ditolak'];
                        foreach($status_list as $s) { echo "<option value='$s' ".(($_GET['status']??'')==$s?'selected':'').">".ucfirst($s)."</option>"; }
                        ?>
                    </select>
                </div>
                <div><label class="text-[10px] font-bold text-kf-dark block mb-1">Area Kampus</label>
                    <select name="area" class="w-full px-3 py-2 bg-white border rounded-xl text-xs outline-none">
                        <option value="">Semua Wilayah</option>
                        <?php 
                        $areas = mysqli_query($host, "SELECT nama_ruangan FROM ruangan");
                        while($a = mysqli_fetch_assoc($areas)) { echo "<option value='".$a['nama_ruangan']."' ".(($_GET['area']??'')==$a['nama_ruangan']?'selected':'').">".$a['nama_ruangan']."</option>"; }
                        ?>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-gray-200">
                <button type="submit" class="px-4 py-2 bg-kf-dark text-white font-bold text-xs rounded-xl shadow-2xs">Terapkan Saringan</button>
                <button type="button" onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-2xs flex items-center gap-1"><i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Resmi</button>
            </div>
        </form>

        <!-- Tabel Hasil -->
        <div class="text-center mb-8 pb-4">
            <h2 class="text-base font-bold uppercase">POLITEKNIK ELEKTRONIKA NEGERI SURABAYA</h2>
            <h3 class="text-xs font-semibold text-gray-700">SISTEM INFORMASI LAPORAN ASET RUSAK (PILAR)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border border-gray-300 border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300 uppercase font-bold text-[10px]">
                        <th class="p-3 border-r">ID</th>
                        <th class="p-3 border-r">Pelapor</th>
                        <th class="p-3 border-r">Masalah</th>
                        <th class="p-3 border-r">Lokasi</th>
                        <th class="p-3 border-r text-center">Status</th>
                        <th class="p-3">PJ (Manager)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    // Logika Filter SQL
                    $where = "1=1";
                    if(!empty($_GET['start_date'])) $where .= " AND l.tanggal_laporan >= '{$_GET['start_date']}'";
                    if(!empty($_GET['end_date'])) $where .= " AND l.tanggal_laporan <= '{$_GET['end_date']}'";
                    if(!empty($_GET['status'])) $where .= " AND l.status = '{$_GET['status']}'";
                    if(!empty($_GET['area'])) $where .= " AND r.nama_ruangan = '{$_GET['area']}'";

                    $query = "SELECT l.*, r.nama_ruangan, u.nama as pelapor, mgr.nama as manajer 
                              FROM laporan l 
                              LEFT JOIN ruangan r ON l.id_ruangan = r.id_ruangan 
                              LEFT JOIN user u ON l.id_pelapor = u.id_user 
                              LEFT JOIN laporan_detail ld ON l.id_laporan = ld.id_laporan
                              LEFT JOIN user mgr ON ld.id_manager_teknisi = mgr.id_user
                              WHERE $where ORDER BY l.tanggal_laporan DESC";
                    
                    $data = mysqli_query($host, $query);
                    while($r = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td class="p-3 border-r text-[10px]"><?= $r['id_laporan'] ?></td>
                        <td class="p-3 border-r"><?= htmlspecialchars($r['pelapor']) ?></td>
                        <td class="p-3 border-r"><?= htmlspecialchars($r['judul_laporan']) ?></td>
                        <td class="p-3 border-r"><?= htmlspecialchars($r['nama_ruangan']) ?></td>
                        <td class="p-3 border-r text-center font-bold uppercase"><?= $r['status'] ?></td>
                        <td class="p-3"><?= htmlspecialchars($r['manajer'] ?? '-') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../../../template/admin/footer.php'; ?>