<?php
// ============================================================
// api/customers.php  — CRUD Customers
// ============================================================
require_once __DIR__ . '/../config/database.php';
setHeaders();

$db   = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // ── GET ──────────────────────────────────────────────────
    case 'GET':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            $stmt = $db->prepare("SELECT * FROM customers WHERE customer_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) respond(["success" => true, "data" => $row]);
            else respond(["success" => false, "message" => "Customer tidak ditemukan"], 404);
        } else {
            $result = $db->query("SELECT * FROM customers ORDER BY customer_id DESC");
            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;
            respond(["success" => true, "total" => count($data), "data" => $data]);
        }
        break;

    // ── POST ─────────────────────────────────────────────────
    case 'POST':
        $body = json_decode(file_get_contents("php://input"), true);
        $name    = trim($body['name']    ?? '');
        $phone   = trim($body['phone']   ?? '');
        $address = trim($body['address'] ?? '');

        if (!$name || !$phone || !$address)
            respond(["success" => false, "message" => "Field name, phone, address wajib diisi"], 400);

        $stmt = $db->prepare("INSERT INTO customers (name, phone, address) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $address);
        if ($stmt->execute()) {
            respond(["success" => true, "message" => "Customer berhasil ditambahkan", "customer_id" => $db->insert_id], 201);
        }
        respond(["success" => false, "message" => "Gagal menambahkan customer"], 500);
        break;

    // ── PUT ──────────────────────────────────────────────────
    case 'PUT':
        $id   = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $body = json_decode(file_get_contents("php://input"), true);
        if (!$id) respond(["success" => false, "message" => "Parameter id wajib ada"], 400);

        $name    = trim($body['name']    ?? '');
        $phone   = trim($body['phone']   ?? '');
        $address = trim($body['address'] ?? '');
        if (!$name || !$phone || !$address)
            respond(["success" => false, "message" => "Field name, phone, address wajib diisi"], 400);

        $stmt = $db->prepare("UPDATE customers SET name=?, phone=?, address=? WHERE customer_id=?");
        $stmt->bind_param("sssi", $name, $phone, $address, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0)
            respond(["success" => true, "message" => "Customer berhasil diupdate"]);
        respond(["success" => false, "message" => "Customer tidak ditemukan atau data sama"], 404);
        break;

    // ── DELETE ───────────────────────────────────────────────
    case 'DELETE':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) respond(["success" => false, "message" => "Parameter id wajib ada"], 400);

        $stmt = $db->prepare("DELETE FROM customers WHERE customer_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0)
            respond(["success" => true, "message" => "Customer berhasil dihapus"]);
        respond(["success" => false, "message" => "Customer tidak ditemukan"], 404);
        break;

    default:
        respond(["success" => false, "message" => "Method tidak didukung"], 405);
}
