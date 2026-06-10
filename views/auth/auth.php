<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PILAR — Masuk atau Daftar</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --blue:       #2563EB;
  --blue-dark:  #1D4ED8;
  --blue-soft:  #EFF6FF;
  --blue-mid:   #BFDBFE;
  --orange:     #F97316;
  --text:       #111827;
  --text-mid:   #374151;
  --muted:      #9CA3AF;
  --bg:         #F3F4F6;
  --white:      #FFFFFF;
  --border:     #E5E7EB;
  --ok:         #22C55E;
  --ok-soft:    #DCFCE7;
  --danger:     #EF4444;
  --danger-soft:#FEE2E2;
  --radius:     20px;
  --radius-sm:  10px;
  --trans:      .2s cubic-bezier(.4,0,.2,1);
}

html, body {
  height: 100%;
  overflow: hidden;
}

body {
  font-family: 'Poppins', sans-serif;
  color: var(--text);
  display: flex;
}

/* ── LEFT PANEL ── */
.left {
  flex: 1;
  background: var(--blue);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 48px 40px;
  position: relative;
  overflow: hidden;
}

/* animated blobs */
.blob {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}
.blob-1 {
  top: -15%; right: -15%;
  width: 420px; height: 420px;
  background: radial-gradient(circle, rgba(255,255,255,0.09) 0%, transparent 65%);
  animation: blobDrift 9s ease-in-out infinite;
}
.blob-2 {
  bottom: -10%; left: -10%;
  width: 320px; height: 320px;
  background: radial-gradient(circle, rgba(249,115,22,0.2) 0%, transparent 65%);
  animation: blobDrift 12s ease-in-out infinite reverse;
}
.blob-3 {
  top: 40%; left: 5%;
  width: 180px; height: 180px;
  background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 65%);
  animation: blobDrift 7s ease-in-out infinite 2s;
}
@keyframes blobDrift {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33%       { transform: translate(18px, -22px) scale(1.06); }
  66%       { transform: translate(-14px, 14px) scale(0.96); }
}

/* dot grid */
.left-dots {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
  background-size: 32px 32px;
  pointer-events: none;
  animation: dotsDrift 20s linear infinite;
}
@keyframes dotsDrift {
  from { background-position: 0 0; }
  to   { background-position: 32px 32px; }
}

/* shimmer line that sweeps across */
.left-shimmer {
  position: absolute;
  top: 0; left: -100%;
  width: 60%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.04), transparent);
  animation: shimmerSweep 5s ease-in-out infinite;
  pointer-events: none;
}
@keyframes shimmerSweep {
  0%   { left: -60%; }
  100% { left: 120%; }
}

.left-inner {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 28px;
}

.left-logo {
  height: 120px;
  filter: brightness(0) invert(1);
  opacity: 0.95;
  animation: logoEntrance .8s cubic-bezier(.22,1,.36,1) both,
             logoFloat 5s ease-in-out 1s infinite;
}
@keyframes logoEntrance {
  from { opacity: 0; transform: translateY(-20px) scale(0.9); }
  to   { opacity: 0.95; transform: translateY(0) scale(1); }
}
@keyframes logoFloat {
  0%, 100% { transform: translateY(0); }
  50%       { transform: translateY(-8px); }
}

.left-divider {
  width: 48px; height: 2px;
  background: rgba(255,255,255,0.25);
  border-radius: 2px;
  animation: expandIn .6s .3s ease both;
}
@keyframes expandIn {
  from { width: 0; opacity: 0; }
  to   { width: 48px; opacity: 1; }
}

.left-title {
  font-size: 1.55rem;
  font-weight: 800;
  color: white;
  line-height: 1.25;
  letter-spacing: -0.5px;
  animation: fadeUp .7s .4s ease both;
}
.left-title span { color: rgba(255,255,255,0.65); }

.left-desc {
  font-size: 0.85rem;
  color: rgba(255,255,255,0.6);
  line-height: 1.7;
  max-width: 260px;
  animation: fadeUp .7s .5s ease both;
}

