<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PackTrack — Dashboard CRUD</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{--bg:#0a0e1a;--surface:#111827;--card:#1a2236;--border:#1e2d45;--accent:#3b82f6;--accent2:#06b6d4;--green:#10b981;--orange:#f59e0b;--red:#ef4444;--text:#e2e8f0;--muted:#64748b;--mono:'JetBrains Mono',monospace;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{background:var(--bg);color:var(--text);font-family:'Space Grotesk',sans-serif;min-height:100vh;}
  body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(59,130,246,.03)1px,transparent 1px),linear-gradient(90deg,rgba(59,130,246,.03)1px,transparent 1px);background-size:40px 40px;pointer-events:none;}

  /* NAV */
  nav{position:sticky;top:0;z-index:100;background:rgba(10,14,26,.9);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;}
  .logo{display:flex;align-items:center;gap:.6rem;font-weight:700;font-size:1.1rem;}
  .logo span{color:var(--accent);}
  .tabs{display:flex;gap:.25rem;background:var(--surface);border:1px solid var(--border);padding:.25rem;border-radius:10px;}
  .tab-btn{padding:.45rem 1rem;border-radius:7px;border:none;background:transparent;color:var(--muted);font-family:'Space Grotesk',sans-serif;font-size:.875rem;font-weight:500;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:.4rem;}
  .tab-btn.active{background:var(--accent);color:#fff;}
  .tab-btn:hover:not(.active){background:var(--card);color:var(--text);}

  /* LAYOUT */
  .container{max-width:1200px;margin:0 auto;padding:1.5rem;}
  .grid-2{display:grid;grid-template-columns:380px 1fr;gap:1.5rem;align-items:start;}

  /* CARD */
  .card{background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
  .card-header{padding:1rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
  .card-title{font-weight:600;font-size:.95rem;display:flex;align-items:center;gap:.5rem;}
  .card-body{padding:1.25rem;}

  /* FORM */
  .form-group{margin-bottom:1rem;}
  label{display:block;font-size:.8rem;font-weight:600;color:var(--muted);margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.5px;}
  input,select,textarea{width:100%;background:var(--surface);border:1px solid var(--border);color:var(--text);padding:.65rem .85rem;border-radius:8px;font-family:'Space Grotesk',sans-serif;font-size:.875rem;outline:none;transition:border-color .2s;}
  input:focus,select:focus,textarea:focus{border-color:var(--accent);}
  select option{background:var(--surface);}

  /* BUTTONS */
  .btn{display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1.2rem;border-radius:8px;border:none;font-family:'Space Grotesk',sans-serif;font-size:.875rem;font-weight:600;cursor:pointer;transition:all .2s;}
  .btn-primary{background:var(--accent);color:#fff;}
  .btn-primary:hover{background:#2563eb;transform:translateY(-1px);}
  .btn-success{background:var(--green);color:#fff;}
  .btn-success:hover{background:#059669;}
  .btn-danger{background:var(--red);color:#fff;}
  .btn-danger:hover{background:#dc2626;}
  .btn-ghost{background:transparent;color:var(--muted);border:1px solid var(--border);}
  .btn-ghost:hover{color:var(--text);border-color:var(--accent);}
  .btn-sm{padding:.35rem .7rem;font-size:.78rem;}
  .btn-full{width:100%;justify-content:center;}

  /* TABLE */
  .table-wrap{overflow-x:auto;}
  table{width:100%;border-collapse:collapse;font-size:.85rem;}
  thead tr{background:var(--surface);}
  th{text-align:left;padding:.7rem .85rem;color:var(--muted);font-weight:600;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap;}
  td{padding:.7rem .85rem;border-bottom:1px solid rgba(30,45,69,.5);vertical-align:middle;}
  tr:last-child td{border-bottom:none;}
  tr:hover td{background:rgba(59,130,246,.04);}
  .actions{display:flex;gap:.4rem;}

  /* STATUS BADGE */
  .status{display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .7rem;border-radius:100px;font-size:.75rem;font-weight:600;white-space:nowrap;}
  .status-packed{background:rgba(100,116,139,.2);color:#94a3b8;}
  .status-delivery{background:rgba(245,158,11,.15);color:var(--orange);}
  .status-warehouse{background:rgba(59,130,246,.15);color:var(--accent);}
  .status-delivered{background:rgba(16,185,129,.15);color:var(--green);}

  /* TOAST */
  #toast{position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;}
  .toast-item{background:var(--card);border:1px solid var(--border);border-radius:10px;padding:.75rem 1.25rem;font-size:.875rem;animation:slideIn .3s ease;max-width:320px;display:flex;align-items:center;gap:.5rem;}
  .toast-item.success{border-color:var(--green);color:var(--green);}
  .toast-item.error{border-color:var(--red);color:var(--red);}
  @keyframes slideIn{from{transform:translateX(100px);opacity:0;}to{transform:translateX(0);opacity:1;}}

  /* LOADER */
  .loader{text-align:center;padding:3rem;color:var(--muted);}
  .empty{text-align:center;padding:2.5rem;color:var(--muted);}

  /* MODAL */
  .modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:200;display:flex;align-items:center;justify-content:center;display:none;}
  .modal-bg.open{display:flex;}
  .modal{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:1.75rem;width:90%;max-width:480px;animation:modalIn .2s ease;}
  @keyframes modalIn{from{transform:scale(.95);opacity:0;}to{transform:scale(1);opacity:1;}}
  .modal-title{font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;}

  .tab-content{display:none;}
  .tab-content.active{display:block;}

  /* STATS */
  .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;}
  .stat{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;display:flex;align-items:center;gap:.75rem;}
  .stat-icon{font-size:1.5rem;}
  .stat-num{font-size:1.5rem;font-weight:700;color:var(--accent);}
  .stat-label{color:var(--muted);font-size:.8rem;}

  @media(max-width:900px){.grid-2{grid-template-columns:1fr;}.stats-row{grid-template-columns:repeat(2,1fr);}.tabs{flex-wrap:wrap;}}
  @media(max-width:600px){.tab-btn span{display:none;}}
</style>
</head>
<body>

<nav>
  <div class="logo">📦 Pack<span>Track</span> <span style="color:var(--muted);font-weight:400;font-size:.85rem;">Dashboard</span></div>
  <div class="tabs">
    <button class="tab-btn active" onclick="switchTab('customers')">👤 <span>Customers</span></button>
    <button class="tab-btn" onclick="switchTab('couriers')">🚚 <span>Couriers</span></button>
    <button class="tab-btn" onclick="switchTab('shipments')">📦 <span>Shipments</span></button>
    <button class="tab-btn" onclick="switchTab('tracking')">📍 <span>Tracking</span></button>
  </div>
  <a href="../docs/index.php" style="color:var(--muted);text-decoration:none;font-size:.85rem;display:flex;align-items:center;gap:.35rem;">📖 Docs</a>
</nav>

<div class="container">
  <!-- STATS -->
  <div class="stats-row" id="stats-row">
    <div class="stat"><div class="stat-icon">👤</div><div><div class="stat-num" id="stat-customers">-</div><div class="stat-label">Customers</div></div></div>
    <div class="stat"><div class="stat-icon">🚚</div><div><div class="stat-num" id="stat-couriers">-</div><div class="stat-label">Couriers</div></div></div>
    <div class="stat"><div class="stat-icon">📦</div><div><div class="stat-num" id="stat-shipments">-</div><div class="stat-label">Shipments</div></div></div>
    <div class="stat"><div class="stat-icon">📍</div><div><div class="stat-num" id="stat-tracking">-</div><div class="stat-label">Tracking Events</div></div></div>
  </div>

  <!-- ═══════════════════ CUSTOMERS TAB ═══════════════════ -->
  <div class="tab-content active" id="tab-customers">
    <div class="grid-2">
      <div class="card">
        <div class="card-header">
          <div class="card-title" id="form-cust-title">➕ Tambah Customer</div>
          <button class="btn btn-ghost btn-sm" onclick="resetForm('customers')" id="btn-cancel-cust" style="display:none">✕ Batal</button>
        </div>
        <div class="card-body">
          <input type="hidden" id="cust-id">
          <div class="form-group"><label>Nama Lengkap</label><input type="text" id="cust-name" placeholder="Contoh: Budi Santoso"></div>
          <div class="form-group"><label>Nomor HP</label><input type="text" id="cust-phone" placeholder="08xxxxxxxxxx"></div>
          <div class="form-group"><label>Alamat</label><textarea id="cust-address" rows="3" placeholder="Jl. Merdeka No. 10, Jakarta..."></textarea></div>
          <button class="btn btn-primary btn-full" onclick="saveCust()">💾 Simpan Customer</button>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <div class="card-title">📋 Daftar Customer</div>
          <button class="btn btn-ghost btn-sm" onclick="loadCustomers()">🔄 Refresh</button>
        </div>
        <div class="table-wrap">
          <table><thead><tr><th>ID</th><th>Nama</th><th>HP</th><th>Alamat</th><th>Aksi</th></tr></thead>
          <tbody id="tbl-customers"><tr><td colspan="5" class="loader">⏳ Memuat data...</td></tr></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══════════════════ COURIERS TAB ═══════════════════ -->
  <div class="tab-content" id="tab-couriers">
    <div class="grid-2">
      <div class="card">
        <div class="card-header">
          <div class="card-title" id="form-cour-title">➕ Tambah Kurir</div>
          <button class="btn btn-ghost btn-sm" onclick="resetForm('couriers')" id="btn-cancel-cour" style="display:none">✕ Batal</button>
        </div>
        <div class="card-body">
          <input type="hidden" id="cour-id">
          <div class="form-group"><label>Nama Kurir</label><input type="text" id="cour-name" placeholder="Nama lengkap kurir"></div>
          <div class="form-group"><label>Jenis Kendaraan</label>
            <select id="cour-vehicle">
              <option value="">-- Pilih Kendaraan --</option>
              <option>Motor</option><option>Mobil Van</option><option>Truk</option><option>Sepeda Motor</option>
            </select>
          </div>
          <button class="btn btn-success btn-full" onclick="saveCour()">💾 Simpan Kurir</button>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><div class="card-title">🚚 Daftar Kurir</div><button class="btn btn-ghost btn-sm" onclick="loadCouriers()">🔄</button></div>
        <div class="table-wrap">
          <table><thead><tr><th>ID</th><th>Nama</th><th>Kendaraan</th><th>Aksi</th></tr></thead>
          <tbody id="tbl-couriers"><tr><td colspan="4" class="loader">⏳ Memuat data...</td></tr></tbody></table>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══════════════════ SHIPMENTS TAB ═══════════════════ -->
  <div class="tab-content" id="tab-shipments">
    <div class="grid-2">
      <div class="card">
        <div class="card-header">
          <div class="card-title" id="form-ship-title">➕ Buat Pengiriman</div>
          <button class="btn btn-ghost btn-sm" onclick="resetForm('shipments')" id="btn-cancel-ship" style="display:none">✕ Batal</button>
        </div>
        <div class="card-body">
          <input type="hidden" id="ship-id">
          <div class="form-group"><label>Customer</label><select id="ship-customer"><option value="">-- Pilih Customer --</option></select></div>
          <div class="form-group"><label>Kurir</label><select id="ship-courier"><option value="">-- Pilih Kurir --</option></select></div>
          <div class="form-group"><label>Tujuan</label><input type="text" id="ship-dest" placeholder="Alamat tujuan pengiriman"></div>
          <div class="form-group"><label>Tanggal Kirim</label><input type="date" id="ship-date"></div>
          <button class="btn btn-primary btn-full" onclick="saveShip()">💾 Simpan Pengiriman</button>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><div class="card-title">📦 Daftar Pengiriman</div><button class="btn btn-ghost btn-sm" onclick="loadShipments()">🔄</button></div>
        <div class="table-wrap">
          <table><thead><tr><th>ID</th><th>Customer</th><th>Kurir</th><th>Tujuan</th><th>Tanggal</th><th>Aksi</th></tr></thead>
          <tbody id="tbl-shipments"><tr><td colspan="6" class="loader">⏳ Memuat data...</td></tr></tbody></table>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══════════════════ TRACKING TAB ═══════════════════ -->
  <div class="tab-content" id="tab-tracking">
    <div class="grid-2">
      <div class="card">
        <div class="card-header">
          <div class="card-title" id="form-track-title">➕ Tambah Tracking</div>
          <button class="btn btn-ghost btn-sm" onclick="resetForm('tracking')" id="btn-cancel-track" style="display:none">✕ Batal</button>
        </div>
        <div class="card-body">
          <input type="hidden" id="track-id">
          <div class="form-group"><label>Shipment</label><select id="track-ship"><option value="">-- Pilih Pengiriman --</option></select></div>
          <div class="form-group"><label>Status</label>
            <select id="track-status">
              <option value="">-- Pilih Status --</option>
              <option>Packed</option><option>On Delivery</option><option>Arrived at Warehouse</option><option>Delivered</option>
            </select>
          </div>
          <div class="form-group"><label>Lokasi Saat Ini</label><input type="text" id="track-location" placeholder="Gudang / Jalan / Kota..."></div>
          <div class="form-group"><label>Waktu Update</label><input type="datetime-local" id="track-time"></div>
          <button class="btn btn-primary btn-full" onclick="saveTrack()">💾 Simpan Tracking</button>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><div class="card-title">📍 Riwayat Tracking</div><button class="btn btn-ghost btn-sm" onclick="loadTracking()">🔄</button></div>
        <div class="table-wrap">
          <table><thead><tr><th>ID</th><th>Shipment</th><th>Customer</th><th>Status</th><th>Lokasi</th><th>Waktu</th><th>Aksi</th></tr></thead>
          <tbody id="tbl-tracking"><tr><td colspan="7" class="loader">⏳ Memuat data...</td></tr></tbody></table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast"></div>

<!-- CONFIRM MODAL -->
<div class="modal-bg" id="confirm-modal">
  <div class="modal">
    <div class="modal-title">⚠️ Konfirmasi Hapus</div>
    <p style="color:var(--muted);margin-bottom:1.5rem;" id="confirm-msg">Apakah Anda yakin ingin menghapus data ini?</p>
    <div style="display:flex;gap:.75rem;justify-content:flex-end;">
      <button class="btn btn-ghost" onclick="closeConfirm()">Batal</button>
      <button class="btn btn-danger" id="confirm-ok">🗑️ Hapus</button>
    </div>
  </div>
</div>

<script>
const API = '../api';
let confirmCallback = null;

// ─── TOAST ───────────────────────────────────────────────
function toast(msg, type='success') {
  const t = document.createElement('div');
  t.className = `toast-item ${type}`;
  t.innerHTML = (type==='success'?'✅':'❌') + ' ' + msg;
  document.getElementById('toast').appendChild(t);
  setTimeout(()=>t.remove(), 3500);
}

// ─── API HELPER ──────────────────────────────────────────
async function api(url, method='GET', body=null) {
  const opts = { method, headers:{'Content-Type':'application/json'} };
  if(body) opts.body = JSON.stringify(body);
  const res = await fetch(url, opts);
  return res.json();
}

// ─── TABS ─────────────────────────────────────────────────
function switchTab(name) {
  document.querySelectorAll('.tab-content').forEach(t=>t.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  document.getElementById('tab-'+name).classList.add('active');
  event.currentTarget.classList.add('active');
  if(name==='shipments'){loadCustomers(true);loadCouriers(true);}
  if(name==='tracking'){loadShipments(true);}
}

// ─── CONFIRM MODAL ───────────────────────────────────────
function confirm(msg, cb) {
  document.getElementById('confirm-msg').textContent = msg;
  document.getElementById('confirm-modal').classList.add('open');
  document.getElementById('confirm-ok').onclick = ()=>{closeConfirm();cb();};
}
function closeConfirm(){document.getElementById('confirm-modal').classList.remove('open');}

// ─── STATUS BADGE ─────────────────────────────────────────
function statusBadge(s) {
  const map = {'Packed':'packed','On Delivery':'delivery','Arrived at Warehouse':'warehouse','Delivered':'delivered'};
  const cls = map[s]||'packed';
  const icons = {'Packed':'📦','On Delivery':'🚚','Arrived at Warehouse':'🏭','Delivered':'✅'};
  return `<span class="status status-${cls}">${icons[s]||''} ${s}</span>`;
}

// ─── STATS ───────────────────────────────────────────────
async function loadStats(){
  const [c,cr,s,t] = await Promise.all([
    api(`${API}/customers.php`), api(`${API}/couriers.php`),
    api(`${API}/shipments.php`), api(`${API}/tracking.php`)
  ]);
  document.getElementById('stat-customers').textContent = c.total||0;
  document.getElementById('stat-couriers').textContent  = cr.total||0;
  document.getElementById('stat-shipments').textContent = s.total||0;
  document.getElementById('stat-tracking').textContent  = t.total||0;
}

// ════════════════ CUSTOMERS ════════════════════════════════
async function loadCustomers(selectOnly=false) {
  const r = await api(`${API}/customers.php`);
  const rows = r.data||[];
  // populate select
  ['ship-customer'].forEach(id=>{
    const el = document.getElementById(id);
    if(!el) return;
    const cur = el.value;
    el.innerHTML = '<option value="">-- Pilih Customer --</option>';
    rows.forEach(c=>el.innerHTML+=`<option value="${c.customer_id}">${c.name}</option>`);
    if(cur) el.value=cur;
  });
  if(selectOnly) return;
  const tbody = document.getElementById('tbl-customers');
  if(!rows.length){tbody.innerHTML='<tr><td colspan="5" class="empty">Belum ada data customer.</td></tr>';return;}
  tbody.innerHTML = rows.map(c=>`
    <tr>
      <td><code style="color:var(--muted);font-family:var(--mono);font-size:.78rem;">#${c.customer_id}</code></td>
      <td><strong>${c.name}</strong></td>
      <td>${c.phone}</td>
      <td style="max-width:200px;color:var(--muted);font-size:.82rem;">${c.address}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-sm" onclick='editCust(${JSON.stringify(c)})'>✏️</button>
        <button class="btn btn-danger btn-sm" onclick='delCust(${c.customer_id},"${c.name}")'>🗑️</button>
      </div></td>
    </tr>`).join('');
}

async function saveCust(){
  const id=document.getElementById('cust-id').value;
  const body={name:document.getElementById('cust-name').value,phone:document.getElementById('cust-phone').value,address:document.getElementById('cust-address').value};
  if(!body.name||!body.phone||!body.address){toast('Semua field wajib diisi!','error');return;}
  const url=id?`${API}/customers.php?id=${id}`:`${API}/customers.php`;
  const r=await api(url,id?'PUT':'POST',body);
  if(r.success){toast(r.message);resetForm('customers');loadCustomers();loadStats();}
  else toast(r.message,'error');
}

function editCust(c){
  document.getElementById('cust-id').value=c.customer_id;
  document.getElementById('cust-name').value=c.name;
  document.getElementById('cust-phone').value=c.phone;
  document.getElementById('cust-address').value=c.address;
  document.getElementById('form-cust-title').textContent='✏️ Edit Customer';
  document.getElementById('btn-cancel-cust').style.display='';
  document.getElementById('cust-name').focus();
}

function delCust(id,name){confirm(`Hapus customer "${name}"? Data terkait akan ikut terhapus.`,async()=>{
  const r=await api(`${API}/customers.php?id=${id}`,'DELETE');
  if(r.success){toast(r.message);loadCustomers();loadStats();}else toast(r.message,'error');
});}

function resetForm(tab){
  if(tab==='customers'){
    ['cust-id','cust-name','cust-phone','cust-address'].forEach(i=>document.getElementById(i).value='');
    document.getElementById('form-cust-title').textContent='➕ Tambah Customer';
    document.getElementById('btn-cancel-cust').style.display='none';
  }
  if(tab==='couriers'){
    ['cour-id','cour-name'].forEach(i=>document.getElementById(i).value='');
    document.getElementById('cour-vehicle').value='';
    document.getElementById('form-cour-title').textContent='➕ Tambah Kurir';
    document.getElementById('btn-cancel-cour').style.display='none';
  }
  if(tab==='shipments'){
    ['ship-id','ship-dest'].forEach(i=>document.getElementById(i).value='');
    document.getElementById('ship-customer').value='';document.getElementById('ship-courier').value='';document.getElementById('ship-date').value='';
    document.getElementById('form-ship-title').textContent='➕ Buat Pengiriman';
    document.getElementById('btn-cancel-ship').style.display='none';
  }
  if(tab==='tracking'){
    ['track-id','track-location'].forEach(i=>document.getElementById(i).value='');
    document.getElementById('track-ship').value='';document.getElementById('track-status').value='';document.getElementById('track-time').value='';
    document.getElementById('form-track-title').textContent='➕ Tambah Tracking';
    document.getElementById('btn-cancel-track').style.display='none';
  }
}

// ════════════════ COURIERS ════════════════════════════════
async function loadCouriers(selectOnly=false){
  const r=await api(`${API}/couriers.php`);
  const rows=r.data||[];
  ['ship-courier'].forEach(id=>{
    const el=document.getElementById(id);if(!el)return;
    const cur=el.value;
    el.innerHTML='<option value="">-- Pilih Kurir --</option>';
    rows.forEach(c=>el.innerHTML+=`<option value="${c.courier_id}">${c.courier_name} (${c.vehicle_type})</option>`);
    if(cur)el.value=cur;
  });
  if(selectOnly)return;
  const tbody=document.getElementById('tbl-couriers');
  if(!rows.length){tbody.innerHTML='<tr><td colspan="4" class="empty">Belum ada data kurir.</td></tr>';return;}
  tbody.innerHTML=rows.map(c=>`
    <tr>
      <td><code style="color:var(--muted);font-family:var(--mono);font-size:.78rem;">#${c.courier_id}</code></td>
      <td><strong>${c.courier_name}</strong></td>
      <td><span style="background:var(--surface);border:1px solid var(--border);padding:.2rem .6rem;border-radius:6px;font-size:.78rem;">${c.vehicle_type}</span></td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-sm" onclick='editCour(${JSON.stringify(c)})'>✏️</button>
        <button class="btn btn-danger btn-sm" onclick='delCour(${c.courier_id},"${c.courier_name}")'>🗑️</button>
      </div></td>
    </tr>`).join('');
}

async function saveCour(){
  const id=document.getElementById('cour-id').value;
  const body={courier_name:document.getElementById('cour-name').value,vehicle_type:document.getElementById('cour-vehicle').value};
  if(!body.courier_name||!body.vehicle_type){toast('Semua field wajib diisi!','error');return;}
  const r=await api(id?`${API}/couriers.php?id=${id}`:`${API}/couriers.php`,id?'PUT':'POST',body);
  if(r.success){toast(r.message);resetForm('couriers');loadCouriers();loadStats();}else toast(r.message,'error');
}

function editCour(c){
  document.getElementById('cour-id').value=c.courier_id;
  document.getElementById('cour-name').value=c.courier_name;
  document.getElementById('cour-vehicle').value=c.vehicle_type;
  document.getElementById('form-cour-title').textContent='✏️ Edit Kurir';
  document.getElementById('btn-cancel-cour').style.display='';
}

function delCour(id,name){confirm(`Hapus kurir "${name}"?`,async()=>{
  const r=await api(`${API}/couriers.php?id=${id}`,'DELETE');
  if(r.success){toast(r.message);loadCouriers();loadStats();}else toast(r.message,'error');
});}

// ════════════════ SHIPMENTS ════════════════════════════════
async function loadShipments(selectOnly=false){
  const r=await api(`${API}/shipments.php`);
  const rows=r.data||[];
  ['track-ship'].forEach(id=>{
    const el=document.getElementById(id);if(!el)return;
    const cur=el.value;
    el.innerHTML='<option value="">-- Pilih Pengiriman --</option>';
    rows.forEach(s=>el.innerHTML+=`<option value="${s.shipment_id}">#${s.shipment_id} — ${s.customer_name} → ${s.destination.substring(0,30)}</option>`);
    if(cur)el.value=cur;
  });
  if(selectOnly)return;
  const tbody=document.getElementById('tbl-shipments');
  if(!rows.length){tbody.innerHTML='<tr><td colspan="6" class="empty">Belum ada data pengiriman.</td></tr>';return;}
  tbody.innerHTML=rows.map(s=>`
    <tr>
      <td><code style="color:var(--muted);font-family:var(--mono);font-size:.78rem;">#${s.shipment_id}</code></td>
      <td>${s.customer_name||'-'}</td>
      <td>${s.courier_name||'-'}</td>
      <td style="max-width:160px;font-size:.82rem;color:var(--muted);">${s.destination}</td>
      <td style="font-family:var(--mono);font-size:.78rem;">${s.shipment_date}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-sm" onclick='editShip(${JSON.stringify(s)})'>✏️</button>
        <button class="btn btn-danger btn-sm" onclick='delShip(${s.shipment_id})'>🗑️</button>
      </div></td>
    </tr>`).join('');
}

async function saveShip(){
  const id=document.getElementById('ship-id').value;
  const body={customer_id:parseInt(document.getElementById('ship-customer').value),courier_id:parseInt(document.getElementById('ship-courier').value),destination:document.getElementById('ship-dest').value,shipment_date:document.getElementById('ship-date').value};
  if(!body.customer_id||!body.courier_id||!body.destination){toast('Semua field wajib diisi!','error');return;}
  const r=await api(id?`${API}/shipments.php?id=${id}`:`${API}/shipments.php`,id?'PUT':'POST',body);
  if(r.success){toast(r.message);resetForm('shipments');loadShipments();loadStats();}else toast(r.message,'error');
}

function editShip(s){
  document.getElementById('ship-id').value=s.shipment_id;
  document.getElementById('ship-customer').value=s.customer_id;
  document.getElementById('ship-courier').value=s.courier_id;
  document.getElementById('ship-dest').value=s.destination;
  document.getElementById('ship-date').value=s.shipment_date;
  document.getElementById('form-ship-title').textContent='✏️ Edit Pengiriman';
  document.getElementById('btn-cancel-ship').style.display='';
}

function delShip(id){confirm(`Hapus pengiriman #${id}? Data tracking terkait ikut terhapus.`,async()=>{
  const r=await api(`${API}/shipments.php?id=${id}`,'DELETE');
  if(r.success){toast(r.message);loadShipments();loadStats();}else toast(r.message,'error');
});}

// ════════════════ TRACKING ════════════════════════════════
async function loadTracking(){
  const r=await api(`${API}/tracking.php`);
  const rows=r.data||[];
  const tbody=document.getElementById('tbl-tracking');
  if(!rows.length){tbody.innerHTML='<tr><td colspan="7" class="empty">Belum ada data tracking.</td></tr>';return;}
  tbody.innerHTML=rows.map(t=>`
    <tr>
      <td><code style="color:var(--muted);font-family:var(--mono);font-size:.78rem;">#${t.tracking_id}</code></td>
      <td><code style="color:var(--accent2);font-family:var(--mono);font-size:.78rem;">#${t.shipment_id}</code></td>
      <td>${t.customer_name||'-'}</td>
      <td>${statusBadge(t.status)}</td>
      <td style="font-size:.82rem;">${t.location}</td>
      <td style="font-family:var(--mono);font-size:.75rem;color:var(--muted);">${t.update_time}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-sm" onclick='editTrack(${JSON.stringify(t)})'>✏️</button>
        <button class="btn btn-danger btn-sm" onclick='delTrack(${t.tracking_id})'>🗑️</button>
      </div></td>
    </tr>`).join('');
}

async function saveTrack(){
  const id=document.getElementById('track-id').value;
  const body={shipment_id:parseInt(document.getElementById('track-ship').value),status:document.getElementById('track-status').value,location:document.getElementById('track-location').value,update_time:document.getElementById('track-time').value.replace('T',' ')||new Date().toISOString().slice(0,19).replace('T',' ')};
  if(!body.shipment_id||!body.status||!body.location){toast('Semua field wajib diisi!','error');return;}
  const r=await api(id?`${API}/tracking.php?id=${id}`:`${API}/tracking.php`,id?'PUT':'POST',body);
  if(r.success){toast(r.message);resetForm('tracking');loadTracking();loadStats();}else toast(r.message,'error');
}

function editTrack(t){
  document.getElementById('track-id').value=t.tracking_id;
  document.getElementById('track-ship').value=t.shipment_id;
  document.getElementById('track-status').value=t.status;
  document.getElementById('track-location').value=t.location;
  document.getElementById('track-time').value=t.update_time.replace(' ','T');
  document.getElementById('form-track-title').textContent='✏️ Edit Tracking';
  document.getElementById('btn-cancel-track').style.display='';
}

function delTrack(id){confirm(`Hapus tracking #${id}?`,async()=>{
  const r=await api(`${API}/tracking.php?id=${id}`,'DELETE');
  if(r.success){toast(r.message);loadTracking();loadStats();}else toast(r.message,'error');
});}

// ─── INIT ─────────────────────────────────────────────────
document.getElementById('ship-date').value=new Date().toISOString().split('T')[0];
loadStats();loadCustomers();loadCouriers();loadShipments();loadTracking();
</script>
</body>
</html>
