<?php 
$active_page = 'profil'; 
include '../../../koneksi.php';
include '../../../template/admin/header.php'; 

// Proteksi Sesi & Ambil Data Pengguna Ter-login Dinamis
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gunakan ID User dari session login, jika belum ada set default ke 1 untuk kebutuhan testing
$id_log = $_SESSION['id_user'] ?? 1; 

$query_admin = mysqli_query($host, "SELECT u.*, s.nama_spesialis FROM `user` u 
                                    LEFT JOIN spesialis_manager s ON u.id_spesialis = s.id_spesialis 
                                    WHERE u.id_user = '$id_log'");
$admin = mysqli_fetch_assoc($query_admin);
?>

<script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content">
        <header class="mb-6"><h1 class="font-script text-4xl text-kf-dark">Manajemen Akun Profil Saya</h1></header>
        <div class="max-w-3xl bg-white rounded-3xl p-8 border border-kf-sky/10 shadow-sm">
            
            <div class="flex flex-col sm:flex-row items-center text-center sm:text-left gap-6 mb-8 pb-6 border-b border-kf-sky/10">
                <div class="relative group shrink-0">
                    <div class="w-24 h-24 rounded-3xl bg-cover bg-center border-2 border-amber-400 shadow-md transition-all group-hover:brightness-90" 
                         id="profile-card-photo" 
                         style="background-image: url('../../../assets/uploads/user/<?= !empty($admin['foto']) ? $admin['foto'] : 'default.png' ?>');"></div>
                    
                    <label class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl bg-kf-dark text-white shadow-md flex items-center justify-center cursor-pointer hover:bg-amber-500 transition">
                        <i data-lucide="camera" class="w-4 h-4"></i>
                        <input type="file" id="ajax-photo-file" accept="image/*" class="hidden" onchange="uploadPhotoViaAjax()">
                    </label>
                </div>
                
                <div class="min-w-0 flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-2 mb-1.5">
                        <h3 class="text-base font-bold text-kf-dark truncate" id="profile-card-name"><?= htmlspecialchars($admin['nama']) ?></h3>
                        <span class="text-[9px] bg-amber-100 text-amber-800 font-bold px-2.5 py-0.5 rounded-full border border-amber-200 uppercase" id="profile-card-category-badge">
                            <?= htmlspecialchars($admin['role']) ?>
                        </span>
                    </div>
                    <p class="text-xs text-kf-muted font-medium flex items-center justify-center sm:justify-start gap-1">
                        <i data-lucide="shield" class="w-3.5 h-3.5 text-amber-500"></i>
                        <span id="profile-card-role-label">
                            <?= !empty($admin['nama_spesialis']) ? htmlspecialchars($admin['nama_spesialis']) : 'Main Administrator' ?>
                        </span>
                    </p>
                    <p class="text-[11px] text-gray-400 mt-1" id="profile-card-username-label">@<?= htmlspecialchars($admin['username']) ?></p>
                </div>
            </div>

            <form id="profile-update-form" onsubmit="submitProfileForm(event)" class="space-y-4">
                <input type="hidden" name="id_user" value="<?= $admin['id_user'] ?>">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Nama Lengkap Admin</label><input type="text" name="nama" id="profile-input-name" value="<?= htmlspecialchars($admin['nama']) ?>" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none border focus:border-amber-400"></div>
                    <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Username Aplikasi</label><input type="text" name="username" id="profile-input-username" value="<?= htmlspecialchars($admin['username']) ?>" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none border focus:border-amber-400"></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Nomor Telepon / WhatsApp</label><input type="tel" name="no_tlp" id="profile-input-phone" value="<?= htmlspecialchars($admin['no_tlp'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none border focus:border-amber-400"></div>
                    <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Alamat Email Resmi</label><input type="email" name="email" id="profile-input-email" value="<?= htmlspecialchars($admin['email']) ?>" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none border focus:border-amber-400"></div>
                </div>
                
                <hr class="border-kf-sky/15 my-2">
                <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Kata Sandi Akun Baru (Kosongkan jika tidak diganti)</label><input type="password" name="password" id="profile-input-pass" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none border focus:border-amber-400"></div>
                
                <div class="flex justify-end pt-2"><button type="submit" class="px-6 py-2.5 bg-kf-dark text-white text-xs font-bold rounded-xl shadow-md">Simpan Perubahan Profil</button></div>
            </form>
        </div>
    </main>
</div>

<div id="toast" class="toast-notification"></div>

<style>
.toast-notification {
    position: fixed; bottom: 24px; right: 24px; background: #1f2937; color: #fff;
    padding: 12px 20px; border-radius: 16px; font-size: 11px; z-index: 99999;
    opacity: 0; transform: translateY(10px); transition: all 0.3s ease; pointer-events: none;
}
.toast-notification.show { opacity: 1; transform: translateY(0); }
</style>

<?php include '../../../template/admin/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    });

    function showToast(msg) {
        const t = document.getElementById('toast'); if(!t) return;
        t.textContent = msg; t.classList.add('show');
        setTimeout(() => { t.classList.remove('show'); }, 2500);
    }

    // 1. AJAX: Update Informasi Text Profil
    function submitProfileForm(event) {
        event.preventDefault();
        const form = document.getElementById('profile-update-form');
        const formData = new FormData(form);

        fetch('../../../controllers/Profil.php?action=update_info', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                showToast('Informasi Akun Anda Berhasil Disimpan! ✨');
                document.getElementById('profile-card-name').textContent = document.getElementById('profile-input-name').value;
                document.getElementById('profile-card-username-label').textContent = '@' + document.getElementById('profile-input-username').value;
                
                // Sinkronisasi komponen teks sidebar jika ada element targetnya
                if(document.getElementById('sidebar-profile-name')) {
                    document.getElementById('sidebar-profile-name').textContent = document.getElementById('profile-input-name').value;
                }
            } else {
                showToast('⚠️ Gagal: ' + data.message);
            }
        })
        .catch(() => showToast('Koneksi bermasalah saat memperbarui profil ❌'));
    }

    // 2. AJAX: Upload & Ganti Foto Profil Langsung
    function uploadPhotoViaAjax() {
        const fileInput = document.getElementById('ajax-photo-file');
        if(fileInput.files.length === 0) return;

        const formData = new FormData();
        formData.append('foto', fileInput.files[0]);

        fetch('../../../controllers/Profil.php?action=update_foto', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                showToast('Foto profil admin berhasil diperbarui! 📸');
                // Ganti preview background gambar seketika
                document.getElementById('profile-card-photo').style.backgroundImage = `url('../../../assets/uploads/user/${data.filename}')`;
            } else {
                showToast('⚠️ Gagal upload: ' + data.message);
            }
        })
        .catch(() => showToast('Gagal memproses file foto baru ❌'));
    }
</script>