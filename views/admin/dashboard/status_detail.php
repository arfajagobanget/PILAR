<?php
$target_status = $_GET['status'] ?? 'menunggu';
$active_page = 'dashboard';

include '../../../koneksi.php';

$stmt = mysqli_prepare(
    $host,
    "SELECT l.*, r.nama_ruangan
     FROM laporan l
     LEFT JOIN ruangan r ON l.id_ruangan = r.id_ruangan
     WHERE l.status = ?
     ORDER BY l.tanggal_laporan DESC"
);

mysqli_stmt_bind_param($stmt, "s", $target_status);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data_laporan = [];

while($row = mysqli_fetch_assoc($result)){
    $data_laporan[] = [
        'id' => $row['id_laporan'],
        'title' => $row['judul_laporan'],
        'loc' => $row['nama_ruangan'] ?? '-',
        'date' => $row['tanggal_laporan'],
        'status' => $row['status'],
        'foto' => $row['foto_sebelum']
    ];
}

include '../../../template/admin/header.php';
?>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content flex flex-col h-full" style="overflow-y: auto;">
        
        <header class="laporan-header" style="margin-bottom: 1.5rem;">
            <div class="flex flex-col gap-2">
                <div>
                    <a href="data.php" class="filter-btn" style="text-decoration: none; padding: 0.5rem 1rem; display: inline-flex; align-items: center; gap: 0.35rem; background: white; border-radius: 999px;">
                        <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Kembali ke Dasbor
                    </a>
                </div>
                <h1 class="page-title" style="margin-top: 0.75rem; font-size: 1.5rem; font-weight: 700;">
                    Log Laporan Berstatus: <span id="status-title-badge" class="badge uppercase tracking-wider" style="font-size: 0.75rem; padding: 0.35rem 0.85rem; vertical-align: middle;"></span>
                </h1>
                <p class="page-sub" style="font-size: 0.8125rem; color: var(--muted); margin-top: 0.25rem;">
                    Menampilkan seluruh berkas keluhan sarpras berdasarkan filter card dashboard PILAR.
                </p>
            </div>
        </header>

        <div class="filter-toolbar" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; background: white; padding: 1rem; border-radius: 20px; border: 1px solid rgba(165,216,255,0.15);">
            <div class="filter-pills">
                <span class="text-xs text-kf-muted flex items-center gap-1.5" style="font-size: 0.8125rem; color: var(--muted);">
                    <i data-lucide="info" class="w-4 h-4" style="color: var(--sky-deep);"></i> 
                    Menampilkan <b id="filtered-count" style="color: var(--dark); font-weight: 600;">0</b> berkas aduan aktif.
                </span>
            </div>
            <div class="search-box" style="display: flex; align-items: center; gap: 0.5rem; background: var(--light); border-radius: 999px; padding: 0.6rem 1.2rem; border: 1px solid rgba(165,216,255,0.25); min-width: 280px;">
                <i data-lucide="search" style="width: 15px; height: 15px; color: var(--muted);"></i>
                <input type="text" id="status-search-input" oninput="searchStatusReports()" placeholder="Cari judul atau lokasi..." style="background: none; border: none; outline: none; font-size: 0.8125rem; width: 100%; color: var(--dark);">
            </div>
        </div>

        <div class="report-grid" id="status-reports-grid-container">
            </div>
    </main>
</div>

<script>
const currentViewStatus = "<?php echo $target_status; ?>";
const laporanDB = <?= json_encode($data_laporan); ?>;


