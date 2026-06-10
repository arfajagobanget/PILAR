<?php 
$active_page = 'chat'; 
include '../../../template/manager_teknisi/header.php'; 
?>

<div class="h-full w-full overflow-auto">
    <div class="flex h-full w-full">
        
        <?php include '../../../template/manager_teknisi/sidebar.php'; ?>

        <main class="flex-1 overflow-auto bg-kf-cream/50 p-8 flex flex-col h-full">
            <header class="mb-4 shrink-0"><h1 class="text-2xl font-bold text-kf-dark">Ruang Hub Obrolan Koordinasi 3-Arah</h1></header>
            <div class="flex-1 bg-white rounded-3xl border border-kf-sky/10 shadow-sm flex overflow-hidden min-h-[500px]">
                <!-- Saluran Daftar Obrolan -->
                <div class="w-80 border-r border-kf-sky/10 flex flex-col shrink-0 bg-kf-light/20">
                    <div class="p-4 border-b border-kf-sky/10 bg-white"><p class="text-xs font-bold text-kf-dark uppercase tracking-wider">Saluran Penugasan Anda</p></div>
                    <div class="flex-1 overflow-y-auto p-2 space-y-1.5" id="manager-chat-channels-list"></div>
                </div>
                <!-- Empty State -->
                <div class="flex-1 flex flex-col bg-white" id="manager-chat-empty-state">
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                        <i data-lucide="message-square" class="w-10 h-10 text-kf-skyDeep mb-2"></i>
                        <p class="text-sm font-bold text-kf-dark">Pilih Saluran Koordinasi Tugas</p>
                        <p class="text-xs text-kf-muted mt-1">Diskusikan keluhan bersama Admin Sarpras dan Pelapor.</p>
                    </div>
                </div>
                <!-- Active Box Chat -->
                <div class="flex-1 flex flex-col bg-white hidden" id="manager-chat-active-box">
                    <div class="p-4 border-b border-kf-sky/10 bg-kf-light/50 flex items-center justify-between">
                        <div><h3 class="text-xs font-bold text-kf-dark" id="manager-chat-box-title">-</h3><p class="text-[9px] text-kf-muted mt-0.5" id="manager-chat-box-participants">-</p></div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" id="manager-chat-box-status">-</span>
                    </div>
                    <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-kf-cream/20" id="manager-chat-box-messages-container"></div>
                    <div class="p-4 border-t border-kf-sky/10 flex gap-2 bg-white">
                        <input type="text" id="manager-chat-box-input" placeholder="Ketik pesan koordinasi..." class="flex-1 px-4 py-2.5 bg-kf-light rounded-xl text-xs outline-none focus:bg-white border border-transparent focus:border-amber-300 transition">
                        <button onclick="sendManagerPageChatMessage()" class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-sm hover:bg-amber-600 transition"><i data-lucide="send" class="w-4 h-4"></i></button>
                    </div>
                </div>
            </div>
        </main>

    </div>
</div>

<?php include '../../../template/manager_teknisi/footer.php';?>