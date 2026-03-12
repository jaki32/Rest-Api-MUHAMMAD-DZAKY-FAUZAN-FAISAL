<?php
// ============================================================
// api/couriers.php  — CRUD Couriers
// ============================================================
require_once __DIR__ . '/../config/database.php';
setHeaders();

$db     = Database::getInstance()->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            $stmt = $db->prepare("SELECT * FROM couriers WHERE courier_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) respond(["success" => true, "data" => $row]);
            else respond(["success" => false, "message" => "Kurir tidak ditemukan"], 404);
        } else {
            $result = $db->query("SELECT * FROM couriers ORDER BY courier_id DESC");
            $data = [];
            while ($row = $result->fetch_assoc()) $data[] = $row;
            respond(["success" => true, "total" => count($data), "data" => $data]);
        }
        break;

    case 'POST':
        $body         = json_decode(file_get_contents("php://input"), true);
        $courier_name = trim($body['courier_name'] ?? '');
        $vehicle_type = trim($body['vehicle_type'] ?? '');

        if (!$courier_name || !$vehicle_type)
            respond(["success" => false, "message" => "Field courier_name dan vehicle_type wajib diisi"], 400);

        $stmt = $db->prepare("INSERT INTO couriers (courier_name, vehicle_type) VALUES (?, ?)");
        $stmt->bind_param("ss", $courier_name, $vehicle_type);
        if ($stmt->execute())
            respond(["success" => true, "message" => "Kurir berhasil ditambahkan", "courier_id" => $db->insert_id], 201);
        respond(["success" => false, "message" => "Gagal menambahkan kurir"], 500);
        break;

    case 'PUT':
        $id   = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $body = json_decode(file_get_contents("php://input"), true);
        if (!$id) respond(["success" => false, "message" => "Parameter id wajib ada"], 400);

        $courier_name = trim($body['courier_name'] ?? '');
        $vehicle_type = trim($body['vehicle_type'] ?? '');
        if (!$courier_name || !$vehicle_type)
            respond(["success" => false, "message" => "Field courier_name dan vehicle_type wajib diisi"], 400);

        $stmt = $db->prepare("UPDATE couriers SET courier_name=?, vehicle_type=? WHERE courier_id=?");
        $stmt->bind_param("ssi", $courier_name, $vehicle_type, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0)
            respond(["success" => true, "message" => "Kurir berhasil diupdate"]);
        respond(["success" => false, "message" => "Kurir tidak ditemukan atau data sama"], 404);
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) respond(["success" => false, "message" => "Parameter id wajib ada"], 400);

        $stmt = $db->prepare("DELETE FROM couriers WHERE courier_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0)
            respond(["success" => true, "message" => "Kurir berhasil dihapus"]);
        respond(["success" => false, "message" => "Kurir tidak ditemukan"], 404);
        break;

    default:
        respond(["success" => false, "message" => "Method tidak didukung"], 405);
}
