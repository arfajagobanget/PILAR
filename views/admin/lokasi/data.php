<?php 
$active_page = 'locations'; 
include '../../../koneksi.php';
include '../../../template/admin/header.php'; 

// Ambil semua data kampus untuk drop-down select
$query_kampus = mysqli_query($host, "SELECT * FROM kampus ORDER BY nama_kampus ASC");
$list_kampus = [];
while($k = mysqli_fetch_assoc($query_kampus)) {
    $list_kampus[] = $k;
}
?>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content">
        <header class="mb-6">
            <h1 class="font-script text-4xl text-kf-dark">Manajemen Hierarki Ruang Lingkup</h1>
            <p class="text-sm text-kf-muted mt-1">Kelola pemetaan data master Kampus, Gedung, dan Ruangan secara berjenjang.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm space-y-5 h-fit">
                
                <form action="../../../controllers/Lokasi.php" method="POST" class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                    <h3 class="text-xs font-bold text-blue-900 mb-2 uppercase tracking-wider">Level 1: Daftarkan Kampus</h3>
                    <div class="flex gap-2">
                        <input type="text" name="nama_kampus" placeholder="Nama Kampus Baru" class="flex-1 px-3 py-2 rounded-xl bg-white border text-xs outline-none" required>
                        <button type="submit" name="tambah_kampus" class="px-3 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold">+</button>
                    </div>
                </form>

                <form action="../../../controllers/Lokasi.php" method="POST" class="p-4 bg-purple-50/50 rounded-2xl border border-purple-100">
                    <h3 class="text-xs font-bold text-purple-900 mb-2 uppercase tracking-wider">Level 2: Daftarkan Gedung</h3>
                    <div class="space-y-2">
                        <select name="id_kampus" class="w-full px-3 py-2 rounded-xl bg-white border text-xs outline-none" required>
                            <option value="">Pilih Posisi Kampus</option>
                            <?php foreach($list_kampus as $k): ?>
                                <option value="<?= $k['id_kampus'] ?>"><?= $k['nama_kampus'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="flex gap-2">
                            <input type="text" name="nama_gedung" placeholder="Nama Gedung Baru" class="flex-1 px-3 py-2 rounded-xl bg-white border text-xs outline-none" required>
                            <button type="submit" name="tambah_gedung" class="px-3 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold">+</button>
                        </div>
                    </div>
                </form>

                <form action="../../../controllers/Lokasi.php" method="POST" class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                    <h3 class="text-xs font-bold text-emerald-900 mb-2 uppercase tracking-wider">Level 3: Daftarkan Ruangan</h3>
                    <div class="space-y-2">
                        <select id="select-kampus-for-room" class="w-full px-3 py-2 rounded-xl bg-white border text-xs outline-none" onchange="loadGedungOption(this.value)" required>
                            <option value="">Pilih Kampus</option>
                            <?php foreach($list_kampus as $k): ?>
                                <option value="<?= $k['id_kampus'] ?>"><?= $k['nama_kampus'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select id="select-gedung-for-room" name="id_gedung" class="w-full px-3 py-2 rounded-xl bg-white border text-xs outline-none" required>
                            <option value="">Pilih Gedung (Pilih Kampus Dahulu)</option>
                        </select>
                        <div class="flex gap-2 pt-1">
                            <input type="text" name="nama_ruangan" placeholder="Nama Ruangan Baru" class="flex-1 px-3 py-2 rounded-xl bg-white border text-xs outline-none" required>
                            <button type="submit" name="tambah_ruangan" class="px-3 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold">+</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm">
                <h3 class="text-xs font-bold text-kf-dark mb-3 uppercase tracking-wider">Log Hierarki Struktur Data Aktif</h3>
                <div class="overflow-y-auto max-h-[530px] text-xs space-y-4" id="locations-log-tree-container">
                    
                    <?php
                    $q_tree = "SELECT k.id_kampus, k.nama_kampus, g.id_gedung, g.nama_gedung, r.id_ruangan, r.nama_ruangan 
                               FROM kampus k
                               LEFT JOIN gedung g ON k.id_kampus = g.id_kampus
                               LEFT JOIN ruangan r ON g.id_gedung = r.id_gedung
                               ORDER BY k.nama_kampus ASC, g.nama_gedung ASC, r.nama_ruangan ASC";
                    $res_tree = mysqli_query($host, $q_tree);
                    
                    $hierarchy = [];
                    while($row = mysqli_fetch_assoc($res_tree)) {
                        $id_k = $row['id_kampus'];
                        $id_g = $row['id_gedung'];
                        $id_r = $row['id_ruangan'];
                        
                        if (!isset($hierarchy[$id_k])) {
                            $hierarchy[$id_k] = [
                                'nama' => $row['nama_kampus'],
                                'gedung' => []
                            ];
                        }
                        if ($id_g && !isset($hierarchy[$id_k]['gedung'][$id_g])) {
                            $hierarchy[$id_k]['gedung'][$id_g] = [
                                'nama' => $row['nama_gedung'],
                                'ruangan' => []
                            ];
                        }
                        if ($id_r) {
                            $hierarchy[$id_k]['gedung'][$id_g]['ruangan'][$id_r] = $row['nama_ruangan'];
                        }
                    }
                    ?>

                    <?php if(empty($hierarchy)): ?>
                        <p class="text-xs text-kf-muted italic text-center py-6">Belum ada struktur data wilayah terpetakan.</p>
                    <?php endif; ?>

                    <?php foreach($hierarchy as $id_k => $kampusData): ?>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center group">
                                <p class="font-bold text-blue-950 text-xs flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-600"></i> Wilayah: <?= $kampusData['nama'] ?>
                                </p>
                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                                    <button type="button" onclick="openEditModal('kampus', <?= $id_k ?>, '<?= $kampusData['nama'] ?>')" class="text-blue-600 hover:underline text-[10px]">Edit</button>
                                    <button type="button" onclick="openDeletePopup('kampus', <?= $id_k ?>, '<?= $kampusData['nama'] ?>')" class="text-red-500 hover:underline text-[10px]">Hapus</button>
                                </div>
                            </div>

                            <?php if(empty($kampusData['gedung'])): ?>
                                <p class="text-gray-400 italic ml-4 text-[10px] mt-1">Belum ada gedung terdaftar</p>
                            <?php endif; ?>

                            <?php foreach($kampusData['gedung'] as $id_g => $gedungData): ?>
                                <div class="ml-4 mt-3 border-l-2 border-purple-200 pl-3">
                                    <div class="flex justify-between items-center group">
                                        <p class="font-semibold text-purple-950 text-[11px]">🏢 Gedung: <?= $gedungData['nama'] ?></p>
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                                            <button type="button" onclick="openEditModal('gedung', <?= $id_g ?>, '<?= $gedungData['nama'] ?>')" class="text-blue-600 hover:underline text-[9px]">Edit</button>
                                            <button type="button" onclick="openDeletePopup('gedung', <?= $id_g ?>, '<?= $gedungData['nama'] ?>')" class="text-red-500 hover:underline text-[9px]">Hapus</button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        <?php if(empty($gedungData['ruangan'])): ?>
                                            <span class="text-gray-400 italic text-[10px]">Belum ada ruangan terdaftar</span>
                                        <?php endif; ?>
                                        
                                        <?php foreach($gedungData['ruangan'] as $id_r => $nama_ruangan): ?>
                                            <span class="inline-flex items-center gap-1 bg-white border border-emerald-200 text-emerald-800 text-[10px] px-2 py-0.5 rounded-md font-medium group relative">
                                                📍 <?= $nama_ruangan ?>
                                                <button type="button" onclick="openEditModal('ruangan', <?= $id_r ?>, '<?= $nama_ruangan ?>')" class="text-blue-600 font-bold ml-1 hidden group-hover:inline">📝</button>
                                                <button type="button" onclick="openDeletePopup('ruangan', <?= $id_r ?>, '<?= $nama_ruangan ?>')" class="text-red-500 font-bold ml-0.5 hidden group-hover:inline">×</button>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </main>
</div>

<div id="edit-popup-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[9999] p-4">
    <div class="bg-white w-full max-w-sm rounded-[32px] p-6 shadow-xl space-y-4 transform scale-95 transition-all">
        <div class="flex justify-between items-center border-b pb-2">
            <h3 class="text-sm font-bold text-gray-900" id="edit-modal-title">Ubah Nama Data</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
        </div>
        
        <form id="edit-data-form" onsubmit="submitEditForm(event)" class="space-y-4">
            <input type="hidden" id="edit-type" name="edit_type">
            <input type="hidden" id="edit-id" name="id">
            
            <div class="space-y-1">
                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Baru</label>
                <input type="text" id="edit-name-input" name="nama_baru" class="w-full px-3 py-2 rounded-xl bg-gray-50 border text-xs outline-none focus:border-blue-500 focus:bg-white transition" required>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition-all">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-xs rounded-xl transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<div id="delete-popup-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[9999] p-4">
    <div class="bg-white w-full max-w-xs rounded-[32px] p-6 text-center shadow-xl space-y-5 transform scale-95 transition-all">
        <div class="w-14 h-14 bg-orange-50 rounded-2xl mx-auto flex items-center justify-center border border-orange-100">
            <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        
        <div class="space-y-1">
            <h3 class="text-lg font-bold text-gray-900" id="delete-title-label">Hapus Data</h3>
            <p class="text-[11px] text-gray-500 leading-relaxed">Tindakan ini tidak bisa dibatalkan.<br>Hapus data <span id="delete-target-name" class="font-semibold text-gray-700"></span> ini?</p>
        </div>

        <div class="space-y-2 pt-1">
            <a id="delete-confirm-link" href="#" class="block w-full py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-md transition-all text-center">
                Hapus
            </a>
            <button type="button" onclick="closeDeletePopup()" class="block w-full py-2.5 bg-blue-50/60 hover:bg-blue-50 text-blue-600 font-bold text-xs rounded-xl transition-all">
                Batal
            </button>
        </div>
    </div>
</div>

<div id="toast" class="toast-notification"></div>

<style>
.toast-notification {
    position: fixed; bottom: 24px; right: 24px; background: #1f2937; color: #fff;
    padding: 12px 20px; border-radius: 16px; font-size: 11px; z-index: 99999;
    opacity: 0; transform: translateY(10px); transition: all 0.3s ease; pointer-events: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.toast-notification.show { opacity: 1; transform: translateY(0); }
</style>

<?php include '../../../template/admin/footer.php'; ?>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const statusAction = urlParams.get('status');

    if (statusAction === 'sukses_tambah_kampus') { showToast('Master Kampus Baru Sukses Ditambahkan! 🌟'); } 
    else if (statusAction === 'sukses_tambah_gedung') { showToast('Gedung Berhasil Didaftarkan ke Posisi Kampus! 🏢'); } 
    else if (statusAction === 'sukses_tambah_ruangan') { showToast('Ruangan Berhasil Dipetakan Menjadi Struktur Aktif! ✨'); } 
    else if (statusAction === 'gagal_kosong') { showToast('⚠️ Gagal, seluruh kolom wajib diisi!'); } 
    else if (statusAction === 'gagal_tambah') { showToast('Gagal memproses entitas data baru! ❌'); } 
    else if (statusAction === 'sukses_hapus') { showToast('Komponen hierarki ruang berhasil dicabut! 🗑️'); } 
    else if (statusAction === 'gagal_hapus_relasi') { showToast('⚠️ Gagal! Data sedang digunakan pada lembar pelaporan aktif.'); }

    if (statusAction) { window.history.replaceState({}, document.title, window.location.pathname); }

    function showToast(msg) {
        const t = document.getElementById('toast'); if(!t) return;
        t.textContent = msg; t.classList.add('show');
        setTimeout(() => { t.classList.remove('show'); }, 2500);
    }

    // AJAX Cascading Dropdown Loader
    function loadGedungOption(idKampus) {
        const selectGedung = document.getElementById('select-gedung-for-room');
        if (!idKampus) {
            selectGedung.innerHTML = '<option value="">Pilih Gedung (Pilih Kampus Dahulu)</option>';
            return;
        }
        
        fetch(`../../../controllers/Lokasi.php?action=get_gedung&id_kampus=${idKampus}`)
            .then(response => response.text())
            .then(htmlOutput => {
                selectGedung.innerHTML = htmlOutput;
            })
            .catch(err => showToast('Gagal memuat interkoneksi data gedung ❌'));
    }

    // Hapus Modal Trigger
    function openDeletePopup(type, id, name) {
        const modal = document.getElementById('delete-popup-modal');
        document.getElementById('delete-target-name').textContent = `"${name}"`;
        document.getElementById('delete-title-label').textContent = `Hapus ${type.charAt(0).toUpperCase() + type.slice(1)}`;
        document.getElementById('delete-confirm-link').href = `../../../controllers/Lokasi.php?delete_type=${type}&id=${id}`;
        modal.style.display = 'flex';
    }

    function closeDeletePopup() {
        document.getElementById('delete-popup-modal').style.display = 'none';
    }

    // ============================================================
    // MECHANISM IN-PLACE AJAX EDIT OVERLAY CONTROL
    // ============================================================
    function openEditModal(type, id, currentName) {
        document.getElementById('edit-type').value = type;
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-name-input').value = currentName;
        document.getElementById('edit-modal-title').textContent = `Ubah Nama ${type.charAt(0).toUpperCase() + type.slice(1)}`;
        
        document.getElementById('edit-popup-modal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('edit-popup-modal').style.display = 'none';
    }

    function submitEditForm(event) {
        event.preventDefault();
        const form = document.getElementById('edit-data-form');
        const formData = new FormData(form);

        // Kirim data ke controller menggunakan AJAX Fetch API
        fetch('../../../controllers/Lokasi.php?action=update_lokasi', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                closeEditModal();
                showToast('Perubahan Berhasil Disimpan! 💾');
                
                // Refresh halaman otomatis setelah 1 detik agar pohon hierarki terupdate dengan data terbaru
                setTimeout(() => { window.location.reload(); }, 1000);
            } else {
                showToast('⚠️ Gagal menyimpan: ' + data.message);
            }
        })
        .catch(error => {
            showToast('Koneksi bermasalah saat memperbarui data ❌');
        });
    }
</script>