<?php
// ============================================================
// config/database.php
// Konfigurasi Koneksi Database
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tracking_paket');

// Untuk INFINITYFREE - uncomment dan isi sesuai panel hosting:
// define('DB_HOST', 'sqlXXX.infinityfree.com');
// define('DB_USER', 'epXXXXXX_user');
// define('DB_PASS', 'passwordanda');
// define('DB_NAME', 'epXXXXXX_tracking');

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Koneksi gagal: " . $this->conn->connect_error]);
            exit();
        }
        $this->conn->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

function setHeaders() {
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }
}

function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}
