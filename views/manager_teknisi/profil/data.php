<?php 
/**
 * profil/data.php
 */
$active_page = 'profile'; 
include '../../../koneksi.php';
include '../../../template/manager_teknisi/header.php'; 

if (session_status() === PHP_SESSION_NONE) session_start();
$id_log = $_SESSION['id_user'] ?? 1;

// Mengambil data user dan spesialisnya
$query_user = mysqli_query($host, "SELECT u.*, s.nama_spesialis 
                                   FROM `user` u 
                                   LEFT JOIN spesialis_manager s ON u.id_spesialis = s.id_spesialis 
                                   WHERE u.id_user = '$id_log'");
$user = mysqli_fetch_assoc($query_user);
?>

<div class="h-full w-full overflow-auto">
    <div class="flex h-full w-full">
        
        <?php include '../../../template/manager_teknisi/sidebar.php'; ?>

        <main class="flex-1 overflow-auto bg-kf-cream/50 p-8">
            <header class="mb-6"><h1 class="font-script text-4xl text-kf-dark">Manajemen Akun Profil Saya</h1></header>
            
            <div class="max-w-3xl bg-white rounded-3xl p-8 border border-kf-sky/10 shadow-sm">
                
                <div class="flex flex-col sm:flex-row items-center text-center sm:text-left gap-6 mb-8 pb-6 border-b border-kf-sky/10">
                    <div class="relative group shrink-0">
                        <div class="w-24 h-24 rounded-3xl bg-cover bg-center border-2 border-amber-400 shadow-md transition-all group-hover:brightness-90" 
                             id="profile-card-photo" 
                             style="background-image: url('../../../assets/uploads/user/<?= !empty($user['foto']) ? $user['foto'] : 'default.png' ?>');"></div>
                        
                        <label class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl bg-kf-dark text-white shadow-md flex items-center justify-center cursor-pointer hover:bg-amber-500 transition">
                            <i data-lucide="camera" class="w-4 h-4"></i>
                            <input type="file" id="ajax-photo-file" accept="image/*" class="hidden" onchange="uploadPhotoViaAjax()">
                        </label>
                    </div>
                    
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-2 mb-1.5">
                            <h3 class="text-base font-bold text-kf-dark truncate" id="profile-card-name"><?= htmlspecialchars($user['nama']) ?></h3>
                            <span class="text-[9px] bg-amber-100 text-amber-800 font-bold px-2.5 py-0.5 rounded-full border border-amber-200 uppercase">
                                <?= htmlspecialchars($user['role'] ?? 'Manager') ?>
                            </span>
                        </div>
                        <p class="text-xs text-kf-muted font-medium flex items-center justify-center sm:justify-start gap-1">
                            <i data-lucide="wrench" class="w-3.5 h-3.5 text-amber-500"></i> 
                            <span id="profile-card-role-label"><?= htmlspecialchars($user['nama_spesialis'] ?? 'Manager') ?></span>
                        </p>
                        <p class="text-[11px] text-gray-400 mt-1" id="profile-card-username-label">@<?= htmlspecialchars($user['username']) ?></p>
                    </div>
                </div>

                <form id="profile-update-form" onsubmit="submitProfileForm(event)" class="space-y-4">
                    <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Nama Lengkap</label><input type="text" name="nama" id="profile-input-name" value="<?= htmlspecialchars($user['nama']) ?>" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none focus:border-amber-400 border"></div>
                        <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Username Aplikasi</label><input type="text" name="username" id="profile-input-username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none focus:border-amber-400 border"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Nomor Telepon</label><input type="tel" name="no_tlp" id="profile-input-phone" value="<?= htmlspecialchars($user['no_tlp'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none focus:border-amber-400 border"></div>
                        <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Alamat Email</label><input type="email" name="email" id="profile-input-email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none focus:border-amber-400 border"></div>
                    </div>
                    <hr class="border-kf-sky/15 my-2">
                    <div><label class="text-[11px] font-bold text-kf-dark block mb-1">Kata Sandi Baru</label><input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl bg-kf-light text-xs outline-none focus:border-amber-400 border"></div>
                    <div class="flex justify-end pt-2"><button type="submit" class="px-6 py-2.5 bg-kf-dark text-white text-xs font-bold rounded-xl shadow-md hover:bg-opacity-90 transition">Simpan Perubahan</button></div>
                </form>
            </div>
        </main>
    </div>
</div>

<div id="toast" class="fixed bottom-6 right-6 bg-kf-dark text-white px-5 py-3 rounded-2xl text-xs opacity-0 transition-opacity duration-300 pointer-events-none z-50"></div>

<?php include '../../../template/manager_teknisi/footer.php';?>

<script>
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.remove('opacity-0');
        setTimeout(() => t.classList.add('opacity-0'), 2500);
    }

    function submitProfileForm(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('../../../controllers/Profil.php?action=update_info', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                showToast('Profil berhasil diperbarui! ✨');
                document.getElementById('profile-card-name').textContent = document.getElementById('profile-input-name').value;
                document.getElementById('profile-card-username-label').textContent = '@' + document.getElementById('profile-input-username').value;
            } else {
                showToast('⚠️ Gagal: ' + data.message);
            }
        });
    }

    function uploadPhotoViaAjax() {
        const file = document.getElementById('ajax-photo-file').files[0];
        const formData = new FormData();
        formData.append('foto', file);

        fetch('../../../controllers/Profil.php?action=update_foto', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('profile-card-photo').style.backgroundImage = `url('../../../assets/uploads/user/${data.filename}')`;
                showToast('Foto berhasil diunggah! 📸');
            }
        });
    }
</script>