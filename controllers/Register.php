<?php
// controllers/Register.php — Handler Pembuatan Akun Baru PILAR

header('Content-Type: application/json');
require_once '../koneksi.php'; // Hubungan koneksi utama proyek PILAR

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan.']);
    exit;
}

$nama       = trim($_POST['nama']       ?? '');
$username   = trim($_POST['username']   ?? '');
$email      = trim($_POST['email']      ?? '');
$password   = $_POST['password']        ?? '';
$konfirmasi = $_POST['konfirmasi']      ?? '';
$kategori   = trim($_POST['status_pengguna'] ?? '');
$role       = 'pelapor'; 

if (!$nama || !$username || !$email || !$password || !$konfirmasi || !$kategori) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode(['status' => 'error', 'message' => 'Password minimal 8 karakter.']);
    exit;
}
if (!preg_match('/[A-Z]/', $password)) {
    echo json_encode(['status' => 'error', 'message' => 'Password harus mengandung huruf kapital.']);
    exit;
}
if (!preg_match('/[a-z]/', $password)) {
    echo json_encode(['status' => 'error', 'message' => 'Password harus mengandung huruf kecil.']);
    exit;
}
if (!preg_match('/[0-9]/', $password)) {
    echo json_encode(['status' => 'error', 'message' => 'Password harus mengandung angka.']);
    exit;
}
if (!preg_match('/[^A-Za-z0-9]/', $password)) {
    echo json_encode(['status' => 'error', 'message' => 'Password harus mengandung simbol (!@#$% dst).']);
    exit;
}

if ($password !== $konfirmasi) {
    echo json_encode(['status' => 'error', 'message' => 'Konfirmasi password tidak cocok.']);
    exit;
}

$allowed_kategori = ['mahasiswa', 'dosen', 'staff'];
if (!in_array($kategori, $allowed_kategori)) {
    echo json_encode(['status' => 'error', 'message' => 'Kategori pengguna tidak valid.']);
    exit;
}

// Cek duplikasi email
$stmt = $host->prepare("SELECT id_user FROM `user` WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar.']);
    $stmt->close(); exit;
}
$stmt->close();

// Cek duplikasi username
$stmt = $host->prepare("SELECT id_user FROM `user` WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username sudah digunakan.']);
    $stmt->close(); exit;
}
$stmt->close();

$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Memasukkan data baru menggunakan kolom status_pengguna hasil sinkronisasi
$stmt = $host->prepare(
    "INSERT INTO `user` (nama, username, email, password, role, status_pengguna, foto)
     VALUES (?, ?, ?, ?, ?, ?, 'default.png')"
);
$stmt->bind_param('ssssss', $nama, $username, $email, $hash, $role, $kategori);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => "Akun berhasil dibuat! Silakan login, $nama."]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $stmt->error]);
}

$stmt->close();
$host->close();
?>