.left-chips {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 100%;
  max-width: 280px;
}
.left-chip {
  display: flex;
  align-items: center;
  gap: 10px;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 12px;
  padding: 10px 14px;
  transition: all .25s ease;
  cursor: default;
}
.left-chip:nth-child(1) { animation: slideChip .6s .6s ease both; }
.left-chip:nth-child(2) { animation: slideChip .6s .75s ease both; }
.left-chip:nth-child(3) { animation: slideChip .6s .9s ease both; }
@keyframes slideChip {
  from { opacity: 0; transform: translateX(-16px); }
  to   { opacity: 1; transform: translateX(0); }
}
.left-chip:hover {
  background: rgba(255,255,255,0.18);
  border-color: rgba(255,255,255,0.3);
  transform: translateX(5px);
}
.left-chip i {
  font-size: 18px; color: rgba(255,255,255,0.85); flex-shrink: 0;
  transition: transform .25s ease;
}
.left-chip:hover i { transform: scale(1.2) rotate(-5deg); }
.left-chip span { font-size: 0.8rem; color: rgba(255,255,255,0.75); font-weight: 500; }

/* ── RIGHT PANEL ── */
.right {
  width: 420px;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 32px 28px;
  background: var(--white);
  overflow-y: auto;
}

.right-inner {
  width: 100%;
  max-width: 360px;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: none;
  border: none;
  font-family: 'Poppins', sans-serif;
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--muted);
  cursor: pointer;
  padding: 0;
  margin-bottom: 28px;
  transition: color var(--trans);
}
.back-btn i { font-size: 16px; }
.back-btn:hover { color: var(--blue); }

.form-header { margin-bottom: 22px; }
.form-header h2 {
  font-size: 1.35rem;
  font-weight: 800;
  letter-spacing: -0.4px;
  color: var(--text);
  margin-bottom: 4px;
}
.form-header p {
  font-size: 0.82rem;
  color: var(--muted);
  line-height: 1.5;
}

