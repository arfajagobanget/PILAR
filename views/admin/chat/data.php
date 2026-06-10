<?php 
$active_page = 'chat'; 
include '../../../template/admin/header.php'; 
?>

<div class="app-layout">
    <?php include '../../../template/admin/sidebar.php'; ?>

    <main class="main-content flex flex-col h-full">
        <header class="mb-4 shrink-0">
            <h1 class="text-2xl font-bold text-kf-dark">Ruang Hub Obrolan Koordinasi Admin</h1>
        </header>
        
        <div id="admin-chat-wrapper" class="flex-1 bg-white rounded-3xl border border-kf-sky/10 shadow-sm flex overflow-hidden min-h-[500px]">
            
            <div class="chat-sidebar-channels border-r border-kf-sky/10 flex flex-col shrink-0 bg-kf-light/20">
                <div class="p-4 border-b border-kf-sky/10 bg-white">
                    <p class="text-xs font-bold text-kf-dark uppercase tracking-wider">Daftar Saluran Komunikasi</p>
                </div>
                <div class="flex-1 overflow-y-auto p-2 space-y-1.5" id="admin-chat-channels-list"></div>
            </div>
            
            <div class="chat-empty-panel flex-1 flex flex-col bg-white" id="admin-chat-empty-state">
                <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                    <p class="text-sm font-bold text-kf-dark">Pilih Saluran Koordinasi</p>
                </div>
            </div>
            
            <div class="chat-active-panel flex-1 flex flex-col bg-white hidden" id="admin-chat-active-box">
                <div class="p-4 border-b border-kf-sky/10 bg-kf-light/50 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <button onclick="backToAdminChannelsList()" class="chat-mobile-back-btn p-2 rounded-xl bg-white border border-kf-sky/20 text-kf-dark hidden hover:bg-kf-light transition active:scale-95">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        </button>
                        
                        <div class="min-w-0">
                            <h3 class="text-xs font-bold text-kf-dark truncate" id="admin-chat-box-title">-</h3>
                            <p class="text-[9px] text-kf-muted mt-0.5 truncate" id="admin-chat-box-participants">-</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0" id="admin-chat-box-status">-</span>
                </div>
                
                <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-kf-cream/20" id="admin-chat-box-messages-container"></div>
                
                <div class="p-4 border-t border-kf-sky/10 flex gap-2 bg-white">
                    <input type="text" id="admin-chat-box-input" placeholder="Ketik pesan..." class="flex-1 px-4 py-2.5 bg-kf-light rounded-xl text-xs outline-none focus:ring-1 focus:ring-kf-skyDeep">
                    <button onclick="sendAdminPageChatMessage()" class="w-10 h-10 bg-kf-dark text-white rounded-xl flex items-center justify-center shadow-xs hover:brightness-110 transition"><i data-lucide="send" class="w-4 h-4"></i></button>
                </div>
            </div>

        </div>
    </main>
</div>

<?php include '../../../template/admin/footer.php'; ?>