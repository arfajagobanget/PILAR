<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PILAR — Portal Pelaporan Fasilitas Kampus</title>
    <link rel="stylesheet" href="assets/css/others/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body>

    <!-- =================== NAVBAR =================== -->
    <nav class="navbar" id="navbar">
        <a href="/" class="nav-logo">
            <img src="assets/img/logo_vertikal.png" alt="PILAR">
        </a>
        <ul class="nav-links">
            <li><a href="#about">Tentang PILAR</a></li>
            <li><a href="#workflow">Cara Kerja</a></li>
            <li><a href="#features">Fitur</a></li>
            <li><a href="views/auth/auth.php" class="nav-btn">Buat Laporan</a></li>
        </ul>
    </nav>


    <!-- =================== HERO =================== -->
    <section class="hero">
        <div class="hero-blob"></div>
        <div class="hero-blob-2"></div>

        <div class="hero-left">

            <div class="hero-tag">
                <i class="ti ti-building-community" aria-hidden="true"></i>
                Platform Pelaporan Kampus
            </div>

            <h1>
                Fasilitas rusak?<br>
                Lapor aja di <em>PILAR</em>
            </h1>

            <p class="hero-desc">
                Cukup foto, tulis lokasi, kirim — tim kami
                langsung gerak. Nggak perlu ribet atau bolak-balik
                ke kantor administrasi lagi.
            </p>


            <div class="hero-cta">
                <a href="views/auth/auth.php" class="btn-primary">
                    Buat Laporan Sekarang
                    <i class="ti ti-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="#workflow" class="btn-ghost">
                    <i class="ti ti-player-play" aria-hidden="true"></i>
                    Lihat Cara Kerja
                </a>
            </div>

            <div class="hero-stats">
                <div class="stat-item">
                    <h3 class="count-up" data-target="150">0</h3>
                    <p>Laporan masuk</p>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <h3 class="count-up" data-target="90">0</h3>
                    <p>Sudah selesai</p>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <h3 class="count-up" data-target="20">0</h3>
                    <p>Kampus mitra</p>
                </div>
            </div>

        </div>

        <div class="hero-right">
            <div class="hero-img-wrap">
                <img src="assets/img/hero.png" class="hero-img" alt="Ilustrasi PILAR">

                <div class="badge-float b1">
                    <i class="ti ti-clipboard-check" aria-hidden="true"></i>
                    Laporan terverifikasi
                </div>

                <div class="badge-float b2">
                    <i class="ti ti-check" aria-hidden="true"></i>
                    Fasilitas diperbaiki
                </div>
            </div>
        </div>
    </section>


    <!-- =================== ABOUT / PILAR ITU APA =================== -->
    <section class="about-section" id="about">

        <div class="about-section-header reveal">
            <div class="section-chip">
                <i class="ti ti-info-circle" aria-hidden="true"></i>
                Mengenal PILAR
            </div>
            <h2>Apa itu PILAR?</h2>
            <p>Kenalan dulu sama platform yang bakal jadi teman barumu kalau ada yang rusak di kampus.</p>
        </div>

        <div class="about-inner">

            <div class="about-visual-wrap reveal">
                <div class="about-mockup">

                    <!-- header bar -->
                    <div class="mock-topbar">
                        <div class="mock-dot r"></div>
                        <div class="mock-dot y"></div>
                        <div class="mock-dot g"></div>
                        <span>PILAR — Buat Laporan</span>
                    </div>

                    <!-- form card -->
                    <div class="mock-body">

                        <div class="mock-field">
                            <label>Judul Laporan</label>
                            <div class="mock-input">Proyektor kelas 3B mati total</div>
                        </div>

                        <div class="mock-field">
                            <label>Lokasi</label>
                            <div class="mock-input mock-input--icon">
                                <i class="ti ti-map-pin" aria-hidden="true"></i>
                                Gedung A, Lantai 3
                            </div>
                        </div>

                        <div class="mock-field">
                            <label>Foto Bukti</label>
                            <div class="mock-upload">
                                <i class="ti ti-camera" aria-hidden="true"></i>
                                <span>foto_kerusakan.jpg</span>
                                <span class="mock-upload-ok">
                                    <i class="ti ti-check" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mock-field">
                            <label>Status</label>
                            <div class="mock-status-row">
                                <span class="mock-badge mock-badge--yellow">
                                    <i class="ti ti-clock" aria-hidden="true"></i>
                                    Menunggu verifikasi
                                </span>
                            </div>
                        </div>

                        <button class="mock-btn" tabindex="-1">
                            <i class="ti ti-send" aria-hidden="true"></i>
                            Kirim Laporan
                        </button>

                    </div>

                </div>

                <p class="about-akronim">
                    <strong>P</strong>usat <strong>I</strong>nformasi dan <strong>L</strong>aporan<strong> A</strong>set yang <strong>R</strong>usak
                </p>
            </div>

            <div class="about-content reveal reveal-d1">
                <p>
                    <strong>PILAR</strong> hadir buat kamu — mahasiswa, dosen, atau staf —
                    yang sering nemuin fasilitas kampus yang rusak tapi nggak tau harus
                    lapor ke siapa atau gimana caranya.
                </p>
                <p>
                    Dari kursi kelas yang patah, proyektor mati, sampai wastafel bocor —
                    semua bisa dilaporin lewat sini. Tinggal foto, pilih lokasi, kirim.
                    Tim kami yang ngurusin sisanya.
                </p>
                <p>
                    Transparan juga — kamu bisa pantau status laporanmu sampai fasilitas
                    beneran selesai diperbaiki.
                </p>

                <div class="about-tags">
                    <span class="about-tag">
                        <i class="ti ti-users" aria-hidden="true"></i>
                        Untuk civitas kampus
                    </span>
                    <span class="about-tag">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                        Transparan & terpantau
                    </span>
                    <span class="about-tag">
                        <i class="ti ti-bolt" aria-hidden="true"></i>
                        Proses cepat
                    </span>
                    <span class="about-tag">
                        <i class="ti ti-device-mobile" aria-hidden="true"></i>
                        Bisa dari HP
                    </span>
                </div>
            </div>

        </div>
    </section>


    <!-- =================== WORKFLOW =================== -->
    <section class="workflow-section" id="workflow">

        <div class="section-header reveal">
            <div class="section-chip">
                <i class="ti ti-route" aria-hidden="true"></i>
                Alur Pelaporan
            </div>
            <h2>Cuma 4 langkah,<br>beneran gampang</h2>
            <p>Nggak perlu ngisi form panjang atau antri. Semua bisa kamu lakuin sendiri dari HP.</p>
        </div>

        <div class="workflow-grid">
            <div class="workflow-connector" aria-hidden="true"></div>

            <div class="workflow-card reveal reveal-d1">
                <div class="wf-num">1</div>
                <h3>Buat Laporan</h3>
                <p>Foto kerusakannya, tulis lokasi dan deskripsi singkat, lalu kirim.</p>
            </div>

            <div class="workflow-card reveal reveal-d2">
                <div class="wf-num">2</div>
                <h3>Verifikasi Admin</h3>
                <p>Admin ngecek laporan kamu dan memastikan datanya sudah lengkap.</p>
            </div>

            <div class="workflow-card reveal reveal-d3">
                <div class="wf-num">3</div>
                <h3>Teknisi Bergerak</h3>
                <p>Teknisi dapat tugas dan langsung ke lokasi untuk menangani kerusakan.</p>
            </div>

            <div class="workflow-card reveal reveal-d4">
                <div class="wf-num">4</div>
                <h3>Selesai!</h3>
                <p>Kamu dapat notifikasi kalau laporan sudah selesai ditangani. Beres!</p>
            </div>
        </div>

    </section>


    <!-- =================== FEATURES =================== -->
    <section class="features-section" id="features">

        <div class="section-header reveal">
            <div class="section-chip">
                <i class="ti ti-sparkles" aria-hidden="true"></i>
                Fitur Unggulan
            </div>
            <h2>Kenapa pakai PILAR?</h2>
            <p>Dirancang supaya pelaporan fasilitas kampus terasa semudah pesan makanan online.</p>
        </div>

        <div class="features-grid">

            <div class="feature-card reveal reveal-d1">
                <div class="feat-icon">
                    <i class="ti ti-chart-bar" aria-hidden="true"></i>
                </div>
                <h3>Dashboard Statistik</h3>
                <p>Lihat jumlah laporan, yang lagi proses, sampai yang sudah selesai — semuanya real-time.</p>
            </div>

            <div class="feature-card reveal reveal-d2">
                <div class="feat-icon">
                    <i class="ti ti-camera" aria-hidden="true"></i>
                </div>
                <h3>Upload Foto Bukti</h3>
                <p>Langsung foto kerusakannya dan upload. Semakin jelas fotonya, makin cepat ditangani.</p>
            </div>

            <div class="feature-card reveal reveal-d3">
                <div class="feat-icon">
                    <i class="ti ti-messages" aria-hidden="true"></i>
                </div>
                <h3>Chat Room</h3>
                <p>Bisa ngobrol langsung sama admin atau manajer teknisi kalau ada info tambahan yang perlu disampaikan.</p>
            </div>

            <div class="feature-card reveal reveal-d1">
                <div class="feat-icon">
                    <i class="ti ti-bell-ringing" aria-hidden="true"></i>
                </div>
                <h3>Notifikasi Update</h3>
                <p>Nggak perlu bolak-balik ngecek — kamu langsung dapet notifikasi setiap ada perubahan status.</p>
            </div>

            <div class="feature-card reveal reveal-d2">
                <div class="feat-icon">
                    <i class="ti ti-map-pin" aria-hidden="true"></i>
                </div>
                <h3>Tandai Lokasi</h3>
                <p>Pilih lokasi kerusakan di peta kampus supaya teknisi bisa langsung ke tempat yang tepat.</p>
            </div>

            <div class="feature-card reveal reveal-d3">
                <div class="feat-icon">
                    <i class="ti ti-shield-check" aria-hidden="true"></i>
                </div>
                <h3>Akses Aman</h3>
                <p>Login pakai akun kampus kamu. Data laporan terlindungi dan cuma bisa diakses yang berwenang.</p>
            </div>

        </div>

    </section>


    <!-- =================== CTA =================== -->
    <section class="cta-section">
        <div class="cta-inner reveal">
            <div class="cta-chip">
                <i class="ti ti-rocket" aria-hidden="true"></i>
                Yuk Mulai
            </div>
            <h2>Ada fasilitas rusak<br>di kampusmu?</h2>
            <p>
                Laporin sekarang dan bantu bikin kampus
                jadi tempat belajar yang lebih nyaman buat semua.
            </p>
            <div class="cta-actions">
                <a href="views/auth/auth.php" class="btn-white">
                    Buat Laporan
                    <i class="ti ti-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="#about" class="btn-outline-white">
                    Kenalan dulu sama PILAR
                </a>
            </div>
        </div>
    </section>


    <!-- =================== FOOTER =================== -->
    <footer>
        <div class="footer-grid">

            <div class="footer-brand">
                <img style="height: 100px;" src="assets/img/logo_vertikal.png" alt="PILAR">
                <p>Pusat Informasi dan Laporan Aset yang Rusak. Satu klik untuk kampus yang lebih baik.</p>
            </div>

            <div class="footer-col">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="#about"><i class="ti ti-info-circle" aria-hidden="true"></i> Tentang PILAR</a></li>
                    <li><a href="#workflow"><i class="ti ti-route" aria-hidden="true"></i> Cara Kerja</a></li>
                    <li><a href="#features"><i class="ti ti-sparkles" aria-hidden="true"></i> Fitur</a></li>
                    <li><a href="views/auth/auth.php"><i class="ti ti-clipboard-plus" aria-hidden="true"></i> Buat Laporan</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Informasi</h4>
                <ul>
                    <li><a href="views/others/profile_team/profile_team.php"><i class="ti ti-users" aria-hidden="true"></i> Tentang Tim Pengembang</a></li>
                    <!-- <li><a href="#"><i class="ti ti-lock" aria-hidden="true"></i> Kebijakan Privasi</a></li> -->
                    <!-- <li><a href="#"><i class="ti ti-mail" aria-hidden="true"></i> Kontak</a></li> -->
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <span>© 2026 PILAR. All Rights Reserved.</span>
            <span class="footer-pill">v1.0 · Portal Kampus</span>
        </div>
    </footer>


    <script>
        // Navbar scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 30);
        });

        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('in'); });
        }, { threshold: 0.1 });
        reveals.forEach(el => revealObs.observe(el));

        // Count-up
        const counters = document.querySelectorAll('.count-up');
        const countObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                const el = e.target;
                const target = parseInt(el.dataset.target);
                let n = 0;
                const step = Math.max(1, Math.ceil(target / 55));
                const run = () => {
                    n = Math.min(n + step, target);
                    el.textContent = n + '+';
                    if (n < target) requestAnimationFrame(run);
                };
                requestAnimationFrame(run);
                countObs.unobserve(el);
            });
        }, { threshold: 0.5 });
        counters.forEach(c => countObs.observe(c));

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const id = a.getAttribute('href');
                if (id === '#') return;
                e.preventDefault();
                const target = document.querySelector(id);
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>

</body>
</html>