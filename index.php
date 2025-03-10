<?php

use Src\Http\Response;
use Src\Controller\EmailController;
use Src\Controller\IpController;

require_once "./vendor/autoload.php";

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$path = $_GET['path'] ?? '';
$action = $_GET['action'] ?? '';

if (!isset($_GET['path'])) {
    echo json_encode(["error" => "Desculpe, mas rota não encontrada"]);
    http_response_code(404);
    exit;
}

if (!isset($_GET['action'])) {
    echo json_encode(["error" => "Desculpe, mas ação não encontrada"]);
    http_response_code(404);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);

if ($path === 'email') {
    if ($method === 'POST') {
        switch ($action) {
            case "send":
                EmailController::send($data);
            break;
            default:
                Response::json([
                    "success" => false,
                    "message" => "Ação não encontrada.",
                ], 400);
        }
    } else {
        Response::json([
            "success" => false,
            "message" => "Método não permitido"
        ], 405);
    }
} else if ($path == 'cookie') {
} else if ($path === 'ip') {
    if ($method === 'POST') {
        switch ($action) {
            case "register":
                IpController::create($data);
            break;
            case "updatePolicy":
                IpController::updatePolicy($data);
            break;
            default:
                Response::json([
                    "success" => false,
                    "message" => "Método não permitido"
                ], 405);
        }
    } else {
        Response::json([
            "success" => false,
            "message" => "Método não permitido"
        ], 405);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Rota não encontrada']);
}
