<?php
/**
 * controllers/laporanSaya.php
 * Proyek PILAR - Backend Controller Manajemen Laporan & Dropdown AJAX (Terintegrasi Otomatis Chat Room)
 */
include '../koneksi.php';
include_once __DIR__ . '/../includes/chat_helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// LOGIKA AJAX: Mengambil data lokasi untuk dropdown
// ============================================================

// 1. Ambil data gedung berdasarkan id_kampus
if (isset($_GET['action']) && $_GET['action'] == 'get_gedung' && isset($_GET['id_kampus'])) {
    $id_kampus = mysqli_real_escape_string($host, $_GET['id_kampus']);
    $query = "SELECT * FROM gedung WHERE id_kampus = '$id_kampus' ORDER BY nama_gedung ASC";
    $execute = mysqli_query($host, $query);

    echo '<option value="">Pilih Gedung</option>';
    while ($row = mysqli_fetch_assoc($execute)) {
        echo '<option value="' . $row['id_gedung'] . '">' . $row['nama_gedung'] . '</option>';
    }
    exit; 
}

// 2. Ambil data ruangan berdasarkan id_gedung
if (isset($_GET['action']) && $_GET['action'] == 'get_ruangan' && isset($_GET['id_gedung'])) {
    $id_gedung = mysqli_real_escape_string($host, $_GET['id_gedung']);
    $query = "SELECT * FROM ruangan WHERE id_gedung = '$id_gedung' ORDER BY nama_ruangan ASC";
    $execute = mysqli_query($host, $query);

    echo '<option value="">Pilih Ruangan / Area</option>';
    while ($row = mysqli_fetch_assoc($execute)) {
        echo '<option value="' . $row['id_ruangan'] . '">' . $row['nama_ruangan'] . '</option>';
    }
    exit; 
}

// ============================================================
// FUNGSI PEMBANTU (HELPER): Otomatisasi Chat System
// ============================================================
// SELESAI: id_pengirim menggunakan id_pelapor agar lolos dari validasi NOT NULL FK database
function kirimPesanOtomatisSistem($host, $id_laporan, $id_pelapor, $pesan_teks) {
    // Cek apakah chat room sudah ada, jika belum buat baru
    $cek_room = mysqli_query($host, "SELECT id_room FROM chat_room WHERE id_laporan = '$id_laporan'");
    if (mysqli_num_rows($cek_room) > 0) {
        $room = mysqli_fetch_assoc($cek_room);
        $id_room = $room['id_room'];
    } else {
        mysqli_query($host, "INSERT INTO chat_room (id_laporan, created_at) VALUES ('$id_laporan', NOW())");
        $id_room = mysqli_insert_id($host);
    }

    $pesan_teks = mysqli_real_escape_string($host, $pesan_teks);
    
    // Query eksekusi menggunakan nama kolom terverifikasi 'waktu_kirim'
    $query_insert_pesan = "INSERT INTO pesan (id_room, id_pengirim, isi_pesan, waktu_kirim) 
                           VALUES ('$id_room', '$id_pelapor', '$pesan_teks', NOW())";
                           
    mysqli_query($host, $query_insert_pesan);
}

// ============================================================
// LOGIKA CRUD: Insert, Update, Delete
// ============================================================

