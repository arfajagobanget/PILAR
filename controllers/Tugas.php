<?php
require_once __DIR__ . '/../koneksi.php';
include_once __DIR__ . '/../includes/chat_helper.php';
session_start();

// Validasi Keamanan
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'manager_teknisi') {
    die("Akses tidak sah.");
}

$action = $_GET['action'] ?? '';

// ============================================================
// FUNGSI: SELESAIKAN / EDIT PENYELESAIAN (DENGAN UPLOAD FOTO)
// ============================================================
if ($action == 'selesaikan_tugas') {
    $id_laporan = mysqli_real_escape_string($host, $_POST['id_laporan']);
    
    // Cek apakah ada file yang diunggah
    if (isset($_FILES['foto']['name']) && $_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_name  = $_FILES['foto']['tmp_name'];
        
        // Buat nama file unik
        $ekstensi_pisahkan = explode('.', $nama_file);
        $ekstensi_file     = strtolower(end($ekstensi_pisahkan));
        $nama_file_baru    = 'fix_' . $id_laporan . '_' . time() . '.' . $ekstensi_file;
        
        // Tentukan folder tujuan (pastikan folder ini ada/writable)
        $folder_tujuan = '../assets/uploads/laporan/sesudah/' . $nama_file_baru;

        if (move_uploaded_file($tmp_name, $folder_tujuan)) {
            // Update tabel laporan_detail dengan nama foto baru
            $q1 = "UPDATE laporan_detail SET foto_sesudah = '$nama_file_baru' WHERE id_laporan = '$id_laporan'";
            
            // Update status tabel laporan
            $q2 = "UPDATE laporan SET status = 'selesai' WHERE id_laporan = '$id_laporan'";
            kirimPesanSistem(
                $host,
                $id_laporan,
                "Perbaikan telah selesai dilakukan. ✅ Silakan cek kembali fasilitas yang dilaporkan. Terima kasih telah menggunakan PILAR."
            );
            mysqli_query($host, $q1);
            mysqli_query($host, $q2);
            
            header("location:../views/manager_teknisi/tugas/data.php?status=success");
        } else {
            header("location:../views/manager_teknisi/tugas/data.php?status=gagal_upload");
        }
    } else {
        header("location:../views/manager_teknisi/tugas/data.php?status=gagal_upload");
    }
    exit;
}

// ============================================================
// FUNGSI: BATALKAN PENYELESAIAN
// ============================================================
if ($action == 'batalkan_tugas') {
    $id_laporan = mysqli_real_escape_string($host, $_POST['id_laporan']);
    
    // Ambil nama foto lama untuk dihapus dari server
    $query_cek = mysqli_query($host, "SELECT foto_sesudah FROM laporan_detail WHERE id_laporan = '$id_laporan'");
    $data = mysqli_fetch_assoc($query_cek);
    
    if ($data && !empty($data['foto_sesudah'])) {
        $path_foto = '../assets/uploads/laporan/sesudah/' . $data['foto_sesudah'];
        if (file_exists($path_foto)) {
            unlink($path_foto);
        }
    }

    // Reset database
    mysqli_query($host, "UPDATE laporan_detail SET foto_sesudah = NULL WHERE id_laporan = '$id_laporan'");
    mysqli_query($host, "UPDATE laporan SET status = 'proses' WHERE id_laporan = '$id_laporan'");
    
    header("location:../views/manager_teknisi/tugas/data.php?status=cancelled");
    exit;
}
?>