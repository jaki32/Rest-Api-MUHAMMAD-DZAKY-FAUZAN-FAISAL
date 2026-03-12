<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PackTrack API — Sistem Tracking Paket Pengiriman</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #0a0e1a;
    --surface: #111827;
    --card: #1a2236;
    --border: #1e2d45;
    --accent: #3b82f6;
    --accent2: #06b6d4;
    --green: #10b981;
    --orange: #f59e0b;
    --red: #ef4444;
    --text: #e2e8f0;
    --muted: #64748b;
    --mono: 'JetBrains Mono', monospace;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { background:var(--bg); color:var(--text); font-family:'Space Grotesk',sans-serif; min-height:100vh; overflow-x:hidden; }

  /* GRID BACKGROUND */
  body::before {
    content:'';
    position:fixed; inset:0;
    background-image:
      linear-gradient(rgba(59,130,246,.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(59,130,246,.04) 1px, transparent 1px);
    background-size:40px 40px;
    pointer-events:none;
  }

  nav {
    position:fixed; top:0; left:0; right:0; z-index:100;
    background:rgba(10,14,26,.85);
    backdrop-filter:blur(20px);
    border-bottom:1px solid var(--border);
    padding:0 2rem;
    display:flex; align-items:center; justify-content:space-between;
    height:64px;
  }
  .logo { display:flex; align-items:center; gap:.75rem; }
  .logo-icon { width:36px; height:36px; background:linear-gradient(135deg,var(--accent),var(--accent2)); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; }
  .logo-text { font-size:1.1rem; font-weight:700; letter-spacing:-.5px; }
  .logo-text span { color:var(--accent); }
  nav a { color:var(--muted); text-decoration:none; font-size:.9rem; font-weight:500; padding:.4rem .9rem; border-radius:6px; transition:all .2s; }
  nav a:hover { color:var(--text); background:var(--card); }
  .nav-cta { background:var(--accent) !important; color:#fff !important; }
  .nav-cta:hover { background:#2563eb !important; }

  /* HERO */
  .hero {
    padding: 140px 2rem 80px;
    max-width:1100px; margin:0 auto; text-align:center;
  }
  .badge {
    display:inline-flex; align-items:center; gap:.5rem;
    background:rgba(59,130,246,.12); border:1px solid rgba(59,130,246,.3);
    color:var(--accent); padding:.35rem 1rem; border-radius:100px;
    font-size:.8rem; font-weight:600; letter-spacing:.5px; text-transform:uppercase;
    margin-bottom:2rem;
  }
  h1 { font-size:clamp(2.5rem,5vw,4rem); font-weight:700; line-height:1.1; letter-spacing:-2px; margin-bottom:1.5rem; }
  h1 span { background:linear-gradient(135deg,var(--accent),var(--accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
  .hero p { font-size:1.15rem; color:var(--muted); max-width:600px; margin:0 auto 2.5rem; line-height:1.7; }
  .hero-btns { display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; }
  .btn { display:inline-flex; align-items:center; gap:.5rem; padding:.8rem 1.8rem; border-radius:10px; font-weight:600; font-size:.95rem; text-decoration:none; transition:all .2s; cursor:pointer; border:none; }
  .btn-primary { background:var(--accent); color:#fff; }
  .btn-primary:hover { background:#2563eb; transform:translateY(-2px); box-shadow:0 8px 24px rgba(59,130,246,.4); }
  .btn-outline { background:transparent; color:var(--text); border:1px solid var(--border); }
  .btn-outline:hover { border-color:var(--accent); color:var(--accent); }

  /* STATS */
  .stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; max-width:800px; margin:4rem auto; }
  .stat-card { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:1.5rem; text-align:center; }
  .stat-num { font-size:2rem; font-weight:700; color:var(--accent); }
  .stat-label { color:var(--muted); font-size:.85rem; margin-top:.25rem; }

  /* SECTION */
  section { max-width:1100px; margin:0 auto; padding:4rem 2rem; }
  .section-title { font-size:1.8rem; font-weight:700; letter-spacing:-1px; margin-bottom:.5rem; }
  .section-sub { color:var(--muted); margin-bottom:3rem; }

  /* ENDPOINTS */
  .endpoint-grid { display:flex; flex-direction:column; gap:.75rem; }
  .endpoint {
    background:var(--card); border:1px solid var(--border); border-radius:12px;
    display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem;
    transition:all .2s;
  }
  .endpoint:hover { border-color:var(--accent); transform:translateX(4px); }
  .method { font-family:var(--mono); font-size:.8rem; font-weight:700; padding:.3rem .7rem; border-radius:6px; min-width:70px; text-align:center; }
  .get    { background:rgba(16,185,129,.15); color:var(--green); }
  .post   { background:rgba(59,130,246,.15); color:var(--accent); }
  .put    { background:rgba(245,158,11,.15); color:var(--orange); }
  .delete { background:rgba(239,68,68,.15);  color:var(--red); }
  .ep-path { font-family:var(--mono); font-size:.9rem; color:var(--text); flex:1; }
  .ep-desc { color:var(--muted); font-size:.85rem; }

  /* ERD */
  .erd-container { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:2.5rem; overflow-x:auto; }
  .erd { display:flex; align-items:center; justify-content:center; gap:1.5rem; flex-wrap:wrap; }
  .erd-table { background:var(--surface); border:1px solid var(--border); border-radius:10px; min-width:180px; overflow:hidden; }
  .erd-head { background:linear-gradient(135deg,var(--accent),var(--accent2)); padding:.6rem 1rem; font-weight:700; font-size:.85rem; text-align:center; }
  .erd-body { padding:.5rem 0; }
  .erd-row { padding:.3rem 1rem; font-family:var(--mono); font-size:.78rem; display:flex; align-items:center; gap:.4rem; }
  .erd-row.pk { color:var(--orange); }
  .erd-row.fk { color:var(--accent2); }
  .erd-arrow { font-size:1.5rem; color:var(--muted); }

  /* FOOTER */
  footer { border-top:1px solid var(--border); padding:2rem; text-align:center; color:var(--muted); font-size:.85rem; }

  @media(max-width:768px) {
    .stats { grid-template-columns:repeat(2,1fr); }
    .ep-desc { display:none; }
    nav a:not(.nav-cta) { display:none; }
  }
</style>
</head>
<body>

<nav>
  <div class="logo">
    <div class="logo-icon">📦</div>
    <div class="logo-text">Pack<span>Track</span> API</div>
  </div>
  <div style="display:flex;gap:.5rem;align-items:center;">
    <a href="#endpoints">Endpoints</a>
    <a href="#database">Database</a>
    <a href="docs/index.php">Docs</a>
    <a href="dashboard/index.php" class="btn nav-cta">🚀 Dashboard</a>
  </div>
</nav>

<div class="hero">
  <div class="badge">📦 REST API · PHP Native · MySQL</div>
  <h1>Sistem <span>Tracking Paket</span><br>Pengiriman</h1>
  <p>REST API lengkap untuk mengelola data pelanggan, kurir, pengiriman, dan tracking paket secara real-time.</p>
  <div class="hero-btns">
    <a href="docs/index.php" class="btn btn-primary">📖 Lihat Dokumentasi</a>
    <a href="dashboard/index.php" class="btn btn-outline">⚡ Coba Dashboard</a>
  </div>
</div>

<div class="stats" style="max-width:800px;margin:0 auto 5rem;padding:0 2rem;">
  <div class="stat-card"><div class="stat-num">4</div><div class="stat-label">Tabel Relasi</div></div>
  <div class="stat-card"><div class="stat-num">16</div><div class="stat-label">Endpoint API</div></div>
  <div class="stat-card"><div class="stat-num">4</div><div class="stat-label">HTTP Methods</div></div>
  <div class="stat-card"><div class="stat-num">JSON</div><div class="stat-label">Format Response</div></div>
</div>

<section id="endpoints">
  <div class="section-title">🔌 Daftar Endpoint API</div>
  <p class="section-sub">Semua endpoint tersedia di folder <code style="color:var(--accent2);background:var(--card);padding:.15rem .4rem;border-radius:4px;">api/</code></p>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
    <?php
    $groups = [
      ['title'=>'👤 Customers','base'=>'/api/customers.php'],
      ['title'=>'🚚 Couriers','base'=>'/api/couriers.php'],
      ['title'=>'📦 Shipments','base'=>'/api/shipments.php'],
      ['title'=>'📍 Tracking','base'=>'/api/tracking.php'],
    ];
    $methods = [
      ['GET','','Ambil semua data'],
      ['GET','?id=1','Ambil data by ID'],
      ['POST','','Tambah data baru'],
      ['PUT','?id=1','Update data'],
      ['DELETE','?id=1','Hapus data'],
    ];
    foreach($groups as $g): ?>
    <div>
      <h3 style="margin-bottom:1rem;font-size:1rem;color:var(--muted);"><?= $g['title'] ?></h3>
      <div class="endpoint-grid">
        <?php foreach($methods as $m): ?>
        <div class="endpoint">
          <span class="method <?= strtolower($m[0]) ?>"><?= $m[0] ?></span>
          <span class="ep-path"><?= $g['base'].$m[1] ?></span>
          <span class="ep-desc"><?= $m[2] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<section id="database">
  <div class="section-title">🗃️ Struktur Database (ERD)</div>
  <p class="section-sub">4 tabel dengan relasi menggunakan Primary Key & Foreign Key</p>
  <div class="erd-container">
    <div class="erd">
      <div class="erd-table">
        <div class="erd-head" style="background:linear-gradient(135deg,#10b981,#059669);">customers</div>
        <div class="erd-body">
          <div class="erd-row pk">🔑 customer_id (PK)</div>
          <div class="erd-row">• name</div>
          <div class="erd-row">• phone</div>
          <div class="erd-row">• address</div>
          <div class="erd-row">• created_at</div>
        </div>
      </div>
      <div class="erd-arrow">⟶</div>
      <div class="erd-table">
        <div class="erd-head">shipments</div>
        <div class="erd-body">
          <div class="erd-row pk">🔑 shipment_id (PK)</div>
          <div class="erd-row fk">🔗 customer_id (FK)</div>
          <div class="erd-row fk">🔗 courier_id (FK)</div>
          <div class="erd-row">• destination</div>
          <div class="erd-row">• shipment_date</div>
        </div>
      </div>
      <div class="erd-arrow">⟶</div>
      <div class="erd-table">
        <div class="erd-head" style="background:linear-gradient(135deg,#f59e0b,#d97706);">tracking</div>
        <div class="erd-body">
          <div class="erd-row pk">🔑 tracking_id (PK)</div>
          <div class="erd-row fk">🔗 shipment_id (FK)</div>
          <div class="erd-row">• status</div>
          <div class="erd-row">• location</div>
          <div class="erd-row">• update_time</div>
        </div>
      </div>
    </div>
    <div style="text-align:center;margin-top:1.5rem;color:var(--muted);font-size:.85rem;">
      🔗 <strong style="color:var(--accent2);">couriers</strong> (courier_id PK) juga berelasi ke <strong style="color:var(--text);">shipments</strong> (courier_id FK)
    </div>
  </div>
</section>

<footer>
  <p>PackTrack API — Tugas Mata Kuliah Integrasi Data &nbsp;·&nbsp; Dibangun dengan PHP Native & MySQL</p>
</footer>

</body>
</html>
