
CREATE TABLE IF NOT EXISTS customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    phone       VARCHAR(20)  NOT NULL,
    address     TEXT         NOT NULL,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- Tabel 2: couriers  (tabel mandiri / lookup)
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS couriers (
    courier_id   INT AUTO_INCREMENT PRIMARY KEY,
    courier_name VARCHAR(100) NOT NULL,
    vehicle_type VARCHAR(50)  NOT NULL,
    created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- Tabel 3: shipments  (FK → customers, FK → couriers)
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS shipments (
    shipment_id   INT AUTO_INCREMENT PRIMARY KEY,
    customer_id   INT          NOT NULL,
    courier_id    INT          NOT NULL,
    destination   VARCHAR(255) NOT NULL,
    shipment_date DATE         NOT NULL,
    created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_shipment_customer FOREIGN KEY (customer_id)
        REFERENCES customers(customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_shipment_courier  FOREIGN KEY (courier_id)
        REFERENCES couriers(courier_id)  ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- Tabel 4: tracking  (FK → shipments)
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS tracking (
    tracking_id  INT AUTO_INCREMENT PRIMARY KEY,
    shipment_id  INT NOT NULL,
    status       ENUM('Packed','On Delivery','Arrived at Warehouse','Delivered') NOT NULL,
    location     VARCHAR(255) NOT NULL,
    update_time  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tracking_shipment FOREIGN KEY (shipment_id)
        REFERENCES shipments(shipment_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- DATA CONTOH (Sample Data)
-- ────────────────────────────────────────────────────────────
INSERT INTO customers (name, phone, address) VALUES
('Budi Santoso',   '081234567890', 'Jl. Merdeka No. 10, Jakarta Pusat'),
('Siti Rahayu',    '082345678901', 'Jl. Sudirman No. 25, Bandung'),
('Ahmad Fauzi',    '083456789012', 'Jl. Diponegoro No. 5, Surabaya');

INSERT INTO couriers (courier_name, vehicle_type) VALUES
('Andi Wijaya',    'Motor'),
('Dedi Kurniawan', 'Mobil Van'),
('Rina Susanti',   'Sepeda Motor');

INSERT INTO shipments (customer_id, courier_id, destination, shipment_date) VALUES
(1, 1, 'Jl. Pahlawan No. 3, Bogor',          '2025-01-15'),
(2, 2, 'Jl. Veteran No. 7, Yogyakarta',       '2025-01-16'),
(3, 3, 'Jl. Diponegoro No. 12, Malang',       '2025-01-17');

INSERT INTO tracking (shipment_id, status, location, update_time) VALUES
(1, 'Packed',               'Gudang Jakarta Pusat',   '2025-01-15 08:00:00'),
(1, 'On Delivery',          'Jalan Tol Jagorawi',     '2025-01-15 10:30:00'),
(1, 'Arrived at Warehouse', 'Gudang Bogor Selatan',   '2025-01-15 13:00:00'),
(1, 'Delivered',            'Jl. Pahlawan No. 3',     '2025-01-15 15:45:00'),
(2, 'Packed',               'Gudang Bandung',          '2025-01-16 09:00:00'),
(2, 'On Delivery',          'Jalur Pantura',           '2025-01-16 11:00:00'),
(3, 'Packed',               'Gudang Surabaya',         '2025-01-17 08:30:00');
