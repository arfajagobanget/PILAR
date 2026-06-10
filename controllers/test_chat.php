<?php
include '../koneksi.php';
// Paksa ambil data laporan terakhir yang punya room
$query = "SELECT p.*, u.nama 
          FROM pesan p 
          LEFT JOIN user u ON p.id_pengirim = u.id_user 
          ORDER BY p.id_pesan DESC LIMIT 5";
$result = mysqli_query($host, $query);

echo "<pre>";
if (!$result) {
    echo "Query Error: " . mysqli_error($host);
} else {
    while($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
}
echo "</pre>";
?>