<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PackTrack API — Dokumentasi</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{--bg:#0a0e1a;--surface:#111827;--card:#1a2236;--border:#1e2d45;--accent:#3b82f6;--accent2:#06b6d4;--green:#10b981;--orange:#f59e0b;--red:#ef4444;--text:#e2e8f0;--muted:#64748b;--mono:'JetBrains Mono',monospace;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{background:var(--bg);color:var(--text);font-family:'Space Grotesk',sans-serif;display:flex;min-height:100vh;}
  body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(59,130,246,.03)1px,transparent 1px),linear-gradient(90deg,rgba(59,130,246,.03)1px,transparent 1px);background-size:40px 40px;pointer-events:none;}

  /* SIDEBAR */
  aside{width:260px;min-height:100vh;background:var(--surface);border-right:1px solid var(--border);padding:1.5rem 1rem;position:fixed;top:0;left:0;overflow-y:auto;z-index:50;}
  .sidebar-logo{display:flex;align-items:center;gap:.6rem;padding:.5rem .5rem 1.5rem;border-bottom:1px solid var(--border);margin-bottom:1.5rem;}
  .sidebar-logo span{font-weight:700;font-size:1rem;}
  .sidebar-logo small{color:var(--muted);font-size:.75rem;}
  .nav-group{margin-bottom:1.5rem;}
  .nav-group-title{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);padding:.5rem;margin-bottom:.5rem;}
  .nav-item{display:block;padding:.5rem .75rem;border-radius:8px;color:var(--muted);text-decoration:none;font-size:.875rem;cursor:pointer;transition:all .15s;border:none;background:none;width:100%;text-align:left;}
  .nav-item:hover{background:var(--card);color:var(--text);}
  .nav-item.active{background:rgba(59,130,246,.15);color:var(--accent);}
  .method-dot{width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:.5rem;}
  .dot-get{background:var(--green);}
  .dot-post{background:var(--accent);}
  .dot-put{background:var(--orange);}
  .dot-delete{background:var(--red);}

  /* MAIN */
  main{margin-left:260px;flex:1;padding:2rem;max-width:900px;}
  .page-header{margin-bottom:3rem;padding-bottom:2rem;border-bottom:1px solid var(--border);}
  .page-header h1{font-size:2rem;font-weight:700;letter-spacing:-1px;margin-bottom:.5rem;}
  .page-header p{color:var(--muted);}

  /* ENDPOINT CARD */
  .ep-card{background:var(--card);border:1px solid var(--border);border-radius:16px;margin-bottom:2rem;overflow:hidden;}
  .ep-header{padding:1rem 1.5rem;display:flex;align-items:center;gap:1rem;border-bottom:1px solid var(--border);}
  .method-badge{font-family:var(--mono);font-size:.8rem;font-weight:700;padding:.35rem .9rem;border-radius:6px;}
  .badge-get   {background:rgba(16,185,129,.15);color:var(--green);border:1px solid rgba(16,185,129,.3);}
  .badge-post  {background:rgba(59,130,246,.15);color:var(--accent);border:1px solid rgba(59,130,246,.3);}
  .badge-put   {background:rgba(245,158,11,.15);color:var(--orange);border:1px solid rgba(245,158,11,.3);}
  .badge-delete{background:rgba(239,68,68,.15);color:var(--red);border:1px solid rgba(239,68,68,.3);}
  .ep-path{font-family:var(--mono);font-size:.95rem;color:var(--text);}
  .ep-title{color:var(--muted);font-size:.85rem;margin-left:auto;}
  .ep-body{padding:1.5rem;}
  .ep-body p{color:var(--muted);font-size:.9rem;margin-bottom:1.25rem;line-height:1.6;}
  .ep-section-title{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:.75rem;}
  .param-table{width:100%;border-collapse:collapse;margin-bottom:1.5rem;font-size:.875rem;}
  .param-table th{text-align:left;padding:.6rem .75rem;background:var(--surface);color:var(--muted);font-weight:600;border-bottom:1px solid var(--border);}
  .param-table td{padding:.6rem .75rem;border-bottom:1px solid rgba(30,45,69,.5);}
  .param-table td:first-child{font-family:var(--mono);color:var(--accent2);}
  .required{color:var(--red);font-size:.75rem;font-weight:600;}
  .optional{color:var(--muted);font-size:.75rem;}
  pre{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1.25rem;font-family:var(--mono);font-size:.8rem;overflow-x:auto;line-height:1.6;position:relative;}
  pre .k{color:var(--accent2);}
  pre .s{color:var(--green);}
  pre .n{color:var(--orange);}
  .copy-btn{position:absolute;top:.75rem;right:.75rem;background:var(--card);border:1px solid var(--border);color:var(--muted);padding:.3rem .6rem;border-radius:6px;font-size:.75rem;cursor:pointer;transition:all .2s;}
  .copy-btn:hover{color:var(--text);border-color:var(--accent);}
  .try-btn{display:inline-flex;align-items:center;gap:.5rem;background:var(--accent);color:#fff;border:none;padding:.6rem 1.2rem;border-radius:8px;font-size:.875rem;font-weight:600;cursor:pointer;transition:all .2s;text-decoration:none;}
  .try-btn:hover{background:#2563eb;transform:translateY(-1px);}

  /* TRY PANEL */
  .try-panel{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1.25rem;margin-top:1.25rem;display:none;}
  .try-panel.open{display:block;}
  .try-url{font-family:var(--mono);font-size:.8rem;color:var(--accent2);background:var(--card);padding:.5rem .75rem;border-radius:6px;margin-bottom:.75rem;word-break:break-all;}
  textarea,input[type=text]{width:100%;background:var(--card);border:1px solid var(--border);color:var(--text);padding:.6rem .75rem;border-radius:8px;font-family:var(--mono);font-size:.8rem;resize:vertical;outline:none;}
  textarea:focus,input:focus{border-color:var(--accent);}
  .try-result{margin-top:.75rem;background:var(--card);border:1px solid var(--border);border-radius:8px;padding:1rem;font-family:var(--mono);font-size:.78rem;max-height:260px;overflow-y:auto;white-space:pre-wrap;color:var(--text);}
  .status-ok{color:var(--green);}
  .status-err{color:var(--red);}
  label{font-size:.8rem;color:var(--muted);display:block;margin-bottom:.3rem;}
  .form-group{margin-bottom:.75rem;}
  .section-divider{border:none;border-top:1px solid var(--border);margin:3rem 0;}

  @media(max-width:900px){aside{display:none;}main{margin-left:0;}}
</style>
</head>
<body>

<aside>
  <div class="sidebar-logo">
    <span>📦</span>
    <div><span>PackTrack API</span><br><small>v1.0 — Dokumentasi</small></div>
  </div>

  <div class="nav-group">
    <div class="nav-group-title">👤 Customers</div>
    <button class="nav-item active" onclick="scrollTo('cust-get-all')"><span class="method-dot dot-get"></span>GET All</button>
    <button class="nav-item" onclick="scrollTo('cust-get-id')"><span class="method-dot dot-get"></span>GET by ID</button>
    <button class="nav-item" onclick="scrollTo('cust-post')"><span class="method-dot dot-post"></span>POST</button>
    <button class="nav-item" onclick="scrollTo('cust-put')"><span class="method-dot dot-put"></span>PUT</button>
    <button class="nav-item" onclick="scrollTo('cust-delete')"><span class="method-dot dot-delete"></span>DELETE</button>
  </div>
  <div class="nav-group">
    <div class="nav-group-title">🚚 Couriers</div>
    <button class="nav-item" onclick="scrollTo('courier-get')"><span class="method-dot dot-get"></span>GET All</button>
    <button class="nav-item" onclick="scrollTo('courier-post')"><span class="method-dot dot-post"></span>POST</button>
    <button class="nav-item" onclick="scrollTo('courier-put')"><span class="method-dot dot-put"></span>PUT</button>
    <button class="nav-item" onclick="scrollTo('courier-delete')"><span class="method-dot dot-delete"></span>DELETE</button>
  </div>
  <div class="nav-group">
    <div class="nav-group-title">📦 Shipments</div>
    <button class="nav-item" onclick="scrollTo('ship-get')"><span class="method-dot dot-get"></span>GET All</button>
    <button class="nav-item" onclick="scrollTo('ship-post')"><span class="method-dot dot-post"></span>POST</button>
    <button class="nav-item" onclick="scrollTo('ship-put')"><span class="method-dot dot-put"></span>PUT</button>
    <button class="nav-item" onclick="scrollTo('ship-delete')"><span class="method-dot dot-delete"></span>DELETE</button>
  </div>
  <div class="nav-group">
    <div class="nav-group-title">📍 Tracking</div>
    <button class="nav-item" onclick="scrollTo('track-get')"><span class="method-dot dot-get"></span>GET All</button>
    <button class="nav-item" onclick="scrollTo('track-post')"><span class="method-dot dot-post"></span>POST</button>
    <button class="nav-item" onclick="scrollTo('track-put')"><span class="method-dot dot-put"></span>PUT</button>
    <button class="nav-item" onclick="scrollTo('track-delete')"><span class="method-dot dot-delete"></span>DELETE</button>
  </div>
  <div style="padding:.5rem;margin-top:1rem;border-top:1px solid var(--border);">
    <a href="../dashboard/index.php" style="display:block;text-align:center;background:var(--accent);color:#fff;padding:.6rem;border-radius:8px;text-decoration:none;font-weight:600;font-size:.875rem;">⚡ Buka Dashboard</a>
  </div>
</aside>

<main>
  <div class="page-header">
    <h1>📖 Dokumentasi API</h1>
    <p>PackTrack REST API — Sistem Tracking Paket Pengiriman · Base URL: <code style="color:var(--accent2);background:var(--card);padding:.2rem .5rem;border-radius:4px;">http://localhost/project-api</code></p>
  </div>

  <!-- ═══════════════════════ CUSTOMERS ═══════════════════════ -->
  <h2 style="font-size:1.2rem;margin-bottom:1.5rem;">👤 Customers</h2>

  <!-- GET ALL -->
  <div class="ep-card" id="cust-get-all">
    <div class="ep-header">
      <span class="method-badge badge-get">GET</span>
      <span class="ep-path">/api/customers.php</span>
      <span class="ep-title">Ambil semua customer</span>
    </div>
    <div class="ep-body">
      <p>Mengambil seluruh data customer yang tersimpan di database.</p>
      <div class="ep-section-title">Contoh Response</div>
      <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
  <span class="k">"success"</span>: <span class="n">true</span>,
  <span class="k">"total"</span>: <span class="n">2</span>,
  <span class="k">"data"</span>: [
    {
      <span class="k">"customer_id"</span>: <span class="n">1</span>,
      <span class="k">"name"</span>: <span class="s">"Budi Santoso"</span>,
      <span class="k">"phone"</span>: <span class="s">"081234567890"</span>,
      <span class="k">"address"</span>: <span class="s">"Jl. Merdeka No. 10, Jakarta"</span>
    }
  ]
}</pre>
      <button class="try-btn" onclick="toggleTry('try-cust-get')">⚡ Try it</button>
      <div class="try-panel" id="try-cust-get">
        <div class="try-url">GET ../api/customers.php</div>
        <button class="try-btn" onclick="runTry('try-cust-get-result','../api/customers.php','GET')">▶ Send Request</button>
        <div class="try-result" id="try-cust-get-result" style="display:none;"></div>
      </div>
    </div>
  </div>

  <!-- POST -->
  <div class="ep-card" id="cust-post">
    <div class="ep-header">
      <span class="method-badge badge-post">POST</span>
      <span class="ep-path">/api/customers.php</span>
      <span class="ep-title">Tambah customer</span>
    </div>
    <div class="ep-body">
      <p>Menambahkan data customer baru. Kirim data dalam format JSON di request body.</p>
      <div class="ep-section-title">Request Body</div>
      <table class="param-table">
        <tr><th>Field</th><th>Tipe</th><th>Keterangan</th></tr>
        <tr><td>name</td><td>string</td><td>Nama customer <span class="required">*wajib</span></td></tr>
        <tr><td>phone</td><td>string</td><td>Nomor HP <span class="required">*wajib</span></td></tr>
        <tr><td>address</td><td>string</td><td>Alamat lengkap <span class="required">*wajib</span></td></tr>
      </table>
      <div class="ep-section-title">Contoh Request Body</div>
      <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
  <span class="k">"name"</span>: <span class="s">"Dewi Anggraini"</span>,
  <span class="k">"phone"</span>: <span class="s">"089012345678"</span>,
  <span class="k">"address"</span>: <span class="s">"Jl. Gatot Subroto No. 45, Jakarta"</span>
}</pre>
      <div class="ep-section-title">Contoh Response (201 Created)</div>
      <pre>{
  <span class="k">"success"</span>: <span class="n">true</span>,
  <span class="k">"message"</span>: <span class="s">"Customer berhasil ditambahkan"</span>,
  <span class="k">"customer_id"</span>: <span class="n">4</span>
}</pre>
      <button class="try-btn" onclick="toggleTry('try-cust-post')">⚡ Try it</button>
      <div class="try-panel" id="try-cust-post">
        <div class="try-url">POST ../api/customers.php</div>
        <div class="form-group"><label>Request Body (JSON):</label>
        <textarea id="body-cust-post" rows="4">{"name":"Test Customer","phone":"08111222333","address":"Jl. Test No.1, Jakarta"}</textarea></div>
        <button class="try-btn" onclick="runTry('res-cust-post','../api/customers.php','POST',document.getElementById('body-cust-post').value)">▶ Send Request</button>
        <div class="try-result" id="res-cust-post" style="display:none;"></div>
      </div>
    </div>
  </div>

  <!-- PUT -->
  <div class="ep-card" id="cust-put">
    <div class="ep-header">
      <span class="method-badge badge-put">PUT</span>
      <span class="ep-path">/api/customers.php?id={id}</span>
      <span class="ep-title">Update customer</span>
    </div>
    <div class="ep-body">
      <p>Memperbarui data customer berdasarkan ID yang diberikan pada query string.</p>
      <div class="ep-section-title">Query Parameter</div>
      <table class="param-table">
        <tr><th>Parameter</th><th>Tipe</th><th>Keterangan</th></tr>
        <tr><td>id</td><td>integer</td><td>ID customer yang diupdate <span class="required">*wajib</span></td></tr>
      </table>
      <div class="ep-section-title">Contoh Response</div>
      <pre>{ <span class="k">"success"</span>: <span class="n">true</span>, <span class="k">"message"</span>: <span class="s">"Customer berhasil diupdate"</span> }</pre>
      <button class="try-btn" onclick="toggleTry('try-cust-put')">⚡ Try it</button>
      <div class="try-panel" id="try-cust-put">
        <div class="form-group"><label>ID Customer:</label><input type="text" id="id-cust-put" value="1" style="width:100px;"></div>
        <div class="form-group"><label>Request Body:</label>
        <textarea id="body-cust-put" rows="4">{"name":"Budi Santoso Updated","phone":"081234567899","address":"Jl. Baru No. 10, Jakarta"}</textarea></div>
        <button class="try-btn" onclick="runTryId('res-cust-put','../api/customers.php','PUT','id-cust-put','body-cust-put')">▶ Send Request</button>
        <div class="try-result" id="res-cust-put" style="display:none;"></div>
      </div>
    </div>
  </div>

  <!-- DELETE -->
  <div class="ep-card" id="cust-delete">
    <div class="ep-header">
      <span class="method-badge badge-delete">DELETE</span>
      <span class="ep-path">/api/customers.php?id={id}</span>
      <span class="ep-title">Hapus customer</span>
    </div>
    <div class="ep-body">
      <p>Menghapus data customer. Data shipment terkait akan ikut terhapus (CASCADE).</p>
      <div class="ep-section-title">Contoh Response</div>
      <pre>{ <span class="k">"success"</span>: <span class="n">true</span>, <span class="k">"message"</span>: <span class="s">"Customer berhasil dihapus"</span> }</pre>
      <button class="try-btn" onclick="toggleTry('try-cust-del')">⚡ Try it</button>
      <div class="try-panel" id="try-cust-del">
        <div class="form-group"><label>ID Customer:</label><input type="text" id="id-cust-del" value="1"></div>
        <button class="try-btn" style="background:var(--red);" onclick="runTryId('res-cust-del','../api/customers.php','DELETE','id-cust-del',null)">▶ Send Request</button>
        <div class="try-result" id="res-cust-del" style="display:none;"></div>
      </div>
    </div>
  </div>

  <hr class="section-divider">

  <!-- ═══════════════════════ COURIERS ═══════════════════════ -->
  <h2 style="font-size:1.2rem;margin-bottom:1.5rem;">🚚 Couriers</h2>

  <div class="ep-card" id="courier-get">
    <div class="ep-header"><span class="method-badge badge-get">GET</span><span class="ep-path">/api/couriers.php</span><span class="ep-title">Ambil semua kurir</span></div>
    <div class="ep-body">
      <p>Mengambil seluruh data kurir.</p>
      <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
  <span class="k">"success"</span>: <span class="n">true</span>,
  <span class="k">"total"</span>: <span class="n">3</span>,
  <span class="k">"data"</span>: [{ <span class="k">"courier_id"</span>: <span class="n">1</span>, <span class="k">"courier_name"</span>: <span class="s">"Andi Wijaya"</span>, <span class="k">"vehicle_type"</span>: <span class="s">"Motor"</span> }]
}</pre>
      <button class="try-btn" onclick="toggleTry('try-courier-get')">⚡ Try it</button>
      <div class="try-panel" id="try-courier-get">
        <div class="try-url">GET ../api/couriers.php</div>
        <button class="try-btn" onclick="runTry('res-courier-get','../api/couriers.php','GET')">▶ Send Request</button>
        <div class="try-result" id="res-courier-get" style="display:none;"></div>
      </div>
    </div>
  </div>

  <div class="ep-card" id="courier-post">
    <div class="ep-header"><span class="method-badge badge-post">POST</span><span class="ep-path">/api/couriers.php</span><span class="ep-title">Tambah kurir</span></div>
    <div class="ep-body">
      <table class="param-table"><tr><th>Field</th><th>Tipe</th><th>Keterangan</th></tr>
        <tr><td>courier_name</td><td>string</td><td>Nama kurir <span class="required">*wajib</span></td></tr>
        <tr><td>vehicle_type</td><td>string</td><td>Jenis kendaraan <span class="required">*wajib</span></td></tr>
      </table>
      <button class="try-btn" onclick="toggleTry('try-courier-post')">⚡ Try it</button>
      <div class="try-panel" id="try-courier-post">
        <div class="form-group"><label>Request Body:</label>
        <textarea id="body-courier-post" rows="3">{"courier_name":"Budi Express","vehicle_type":"Truk"}</textarea></div>
        <button class="try-btn" onclick="runTry('res-courier-post','../api/couriers.php','POST',document.getElementById('body-courier-post').value)">▶ Send Request</button>
        <div class="try-result" id="res-courier-post" style="display:none;"></div>
      </div>
    </div>
  </div>

  <div class="ep-card" id="courier-put">
    <div class="ep-header"><span class="method-badge badge-put">PUT</span><span class="ep-path">/api/couriers.php?id={id}</span></div>
    <div class="ep-body">
      <button class="try-btn" onclick="toggleTry('try-courier-put')">⚡ Try it</button>
      <div class="try-panel" id="try-courier-put">
        <div class="form-group"><label>ID Kurir:</label><input type="text" id="id-courier-put" value="1" style="width:100px;"></div>
        <div class="form-group"><label>Request Body:</label>
        <textarea id="body-courier-put" rows="3">{"courier_name":"Andi Wijaya Updated","vehicle_type":"Motor Besar"}</textarea></div>
        <button class="try-btn" onclick="runTryId('res-courier-put','../api/couriers.php','PUT','id-courier-put','body-courier-put')">▶ Send Request</button>
        <div class="try-result" id="res-courier-put" style="display:none;"></div>
      </div>
    </div>
  </div>

  <div class="ep-card" id="courier-delete">
    <div class="ep-header"><span class="method-badge badge-delete">DELETE</span><span class="ep-path">/api/couriers.php?id={id}</span></div>
    <div class="ep-body">
      <button class="try-btn" onclick="toggleTry('try-courier-del')">⚡ Try it</button>
      <div class="try-panel" id="try-courier-del">
        <div class="form-group"><label>ID Kurir:</label><input type="text" id="id-courier-del" value="1"></div>
        <button class="try-btn" style="background:var(--red);" onclick="runTryId('res-courier-del','../api/couriers.php','DELETE','id-courier-del',null)">▶ Send Request</button>
        <div class="try-result" id="res-courier-del" style="display:none;"></div>
      </div>
    </div>
  </div>

  <hr class="section-divider">

  <!-- SHIPMENTS & TRACKING headers only for brevity, same pattern -->
  <h2 style="font-size:1.2rem;margin-bottom:1.5rem;">📦 Shipments</h2>
  <div class="ep-card" id="ship-get">
    <div class="ep-header"><span class="method-badge badge-get">GET</span><span class="ep-path">/api/shipments.php</span><span class="ep-title">Ambil semua pengiriman (dengan JOIN)</span></div>
    <div class="ep-body">
      <p>Data pengiriman dikembalikan beserta detail customer dan kurir (JOIN 3 tabel).</p>
      <pre><button class="copy-btn" onclick="copyCode(this)">Copy</button>{
  <span class="k">"success"</span>: <span class="n">true</span>,
  <span class="k">"data"</span>: [{
    <span class="k">"shipment_id"</span>: <span class="n">1</span>,
    <span class="k">"destination"</span>: <span class="s">"Jl. Pahlawan No.3, Bogor"</span>,
    <span class="k">"shipment_date"</span>: <span class="s">"2025-01-15"</span>,
    <span class="k">"customer_name"</span>: <span class="s">"Budi Santoso"</span>,
    <span class="k">"courier_name"</span>: <span class="s">"Andi Wijaya"</span>,
    <span class="k">"vehicle_type"</span>: <span class="s">"Motor"</span>
  }]
}</pre>
      <button class="try-btn" onclick="toggleTry('try-ship-get')">⚡ Try it</button>
      <div class="try-panel" id="try-ship-get">
        <button class="try-btn" onclick="runTry('res-ship-get','../api/shipments.php','GET')">▶ Send Request</button>
        <div class="try-result" id="res-ship-get" style="display:none;"></div>
      </div>
    </div>
  </div>
  <div class="ep-card" id="ship-post">
    <div class="ep-header"><span class="method-badge badge-post">POST</span><span class="ep-path">/api/shipments.php</span></div>
    <div class="ep-body">
      <table class="param-table"><tr><th>Field</th><th>Tipe</th><th>Ket</th></tr>
        <tr><td>customer_id</td><td>int</td><td><span class="required">*wajib</span></td></tr>
        <tr><td>courier_id</td><td>int</td><td><span class="required">*wajib</span></td></tr>
        <tr><td>destination</td><td>string</td><td><span class="required">*wajib</span></td></tr>
        <tr><td>shipment_date</td><td>date (Y-m-d)</td><td><span class="optional">opsional</span></td></tr>
      </table>
      <button class="try-btn" onclick="toggleTry('try-ship-post')">⚡ Try it</button>
      <div class="try-panel" id="try-ship-post">
        <div class="form-group"><label>Request Body:</label>
        <textarea id="body-ship-post" rows="4">{"customer_id":1,"courier_id":1,"destination":"Jl. Merdeka No. 5, Depok","shipment_date":"2025-02-01"}</textarea></div>
        <button class="try-btn" onclick="runTry('res-ship-post','../api/shipments.php','POST',document.getElementById('body-ship-post').value)">▶ Send Request</button>
        <div class="try-result" id="res-ship-post" style="display:none;"></div>
      </div>
    </div>
  </div>
  <div class="ep-card" id="ship-put">
    <div class="ep-header"><span class="method-badge badge-put">PUT</span><span class="ep-path">/api/shipments.php?id={id}</span></div>
    <div class="ep-body">
      <button class="try-btn" onclick="toggleTry('try-ship-put')">⚡ Try it</button>
      <div class="try-panel" id="try-ship-put">
        <div class="form-group"><label>ID Shipment:</label><input type="text" id="id-ship-put" value="1" style="width:100px;"></div>
        <div class="form-group"><label>Body:</label>
        <textarea id="body-ship-put" rows="4">{"customer_id":1,"courier_id":2,"destination":"Jl. Baru No. 9, Bogor","shipment_date":"2025-01-20"}</textarea></div>
        <button class="try-btn" onclick="runTryId('res-ship-put','../api/shipments.php','PUT','id-ship-put','body-ship-put')">▶ Send Request</button>
        <div class="try-result" id="res-ship-put" style="display:none;"></div>
      </div>
    </div>
  </div>
  <div class="ep-card" id="ship-delete">
    <div class="ep-header"><span class="method-badge badge-delete">DELETE</span><span class="ep-path">/api/shipments.php?id={id}</span></div>
    <div class="ep-body">
      <button class="try-btn" onclick="toggleTry('try-ship-del')">⚡ Try it</button>
      <div class="try-panel" id="try-ship-del">
        <div class="form-group"><label>ID Shipment:</label><input type="text" id="id-ship-del" value="1"></div>
        <button class="try-btn" style="background:var(--red);" onclick="runTryId('res-ship-del','../api/shipments.php','DELETE','id-ship-del',null)">▶ Send Request</button>
        <div class="try-result" id="res-ship-del" style="display:none;"></div>
      </div>
    </div>
  </div>

  <hr class="section-divider">
  <h2 style="font-size:1.2rem;margin-bottom:1.5rem;">📍 Tracking</h2>

  <div class="ep-card" id="track-get">
    <div class="ep-header"><span class="method-badge badge-get">GET</span><span class="ep-path">/api/tracking.php</span><span class="ep-title">Ambil semua tracking</span></div>
    <div class="ep-body">
      <p>Bisa filter by shipment: <code style="color:var(--accent2);">/api/tracking.php?shipment_id=1</code></p>
      <button class="try-btn" onclick="toggleTry('try-track-get')">⚡ Try it</button>
      <div class="try-panel" id="try-track-get">
        <button class="try-btn" onclick="runTry('res-track-get','../api/tracking.php','GET')">▶ Semua Tracking</button>
        <div class="try-result" id="res-track-get" style="display:none;"></div>
      </div>
    </div>
  </div>
  <div class="ep-card" id="track-post">
    <div class="ep-header"><span class="method-badge badge-post">POST</span><span class="ep-path">/api/tracking.php</span></div>
    <div class="ep-body">
      <p>Status valid: <code style="color:var(--orange);">Packed</code> · <code style="color:var(--orange);">On Delivery</code> · <code style="color:var(--orange);">Arrived at Warehouse</code> · <code style="color:var(--orange);">Delivered</code></p>
      <button class="try-btn" onclick="toggleTry('try-track-post')">⚡ Try it</button>
      <div class="try-panel" id="try-track-post">
        <div class="form-group"><label>Request Body:</label>
        <textarea id="body-track-post" rows="4">{"shipment_id":1,"status":"On Delivery","location":"Jalan Tol Jakarta-Bogor","update_time":"2025-01-15 10:00:00"}</textarea></div>
        <button class="try-btn" onclick="runTry('res-track-post','../api/tracking.php','POST',document.getElementById('body-track-post').value)">▶ Send Request</button>
        <div class="try-result" id="res-track-post" style="display:none;"></div>
      </div>
    </div>
  </div>
  <div class="ep-card" id="track-put">
    <div class="ep-header"><span class="method-badge badge-put">PUT</span><span class="ep-path">/api/tracking.php?id={id}</span></div>
    <div class="ep-body">
      <button class="try-btn" onclick="toggleTry('try-track-put')">⚡ Try it</button>
      <div class="try-panel" id="try-track-put">
        <div class="form-group"><label>ID Tracking:</label><input type="text" id="id-track-put" value="1" style="width:100px;"></div>
        <div class="form-group"><label>Body:</label>
        <textarea id="body-track-put" rows="4">{"shipment_id":1,"status":"Delivered","location":"Tujuan Akhir","update_time":"2025-01-15 16:00:00"}</textarea></div>
        <button class="try-btn" onclick="runTryId('res-track-put','../api/tracking.php','PUT','id-track-put','body-track-put')">▶ Send Request</button>
        <div class="try-result" id="res-track-put" style="display:none;"></div>
      </div>
    </div>
  </div>
  <div class="ep-card" id="track-delete">
    <div class="ep-header"><span class="method-badge badge-delete">DELETE</span><span class="ep-path">/api/tracking.php?id={id}</span></div>
    <div class="ep-body">
      <button class="try-btn" onclick="toggleTry('try-track-del')">⚡ Try it</button>
      <div class="try-panel" id="try-track-del">
        <div class="form-group"><label>ID Tracking:</label><input type="text" id="id-track-del" value="1"></div>
        <button class="try-btn" style="background:var(--red);" onclick="runTryId('res-track-del','../api/tracking.php','DELETE','id-track-del',null)">▶ Send Request</button>
        <div class="try-result" id="res-track-del" style="display:none;"></div>
      </div>
    </div>
  </div>

  <div style="height:4rem;"></div>
