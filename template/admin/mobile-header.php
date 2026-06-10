<?php
/**
 * template/admin/mobile-header.php
 * Top bar yang muncul eksklusif di resolusi layar mobile (<= 768px)
 */
?>
<header class="mobile-header">
  <div class="mobile-brand">
    <img src="/PILAR/assets/img/logo_vertikal.png" alt="Logo PILAR" style="width: 30%; height: 10%; object-fit: contain;">
  </div>

  <button class="hamburger" onclick="openSidebar()" aria-label="Buka menu">
    <i data-lucide="menu" style="width:20px;height:20px"></i>
  </button>
</header>