<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tim Pengembang — PILAR</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Lightbox -->
  <div class="lightbox" id="lightbox">
    <button class="lightbox-close" id="lbClose"><i class="ti ti-x"></i></button>
    <img src="" alt="" id="lbImg">
  </div>

  <!-- NAVBAR -->
  <nav class="navbar" id="navbar">
    <a href="../../index.php" class="nav-logo">
      <img src="../../../assets/img/logo_vertikal.png" style="height:80px;" alt="PILAR">
    </a>
    <a href="../../../index.php" class="nav-back">
      <i class="ti ti-arrow-left"></i>
      Kembali ke PILAR
    </a>
  </nav>


  <!-- ── HERO ── -->
  <section class="hero">
    <div class="hero-blob"></div>
    <div class="hero-blob-2"></div>

    <!-- Floating chips scattered in the right half -->
    <div class="hero-float-chip fc1">
      <i class="ti ti-code"></i>
      PHP & MySQL
    </div>
    <div class="hero-float-chip fc2">
      <i class="ti ti-git-branch"></i>
      Scrum Framework
    </div>
    <div class="hero-float-chip fc3">
      <i class="ti ti-award"></i>
      Semester Genap 2025/2026
    </div>

    <div class="hero-left">
      <img src="../../../assets/img/logo_kaba.png" class="kaba-logo-hero" alt="KABA Dev Logo"
           onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
      <div style="display:none; width:200px; height:200px; background:linear-gradient(135deg,#EFF6FF,#BFDBFE); border-radius:28px; border:1px solid #E5E7EB; align-items:center; justify-content:center; font-size:3rem; font-weight:800; color:#2563EB; box-shadow:0 16px 48px rgba(37,99,235,0.15);">K</div>
    </div>

    <div class="hero-right">
      <div class="hero-chip">
        <i class="ti ti-code"></i>
        Tim Pengembang PILAR
      </div>

      <h1>Hai, kami <em>KABA DEV.</em></h1>

      <p>
        Kami adalah tim pengembang di balik website <strong>PILAR</strong> — platform pelaporan fasilitas kampus
        yang kamu gunakan sekarang. Tim ini terbentuk dari tugas proyek satu semester
        dalam mata kuliah <strong>Praktikum Pengembangan Perangkat Lunak</strong>.
      </p>
      <p>
        Kelas <strong>S.Tr Teknologi Rekayasa Internet</strong>, PENS — di bawah bimbingan
        <strong>Bapak Febby Ronaldo, S.Tr.T., M.Tr.Kom.</strong>
      </p>

      <div class="hero-meta">
        <span class="meta-tag"><i class="ti ti-school"></i>PENS</span>
        <span class="meta-tag"><i class="ti ti-users"></i>4 Anggota Tim</span>
        <span class="meta-tag"><i class="ti ti-calendar"></i>Semester Genap 2025/2026</span>
        <span class="meta-tag"><i class="ti ti-code-circle"></i>PHP & MySQL</span>
      </div>
    </div>
  </section>


  <!-- TECH TICKER -->
  <div class="ticker-section">
    <div class="ticker-track" id="ticker">
      <!-- items duplicated for seamless loop -->
      <div class="ticker-item"><i class="ti ti-brand-php"></i>PHP 8</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-database"></i>MySQL</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-brand-html5"></i>HTML5</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-brand-css3"></i>CSS3</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-git-branch"></i>Scrum</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-server"></i>Apache</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-device-laptop"></i>Responsive UI</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-shield-lock"></i>Session Auth</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <!-- duplicate -->
      <!-- items duplicated for seamless loop -->
      <div class="ticker-item"><i class="ti ti-brand-php"></i>PHP 8</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-database"></i>MySQL</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-brand-html5"></i>HTML5</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-brand-css3"></i>CSS3</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-git-branch"></i>Scrum</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-server"></i>Apache</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-device-laptop"></i>Responsive UI</div>
      <div class="ticker-item"><span class="dot"></span></div>
      <div class="ticker-item"><i class="ti ti-shield-lock"></i>Session Auth</div>
      <div class="ticker-item"><span class="dot"></span></div>
    </div>
  </div>


  <!-- ── SECTION DIVIDER ── -->
  <div class="section-divider">
    <span></span><span></span><span></span>
  </div>


  <!-- ── TIM ── -->
  <section class="team-section" id="tim">

    <div class="section-header reveal">
      <div class="section-chip">
        <i class="ti ti-id-badge"></i>
        Perkenalan Tim
      </div>
      <h2>Orang-orang di balik PILAR</h2>
      <p>Dari dosen pembimbing sampai developer-nya — ini semua yang bikin PILAR bisa berjalan.</p>
    </div>

    <!-- Dosen -->
    <div class="dosen-card reveal">
      <div class="member-photo-placeholder">FR</div>
      <div class="dosen-info">
        <div class="member-role-badge badge-blue">
          <i class="ti ti-award"></i>
          Dosen Pengampu & Scrum Master
        </div>
        <h3>Febby Ronaldo, S.Tr.T., M.Tr.Kom.</h3>
        <p class="nim">Dosen Pengampu Mata Kuliah — PENS</p>
        <p>
          Bertindak sebagai <strong>Scrum Master</strong> sekaligus dosen pengampu
          mata kuliah Praktikum Pengembangan Perangkat Lunak. Bertugas membimbing jalannya
          proyek dari sisi metodologi pengembangan, memastikan sprint berjalan sesuai
          kerangka Scrum, serta melakukan supervisi terhadap seluruh proses perancangan
          dan implementasi sistem PILAR.
        </p>
      </div>
    </div>

    <!-- Label pembatas -->
    <div class="member-section-label reveal">Tim Developer</div>

    <!-- ── MEMBER 1: Deni (Product Owner) ── -->
    <div class="member-card reveal from-left">
      <div class="mc-photo-side">
        <img src="../../../assets/img/deni.jpg" alt="Deni Andriansyah"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="mc-photo-fallback" style="display:none">DA</div>

        <!-- floating tags -->
        <div class="mc-float-tags">
          <div class="mc-float-tag ft-orange" style="top:14%; left:10%;">
            <i class="ti ti-crown"></i> Product Owner
          </div>
          <div class="mc-float-tag ft-blue" style="bottom:22%; left:8%;">
            <i class="ti ti-target"></i> Vision & Strategy
          </div>
          <div class="mc-float-tag ft-green" style="top:48%; right:6%;">
            <i class="ti ti-list-check"></i> Backlog
          </div>
        </div>
      </div>

      <div class="mc-info">
        <div class="mc-number">01</div>
        <div class="member-role-badge badge-orange">
          <i class="ti ti-crown"></i>
          Product Owner
        </div>
        <h3>Deni Andriansyah</h3>
        <p class="mc-nim">2424600042</p>
        <p class="mc-desc">
          Memimpin arah pengembangan produk, mendefinisikan kebutuhan sistem,
          menetapkan prioritas fitur dalam product backlog, serta memastikan hasil akhir
          sesuai dengan tujuan dan kebutuhan pengguna. Bertindak sebagai jembatan antara
          stakeholder dan tim developer dalam setiap sprint PILAR.
        </p>
        <div class="mc-skills">
          <span class="mc-skill-pill"><i class="ti ti-bulb"></i>Product Strategy</span>
          <span class="mc-skill-pill"><i class="ti ti-list-details"></i>Backlog Management</span>
          <span class="mc-skill-pill"><i class="ti ti-users"></i>Stakeholder Comm.</span>
          <span class="mc-skill-pill"><i class="ti ti-chart-bar"></i>Sprint Planning</span>
        </div>
      </div>
    </div>

    <!-- ── MEMBER 2: Cintamani (reverse layout) ── -->
    <div class="member-card reverse reveal from-right">
      <div class="mc-photo-side">
        <img src="../../../assets/img/zhee.jpg" alt="Cintamani Zahrotul Qudsy"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="mc-photo-fallback" style="display:none">CZ</div>

        <div class="mc-float-tags">
          <div class="mc-float-tag ft-green" style="top:14%; right:8%;">
            <i class="ti ti-layout"></i> Frontend Dev
          </div>
          <div class="mc-float-tag ft-blue" style="bottom:22%; right:6%;">
            <i class="ti ti-palette"></i> UI/UX
          </div>
          <div class="mc-float-tag ft-orange" style="top:50%; left:6%;">
            <i class="ti ti-brand-css3"></i> CSS & HTML
          </div>
        </div>
      </div>

      <div class="mc-info">
        <div class="mc-number">02</div>
        <div class="member-role-badge badge-green">
          <i class="ti ti-code"></i>
          Development Team
        </div>
        <h3>Cintamani Zahrotul Qudsy</h3>
        <p class="mc-nim">2424600048</p>
        <p class="mc-desc">
          Bertanggung jawab dalam pengembangan fitur frontend, memastikan
          tampilan antarmuka responsif dan user-friendly, serta berkolaborasi
          dalam integrasi antara sisi klien dan server. Fokus pada pengalaman
          pengguna yang intuitif di setiap halaman PILAR.
        </p>
        <div class="mc-skills">
          <span class="mc-skill-pill"><i class="ti ti-brand-html5"></i>HTML5</span>
          <span class="mc-skill-pill"><i class="ti ti-brand-css3"></i>CSS3</span>
          <span class="mc-skill-pill"><i class="ti ti-layout-dashboard"></i>Responsive Design</span>
          <span class="mc-skill-pill"><i class="ti ti-plug"></i>Auth Session</span>
        </div>
      </div>
    </div>

    <!-- ── MEMBER 3: Arfa' ── -->
    <div class="member-card reveal from-left">
      <div class="mc-photo-side">
        <img src="../../../assets/img/arfa.png" alt="Muhammad Arfa' Ubaidillah"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="mc-photo-fallback" style="display:none">MU</div>

        <div class="mc-float-tags">
          <div class="mc-float-tag ft-blue" style="top:14%; left:8%;">
            <i class="ti ti-server"></i> Backend Dev
          </div>
          <div class="mc-float-tag ft-green" style="bottom:22%; left:6%;">
            <i class="ti ti-database"></i> MySQL DB
          </div>
          <div class="mc-float-tag ft-orange" style="top:50%; right:6%;">
            <i class="ti ti-brand-php"></i> PHP 8
          </div>
        </div>
      </div>

      <div class="mc-info">
        <div class="mc-number">03</div>
        <div class="member-role-badge badge-green">
          <i class="ti ti-server"></i>
          Development Team
        </div>
        <h3>Muhammad Arfa' Ubaidillah</h3>
        <p class="mc-nim">2424600034</p>
        <p class="mc-desc">
          Bertanggung jawab dalam pengembangan fitur backend, merancang
          arsitektur database, serta memastikan logika sistem dan alur data
          berjalan dengan aman dan efisien. Mengelola struktur tabel, relasi data,
          dan keamanan sistem autentikasi PILAR.
        </p>
        <div class="mc-skills">
          <span class="mc-skill-pill"><i class="ti ti-brand-php"></i>PHP</span>
          <span class="mc-skill-pill"><i class="ti ti-database"></i>MySQL</span>
          <span class="mc-skill-pill"><i class="ti ti-shield-lock"></i>Auth & Security</span>
          <span class="mc-skill-pill"><i class="ti ti-sitemap"></i>DB Architecture</span>
        </div>
      </div>
    </div>

  </section>


  <!-- ── GALERI ── -->
  <section class="gallery-section" id="dokumentasi">

    <div class="section-header reveal">
      <div class="section-chip">
        <i class="ti ti-camera"></i>
        Dokumentasi
      </div>
      <h2>Momen di balik layar</h2>
      <p>Secuplik perjalanan kami selama ngerjain PILAR dari awal sampai selesai.</p>
    </div>

    <div class="gallery-grid">

      <!-- m1 vertical -->
      <div class="gallery-item gi-1 reveal scale-up">
        <img src="../../../assets/img/galeri/m1.jpg" class="gallery-img" alt="Dokumentasi 1"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="gallery-placeholder" style="display:none"><i class="ti ti-photo"></i><span>Foto 1</span></div>
        <div class="gallery-overlay"><i class="ti ti-zoom-in"></i></div>
        <div class="gallery-caption">Behind the scenes #1</div>
      </div>

      <!-- m2 horizontal -->
      <div class="gallery-item gi-2 reveal d1">
        <img src="../../../assets/img/galeri/m2.jpg" class="gallery-img" alt="Dokumentasi 2"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="gallery-placeholder" style="display:none"><i class="ti ti-photo"></i><span>Foto 2</span></div>
        <div class="gallery-overlay"><i class="ti ti-zoom-in"></i></div>
        <div class="gallery-caption">Behind the scenes #2</div>
      </div>

      <!-- m3 horizontal -->
      <div class="gallery-item gi-3 reveal d2">
        <img src="../../../assets/img/galeri/m3.jpg" class="gallery-img" alt="Dokumentasi 3"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="gallery-placeholder" style="display:none"><i class="ti ti-photo"></i><span>Foto 3</span></div>
        <div class="gallery-overlay"><i class="ti ti-zoom-in"></i></div>
        <div class="gallery-caption">Behind the scenes #3</div>
      </div>

      <!-- m4 horizontal (bottom-left) -->
      <div class="gallery-item gi-4 reveal d1">
        <img src="../../../assets/img/galeri/m4.jpg" class="gallery-img" alt="Dokumentasi 4"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="gallery-placeholder" style="display:none"><i class="ti ti-photo"></i><span>Foto 4</span></div>
        <div class="gallery-overlay"><i class="ti ti-zoom-in"></i></div>
        <div class="gallery-caption">Behind the scenes #4</div>
      </div>

      <!-- m5 horizontal -->
      <div class="gallery-item gi-5 reveal d2">
        <img src="../../../assets/img/galeri/m5.jpg" class="gallery-img" alt="Dokumentasi 5"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="gallery-placeholder" style="display:none"><i class="ti ti-photo"></i><span>Foto 5</span></div>
        <div class="gallery-overlay"><i class="ti ti-zoom-in"></i></div>
        <div class="gallery-caption">Behind the scenes #5</div>
      </div>

      <!-- m6 vertical -->
      <div class="gallery-item gi-6 reveal d3">
        <img src="../../../assets/img/galeri/m6.jpg" class="gallery-img" alt="Dokumentasi 6"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="gallery-placeholder" style="display:none"><i class="ti ti-photo"></i><span>Foto 6</span></div>
        <div class="gallery-overlay"><i class="ti ti-zoom-in"></i></div>
        <div class="gallery-caption">Behind the scenes #6</div>
      </div>

      <!-- m7 vertical -->
      <div class="gallery-item gi-7 reveal d4">
        <img src="../../../assets/img/galeri/m7.jpg" class="gallery-img" alt="Dokumentasi 7"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="gallery-placeholder" style="display:none"><i class="ti ti-photo"></i><span>Foto 7</span></div>
        <div class="gallery-overlay"><i class="ti ti-zoom-in"></i></div>
        <div class="gallery-caption">Behind the scenes #7</div>
      </div>

      <!-- m8 vertical -->
      <div class="gallery-item gi-8 reveal d5">
        <img src="../../../assets/img/galeri/m8.jpg" class="gallery-img" alt="Dokumentasi 8"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="gallery-placeholder" style="display:none"><i class="ti ti-photo"></i><span>Foto 8</span></div>
        <div class="gallery-overlay"><i class="ti ti-zoom-in"></i></div>
        <div class="gallery-caption">Behind the scenes #8</div>
      </div>

    </div>

  </section>


  <!-- ── ABOUT KABA ── -->
  <section class="about-kaba" id="kaba">
    <div class="about-kaba-dots"></div>

    <div class="about-kaba-inner">

      <div class="kaba-logo-wrap reveal">
        <div class="kaba-logo-box">
          <!--
            Fix: Removed the double src= attribute typo.
            Using the same path as the hero logo which works.
          -->
          <img src="../../../assets/img/logo_kaba.png" alt="KABA Dev"
               onerror="this.outerHTML='<span style=\'font-size:3rem;font-weight:800;color:white;opacity:0.9;\'>K</span>'">
        </div>
        <p class="kaba-name">KABA DEV.</p>
        <p class="kaba-abbr">Khawarizmi's Algorithm Based Development</p>
      </div>

      <div class="about-kaba-text reveal d1">
        <div class="kaba-chip">
          <i class="ti ti-flame"></i>
          Filosofi Tim
        </div>
        <h2>
          Nama yang lahir dari<br>
          <span>warisan ilmuwan abad ke-9</span>
        </h2>
        <p>
          <strong>KABA Dev.</strong> atau <strong>Khawarizmi's Algorithm Based Development</strong> —
          nama ini berangkat dari sosok <strong>Al-Khawarizmi</strong>, ahli matematika Muslim
          asal Persia yang berkarya di bidang teknologi dan algoritma, sekaligus dikenal sebagai
          pencetus teori aljabar dan <em>Bapak Aljabar</em> yang terkemuka di abad ke-9.
        </p>

        <div class="kaba-timeline">
          <div class="kaba-tl-item">
            <div class="kaba-tl-dot"><i class="ti ti-math-function"></i></div>
            <div class="kaba-tl-text">
              <strong>Al-Khawarizmi (780–850 M)</strong> — Menulis <em>Al-Kitāb al-mukhtaṣar fī ḥisāb al-jabr wal-muqābala</em>,
              menjadi fondasi aljabar modern dan kata "algoritma" berasal dari namanya.
            </div>
          </div>
          <div class="kaba-tl-item">
            <div class="kaba-tl-dot"><i class="ti ti-code"></i></div>
            <div class="kaba-tl-text">
              <strong>KABA Dev. (2025–2026)</strong> — Tim ini bermaksud merepresentasikan semangat ilmuwan tersebut:
              <strong>software beralgoritma</strong> yang dirancang sesuai kondisi aktual masyarakat.
            </div>
          </div>
          <div class="kaba-tl-item">
            <div class="kaba-tl-dot"><i class="ti ti-building"></i></div>
            <div class="kaba-tl-text">
              <strong>PILAR</strong> — Wujud nyata dari semangat itu: solusi berbasis logika yang menjawab kebutuhan nyata kampus.
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>


  <!-- FOOTER -->
  <footer>
    <div class="footer-bottom">
      <img src="../../../assets/img/logo_vertikal.png" style="height:80px;" alt="PILAR">
      <span>© 2026 PILAR · Dikembangkan dengan cinta oleh KABA Dev.</span>
    </div>
  </footer>


  <script>
  /* ── Navbar scroll ── */
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 30);
  }, { passive: true });

  /* ── Reveal on scroll (IntersectionObserver) ── */
  const reveals = document.querySelectorAll('.reveal');
  const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('in');
        // Don't unobserve so elements stay visible
      }
    });
  }, { threshold: 0.08 });
  reveals.forEach(el => revealObs.observe(el));

  /* ── Animated counters ── */
  function animateCount(el) {
    const target = parseInt(el.dataset.count);
    const duration = 1200;
    const step = target / (duration / 16);
    let current = 0;
    const timer = setInterval(() => {
      current = Math.min(current + step, target);
      el.textContent = Math.floor(current);
      if (current >= target) clearInterval(timer);
    }, 16);
  }

  const counterObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.querySelectorAll('[data-count]').forEach(animateCount);
        counterObs.unobserve(e.target);
      }
    });
  }, { threshold: 0.2 });

  document.querySelectorAll('.stats-bar').forEach(el => counterObs.observe(el));

  /* ── Gallery lightbox ── */
  const lightbox = document.getElementById('lightbox');
  const lbImg    = document.getElementById('lbImg');
  const lbClose  = document.getElementById('lbClose');

  document.querySelectorAll('.gallery-item').forEach(item => {
    item.addEventListener('click', () => {
      const img = item.querySelector('img');
      if (!img || img.style.display === 'none') return;
      lbImg.src = img.src;
      lbImg.alt = img.alt;
      lightbox.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  });

  function closeLightbox() {
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
  }
  lbClose.addEventListener('click', closeLightbox);
  lightbox.addEventListener('click', e => { if (e.target === lightbox) closeLightbox(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

  /* ── Parallax blobs on mousemove ── */
  document.addEventListener('mousemove', e => {
    const mx = (e.clientX / window.innerWidth - 0.5) * 24;
    const my = (e.clientY / window.innerHeight - 0.5) * 24;
    document.querySelectorAll('.hero-blob').forEach(b => {
      b.style.transform = `translate(${mx * 0.6}px, ${my * 0.6}px)`;
    });
    document.querySelectorAll('.hero-blob-2').forEach(b => {
      b.style.transform = `translate(${-mx * 0.4}px, ${-my * 0.4}px)`;
    });
  }, { passive: true });

  /* ── Smooth stagger for member cards ── */
  const memberCards = document.querySelectorAll('.member-card');
  memberCards.forEach((card, i) => {
    card.style.transitionDelay = `${i * 0.08}s`;
  });
  </script>

</body>
</html>