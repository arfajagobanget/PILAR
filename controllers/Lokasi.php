<?php
include '../koneksi.php'; 

// ============================================================
// LOGIKA AJAX: Mengambil data gedung berdasarkan kampus
// ============================================================
if (isset($_GET['action']) && $_GET['action'] == 'get_gedung' && isset($_GET['id_kampus'])) {
    $id_kampus = mysqli_real_escape_string($host, $_GET['id_kampus']);
    $query = "SELECT * FROM gedung WHERE id_kampus = '$id_kampus' ORDER BY nama_gedung ASC";
    $execute = mysqli_query($host, $query);

    echo '<option value="">Pilih Gedung / Blok</option>';
    while ($row = mysqli_fetch_assoc($execute)) {
        echo '<option value="' . $row['id_gedung'] . '">' . $row['nama_gedung'] . '</option>';
    }
    exit; 
}

// ============================================================
// NEW FEATURE - LOGIKA AJAX: Update Nama Entitas tanpa refresh halaman
// ============================================================
if (isset($_GET['action']) && $_GET['action'] == 'update_lokasi') {
    header('Content-Type: application/json');
    
    $type      = mysqli_real_escape_string($host, $_POST['edit_type']);
    $id        = mysqli_real_escape_string($host, $_POST['id']);
    $nama_baru = mysqli_real_escape_string($host, $_POST['nama_baru']);

    if(empty($id) || empty($nama_baru)) {
        echo json_encode(['status' => 'error', 'message' => 'Kolom tidak boleh kosong']);
        exit;
    }

    if ($type === 'kampus') {
        $query = "UPDATE kampus SET nama_kampus = '$nama_baru' WHERE id_kampus = '$id'";
    } elseif ($type === 'gedung') {
        $query = "UPDATE gedung SET nama_gedung = '$nama_baru' WHERE id_gedung = '$id'";
    } elseif ($type === 'ruangan') {
        $query = "UPDATE ruangan SET nama_ruangan = '$nama_baru' WHERE id_ruangan = '$id'";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tipe entitas tidak valid']);
        exit;
    }

    $execute = mysqli_query($host, $query);
    if($execute) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database']);
    }
    exit;
}

// ============================================================
// LOGIKA CRUD: Tambah & Hapus
// ============================================================

// ---- 1. TAMBAH KAMPUS ----
if (isset($_POST['tambah_kampus'])) {
    $nama_kampus = mysqli_real_escape_string($host, $_POST['nama_kampus']);
    
    if (empty($nama_kampus)) {
        header("location:../views/admin/lokasi/data.php?status=gagal_kosong");
        exit;
    }

    $query = "INSERT INTO kampus (id_kampus, nama_kampus) VALUES (NULL, '$nama_kampus')";
    $execute = mysqli_query($host, $query);

    if ($execute) {
        header("location:../views/admin/lokasi/data.php?status=sukses_tambah_kampus");
    } else {
        header("location:../views/admin/lokasi/data.php?status=gagal_tambah");
    }
}

// ---- 2. TAMBAH GEDUNG ----
if (isset($_POST['tambah_gedung'])) {
    $id_kampus   = mysqli_real_escape_string($host, $_POST['id_kampus']);
    $nama_gedung = mysqli_real_escape_string($host, $_POST['nama_gedung']);

    if (empty($id_kampus) || empty($nama_gedung)) {
        header("location:../views/admin/lokasi/data.php?status=gagal_kosong");
        exit;
    }

    $query = "INSERT INTO gedung (id_gedung, id_kampus, nama_gedung) VALUES (NULL, '$id_kampus', '$nama_gedung')";
    $execute = mysqli_query($host, $query);

    if ($execute) {
        header("location:../views/admin/lokasi/data.php?status=sukses_tambah_gedung");
    } else {
        header("location:../views/admin/lokasi/data.php?status=gagal_tambah");
    }
}

// ---- 3. TAMBAH RUANGAN ----
if (isset($_POST['tambah_ruangan'])) {
    $id_gedung    = mysqli_real_escape_string($host, $_POST['id_gedung']);
    $nama_ruangan = mysqli_real_escape_string($host, $_POST['nama_ruangan']);

    if (empty($id_gedung) || empty($nama_ruangan)) {
        header("location:../views/admin/lokasi/data.php?status=gagal_kosong");
        exit;
    }

    $query = "INSERT INTO ruangan (id_ruangan, id_gedung, nama_ruangan) VALUES (NULL, '$id_gedung', '$nama_ruangan')";
    $execute = mysqli_query($host, $query);

    if ($execute) {
        header("location:../views/admin/lokasi/data.php?status=sukses_tambah_ruangan");
    } else {
        header("location:../views/admin/lokasi/data.php?status=gagal_tambah");
    }
}

// ---- 4. HAPUS DATA VIA MODAL OVERLAY ----
if (isset($_GET['delete_type']) && isset($_GET['id'])) {
    $type = $_GET['delete_type'];
    $id   = mysqli_real_escape_string($host, $_GET['id']);
    
    if ($type === 'kampus') {
        $query = "DELETE FROM kampus WHERE id_kampus = '$id'";
    } elseif ($type === 'gedung') {
        $query = "DELETE FROM gedung WHERE id_gedung = '$id'";
    } elseif ($type === 'ruangan') {
        $query = "DELETE FROM ruangan WHERE id_ruangan = '$id'";
    }

    $execute = mysqli_query($host, $query);
    if ($execute) {
        header("location:../views/admin/lokasi/data.php?status=sukses_hapus");
    } else {
        header("location:../views/admin/lokasi/data.php?status=gagal_hapus_relasi");
    }
}
?>