// ---- INSERT DATA (BUAT LAPORAN BARU) ----
if (isset($_POST['tambah'])) {
    $id_pelapor      = mysqli_real_escape_string($host, $_POST['id_pelapor']);
    $id_ruangan      = mysqli_real_escape_string($host, $_POST['id_ruangan']);
    $judul_laporan   = mysqli_real_escape_string($host, $_POST['judul_laporan']); 
    $deskripsi       = mysqli_real_escape_string($host, $_POST['deskripsi']);
    $tanggal_laporan = date('Y-m-d');
    $status          = 'menunggu'; 

    // Proses Sinkronisasi Berkas Gambar
    $foto_db = 'default.png';
    if (isset($_FILES['foto_sebelum']['name']) && $_FILES['foto_sebelum']['error'] === 0) {
        $nama_file = $_FILES['foto_sebelum']['name'];
        $tmp_name  = $_FILES['foto_sebelum']['tmp_name'];
        
        $ekstensi_pisahkan = explode('.', $nama_file);
        $ekstensi_file     = strtolower(end($ekstensi_pisahkan));
        $nama_file_baru    = uniqid('img_') . '.' . $ekstensi_file;
        $folder_tujuan     = '../assets/uploads/laporan/sebelum/' . $nama_file_baru;

        if (move_uploaded_file($tmp_name, $folder_tujuan)) {
            $foto_db = $nama_file_baru;
        }
    }

    $perintah = "INSERT INTO laporan (id_pelapor, id_ruangan, judul_laporan, deskripsi, foto_sebelum, tanggal_laporan, status) 
                 VALUES ('$id_pelapor', '$id_ruangan', '$judul_laporan', '$deskripsi', '$foto_db', '$tanggal_laporan', '$status')";
    

    $execute = mysqli_query($host, $perintah);

    if ($execute) {
        $id_laporan_baru = mysqli_insert_id($host);
        
        $user_q = mysqli_query($host, "SELECT nama FROM `user` WHERE id_user = '$id_pelapor'");
        $user_d = mysqli_fetch_assoc($user_q);
        $nama_user = $user_d['nama'] ?? 'Kak';

        $nama = $_SESSION['nama'];
        kirimPesanSistem(
            $host,
            $id_laporan_baru,
            "Hai $nama, terimakasih ya sudah melaporkan. Status laporan kamu saat ini adalah \"menunggu\". Mohon ditunggu, tim admin akan segera memeriksa laporanmu! 😊"
        );

        header("location:../views/pelapor/laporan_saya/data.php?status=sukses_tambah");
    } else {
        header("location:../views/pelapor/laporan_saya/data.php?status=gagal_tambah");
    }
    exit;
}

// ---- UPDATE DATA (SIMPAN PERUBAHAN LAPORAN) ----
if (isset($_POST['update'])) {
    $id_laporan    = mysqli_real_escape_string($host, $_GET['id_laporan']);
    $id_pelapor    = mysqli_real_escape_string($host, $_POST['id_pelapor']);
    $id_ruangan    = mysqli_real_escape_string($host, $_POST['id_ruangan']);
    $judul_laporan = mysqli_real_escape_string($host, $_POST['judul_laporan']); 
    $deskripsi     = mysqli_real_escape_string($host, $_POST['deskripsi']);
    $status        = mysqli_real_escape_string($host, $_POST['status']); 
    $foto_db       = mysqli_real_escape_string($host, $_POST['foto_lama']); 

    // Ambil status lama untuk mendeteksi perubahan status
    $old_status_q = mysqli_query($host, "SELECT status FROM laporan WHERE id_laporan = '$id_laporan'");
    $old_status_d = mysqli_fetch_assoc($old_status_q);
    $status_lama  = $old_status_d['status'] ?? 'menunggu';

    if (isset($_FILES['foto_sebelum']['name']) && $_FILES['foto_sebelum']['error'] === 0) {
        $nama_file = $_FILES['foto_sebelum']['name'];
        $tmp_name  = $_FILES['foto_sebelum']['tmp_name'];
        
        $ekstensi_pisahkan = explode('.', $nama_file);
        $ekstensi_file     = strtolower(end($ekstensi_pisahkan));
        $nama_file_baru    = uniqid('img_') . '.' . $ekstensi_file;
        $folder_tujuan     = '../assets/uploads/laporan/sebelum/' . $nama_file_baru;
        
        if (move_uploaded_file($tmp_name, $folder_tujuan)) {
            if ($foto_db && $foto_db !== 'default.png') {
                $path_foto_lama = '../assets/uploads/laporan/sebelum/' . $foto_db;
                if (file_exists($path_foto_lama)) {
                    unlink($path_foto_lama);
                }
            }
            $foto_db = $nama_file_baru; 
        }
    }

    $query = "UPDATE laporan SET 
                id_pelapor    = '$id_pelapor',
                id_ruangan    = '$id_ruangan',
                judul_laporan = '$judul_laporan',
                deskripsi     = '$deskripsi',
                foto_sebelum  = '$foto_db',
                status        = '$status'
              WHERE id_laporan = '$id_laporan'";

    $execute = mysqli_query($host, $query);

    if ($execute) {
        // Deteksi pemicu perubahan status dari sisi staff/admin panel
        if (strtolower($status_lama) !== strtolower($status)) {
            $user_q = mysqli_query($host, "SELECT nama FROM `user` WHERE id_user = '$id_pelapor'");
            $user_d = mysqli_fetch_assoc($user_q);
            $nama_user = $user_d['nama'] ?? 'Kak';

            if (strtolower($status) === 'diverifikasi') {
                $pesan_update = "[SISTEM] Hai " . $nama_user . ", laporan kamu sudah \"diverifikasi admin\" dan diteruskan ke tim manajer teknisi untuk dijadwalkan perbaikannya. Terimakasih! 👍";
            } elseif (strtolower($status) === 'proses') {
                $pesan_update = "[SISTEM] Kabar baik Kak " . $nama_user . ", laporan kamu saat ini sedang dalam \"proses perbaikan\" oleh teknisi lapangan. Semoga cepat selesai ya! 🛠️";
            } elseif (strtolower($status) === 'selesai') {
                $pesan_update = "[SISTEM] Mantap! Laporan fasilitas kamu sudah dinyatakan \"selesai\" sepenuhnya. Terimakasih banyak ya sudah ikut membantu menjaga kampus kita! 🥰👏";
            } elseif (strtolower($status) === 'ditolak') {
                $pesan_update = "[SISTEM] Halo " . $nama_user . ". Mohon maaf ya, laporan kamu berstatus \"ditolak\" oleh pihak admin karena beberapa pertimbangan teknis.";
            } else {
                $pesan_update = "[SISTEM] Status pengaduan kamu telah berubah menjadi \"" . $status . "\".";
            }

            kirimPesanOtomatisSistem($host, $id_laporan, $id_pelapor, $pesan_update);
        }

        header("location:../views/pelapor/laporan_saya/data.php?status=sukses_update");
    } else {
        header("location:../views/pelapor/laporan_saya/data.php?status=gagal_update");
    }
    exit;
}

