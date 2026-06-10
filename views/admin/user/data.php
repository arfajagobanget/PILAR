<?php 
$active_page = 'users'; 
include '../../../koneksi.php';
include '../../../template/admin/header.php'; 

// Ambil data spesialis dari database untuk drop-down form
$list_spesialis = mysqli_query($host, "SELECT * FROM spesialis_manager ORDER BY nama_spesialis ASC");
?>

<script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content">
        <header class="mb-6"><h1 class="font-script text-4xl text-kf-dark">Manajemen Pengguna Otoritas</h1></header>
        
        <div class="flex flex-col gap-6">
            
            <div class="bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm w-full">
                <h3 class="text-xs font-bold text-kf-dark mb-4 uppercase tracking-wider" id="user-form-title">Tambah User Baru</h3>
                
                <form action="../../../controllers/ManajemenUser.php?action=tambah" method="POST" enctype="multipart/form-data" id="main-user-form">
                    <input type="hidden" name="id_user" id="user-id-field">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Nama Lengkap</label><input type="text" name="nama" id="user-nama" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light border border-kf-sky/20 text-xs outline-none"></div>
                        <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Username</label><input type="text" name="username" id="user-username" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light border border-kf-sky/20 text-xs outline-none"></div>
                        <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Email</label><input type="email" name="email" id="user-email" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light border border-kf-sky/20 text-xs outline-none"></div>
                        <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Password</label><input type="password" name="password" id="user-password" placeholder="Isi kata sandi akun" class="w-full px-4 py-2.5 rounded-xl bg-kf-light border border-kf-sky/20 text-xs outline-none"></div>
                        
                        <div>
                            <label class="text-[11px] font-bold text-kf-dark block mb-1">Otoritas Role</label>
                            <select name="role" id="user-role" onchange="toggleRoleDivision(this.value)" class="w-full px-4 py-2.5 rounded-xl bg-kf-light border border-kf-sky/20 text-xs outline-none">
                                <option value="admin">Staff Administrator</option>
                                <option value="manager_teknisi">Manager Teknisi</option>
                            </select>
                        </div>
                        
                        <div id="division-select-container" class="hidden">
                            <label class="text-[11px] font-bold text-blue-900 block mb-1">Spesialisasi Divisi (Dinamis DB)</label>
                            <select name="id_spesialis" id="user-spesialis" class="w-full px-4 py-2.5 rounded-xl bg-blue-50/50 border border-blue-200 text-xs outline-none">
                                <option value="">-- Pilih Penugasan Divisi --</option>
                                <?php while($s = mysqli_fetch_assoc($list_spesialis)): ?>
                                    <option value="<?= $s['id_spesialis'] ?>"><?= $s['nama_spesialis'] ?></option>
                                <?php endwhile; ?> </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold text-kf-dark block mb-1">Foto Profil (Optional)</label>
                            <input type="file" name="foto" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            <input type="hidden" name="foto_lama" id="user-foto-lama">
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-4 justify-end">
                        <button type="button" id="user-cancel-btn" onclick="resetUserForm()" class="hidden px-6 py-2.5 bg-gray-200 text-kf-muted text-xs font-bold rounded-xl">Batal</button>
                        <button type="submit" id="user-submit-btn" class="px-8 py-2.5 bg-kf-dark text-white text-xs font-bold rounded-xl shadow-xs">Simpan Personel</button>
                    </div>
                </form>
            </div>
            
            <div class="bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm w-full">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-kf-sky/10 font-bold text-kf-muted uppercase bg-kf-light/50">
                                <th class="p-3">Avatar</th>
                                <th class="p-3">Nama Pengelola</th>
                                <th class="p-3">Email / Username</th>
                                <th class="p-3">Role</th>
                                <th class="p-3">Divisi Spesialis</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-kf-sky/5 text-kf-dark">
                            <?php 
                            $q_user = "SELECT u.*, s.nama_spesialis FROM `user` u 
                                       LEFT JOIN spesialis_manager s ON u.id_spesialis = s.id_spesialis 
                                       WHERE u.role IN ('admin', 'manager_teknisi') ORDER BY u.id_user DESC";
                            $res_user = mysqli_query($host, $q_user);
                            while($row = mysqli_fetch_assoc($res_user)):
                            ?>
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-3">
                                    <img src="../../../assets/uploads/user/<?= $row['foto'] ?>" class="w-8 h-8 rounded-xl object-cover border border-gray-100">
                                </td>
                                <td class="p-3 font-semibold"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="p-3 text-gray-500"><?= htmlspecialchars($row['email']) ?><br><span class="text-[10px] text-gray-400">@<?= htmlspecialchars($row['username']) ?></span></td>
                                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold <?= $row['role'] == 'admin' ? 'bg-purple-50 text-purple-700':'bg-amber-50 text-amber-700' ?>"><?= $row['role'] ?></span></td>
                                <td class="p-3 font-medium text-gray-600"><?= htmlspecialchars($row['nama_spesialis'] ?? '-') ?></td>
                                <td class="p-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="editUserCard(<?= htmlspecialchars(json_encode($row)) ?>)" class="px-2.5 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 font-medium">Edit</button>
                                        <button type="button" onclick="triggerDeleteUserPopup(<?= $row['id_user'] ?>, '<?= htmlspecialchars($row['nama']) ?>')" class="px-2.5 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="delete-user-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[9999] p-4">
    <div class="bg-white w-full max-w-xs rounded-[32px] p-6 text-center shadow-xl space-y-5 transform scale-95 transition-all duration-200">
        <div class="w-14 h-14 bg-orange-50 rounded-2xl mx-auto flex items-center justify-center border border-orange-100">
            <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        
        <div class="space-y-1">
            <h3 class="text-lg font-bold text-gray-900">Pencabutan Akses</h3>
            <p class="text-[11px] text-gray-500 leading-relaxed">Tindakan ini menghapus seluruh data permanen.<br>Hapus akun pengelola <span id="delete-user-label" class="font-bold text-gray-700"></span>?</p>
        </div>

        <div class="space-y-2 pt-1">
            <a id="delete-user-action-link" href="#" class="block w-full py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-md transition-all text-center">
                Ya, Hapus Akun
            </a>
            <button type="button" onclick="closeDeleteUserPopup()" class="block w-full py-2.5 bg-blue-50/60 hover:bg-blue-50 text-blue-600 font-bold text-xs rounded-xl transition-all">
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.toast-notification.show { opacity: 1; transform: translateY(0); }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Parsing status URL system feedback
        const urlParams = new URLSearchParams(window.location.search);
        const statusAction = urlParams.get('status');

        if (statusAction === 'sukses_tambah') { showToast('Akun Personel Otoritas Berhasil Ditambahkan! ✨'); } 
        else if (statusAction === 'gagal_duplikat') { showToast('⚠️ Gagal! Username atau Email sudah terdaftar.'); }
        else if (statusAction === 'sukses_update') { showToast('Perubahan Data Akun Berhasil Disimpan! 💾'); } 
        else if (statusAction === 'sukses_hapus') { showToast('Hak Akses Personel Telah Dicabut & Dihapus! 🗑️'); } 
        else if (statusAction === 'gagal_tambah' || statusAction === 'gagal_update') { showToast('Gagal memproses perubahan database! ❌'); }
        else if (statusAction === 'gagal_hapus') { showToast('Gagal mencabut hak akses pengguna! ❌'); }

        if (statusAction) { window.history.replaceState({}, document.title, window.location.pathname); }
    });

    function showToast(msg) {
        const t = document.getElementById('toast'); if(!t) return;
        t.textContent = msg; t.classList.add('show');
        setTimeout(() => { t.classList.remove('show'); }, 2500);
    }

    function toggleRoleDivision(roleValue) {
        const container = document.getElementById('division-select-container');
        if(roleValue === 'manager_teknisi') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            document.getElementById('user-spesialis').value = "";
        }
    }

    function editUserCard(data) {
        document.getElementById('user-form-title').textContent = "Ubah Data Akun";
        document.getElementById('main-user-form').action = "../../../controllers/ManajemenUser.php?action=edit";
        
        document.getElementById('user-id-field').value = data.id_user;
        document.getElementById('user-nama').value = data.nama;
        document.getElementById('user-username').value = data.username;
        document.getElementById('user-email').value = data.email;
        document.getElementById('user-role').value = data.role;
        document.getElementById('user-foto-lama').value = data.foto;
        document.getElementById('user-password').required = false;
        document.getElementById('user-password').placeholder = "Kosongkan jika tidak ingin mengubah sandi";
        
        toggleRoleDivision(data.role);
        if(data.role === 'manager_teknisi') {
            document.getElementById('user-spesialis').value = data.id_spesialis ?? "";
        }
        
        document.getElementById('user-submit-btn').textContent = "Perbarui Personel";
        document.getElementById('user-cancel-btn').classList.remove('hidden');
    }

    function resetUserForm() {
        document.getElementById('user-form-title').textContent = "Tambah User Baru";
        document.getElementById('main-user-form').action = "../../../controllers/ManajemenUser.php?action=tambah";
        document.getElementById('main-user-form').reset();
        document.getElementById('user-id-field').value = "";
        document.getElementById('user-foto-lama').value = "";
        document.getElementById('user-password').required = true;
        document.getElementById('user-password').placeholder = "Isi kata sandi akun";
        
        document.getElementById('division-select-container').classList.add('hidden');
        document.getElementById('user-spesialis').value = "";
        
        document.getElementById('user-submit-btn').textContent = "Simpan Personel";
        document.getElementById('user-cancel-btn').classList.add('hidden');
    }

    function triggerDeleteUserPopup(id, name) {
        const modal = document.getElementById('delete-user-modal');
        document.getElementById('delete-user-label').textContent = `"${name}"`;
        document.getElementById('delete-user-action-link').href = `../../../controllers/ManajemenUser.php?action=hapus&id=${id}`;
        modal.style.display = 'flex';
    }

    function closeDeleteUserPopup() {
        document.getElementById('delete-user-modal').style.display = 'none';
    }
</script>