/* alert */
.alert {
  padding: 10px 12px;
  border-radius: 10px;
  font-size: 0.8rem;
  font-weight: 500;
  margin-bottom: 14px;
  display: none;
  align-items: center;
  gap: 8px;
  line-height: 1.4;
}
.alert i { font-size: 16px; flex-shrink: 0; }
.alert.show    { display: flex; animation: fadeUp .2s ease both; }
.alert.success { background: var(--ok-soft); border: 1px solid #86EFAC; color: #15803D; }
.alert.error   { background: var(--danger-soft); border: 1px solid #FCA5A5; color: #B91C1C; }
.alert.info    { background: var(--blue-soft); border: 1px solid var(--blue-mid); color: var(--blue-dark); }

/* field */
.field { margin-bottom: 10px; }
.field-label {
  display: block;
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--text-mid);
  margin-bottom: 5px;
  letter-spacing: 0.01em;
}
.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.input-wrap input,
.input-wrap select {
  width: 100%;
  padding: 10px 38px 10px 12px;
  background: var(--bg);
  border: 1.5px solid var(--border);
  border-radius: var(--radius-sm);
  font-family: 'Poppins', sans-serif;
  font-size: 0.84rem;
  color: var(--text);
  outline: none;
  transition: var(--trans);
}
.input-wrap select { padding-right: 12px; appearance: none; cursor: pointer; }
.input-wrap input::placeholder { color: var(--muted); font-size: 0.82rem; }
.input-wrap input:focus,
.input-wrap select:focus {
  border-color: var(--blue);
  background: var(--white);
  box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.input-wrap input.valid   { border-color: var(--ok);     box-shadow: 0 0 0 3px rgba(34,197,94,0.1); }
.input-wrap input.invalid { border-color: var(--danger); box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }
.input-wrap input[readonly] { color: var(--muted); cursor: default; }

.input-icon {
  position: absolute; right: 11px;
  color: var(--muted); font-size: 16px;
  pointer-events: none;
}
.eye-btn {
  position: absolute; right: 11px;
  background: none; border: none;
  color: var(--muted); font-size: 16px;
  cursor: pointer; padding: 0;
  display: flex; align-items: center;
  transition: color var(--trans);
}
.eye-btn:hover { color: var(--text); }

.autofill-tag {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: 0.7rem; color: var(--blue); font-weight: 600; margin-top: 4px;
}
.autofill-tag i { font-size: 11px; }

/* pw strength */
.pw-strength { margin-top: 8px; display: none; }
.bar-wrap { display: flex; gap: 3px; margin-bottom: 7px; }
.bar-seg { flex: 1; height: 3px; border-radius: 4px; background: var(--border); transition: background var(--trans); }
.strength-lbl { font-size: 0.68rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 6px; color: var(--muted); }
.req-list { list-style: none; display: flex; flex-direction: column; gap: 3px; }
.req-item { display: flex; align-items: center; gap: 7px; font-size: 0.74rem; color: var(--muted); transition: color var(--trans); }
.req-item.ok   { color: var(--ok); }
.req-item.fail { color: var(--danger); }
.req-dot { width: 14px; height: 14px; border-radius: 50%; border: 1.5px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 8px; flex-shrink: 0; transition: var(--trans); }
.req-item.ok   .req-dot { border-color: var(--ok);    background: var(--ok-soft);     color: var(--ok); }
.req-item.fail .req-dot { border-color: var(--danger); background: var(--danger-soft); color: var(--danger); }

/* submit btn */
.btn-submit {
  width: 100%; padding: 11px;
  border: none; border-radius: var(--radius-sm);
  font-family: 'Poppins', sans-serif;
  font-size: 0.88rem; font-weight: 700;
  cursor: pointer; transition: all var(--trans);
  display: flex; align-items: center; justify-content: center; gap: 7px;
  margin-top: 16px; margin-bottom: 14px;
}
.btn-submit i { font-size: 17px; }
.btn-submit.orange {
  background: var(--orange); color: white;
  box-shadow: 0 4px 14px rgba(249,115,22,0.3);
}
.btn-submit.orange:hover  { background: #EA6C0A; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(249,115,22,0.4); }
.btn-submit.blue {
  background: var(--blue); color: white;
  box-shadow: 0 4px 14px rgba(37,99,235,0.3);
}
.btn-submit.blue:hover    { background: var(--blue-dark); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); }
.btn-submit:active        { transform: translateY(0); }
.btn-submit:disabled      { opacity: .5; cursor: not-allowed; transform: none; }

.switch-text { text-align: center; font-size: 0.8rem; color: var(--muted); }
.switch-text a { color: var(--blue); font-weight: 700; text-decoration: none; }
.switch-text a:hover { color: var(--orange); }

/* spinner */
.spinner {
  display: inline-block; width: 13px; height: 13px;
  border: 2px solid rgba(255,255,255,0.35); border-top-color: #fff;
  border-radius: 50%; animation: spin .6s linear infinite;
}
@keyframes spin    { to { transform: rotate(360deg); } }
@keyframes fadeUp  { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

.section { display: none; }
.section.active { display: block; animation: fadeUp .25s ease both; }

/* responsive */
@media (max-width: 720px) {
  html, body { overflow: auto; height: auto; }
  body { flex-direction: column; }
  .left { padding: 40px 24px; min-height: auto; }
  .left-logo { height: 80px; }
  .left-chips { display: none; }
  .right { width: 100%; padding: 28px 24px 48px; }
}
</style>
</head>
<body>

<!-- ── LEFT ── -->
<div class="left">
  <div class="left-dots"></div>
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>
  <div class="left-shimmer"></div>
  <div class="left-inner">

    <img src="../../assets/img/logo_vertikal.png" class="left-logo" alt="PILAR">

    <div class="left-divider"></div>

    <p class="left-title">
      Satu tempat buat<br>
      <span>lapor semua kerusakan</span>
    </p>

    <p class="left-desc">
      Foto, tulis lokasi, kirim — tim kami langsung gerak. Nggak ribet, nggak lama.
    </p>

    <div class="left-chips">
      <div class="left-chip">
        <i class="ti ti-camera"></i>
        <span>Foto & kirim dalam hitungan detik</span>
      </div>
      <div class="left-chip">
        <i class="ti ti-eye"></i>
        <span>Pantau status laporan real-time</span>
      </div>
      <div class="left-chip">
        <i class="ti ti-messages"></i>
        <span>Chat langsung sama teknisi</span>
      </div>
    </div>

  </div>
</div>

<!-- ── RIGHT ── -->
<div class="right">
  <div class="right-inner">

    <button class="back-btn" onclick="window.location.href='../../index.php'">
      <i class="ti ti-arrow-left"></i>
      Kembali
    </button>

    <div class="form-header">
      <h2 id="pageTitle">Halo, selamat datang!</h2>
      <p id="pageDesc">Masuk biar kamu bisa bikin laporan dan pantau statusnya.</p>
    </div>

    <div class="alert" id="alertBox"></div>

    <!-- LOGIN -->
    <div class="section active" id="loginSection">

      <div class="field">
        <label class="field-label">Email atau Username</label>
        <div class="input-wrap">
          <input type="text" id="loginIdentifier" placeholder="Email atau username kamu">
          <i class="ti ti-user input-icon"></i>
        </div>
      </div>

      <div class="field">
        <label class="field-label">Password</label>
        <div class="input-wrap">
          <input type="password" id="loginPassword" placeholder="Password kamu">
          <button class="eye-btn" type="button" onclick="toggleEye('loginPassword',this)"><i class="ti ti-eye"></i></button>
        </div>
      </div>

      <button class="btn-submit blue" id="loginBtn" onclick="doLogin()">
        <i class="ti ti-login"></i>
        Masuk Sekarang
      </button>

      <div class="switch-text">
        Belum punya akun? <a href="#" onclick="switchTo('register')">Daftar di sini</a>
      </div>
    </div>

    <!-- REGISTER -->
    <div class="section" id="registerSection">

      <button class="back-btn" onclick="switchTo('login')">
        <i class="ti ti-arrow-left"></i>
        Kembali ke login
      </button>

      <div class="field">
        <label class="field-label">Nama Lengkap</label>
        <div class="input-wrap">
          <input type="text" id="regNama" placeholder="Nama lengkap kamu">
          <i class="ti ti-user input-icon"></i>
        </div>
      </div>

      <div class="field">
        <label class="field-label">Email</label>
        <div class="input-wrap">
          <input type="email" id="regEmail" placeholder="Email aktif kamu">
          <i class="ti ti-mail input-icon"></i>
        </div>
      </div>

      <div class="field">
        <label class="field-label">Username</label>
        <div class="input-wrap">
          <input type="text" id="regUsername" placeholder="Buat username unik">
          <i class="ti ti-at input-icon"></i>
        </div>
      </div>

      <div class="field">
        <label class="field-label">Kategori Pengguna</label>
        <div class="input-wrap">
          <select id="regKategori">
            <option value="">-- Pilih kategori --</option>
            <option value="mahasiswa">Mahasiswa</option>
            <option value="dosen">Dosen</option>
            <option value="staff">Staff</option>
          </select>
        </div>
      </div>

      <div class="field">
        <label class="field-label">Password</label>
        <div class="input-wrap">
          <input type="password" id="regPassword" placeholder="Min. 8 karakter" oninput="checkPassword(this.value)">
          <button class="eye-btn" type="button" onclick="toggleEye('regPassword',this)"><i class="ti ti-eye"></i></button>
        </div>
        <div class="pw-strength" id="pwStrength">
          <div style="margin:7px 0 5px">
            <span class="strength-lbl" id="strengthLbl">Kekuatan Password</span>
          </div>
          <div class="bar-wrap">
            <div class="bar-seg" id="s1"></div>
            <div class="bar-seg" id="s2"></div>
            <div class="bar-seg" id="s3"></div>
            <div class="bar-seg" id="s4"></div>
          </div>
          <ul class="req-list">
            <li class="req-item" id="r-len">  <span class="req-dot"></span> Minimal 8 karakter</li>
            <li class="req-item" id="r-upper"><span class="req-dot"></span> Huruf kapital (A–Z)</li>
            <li class="req-item" id="r-lower"><span class="req-dot"></span> Huruf kecil (a–z)</li>
            <li class="req-item" id="r-num">  <span class="req-dot"></span> Angka (0–9)</li>
            <li class="req-item" id="r-sym">  <span class="req-dot"></span> Simbol (!@#$%…)</li>
          </ul>
        </div>
      </div>

      <div class="field">
        <label class="field-label">Konfirmasi Password</label>
        <div class="input-wrap">
          <input type="password" id="regKonfirmasi" placeholder="Ulangi password kamu" oninput="checkConfirm()">
          <button class="eye-btn" type="button" onclick="toggleEye('regKonfirmasi',this)"><i class="ti ti-eye"></i></button>
        </div>
      </div>

      <!-- hidden role field kept for backend compatibility -->
      <input type="hidden" id="regRole" value="pelapor">

      <button class="btn-submit orange" id="registerBtn" onclick="doRegister()">
        <i class="ti ti-user-plus"></i>
        Daftar Sekarang
      </button>

      <div class="switch-text">
        Sudah punya akun? <a href="#" onclick="switchTo('login')">Masuk di sini</a>
      </div>
    </div>

  </div>
</div>

<script>
function switchTo(page) {
  const isLogin = page === 'login';
  document.getElementById('loginSection').classList.toggle('active', isLogin);
  document.getElementById('registerSection').classList.toggle('active', !isLogin);
  document.getElementById('pageTitle').textContent = isLogin ? 'Halo, selamat datang!' : 'Yuk, bikin akun!';
  document.getElementById('pageDesc').textContent  = isLogin
    ? 'Masuk biar kamu bisa bikin laporan dan pantau statusnya.'
    : 'Daftar gratis dan mulai laporin fasilitas kampus yang perlu diperbaiki.';
  clearAlert();
}

function toggleEye(id, btn) {
  const inp = document.getElementById(id);
  const isPass = inp.type === 'password';
  inp.type = isPass ? 'text' : 'password';
  btn.innerHTML = isPass ? '<i class="ti ti-eye-off"></i>' : '<i class="ti ti-eye"></i>';
}

function showAlert(type, msg) {
  const el = document.getElementById('alertBox');
  const icon = type === 'success' ? 'ti-circle-check' : type === 'error' ? 'ti-alert-circle' : 'ti-info-circle';
  el.className = `alert ${type} show`;
  el.innerHTML = `<i class="ti ${icon}"></i> ${msg}`;
  el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
function clearAlert() {
  const el = document.getElementById('alertBox');
  el.className = 'alert'; el.textContent = '';
}

const rules = {
  'r-len':   v => v.length >= 8,
  'r-upper': v => /[A-Z]/.test(v),
  'r-lower': v => /[a-z]/.test(v),
  'r-num':   v => /[0-9]/.test(v),
  'r-sym':   v => /[^A-Za-z0-9]/.test(v),
};

function checkPassword(val) {
  const wrap = document.getElementById('pwStrength');
  if (!val) { wrap.style.display = 'none'; return; }
  wrap.style.display = 'block';
  let passed = 0;
  for (const [id, fn] of Object.entries(rules)) {
    const li = document.getElementById(id);
    const dot = li.querySelector('.req-dot');
    const ok = fn(val);
    li.className = 'req-item ' + (ok ? 'ok' : 'fail');
    dot.textContent = ok ? '✓' : '✕';
    if (ok) passed++;
  }
  const colors  = ['#ef4444','#f59e0b','#60a5fa','#22c55e'];
  const labels  = ['Sangat Lemah','Lemah','Cukup Kuat','Kuat','Sangat Kuat'];
  const strength = passed === 0 ? 0 : passed <= 2 ? 1 : passed <= 3 ? 2 : passed === 4 ? 3 : 4;
  ['s1','s2','s3','s4'].forEach((s,i) => {
    document.getElementById(s).style.background = i < strength ? colors[Math.min(strength-1,3)] : 'var(--border)';
  });
  const lbl = document.getElementById('strengthLbl');
  lbl.textContent = labels[strength];
  lbl.style.color = strength < 2 ? '#ef4444' : strength < 4 ? '#f59e0b' : '#22c55e';
  checkConfirm();
}

function checkConfirm() {
  const pw = document.getElementById('regPassword').value;
  const cf = document.getElementById('regKonfirmasi');
  if (!cf.value) { cf.classList.remove('valid','invalid'); return; }
  cf.classList.toggle('valid',   pw === cf.value);
  cf.classList.toggle('invalid', pw !== cf.value);
}

function isPasswordStrong(pw) {
  return Object.values(rules).every(fn => fn(pw));
}

async function doRegister() {
  clearAlert();
  const nama       = document.getElementById('regNama').value.trim();
  const email      = document.getElementById('regEmail').value.trim();
  const username   = document.getElementById('regUsername').value.trim();
  const kategori   = document.getElementById('regKategori').value;
  const password   = document.getElementById('regPassword').value;
  const konfirmasi = document.getElementById('regKonfirmasi').value;

  if (!nama || !email || !username || !kategori || !password || !konfirmasi)
    return showAlert('error', 'Semua field wajib diisi dulu ya.');
  if (!isPasswordStrong(password))
    return showAlert('error', 'Password belum memenuhi semua persyaratan.');
  if (password !== konfirmasi)
    return showAlert('error', 'Konfirmasi password nggak cocok nih.');

  const btn = document.getElementById('registerBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Mendaftarkan...';
  showAlert('info', 'Menyimpan data kamu...');

  const body = new URLSearchParams({ nama, email, username, password, konfirmasi, status_pengguna: kategori });
  try {
    const res  = await fetch('../../controllers/Register.php', { method: 'POST', body });
    const data = await res.json();
    showAlert(data.status === 'success' ? 'success' : 'error', data.message);
    if (data.status === 'success') {
      setTimeout(() => switchTo('login'), 2500);
      ['regNama','regEmail','regUsername','regPassword','regKonfirmasi'].forEach(id =>
        document.getElementById(id).value = '');
      document.getElementById('regKategori').value = '';
      document.getElementById('pwStrength').style.display = 'none';
    }
  } catch (e) {
    showAlert('error', 'Gagal terhubung ke server.');
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="ti ti-user-plus"></i> Daftar Sekarang';
}

async function doLogin() {
  clearAlert();
  const identifier = document.getElementById('loginIdentifier').value.trim();
  const password   = document.getElementById('loginPassword').value;

  if (!identifier || !password)
    return showAlert('error', 'Email/username dan password wajib diisi ya.');

  const btn = document.getElementById('loginBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Memverifikasi...';
  showAlert('info', 'Lagi ngecek kredensial kamu...');

  const body = new URLSearchParams({ identifier, password, remember: '0' });
  try {
    const res  = await fetch('../../controllers/Login.php', { method: 'POST', body });
    const data = await res.json();
    showAlert(data.status === 'success' ? 'success' : 'error', data.message);
    if (data.status === 'success') {
      setTimeout(() => {
        const role = data.user.role;
        if (role === 'admin')
          window.location.href = '../admin/dashboard/data.php';
        else if (role === 'manager_teknisi')
          window.location.href = '../manager_teknisi/dashboard/data.php';
        else
          window.location.href = '../pelapor/dashboard/data.php';
      }, 1500);
    }
  } catch (e) {
    showAlert('error', 'Gagal terhubung ke server.');
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="ti ti-login"></i> Masuk Sekarang';
}

document.addEventListener('keydown', e => {
  if (e.key !== 'Enter') return;
  document.getElementById('loginSection').classList.contains('active')
    ? doLogin() : doRegister();
});
</script>
</body>
</html>