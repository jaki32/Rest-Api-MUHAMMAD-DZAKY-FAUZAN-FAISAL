# 📦 TrackAPI — Sistem Tracking Paket Pengiriman
## REST API dengan PHP Native + MySQL

---

## 📁 Struktur Project

```
project-api/
│
├── config/
│   ├── database.php      ← Konfigurasi koneksi MySQL
│   └── helpers.php       ← Helper functions (CORS, response, validasi)
│
├── api/
│   ├── customers.php     ← Endpoint: GET/POST/PUT/DELETE customers
│   ├── couriers.php      ← Endpoint: GET/POST/PUT/DELETE couriers
│   ├── shipments.php     ← Endpoint: GET/POST/PUT/DELETE shipments
│   └── tracking.php      ← Endpoint: GET/POST/PUT/DELETE tracking
│
├── docs/
│   └── index.php         ← Landing page dokumentasi API (Try It live)
│
├── dashboard/
│   └── index.php         ← Halaman CRUD Dashboard
│
├── index.php             ← Landing page utama
└── database.sql          ← DDL + data dummy untuk MySQL
```

---

## 🗄️ ERD Sederhana

```
customers          couriers
──────────         ──────────
customer_id (PK)   courier_id (PK)
name               courier_name
phone              vehicle_type
address            created_at
created_at
     │                   │
     └──────┬────────────┘
            │
        shipments
        ──────────────────
        shipment_id (PK)
        customer_id (FK) → customers
        courier_id  (FK) → couriers
        destination
        shipment_date
        created_at
            │
            │
         tracking
         ──────────────────
         tracking_id (PK)
         shipment_id (FK) → shipments
         status  [Packed | On Delivery | Arrived at Warehouse | Delivered]
         location
         update_time
```

**Relasi:**
- `shipments.customer_id` → `customers.customer_id` (Many-to-One)
- `shipments.courier_id`  → `couriers.courier_id`   (Many-to-One)
- `tracking.shipment_id`  → `shipments.shipment_id`  (Many-to-One)

---

## 🖥️ Cara Menjalankan di Localhost (XAMPP)

### Langkah 1 — Persiapan XAMPP
1. Download dan install **XAMPP** dari https://www.apachefriends.org
2. Buka **XAMPP Control Panel**
3. Start modul **Apache** dan **MySQL**

### Langkah 2 — Letakkan Project
1. Copy folder `project-api` ke dalam:
   ```
   C:\xampp\htdocs\project-api\
   ```
   (Mac/Linux: `/Applications/XAMPP/htdocs/project-api/`)

### Langkah 3 — Buat Database
1. Buka browser → akses `http://localhost/phpmyadmin`
2. Klik **New** di panel kiri
3. Nama database: `tracking_paket` → klik **Create**
4. Klik tab **SQL** → paste isi file `database.sql` → klik **Go**
5. Database + tabel + data dummy berhasil dibuat ✅

