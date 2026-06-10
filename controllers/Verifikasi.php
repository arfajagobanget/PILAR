<?php
session_start();
include_once __DIR__ . '/../koneksi.php';
include_once __DIR__ . '/../includes/chat_helper.php';

function getLaporanData($host) {
    return mysqli_query($host, "SELECT l.*, r.nama_ruangan, u.nama as nama_pelapor, ld.catatan_verifikasi 
                                FROM laporan l 
                                LEFT JOIN ruangan r ON l.id_ruangan = r.id_ruangan 
                                LEFT JOIN user u ON l.id_pelapor = u.id_user 
                                LEFT JOIN laporan_detail ld ON l.id_laporan = ld.id_laporan
                                ORDER BY l.tanggal_laporan DESC");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $id = mysqli_real_escape_string($host, $_POST['id']);
    $action = $_POST['action'];
    $id_admin = $_SESSION['id_user'] ?? 1;

    // Aksi APPROVE
    if ($action == 'approve') {
        mysqli_query($host, "UPDATE laporan SET status = 'diverifikasi' WHERE id_laporan = '$id'");
        $laporan = mysqli_fetch_assoc(
            mysqli_query(
                $host,
                "SELECT u.nama
                FROM laporan l
                JOIN user u
                ON l.id_pelapor=u.id_user
                WHERE l.id_laporan='$id'"
            )
        );

        kirimPesanSistem(
            $host,
            $id,
            "Halo {$laporan['nama']}! Laporanmu telah diverifikasi oleh admin dan siap diproses lebih lanjut. 🎉"
        );
        $check = mysqli_query($host, "SELECT id_laporan_detail FROM laporan_detail WHERE id_laporan = '$id'");
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($host, "UPDATE laporan_detail SET id_admin = '$id_admin', catatan_verifikasi = 'Disetujui Admin', tanggal_verifikasi = NOW() WHERE id_laporan = '$id'");
        } else {
            mysqli_query($host, "INSERT INTO laporan_detail (id_laporan, id_admin, catatan_verifikasi, tanggal_verifikasi) VALUES ('$id', '$id_admin', 'Disetujui Admin', NOW())");
        }
    } 
    // Aksi REJECT
    elseif ($action == 'reject') {
        $note = mysqli_real_escape_string($host, $_POST['catatan'] ?? 'Tanpa alasan');
        mysqli_query($host, "UPDATE laporan SET status = 'ditolak' WHERE id_laporan = '$id'");
        kirimPesanSistem(
            $host,
            $id,
            "Mohon maaf, laporanmu belum dapat diproses dan saat ini berstatus \"ditolak\". Silakan lihat catatan admin untuk informasi lebih lanjut."
        );
        $check = mysqli_query($host, "SELECT id_laporan_detail FROM laporan_detail WHERE id_laporan = '$id'");
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($host, "UPDATE laporan_detail SET id_admin = '$id_admin', catatan_verifikasi = '$note', tanggal_verifikasi = NOW() WHERE id_laporan = '$id'");
        } else {
            mysqli_query($host, "INSERT INTO laporan_detail (id_laporan, id_admin, catatan_verifikasi, tanggal_verifikasi) VALUES ('$id', '$id_admin', '$note', NOW())");
        }
    } 
    // Aksi ASSIGN
    elseif ($action == 'assign') {
        $manager_id = mysqli_real_escape_string($host, $_POST['manager_id']);
        $catatan_tugas = mysqli_real_escape_string($host, $_POST['catatan_tugas']);
        
        mysqli_query($host, "UPDATE laporan SET status = 'diproses' WHERE id_laporan = '$id'");
        mysqli_query($host, "UPDATE laporan_detail 
                             SET id_manager_teknisi = '$manager_id', 
                                 catatan_penugasan = '$catatan_tugas',
                                 tanggal_penugasan = NOW() 
                             WHERE id_laporan = '$id'");
        kirimPesanSistem(
            $host,
            $id,
            "Kabar baik! Laporanmu sudah ditugaskan kepada teknisi dan sedang dalam proses penanganan. 🔧"
        );
    }
    // Aksi CANCEL / BATAL PROSES
    elseif ($action == 'cancel' || $action == 'cancel_process') {
        mysqli_query($host, "UPDATE laporan SET status = 'menunggu' WHERE id_laporan = '$id'");
        mysqli_query($host, "DELETE FROM laporan_detail WHERE id_laporan = '$id'");
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>