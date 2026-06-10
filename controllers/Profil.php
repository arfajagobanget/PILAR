<?php
include '../koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['action'] ?? '';
$id_user = $_SESSION['id_user'] ?? 1; // Mengacu pada akun pengelola ter-login aktif
$upload_dir = '../assets/uploads/user/';

header('Content-Type: application/json');

// ============================================================
// LOGIKA 1: UPDATE INFORMASI TEXT & PASSWORD PROFIL
// ============================================================
if ($action === 'update_info') {
    $nama = mysqli_real_escape_string($host, $_POST['nama']);
    $username = mysqli_real_escape_string($host, $_POST['username']);
    $email = mysqli_real_escape_string($host, $_POST['email']);
    $no_tlp = mysqli_real_escape_string($host, $_POST['no_tlp']);

    if (empty($nama) || empty($username) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Kolom utama tidak boleh kosong']);
        exit;
    }

    // Tambah klausa ganti password jika diinputkan admin
    $pass_clause = "";
    if (!empty($_POST['password'])) {
        $new_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $pass_clause = ", password = '$new_pass'";
    }

    $query = "UPDATE `user` SET nama='$nama', username='$username', email='$email', no_tlp='$no_tlp' $pass_clause WHERE id_user='$id_user'";
    
    if (mysqli_query($host, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data di database']);
    }
    exit;
}

// ============================================================
// LOGIKA 2: UPLOAD & REPLACE FILE FOTO PROFIL (FILE CLEAN-UP)
// ============================================================
elseif ($action === 'update_foto') {
    if (!isset($_FILES['foto']['name']) || $_FILES['foto']['error'] !== 0) {
        echo json_encode(['status' => 'error', 'message' => 'Berkas berkas gambar rusak atau tidak terbaca']);
        exit;
    }

    // Ambil data nama file lama di database untuk dihapus
    $res_old = mysqli_query($host, "SELECT foto FROM `user` WHERE id_user='$id_user'");
    $old_data = mysqli_fetch_assoc($res_old);
    $foto_lama = $old_data['foto'] ?? 'default.png';

    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    
    // Validasi ekstensi tipe file gambar
    $valid_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $valid_extensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Format file wajib berupa JPG, JPEG, PNG, atau WEBP']);
        exit;
    }

    $foto_name_baru = uniqid('usr_') . '.' . $ext;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $foto_name_baru)) {
        // Hapus file fisik gambar profil yang lama dari folder storage jika bukan default.png
        if ($foto_lama !== 'default.png' && file_exists($upload_dir . $foto_lama)) {
            unlink($upload_dir . $foto_lama);
        }

        // Simpan nama file terbaru ke database
        mysqli_query($host, "UPDATE `user` SET foto='$foto_name_baru' WHERE id_user='$id_user'");
        
        echo json_encode([
            'status' => 'success',
            'filename' => $foto_name_baru
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memindahkan file ke folder tujuan']);
    }
    exit;
}
?>