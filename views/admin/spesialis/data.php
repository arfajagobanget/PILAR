<?php 
$active_page = 'spesialis'; 
include '../../../koneksi.php';
include '../../../template/admin/header.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content">
        <header class="mb-6"><h1 class="font-script text-4xl text-kf-dark">Manajemen Spesialisasi Kerja</h1></header>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm h-fit">
                <h3 class="text-xs font-bold text-kf-dark mb-4 uppercase tracking-wider" id="spesialis-title">Tambah Spesialis Baru</h3>
                <form action="../../../controllers/SpesialisManager.php?action=tambah" method="POST" id="spesialis-form" class="space-y-4">
                    <input type="hidden" name="id_spesialis" id="spesialis-id">
                    <div>
                        <label class="text-[11px] font-bold text-kf-dark block mb-1">Nama Bidang Spesialisasi</label>
                        <input type="text" name="nama_spesialis" id="spesialis-name" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light border border-kf-sky/20 text-xs outline-none">
                    </div>
                    <button type="submit" id="spesialis-btn" class="w-full py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl">Simpan Jabatan</button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-kf-sky/10 font-bold text-kf-muted uppercase bg-kf-light/50">
                            <th class="p-3">ID</th>
                            <th class="p-3">Nama Bidang Kerja</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-kf-sky/5 text-kf-dark">
                        <?php 
                        $res = mysqli_query($host, "SELECT * FROM spesialis_manager ORDER BY id_spesialis DESC");
                        while($r = mysqli_fetch_assoc($res)):
                        ?>
                        <tr>
                            <td class="p-3 text-gray-400">#<?= $r['id_spesialis'] ?></td>
                            <td class="p-3 font-semibold"><?= $r['nama_spesialis'] ?></td>
                            <td class="p-3 text-center">
                                <button onclick="editSpesialis(<?= $r['id_spesialis'] ?>, '<?= $r['nama_spesialis'] ?>')" class="text-blue-600 font-bold mr-2">Edit</button>
                                <a href="../../../controllers/SpesialisManager.php?action=hapus&id=<?= $r['id_spesialis'] ?>" onclick="return confirm('Hapus bidang kerja? Manager terkait akan terset Kosong.')" class="text-red-500 font-bold">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include '../../../template/admin/footer.php'; ?>

<script>
    // FIX: Memicu render ulang seluruh komponen ikon Lucide saat dokumen selesai dimuat
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function editSpesialis(id, name) {
        document.getElementById('spesialis-title').textContent = "Ubah Bidang Kerja";
        document.getElementById('spesialis-form').action = "../../../controllers/SpesialisManager.php?action=edit";
        document.getElementById('spesialis-id').value = id;
        document.getElementById('spesialis-name').value = name;
        document.getElementById('spesialis-btn').textContent = "Simpan Perubahan";
    }
</script>