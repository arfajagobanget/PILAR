<?php 
$active_page = 'pelapor_data'; 
include '../../../koneksi.php';
include '../../../template/admin/header.php'; 
?>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content">
        <header class="mb-6">
            <h1 class="font-script text-4xl text-kf-dark">Log Database Pelapor Aktif</h1>
            <p class="text-xs text-gray-400 mt-1">Daftar mahasiswa, dosen, atau staff yang terdaftar dalam sistem PILAR.</p>
        </header>

        <div class="bg-white rounded-3xl p-6 border border-kf-sky/10 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-kf-sky/10 font-bold text-kf-muted uppercase bg-kf-light/50">
                            <th class="p-3">Nama Akun</th>
                            <th class="p-3">Kontak Email / Telp</th>
                            <th class="p-3">Klasifikasi Identitas</th>
                            <th class="p-3">Tanggal Join</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-kf-sky/5 text-kf-dark">
                        <?php 
                        $res = mysqli_query($host, "SELECT * FROM user WHERE role='pelapor' ORDER BY id_user DESC");
                        if(mysqli_num_rows($res) == 0) echo '<tr><td colspan="4" class="p-4 text-center text-gray-400 italic">Belum ada akun pelapor terdaftar.</td></tr>';
                        while($row = mysqli_fetch_assoc($res)):
                        ?>
                        <tr class="hover:bg-gray-50/40">
                            <td class="p-3 font-semibold"><?= $row['nama'] ?><br><span class="text-[10px] font-normal text-gray-400">@<?= $row['username'] ?></span></td>
                            <td class="p-3 text-gray-600"><?= $row['email'] ?><br><span class="text-[10px] text-gray-400"><?= $row['no_tlp'] ?? '-' ?></span></td>
                            <td class="p-3"><span class="px-2 py-0.5 bg-blue-50 text-blue-700 font-bold rounded text-[10px] uppercase"><?= $row['status_pengguna'] ?? 'Umum' ?></span></td>
                            <td class="p-3 text-gray-400"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include '../../../template/admin/footer.php'; ?>