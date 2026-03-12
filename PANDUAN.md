# 📦 PackTrack API — Panduan Lengkap

## Struktur Folder Project
```
project-api/
│
├── config/
│   └── database.php        ← Konfigurasi koneksi database
│
├── api/
│   ├── customers.php       ← CRUD endpoint customers
│   ├── couriers.php        ← CRUD endpoint couriers
│   ├── shipments.php       ← CRUD endpoint shipments
│   └── tracking.php        ← CRUD endpoint tracking
│
├── docs/
│   └── index.php           ← Halaman dokumentasi API interaktif
│
├── dashboard/
│   └── index.php           ← Dashboard CRUD berbasis web
│
├── index.php               ← Landing page utama
├── database.sql            ← DDL + sample data
└── PANDUAN.md              ← File ini
```

---

## ERD (Entity Relationship Diagram)

```
[customers] ─────────────────────────────────────────────────────────────────────┐
  customer_id (PK)                                                                │
  name                                                                            │
  phone                                                                           │
  address                                                                         │
       │                                                                          │
       │ 1                                                                        │
       ▼ N                                                                        │
[shipments]              [couriers]                                               │
  shipment_id (PK)  N←── courier_id (PK)                                        │
  customer_id (FK) ──────────────────────────────────────────────────────────────┘
  courier_id  (FK)       courier_name
  destination            vehicle_type
  shipment_date
       │
       │ 1
       ▼ N
[tracking]
  tracking_id (PK)
  shipment_id (FK)
  status
  location
  update_time
```

**Relasi:**
- `customers` → `shipments` : One-to-Many (1 customer bisa punya banyak shipment)
- `couriers`  → `shipments` : One-to-Many (1 kurir bisa handle banyak shipment)
- `shipments` → `tracking`  : One-to-Many (1 shipment punya banyak event tracking)

---

## Cara Menjalankan di Localhost (XAMPP)

### Langkah 1: Siapkan XAMPP
1. Download & install XAMPP dari https://www.apachefriends.org
2. Jalankan Apache dan MySQL dari XAMPP Control Panel

### Langkah 2: Copy Project
1. Copy folder `project-api` ke `C:\xampp\htdocs\` (Windows)
   atau `/opt/lampp/htdocs/` (Linux/Mac)

### Langkah 3: Buat Database
1. Buka browser → http://localhost/phpmyadmin
2. Klik "New" → buat database baru → isi nama: `tracking_paket` → klik "Create"
3. Klik tab "Import" → pilih file `database.sql` → klik "Go"

### Langkah 4: Konfigurasi Database
Buka `config/database.php`, pastikan setting berikut benar:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // username XAMPP default
define('DB_PASS', '');         // password XAMPP default (kosong)
define('DB_NAME', 'tracking_paket');
```

### Langkah 5: Buka di Browser
- Landing Page : http://localhost/project-api/
- Dokumentasi  : http://localhost/project-api/docs/
- Dashboard    : http://localhost/project-api/dashboard/
- API Test     : http://localhost/project-api/api/customers.php

---

## Cara Deploy ke InfinityFree

### Langkah 1: Daftar Akun
1. Buka https://infinityfree.com → klik "Register Free"
2. Verifikasi email → login ke panel

### Langkah 2: Buat Hosting
1. Klik "Create New Account"
2. Pilih subdomain (misalnya: `packtrack.great-site.net`)
3. Catat: **Hosting Username**, **Password**, dan **FTP Details**

### Langkah 3: Buat Database MySQL
1. Di panel InfinityFree → klik "MySQL Databases"
2. Buat database baru → catat:
   - **MySQL Host**: (contoh: `sql305.infinityfree.com`)
   - **Database Name**: (contoh: `epXXXXXX_tracking`)
   - **Username**: (contoh: `epXXXXXX_user`)
   - **Password**: (password yang Anda buat)

### Langkah 4: Upload Database
1. Di panel → klik "phpMyAdmin"
2. Login → pilih database Anda → klik "Import"
3. Upload file `database.sql` → klik "Go"

### Langkah 5: Edit Konfigurasi
Buka `config/database.php`, ubah ke data hosting Anda:
```php
define('DB_HOST', 'sql305.infinityfree.com'); // sesuaikan
define('DB_USER', 'epXXXXXX_user');           // sesuaikan
define('DB_PASS', 'password_anda');           // sesuaikan
define('DB_NAME', 'epXXXXXX_tracking');       // sesuaikan
```

### Langkah 6: Upload File via FTP
**Menggunakan FileZilla (rekomendasi):**
1. Download FileZilla: https://filezilla-project.org
2. Isi data:
   - Host: `ftpupload.net`
   - Username: (dari panel InfinityFree)
   - Password: (password hosting)
   - Port: `21`
3. Klik "Quickconnect"
4. Di panel kanan (server), masuk ke folder `htdocs`
5. Di panel kiri (lokal), pilih folder `project-api`
6. Upload semua file/folder

### Langkah 7: Akses Website
Buka: `https://packtrack.great-site.net` (sesuaikan dengan subdomain Anda)

---

## Contoh Request & Response JSON

### GET /api/customers.php
**Request:** `GET http://localhost/project-api/api/customers.php`
**Response:**
```json
{
  "success": true,
  "total": 3,
  "data": [
    {
      "customer_id": 1,
      "name": "Budi Santoso",
      "phone": "081234567890",
      "address": "Jl. Merdeka No. 10, Jakarta Pusat"
    }
  ]
}
```

### POST /api/customers.php
**Request Body:**
```json
{ "name": "Dewi Anggraini", "phone": "089012345678", "address": "Jl. Gatot Subroto No. 45" }
```
**Response (201):**
```json
{ "success": true, "message": "Customer berhasil ditambahkan", "customer_id": 4 }
```

### PUT /api/customers.php?id=1
**Request Body:**
```json
{ "name": "Budi Santoso Jr.", "phone": "081234567890", "address": "Jl. Baru No. 5" }
```
**Response:**
```json
{ "success": true, "message": "Customer berhasil diupdate" }
```

### DELETE /api/customers.php?id=4
**Response:**
```json
{ "success": true, "message": "Customer berhasil dihapus" }
```

### GET /api/tracking.php?shipment_id=1
**Response:**
```json
{
  "success": true,
  "total": 4,
  "data": [
    { "tracking_id": 4, "shipment_id": 1, "status": "Delivered", "location": "Jl. Pahlawan No. 3", "update_time": "2025-01-15 15:45:00", "customer_name": "Budi Santoso" },
    { "tracking_id": 3, "shipment_id": 1, "status": "Arrived at Warehouse", "location": "Gudang Bogor Selatan", "update_time": "2025-01-15 13:00:00", "customer_name": "Budi Santoso" }
  ]
}
```

---

## Status HTTP yang Digunakan

| Kode | Arti |
|------|------|
| 200  | OK — request berhasil |
| 201  | Created — data berhasil dibuat |
| 400  | Bad Request — validasi gagal |
| 404  | Not Found — data tidak ditemukan |
| 405  | Method Not Allowed |
| 500  | Internal Server Error |
