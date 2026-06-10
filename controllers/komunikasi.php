<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
session_start();
include '../koneksi.php';

// Gunakan id_user dari session login aktif, atau fallback ke ID pelapor saat ini
$id_user = $_SESSION['id_user'] ?? 1; 
$action = $_GET['action'] ?? '';

// ════════════════════════════════════════════════════════════
// GERBANG 0: DAFTAR LAPORAN UNTUK SIDEBAR CHAT
// ════════════════════════════════════════════════════════════
if ($action === 'list_reports') {

    $query = "
        SELECT
            l.id_laporan,
            l.judul_laporan,
            l.status,
            u.nama AS pelapor
        FROM laporan l
        LEFT JOIN user u ON l.id_pelapor = u.id_user
        ORDER BY l.id_laporan DESC
    ";

    $result = mysqli_query($host, $query);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'reports' => $data
    ]);

    exit;
}

if ($action === 'list_reports_manager') {

    $id_user = $_SESSION['id_user'];

    $query = mysqli_query(
        $host,
        "SELECT
            l.id_laporan,
            l.judul_laporan,
            l.status,
            u.nama AS pelapor
        FROM laporan l
        INNER JOIN laporan_detail ld
            ON l.id_laporan = ld.id_laporan
        INNER JOIN user u
            ON l.id_pelapor = u.id_user
        WHERE ld.id_manager_teknisi = '$id_user'
        ORDER BY l.id_laporan DESC"
    );

    $reports = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $reports[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'reports' => $reports
    ]);

    exit;
}

// ════════════════════════════════════════════════════════════
// GERBANG 1: FETCH MESSAGES (MENARIK PESAN DARI DATABASE)
// ════════════════════════════════════════════════════════════
if ($action === 'fetch_messages') {
    if (!isset($_GET['id_laporan'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID Laporan tidak menyertai request.']);
        exit;
    }

    $id_laporan = mysqli_real_escape_string($host, $_GET['id_laporan']);
    
    // 1. Cari id_room yang terikat dengan id_laporan ini
    $room_q = mysqli_query($host, "SELECT id_room FROM chat_room WHERE id_laporan = '$id_laporan'"); //
    
    if (mysqli_num_rows($room_q) == 0) {
        // Jika belum ada room terbentuk, amankan dengan membuatkannya secara otomatis
        mysqli_query($host, "INSERT INTO chat_room (id_laporan, created_at) VALUES ('$id_laporan', NOW())"); //
        $id_room = mysqli_insert_id($host);
    } else {
        $room = mysqli_fetch_assoc($room_q);
        $id_room = $room['id_room'];
    }

    // 2. Tarik semua data pesan di room ini, JOIN ke tabel user untuk mendapat info nama & role pengirim
    $query_pesan = "SELECT p.*, u.nama as nama_pengirim, u.role as role_pengirim 
                    FROM pesan p 
                    LEFT JOIN user u ON p.id_pengirim = u.id_user 
                    WHERE p.id_room = '$id_room' 
                    ORDER BY p.id_pesan ASC"; //
              
    $execute = mysqli_query($host, $query_pesan);
    $messages = [];
    
    while ($row = mysqli_fetch_assoc($execute)) {
        $messages[] = [
            'id_pesan'    => (int)$row['id_pesan'], //
            'id_pengirim' => $row['id_pengirim'] !== null ? (int)$row['id_pengirim'] : null, //
            'pengirim' => $row['nama_pengirim'] ?? '[SISTEM]',
            'role'     => $row['role_pengirim'] ?? 'sistem',
            'text'        => $row['isi_pesan'], //
            'waktu'       => date('H:i', strtotime($row['waktu_kirim'])) //
        ];
    }

    // Kembalikan data dalam struktur JSON yang dinantikan oleh fetch() di app.js
    echo json_encode([
        'status'   => 'success', 
        'id_room'  => (int)$id_room, 
        'messages' => $messages
    ]);
    exit;
}

// ════════════════════════════════════════════════════════════
// GERBANG 2: SEND MESSAGE (KIRIM PESAN BARU DARI INPUT CHAT)
// ════════════════════════════════════════════════════════════
if ($action === 'send_message') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Metode request ilegal.']);
        exit;
    }

    $id_room   = mysqli_real_escape_string($host, $_POST['id_room']); //
    $isi_pesan = trim($_POST['isi_pesan'] ?? ''); //

    if (empty($isi_pesan)) {
        echo json_encode(['status' => 'error', 'message' => 'Pesan tidak boleh kosong.']);
        exit;
    }

    // Insert pesan baru ke database sesuai id_user pelapor yang sedang login
    $query_insert = "INSERT INTO pesan (id_room, id_pengirim, isi_pesan, waktu_kirim) 
                     VALUES ('$id_room', '$id_user', '$isi_pesan', NOW())"; //
                     
    if (mysqli_query($host, $query_insert)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil terkirim.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pesan: ' . mysqli_error($host)]);
    }
    exit;
}

$host->close();
?>