<?php
// controllers/Login.php — Handler Login Akses PILAR

header('Content-Type: application/json');
session_start();
require_once '../koneksi.php'; // Hubungan koneksi utama proyek PILAR

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan.']);
    exit;
}

$identifier = trim($_POST['identifier'] ?? '');
$password   = $_POST['password']        ?? '';
$remember   = isset($_POST['remember']) && $_POST['remember'] === '1';

if (!$identifier || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Email/username dan password wajib diisi.']);
    exit;
}

// Cari user menggunakan operator multi-identifier (Email ATAU Username) beserta tabel spesialisasi jika ada
$stmt = $host->prepare(
    "SELECT id_user, nama, username, email, password, role, status_pengguna
     FROM `user`
     WHERE email = ? OR username = ?
     LIMIT 1"
);
$stmt->bind_param('ss', $identifier, $identifier);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'Email atau username tidak ditemukan.']);
    exit;
}

if (!password_verify($password, $user['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Password salah. Coba lagi.']);
    exit;
}

// Simpan data kredensial ke dalam Session Utama Global
$_SESSION['id_user']  = $user['id_user'];
$_SESSION['nama']     = $user['nama'];
$_SESSION['username'] = $user['username'];
$_SESSION['role']     = $user['role'];
$_SESSION['status']   = $user['status_pengguna'];

if ($remember) {
    $token = bin2hex(random_bytes(32));
    $hash_token = hash('sha256', $token);

    $stmt = $host->prepare("UPDATE `user` SET remember_token = ? WHERE id_user = ?");
    $stmt->bind_param('si', $hash_token, $user['id_user']);
    $stmt->execute();
    $stmt->close();

    setcookie('remember_token', $token,     time() + (86400 * 30), '/', '', false, true);
    setcookie('remember_id',    $user['id_user'], time() + (86400 * 30), '/', '', false, true);
}

$host->close();

echo json_encode([
    'status'  => 'success',
    'message' => 'Login berhasil! Selamat datang, ' . $user['nama'] . '.',
    'user'    => [
        'nama'     => $user['nama'],
        'username' => $user['username'],
        'role'     => $user['role'],
        'status'   => $user['status_pengguna'],
    ]
]);
?>