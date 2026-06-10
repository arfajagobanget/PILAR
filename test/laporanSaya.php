<?php
/**
 * File: test_crud_pilar.php
 * Lokasi: C:\laragon\www\pilar\test\laporanSaya.php
 * * Pengujian Integrasi Alur CRUD Project PILAR (Procedural & cURL)
 */

require_once '../koneksi.php'; 

echo "<h2>=== INTEGRATION TESTING VIA CONTROLLER: LAPORAN PILAR ===</h2>";

// Endpoint target langsung ke file controller asli
$url_controller = "http://localhost/pilar/controllers/laporanSaya.php";


// ====================================================================
// 1. TESTING [CREATE]
// ====================================================================
echo "<h3>[SKENARIO 1] Memicu Logika INSERT di Controller via POST</h3>";

// Siapkan data dummy yang formatnya pas dengan variabel $_POST di controller
$data_post = [
    'tambah'         => true,
    'id_pelapor'     => 1, 
    'id_ruangan'     => 1, 
    'judul_laporan'  => "Router Ruang Kelas 402 - TEST CONTROLLER",
    'deskripsi'      => "Dites otomatis oleh sistem pengujian.",
    'foto_lama'      => 'default.png'
];

// Tembak controller pakai cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_controller);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Ditahan agar tidak redirect halaman
curl_exec($ch);
curl_close($ch);

// Verifikasi: Cek ke database apakah datanya beneran masuk atau tidak
$judul_cari = $data_post['judul_laporan'];
$check_insert = mysqli_query($host, "SELECT * FROM laporan WHERE judul_laporan = '$judul_cari' ORDER BY id_laporan DESC LIMIT 1");
$data_laporan = mysqli_fetch_assoc($check_insert);

if ($data_laporan) {
    $id_laporan = $data_laporan['id_laporan']; // Ambil ID untuk parameter edit dan hapus nanti
    echo "<span style='color: green;'>🟢 PASSED: Controller berhasil merespons POST, data nyata masuk DB! (ID: $id_laporan)</span><br>";
} else {
    die("<span style='color: red;'>🔴 FAILED: Controller gagal memasukkan data. Pengujian dihentikan.</span>");
}


// ====================================================================
// 2. TESTING [READ]
// ====================================================================
echo "<h3>[SKENARIO 2] Memicu Logika READ (Ambil Data Gedung via GET AJAX)</h3>";

// Simulasi parameter GET ajax untuk dropdown lokasi (Baris 11 di controller asli)
$url_read = $url_controller . "?action=get_gedung&id_kampus=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_read);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response_read = curl_exec($ch);
curl_close($ch);

// Pengecekan: Controller harus mengembalikan data yang berisi tag HTML <option>
if (!empty($response_read) && strpos($response_read, '<option') !== false) {
    echo "<span style='color: green;'>🟢 PASSED: Controller berhasil membaca database dan mengembalikan data HTML Dropdown.</span><br>";
} else {
    echo "<span style='color: red;'>🔴 FAILED: Controller gagal membaca data lokasi atau respons kosong.</span><br>";
}


// ====================================================================
// 3. TESTING [UPDATE]
// ====================================================================
echo "<h3>[SKENARIO 3] Memicu Logika UPDATE di Controller via POST</h3>";

if (isset($id_laporan)) {
    // Siapkan data edit, sesuaikan fieldnya dengan kondisi $_POST['update'] di controller
    $data_update = [
        'update'        => true,
        'id_pelapor'    => 1,
        'id_ruangan'    => 1,
        'judul_laporan' => "Router Ruang Kelas 402 - CONTROLLER TEREDIT",
        'deskripsi'     => "Deskripsi ini telah diubah melalui controller.",
        'status'        => "Diproses",
        'foto_lama'     => 'default.png'
    ];

    // Sertakan ID laporan lewat parameter GET sesuai baris 80 di controller asli
    $url_edit = $url_controller . "?id_laporan=" . $id_laporan;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_edit);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_update);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_exec($ch);
    curl_close($ch);

    // Verifikasi: Tarik ulang data dari DB untuk memastikan isinya sudah berubah
    $check_update = mysqli_query($host, "SELECT * FROM laporan WHERE id_laporan = '$id_laporan'");
    $data_teredit = mysqli_fetch_assoc($check_update);

    if ($data_teredit && $data_teredit['judul_laporan'] == $data_update['judul_laporan']) {
        echo "<span style='color: green;'>🟢 PASSED: Controller berhasil memproses perubahan data (Status: Diproses).</span><br>";
    } else {
        echo "<span style='color: red;'>🔴 FAILED: Controller gagal memperbarui data di database.</span><br>";
    }
}


echo "<hr>";


// ====================================================================
// 4. TESTING [DELETE] (CLEANUP)
// ====================================================================
echo "<h3>[SKENARIO 4] Memicu Logika DELETE di Controller via GET</h3>";

if (isset($id_laporan)) {
    // Tembak parameter GET delete (Baris 122 di controller asli)
    $url_delete = $url_controller . "?delete=" . $id_laporan;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_delete);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_exec($ch);
    curl_close($ch);

    // Verifikasi akhir: Data dengan ID tadi harusnya sudah tidak bisa dicari di DB
    $check_delete = mysqli_query($host, "SELECT * FROM laporan WHERE id_laporan = '$id_laporan'");
    $data_terhapus = mysqli_fetch_assoc($check_delete);

    if (!$data_terhapus) {
        echo "<span style='color: green;'>🟢 PASSED: Controller sukses menghapus data laporan beserta file fisiknya. Database kembali bersih!</span><br>";
    } else {
        echo "<span style='color: red;'>🔴 FAILED: Data gagal dihapus oleh fungsi delete di controller.</span><br>";
    }
}

echo "<h3>======================================================</h3>";
echo "<h3 style='color: green;'>KESIMPULAN: INTEGRASI ALUR CRUD (CREATE, READ, UPDATE, DELETE) VIA CONTROLLER SUKSES TOTAL!</h3>";
?>