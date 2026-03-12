<?php
// ============================================================
//  config/helpers.php
//  Helper functions untuk semua file API
// ============================================================

require_once __DIR__ . '/database.php';

// Set CORS headers agar bisa diakses dari halaman lain
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Kirim response JSON
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}

// Baca body JSON dari request
function getRequestBody() {
    $raw = file_get_contents("php://input");
    return json_decode($raw, true) ?? [];
}

// Validasi field wajib
function requireFields($data, $fields) {
    foreach ($fields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            sendResponse([
                "success" => false,
                "message" => "Field '$field' wajib diisi."
            ], 400);
        }
    }
}
?>