</main>

<script>
function scrollTo(id) {
  document.getElementById(id)?.scrollIntoView({behavior:'smooth', block:'start'});
  document.querySelectorAll('.nav-item').forEach(i=>i.classList.remove('active'));
  event.target.classList.add('active');
}

function toggleTry(id) {
  const el = document.getElementById(id);
  el.classList.toggle('open');
}

async function runTry(resultId, url, method, body=null) {
  const resultEl = document.getElementById(resultId);
  resultEl.style.display='block';
  resultEl.textContent='⏳ Mengirim request...';
  try {
    const opts = { method, headers:{'Content-Type':'application/json'} };
    if(body) opts.body = body;
    const res = await fetch(url, opts);
    const text = await res.text();
    let parsed;
    try { parsed = JSON.parse(text); resultEl.textContent = JSON.stringify(parsed,null,2); }
    catch { resultEl.textContent = text; }
    resultEl.className = 'try-result ' + (res.ok ? 'status-ok':'status-err');
  } catch(e) {
    resultEl.textContent = '❌ Error: ' + e.message;
    resultEl.className = 'try-result status-err';
  }
}

function runTryId(resultId, url, method, idElId, bodyElId) {
  const id = document.getElementById(idElId).value;
  const body = bodyElId ? document.getElementById(bodyElId).value : null;
  runTry(resultId, url + '?id=' + id, method, body);
}

function copyCode(btn) {
  const pre = btn.parentElement;
  const text = pre.innerText.replace('Copy','').trim();
  navigator.clipboard.writeText(text).then(() => {
    btn.textContent='✓ Copied!';
    setTimeout(()=>btn.textContent='Copy',2000);
  });
}
</script>
</body>
</html>
