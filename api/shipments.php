<?php
// ============================================================
// api/shipments.php  — CRUD Shipments
// ============================================================
require_once __DIR__ . '/../config/database.php';
setHeaders();

$db     = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $sql_base = "SELECT s.*, c.name AS customer_name, c.phone, cr.courier_name, cr.vehicle_type
                     FROM shipments s
                     JOIN customers c  ON s.customer_id  = c.customer_id
                     JOIN couriers  cr ON s.courier_id   = cr.courier_id";
        if ($id) {
            $stmt = $db->prepare($sql_base . " WHERE s.shipment_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) respond(["success" => true, "data" => $row]);
            else respond(["success" => false, "message" => "Pengiriman tidak ditemukan"], 404);
        } else {
            $result = $db->query($sql_base . " ORDER BY s.shipment_id DESC");
            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;
            respond(["success" => true, "total" => count($data), "data" => $data]);
        }
        break;

    case 'POST':
        $body          = json_decode(file_get_contents("php://input"), true);
        $customer_id   = (int)($body['customer_id']   ?? 0);
        $courier_id    = (int)($body['courier_id']    ?? 0);
        $destination   = trim($body['destination']    ?? '');
        $shipment_date = trim($body['shipment_date']  ?? date('Y-m-d'));

        if (!$customer_id || !$courier_id || !$destination)
            respond(["success" => false, "message" => "Field customer_id, courier_id, destination wajib diisi"], 400);

        $stmt = $db->prepare("INSERT INTO shipments (customer_id, courier_id, destination, shipment_date) VALUES (?,?,?,?)");
        $stmt->bind_param("iiss", $customer_id, $courier_id, $destination, $shipment_date);
        if ($stmt->execute())
            respond(["success" => true, "message" => "Pengiriman berhasil dibuat", "shipment_id" => $db->insert_id], 201);
        respond(["success" => false, "message" => "Gagal membuat pengiriman"], 500);
        break;

    case 'PUT':
        $id   = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $body = json_decode(file_get_contents("php://input"), true);
        if (!$id) respond(["success" => false, "message" => "Parameter id wajib ada"], 400);

        $customer_id   = (int)($body['customer_id']   ?? 0);
        $courier_id    = (int)($body['courier_id']    ?? 0);
        $destination   = trim($body['destination']    ?? '');
        $shipment_date = trim($body['shipment_date']  ?? '');
        if (!$customer_id || !$courier_id || !$destination || !$shipment_date)
            respond(["success" => false, "message" => "Semua field wajib diisi"], 400);

        $stmt = $db->prepare("UPDATE shipments SET customer_id=?, courier_id=?, destination=?, shipment_date=? WHERE shipment_id=?");
        $stmt->bind_param("iissi", $customer_id, $courier_id, $destination, $shipment_date, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0)
            respond(["success" => true, "message" => "Pengiriman berhasil diupdate"]);
        respond(["success" => false, "message" => "Pengiriman tidak ditemukan atau data sama"], 404);
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) respond(["success" => false, "message" => "Parameter id wajib ada"], 400);

        $stmt = $db->prepare("DELETE FROM shipments WHERE shipment_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0)
            respond(["success" => true, "message" => "Pengiriman berhasil dihapus"]);
        respond(["success" => false, "message" => "Pengiriman tidak ditemukan"], 404);
        break;

    default:
        respond(["success" => false, "message" => "Method tidak didukung"], 405);
}
