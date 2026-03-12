

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