// ---- DELETE DATA (HAPUS LAPORAN) ----
if (isset($_GET['delete'])) {
    $id_laporan = mysqli_real_escape_string($host, $_GET['delete']);

    // 1. Ambil nama foto untuk dihapus dari folder
    $query_cari_foto = "SELECT foto_sebelum FROM laporan WHERE id_laporan = '$id_laporan'";
    $hasil_cari      = mysqli_query($host, $query_cari_foto);
    
    if ($hasil_cari && mysqli_num_rows($hasil_cari) > 0) {
        $data_laporan = mysqli_fetch_assoc($hasil_cari);
        $nama_foto    = $data_laporan['foto_sebelum'];
        
        if ($nama_foto && $nama_foto !== 'default.png') {
            $path_file = '../assets/uploads/laporan/sebelum/' . $nama_foto;
            if (file_exists($path_file)) {
                unlink($path_file);
            }
        }
    }

    // 2. Hapus data di tabel anak (chat) agar tidak orphan
    $cek_room = mysqli_query($host, "SELECT id_room FROM chat_room WHERE id_laporan = '$id_laporan'");
    if ($room = mysqli_fetch_assoc($cek_room)) {
        $id_room = $room['id_room'];
        mysqli_query($host, "DELETE FROM pesan WHERE id_room = '$id_room'");
    }
    mysqli_query($host, "DELETE FROM chat_room WHERE id_laporan = '$id_laporan'");

    // 3. BARU: Hapus data di laporan_detail (Kunci perbaikan agar tidak error FK constraint)
    mysqli_query($host, "DELETE FROM laporan_detail WHERE id_laporan = '$id_laporan'");

    // 4. Hapus data induk (laporan)
    $query   = "DELETE FROM laporan WHERE id_laporan = '$id_laporan'";
    $execute = mysqli_query($host, $query);

    if ($execute) {
        header("location:../views/pelapor/laporan_saya/data.php?status=sukses_hapus");
    } else {
        header("location:../views/pelapor/laporan_saya/data.php?status=gagal_hapus");
    }
    exit;
}
?>