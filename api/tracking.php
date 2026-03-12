<?php
// ============================================================
// api/tracking.php  — CRUD Tracking
// ============================================================
require_once __DIR__ . '/../config/database.php';
setHeaders();

$db     = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

$valid_statuses = ['Packed', 'On Delivery', 'Arrived at Warehouse', 'Delivered'];

switch ($method) {

    case 'GET':
        $id          = isset($_GET['id'])          ? (int)$_GET['id']          : null;
        $shipment_id = isset($_GET['shipment_id']) ? (int)$_GET['shipment_id'] : null;

        $sql_base = "SELECT t.*, s.destination, c.name AS customer_name
                     FROM tracking t
                     JOIN shipments s ON t.shipment_id = s.shipment_id
                     JOIN customers c ON s.customer_id = c.customer_id";

        if ($id) {
            $stmt = $db->prepare($sql_base . " WHERE t.tracking_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) respond(["success" => true, "data" => $row]);
            else respond(["success" => false, "message" => "Tracking tidak ditemukan"], 404);
        } elseif ($shipment_id) {
            $stmt = $db->prepare($sql_base . " WHERE t.shipment_id = ? ORDER BY t.update_time DESC");
            $stmt->bind_param("i", $shipment_id);
            $stmt->execute();
            $data = [];
            $res  = $stmt->get_result();
            while ($row = $res->fetch_assoc()) $data[] = $row;
            respond(["success" => true, "total" => count($data), "data" => $data]);
        } else {
            $result = $db->query($sql_base . " ORDER BY t.update_time DESC");
            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;
            respond(["success" => true, "total" => count($data), "data" => $data]);
        }
        break;

    case 'POST':
        $body        = json_decode(file_get_contents("php://input"), true);
        $shipment_id = (int)($body['shipment_id'] ?? 0);
        $status      = trim($body['status']       ?? '');
        $location    = trim($body['location']     ?? '');
        $update_time = trim($body['update_time']  ?? date('Y-m-d H:i:s'));

        if (!$shipment_id || !$status || !$location)
            respond(["success" => false, "message" => "Field shipment_id, status, location wajib diisi"], 400);
        if (!in_array($status, $valid_statuses))
            respond(["success" => false, "message" => "Status tidak valid. Pilihan: " . implode(', ', $valid_statuses)], 400);

        $stmt = $db->prepare("INSERT INTO tracking (shipment_id, status, location, update_time) VALUES (?,?,?,?)");
        $stmt->bind_param("isss", $shipment_id, $status, $location, $update_time);
        if ($stmt->execute())
            respond(["success" => true, "message" => "Tracking berhasil ditambahkan", "tracking_id" => $db->insert_id], 201);
        respond(["success" => false, "message" => "Gagal menambahkan tracking"], 500);
        break;

    case 'PUT':
        $id   = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $body = json_decode(file_get_contents("php://input"), true);
        if (!$id) respond(["success" => false, "message" => "Parameter id wajib ada"], 400);

        $shipment_id = (int)($body['shipment_id'] ?? 0);
        $status      = trim($body['status']       ?? '');
        $location    = trim($body['location']     ?? '');
        $update_time = trim($body['update_time']  ?? '');
        if (!$shipment_id || !$status || !$location || !$update_time)
            respond(["success" => false, "message" => "Semua field wajib diisi"], 400);
        if (!in_array($status, $valid_statuses))
            respond(["success" => false, "message" => "Status tidak valid. Pilihan: " . implode(', ', $valid_statuses)], 400);

        $stmt = $db->prepare("UPDATE tracking SET shipment_id=?, status=?, location=?, update_time=? WHERE tracking_id=?");
        $stmt->bind_param("isssi", $shipment_id, $status, $location, $update_time, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0)
            respond(["success" => true, "message" => "Tracking berhasil diupdate"]);
        respond(["success" => false, "message" => "Tracking tidak ditemukan atau data sama"], 404);
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) respond(["success" => false, "message" => "Parameter id wajib ada"], 400);

        $stmt = $db->prepare("DELETE FROM tracking WHERE tracking_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0)
            respond(["success" => true, "message" => "Tracking berhasil dihapus"]);
        respond(["success" => false, "message" => "Tracking tidak ditemukan"], 404);
        break;

    default:
        respond(["success" => false, "message" => "Method tidak didukung"], 405);
}