function renderStatusDetailGrid() {
    const container = document.getElementById('status-reports-grid-container');
    const badgeTitle = document.getElementById('status-title-badge');
    if (!container) return;

    container.innerHTML = '';
    
    const currentStatusMeta = {
    label: currentViewStatus,
    cls: 'status-' + currentViewStatus
};
    badgeTitle.textContent = currentStatusMeta.label;
    badgeTitle.className = `badge ${currentStatusMeta.cls} uppercase tracking-wider`;

    const filteredReports = laporanDB;
    document.getElementById('filtered-count').textContent = filteredReports.length;

    if (filteredReports.length === 0) {
        container.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 1rem;" class="slide-up">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--light); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <i data-lucide="folder-open" style="width: 26px; height: 26px; color: var(--muted);"></i>
                </div>
                <p style="font-size: 0.9375rem; font-weight: 600; color: var(--dark);">Belum ada keluhan fisik</p>
                <p style="font-size: 0.8125rem; color: var(--muted); mt-1">Tidak ada rincian data aduan untuk status kelompok ini.</p>
            </div>
        `;
        lucide.createIcons();
        return;
    }

    filteredReports.forEach(r => {
        const card = document.createElement('div');
        card.className = "report-card slide-up";
        
        let actionDateLabel = 'Waktu Verifikasi';
        if (r.status === 'proses') actionDateLabel = 'Mulai Ditugaskan';
        if (r.status === 'selesai') actionDateLabel = 'Selesai Diperbaiki';
        if (r.status === 'ditolak') actionDateLabel = 'Waktu Penolakan';

        const dates = {incoming: r.date, processed: r.date};

        // Kondisi perbaikan tombol aksi murni berbasis kustom class style pelapor Anda
        let actionButtonHtml = '';
        if (r.status === 'ditolak') {
            actionButtonHtml = `
                <button onclick="revertReportStatus(${r.id}, 'menunggu')" class="btn-outline-delete" style="background: var(--light); color: var(--sky-deep); width: 100%; border-radius: 14px; font-size: 0.8125rem; font-weight: 600; padding: 0.65rem; display: flex; align-items: center; justify-content: center; gap: 0.35rem; margin-top: 1rem; border: none;">
                    <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i> Batalkan Penolakan
                </button>
            `;
        } else if (r.status === 'diverifikasi' || r.status === 'proses') {
            actionButtonHtml = `
                <button onclick="revertReportStatus(${r.id}, 'menunggu')" class="btn-outline-edit" style="width: 100%; border-radius: 14px; font-size: 0.8125rem; font-weight: 600; padding: 0.65rem; display: flex; align-items: center; justify-content: center; gap: 0.35rem; margin-top: 1rem; border: none;">
                    <i data-lucide="undo-2" style="width: 14px; height: 14px;"></i> Kembalikan ke Antrean
                </button>
            `;
        } else if (r.status === 'selesai') {
            actionButtonHtml = `
                <button onclick="revertReportStatus(${r.id}, 'proses')" class="btn-outline-edit" style="background: #FFF3E0; color: #E67700; width: 100%; border-radius: 14px; font-size: 0.8125rem; font-weight: 600; padding: 0.65rem; display: flex; align-items: center; justify-content: center; gap: 0.35rem; margin-top: 1rem; border: none;">
                    <i data-lucide="wrench" style="width: 14px; height: 14px;"></i> Kerjakan Ulang Aset
                </button>
            `;
        } else if (r.status === 'menunggu') {
            actionButtonHtml = `
                <button onclick="revertReportStatus(${r.id}, 'ditolak')" class="btn-outline-delete" style="width: 100%; border-radius: 14px; font-size: 0.8125rem; font-weight: 600; padding: 0.65rem; display: flex; align-items: center; justify-content: center; gap: 0.35rem; margin-top: 1rem; border: none;">
                    <i data-lucide="x-circle" style="width: 14px; height: 14px;"></i> Tolak Laporan Ini
                </button>
            `;
        }

        card.innerHTML = `
            <div class="report-card-thumb"
                onclick="openDetailModal(${r.id})"
                style="
                    height:180px;
                    overflow:hidden;
                    cursor:pointer;
                ">
                <img
                    src="../../../assets/uploads/laporan/sebelum/${r.foto}"
                    alt="Foto Laporan"
                    style="
                        width:100%;
                        height:100%;
                        object-fit:cover;
                    "
                    onerror="this.src='../../../assets/uploads/laporan/sebelum/no-image.jpg'"
                >
            </div>
            <div class="report-card-body" style="padding: 1.25rem;">
                <h3 class="report-card-title" onclick="openDetailModal(${r.id})" style="font-size: 0.875rem; font-weight: 600; color: var(--dark); line-height: 1.4; margin-bottom: 0.35rem;">${r.title}</h3>
                <p class="report-card-loc" style="font-size: 0.75rem; color: var(--muted); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.25rem;">
                    <i data-lucide="map-pin" style="width: 13px; height: 13px;"></i> ${r.loc}
                </p>
                
                <div style="background: var(--light); padding: 0.75rem; border-radius: 14px; font-size: 0.75rem;" class="space-y-1.5">
                    <div style="display: flex; justify-content: space-between; color: var(--muted);">
                        <span>Tanggal Masuk:</span>
                        <b style="color: var(--dark);">${dates.incoming}</b>
                    </div>
                    <div style="display: flex; justify-content: space-between; color: var(--muted); border-top: 1px dashed rgba(165,216,255,0.3); padding-top: 6px; margin-top: 6px;">
                        <span>${actionDateLabel}:</span>
                        <b style="color: var(--dark);">${dates.processed}</b>
                    </div>
                </div>
                
                ${actionButtonHtml}
            </div>
        `;
        container.appendChild(card);
    });
    lucide.createIcons();
}

function revertReportStatus(id, targetNewStatus) {
    if (r) {
        r.status = targetNewStatus;
        if(targetNewStatus === 'menunggu') r.manager = '';
        
        if (chatsData[id]) {
            chatsData[id].push({
                sender: 'Admin',
                name: 'Sistem',
                msg: `🔄 Berkas aduan dikonfigurasi ke status "${statusMap[targetNewStatus].label}" oleh Administrator.`,
                time: 'Sekarang'
            });
        }
        
        showToast(`Sukses memproses aduan #PLR-${id} ke status baru! ⚙️`);
        renderStatusDetailGrid();
    }
}

function searchStatusReports() {
    const input = document.getElementById('status-search-input').value.toLowerCase();
    const cards = document.querySelectorAll('#status-reports-grid-container .report-card');
    
    cards.forEach(card => {
        const title = card.querySelector('.report-card-title').textContent.toLowerCase();
        const loc = card.querySelector('.report-card-loc').textContent.toLowerCase();
        if (title.includes(input) || loc.includes(input)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(renderStatusDetailGrid, 150);
});
</script>

<?php include '../../../template/admin/footer.php'; ?>