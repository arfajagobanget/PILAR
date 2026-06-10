<?php
include '../koneksi.php';

$action = $_GET['action'] ?? '';
$upload_dir = '../assets/uploads/user/';

// Buat direktori jika folder uploads/user belum tersedia fisik
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ------------------------------------------------------------
// PROSES TAMBAH USER AKUN NEW
// ------------------------------------------------------------
if ($action === 'tambah') {
    $nama = mysqli_real_escape_string($host, $_POST['nama']);
    $username = mysqli_real_escape_string($host, $_POST['username']);
    $email = mysqli_real_escape_string($host, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = mysqli_real_escape_string($host, $_POST['role']);
    $id_spesialis = ($role === 'manager_teknisi' && !empty($_POST['id_spesialis'])) ? "'".$_POST['id_spesialis']."'" : "NULL";

    // Validasi Duplikasi Identitas Sederhana
    $check = mysqli_query($host, "SELECT id_user FROM `user` WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        header("location:../views/admin/user/data.php?status=gagal_duplikat");
        exit;
    }

    // Manajemen Upload Berkas Gambar Foto Dinamis
    $foto_name = 'default.png';
    if (isset($_FILES['foto']['name']) && $_FILES['foto']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $foto_name = uniqid('usr_') . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $foto_name);
    }

    $q = "INSERT INTO `user` (id_user, nama, username, email, password, role, id_spesialis, foto) 
          VALUES (NULL, '$nama', '$username', '$email', '$password', '$role', $id_spesialis, '$foto_name')";
          
    if (mysqli_query($host, $q)) {
        header("location:../views/admin/user/data.php?status=sukses_tambah");
    } else {
        header("location:../views/admin/user/data.php?status=gagal_tambah");
    }
}

// ------------------------------------------------------------
// PROSES UPDATE EDIT DATA USER (Dinamis File Replacer)
// ------------------------------------------------------------
elseif ($action === 'edit') {
    $id_user = mysqli_real_escape_string($host, $_POST['id_user']);
    $nama = mysqli_real_escape_string($host, $_POST['nama']);
    $username = mysqli_real_escape_string($host, $_POST['username']);
    $email = mysqli_real_escape_string($host, $_POST['email']);
    $role = mysqli_real_escape_string($host, $_POST['role']);
    $id_spesialis = ($role === 'manager_teknisi' && !empty($_POST['id_spesialis'])) ? "'".$_POST['id_spesialis']."'" : "NULL";
    $foto_db = $_POST['foto_lama'];

    // Update password hanya jika kolom diinputkan user
    $pass_clause = "";
    if (!empty($_POST['password'])) {
        $new_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $pass_clause = ", password = '$new_pass'";
    }

    // Jika mengunggah berkas foto profil yang baru
    if (isset($_FILES['foto']['name']) && $_FILES['foto']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $foto_name_baru = uniqid('usr_') . '.' . $ext;
        
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $foto_name_baru)) {
            // Hapus berkas file fisik lama jika bukan gambar default
            if ($foto_db && $foto_db !== 'default.png' && file_exists($upload_dir . $foto_db)) {
                unlink($upload_dir . $foto_db);
            }
            $foto_db = $foto_name_baru;
        }
    }

    $q = "UPDATE `user` SET nama='$nama', username='$username', email='$email', role='$role', id_spesialis=$id_spesialis, foto='$foto_db' $pass_clause WHERE id_user='$id_user'";
    
    if (mysqli_query($host, $q)) {
        header("location:../views/admin/user/data.php?status=sukses_update");
    } else {
        header("location:../views/admin/user/data.php?status=gagal_update");
    }
}

// ------------------------------------------------------------
// PROSES DELETION HAPUS DATA USER
// ------------------------------------------------------------
elseif ($action === 'hapus') {
    $id = mysqli_real_escape_string($host, $_GET['id']);
    
    // Cari nama file foto profil untuk dihapus fisiknya dari server
    $res = mysqli_query($host, "SELECT foto FROM `user` WHERE id_user='$id'");
    if ($row = mysqli_fetch_assoc($res)) {
        $nama_foto = $row['foto'];
        if ($nama_foto && $nama_foto !== 'default.png' && file_exists($upload_dir . $nama_foto)) {
            unlink($upload_dir . $nama_foto);
        }
    }

    if (mysqli_query($host, "DELETE FROM `user` WHERE id_user='$id'")) {
        header("location:../views/admin/user/data.php?status=sukses_hapus");
    } else {
        header("location:../views/admin/user/data.php?status=gagal_hapus");
    }
}
?>