### Langkah 4 — Konfigurasi Database
Buka file `config/database.php` dan sesuaikan:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Username XAMPP default
define('DB_PASS', '');          // Password XAMPP default (kosong)
define('DB_NAME', 'tracking_paket');
```

### Langkah 5 — Akses Aplikasi
| Halaman          | URL                                          |
|------------------|----------------------------------------------|
| Landing Page     | http://localhost/project-api/                |
| Dokumentasi API  | http://localhost/project-api/docs/           |
| Dashboard CRUD   | http://localhost/project-api/dashboard/      |
| API Customers    | http://localhost/project-api/api/customers.php |
| API Couriers     | http://localhost/project-api/api/couriers.php  |
| API Shipments    | http://localhost/project-api/api/shipments.php |
| API Tracking     | http://localhost/project-api/api/tracking.php  |

---

## 🌐 Cara Deploy ke InfinityFree

### Persiapan
- Daftar akun gratis di https://infinityfree.com
- Login → klik **+ Create Account** → isi subdomain (misal: `trackapi`)
- Catat info FTP dan cPanel yang diberikan

### Langkah 1 — Buat Database di InfinityFree
1. Masuk ke **cPanel** InfinityFree (biasanya via tombol di dashboard)
2. Cari menu **MySQL Databases**
3. Buat database baru → catat nama database (format: `epiz_XXXXXXXX_tracking`)
4. Buat user MySQL → assign ke database → catat username & password
5. Klik **phpMyAdmin** → pilih database → tab SQL → paste isi `database.sql` → Go

### Langkah 2 — Update Konfigurasi Database
Buka `config/database.php` dan ubah sesuai data InfinityFree:
```php
define('DB_HOST', 'sql300.infinityfree.com');  // cek di cPanel
define('DB_USER', 'epiz_XXXXXXXX_user');        // username yg dibuat
define('DB_PASS', 'passwordmu');                 // password yg dibuat
define('DB_NAME', 'epiz_XXXXXXXX_tracking');    // nama database
```

> ⚠️ **Penting:** Nama DB, user, host akan berbeda tiap akun.
> Cek di cPanel → MySQL Databases untuk nilai yang tepat.

### Langkah 3 — Upload File via FTP

**Opsi A — FileZilla (Direkomendasikan):**
1. Download FileZilla dari https://filezilla-project.org
2. Buka FileZilla → masukkan data FTP dari InfinityFree:
   - Host: `ftpupload.net`
   - Username: (username InfinityFree)
   - Password: (password InfinityFree)
   - Port: `21`
3. Klik **Quickconnect**
4. Navigasi ke folder `htdocs` di panel kanan
5. Drag & drop seluruh folder `project-api` ke `htdocs`

**Opsi B — File Manager cPanel:**
1. Masuk cPanel → **File Manager**
2. Buka folder `htdocs`
3. Upload file ZIP project → klik kanan → Extract

### Langkah 4 — Akses Website
```
https://trackapi.infinityfreeapp.com/
https://trackapi.infinityfreeapp.com/docs/
https://trackapi.infinityfreeapp.com/dashboard/
https://trackapi.infinityfreeapp.com/api/customers.php
```

### ⚠️ Tips Penting InfinityFree
- Jangan gunakan `.htaccess` yang kompleks
- File PHP maksimal 2MB
- Hindari fungsi `exec()`, `shell_exec()` (tidak didukung)
- MySQL host **bukan** `localhost` di hosting, gunakan host dari cPanel
- Jika ada error 500, cek error log di cPanel → **Error Logs**

---

## 📋 Contoh Request & Response JSON

### GET All Customers
```
GET http://localhost/project-api/api/customers.php
```
```json
{
  "success": true,
  "total": 3,
  "data": [
    {
      "customer_id": 1,
      "name": "Budi Santoso",
      "phone": "081234567890",
      "address": "Jl. Merdeka No. 10, Jakarta",
      "created_at": "2025-01-10 08:00:00"
    }
  ]
}
```

### POST Add Customer
```
POST http://localhost/project-api/api/customers.php
Content-Type: application/json

{
  "name": "Dewi Lestari",
  "phone": "089876543210",
  "address": "Jl. Kenanga No. 7, Semarang"
}
```
```json
{
  "success": true,
  "message": "Customer berhasil ditambahkan.",
  "customer_id": 4
}
```

### PUT Update Customer
```
PUT http://localhost/project-api/api/customers.php?id=1
Content-Type: application/json

{
  "name": "Budi Santoso Updated",
  "phone": "081234567899",
  "address": "Jl. Merdeka No. 15, Jakarta"
}
```
```json
{
  "success": true,
  "message": "Customer berhasil diperbarui."
}
```

### DELETE Customer
```
DELETE http://localhost/project-api/api/customers.php?id=1
```
```json
{
  "success": true,
  "message": "Customer berhasil dihapus."
}
```

### POST Add Tracking
```
POST http://localhost/project-api/api/tracking.php
Content-Type: application/json

{
  "shipment_id": 1,
  "status": "On Delivery",
  "location": "Tol Jakarta-Cikampek KM 30"
}
```
```json
{
  "success": true,
  "message": "Tracking berhasil ditambahkan.",
  "tracking_id": 5
}
```

---

## 🛠️ Testing API dengan Postman / Thunder Client
1. Install **Postman** (https://www.postman.com) atau extension **Thunder Client** di VS Code
2. Import collection baru → tambahkan request sesuai endpoint di atas
3. Untuk POST/PUT: pilih tab **Body** → **raw** → pilih **JSON**

---

## 📚 Teknologi yang Digunakan
| Teknologi    | Kegunaan                              |
|-------------|---------------------------------------|
| PHP Native   | Backend API                           |
| MySQL        | Database relasional                   |
| HTML + CSS   | Frontend (tanpa framework JS)         |
| Fetch API    | Komunikasi frontend ↔ backend         |
| Google Fonts | Typography (Space Grotesk + JetBrains Mono) |
