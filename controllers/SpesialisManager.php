<?php
include '../koneksi.php';
$action = $_GET['action'] ?? '';

if ($action === 'tambah') {
    $nama = mysqli_real_escape_string($host, $_POST['nama_spesialis']);
    mysqli_query($host, "INSERT INTO spesialis_manager VALUES (NULL, '$nama')");
    header("location:../views/admin/spesialis/data.php?status=sukses");
} 
elseif ($action === 'edit') {
    $id = mysqli_real_escape_string($host, $_POST['id_spesialis']);
    $nama = mysqli_real_escape_string($host, $_POST['nama_spesialis']);
    mysqli_query($host, "UPDATE spesialis_manager SET nama_spesialis='$nama' WHERE id_spesialis='$id'");
    header("location:../views/admin/spesialis/data.php?status=sukses_update");
} 
elseif ($action === 'hapus') {
    $id = mysqli_real_escape_string($host, $_GET['id']);
    mysqli_query($host, "DELETE FROM spesialis_manager WHERE id_spesialis='$id'");
    header("location:../views/admin/spesialis/data.php?status=sukses_hapus");
}
?>